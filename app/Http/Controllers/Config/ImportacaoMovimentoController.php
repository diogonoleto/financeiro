<?php
namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financeiro\FinImportacao;
use App\Models\Financeiro\FinConta;
use App\Models\Financeiro\FinCategoria;
use App\Models\Financeiro\FinMovimento;
use App\Models\Financeiro\FinCentroCusto;
use App\Models\Empresa\Empresa;
use Carbon\Carbon;
use Gate;
use Input;
use Excel;

class ImportacaoMovimentoController extends Controller
{
  public function index()
  {
    if( Gate::denies('config_importacao_read') ){
      return redirect()->route('financeiro');
    }
    $title = "Importação de movimentos";
    return view('config.importacao.movimento.index', compact('title'));
  }
  public function create(FinImportacao $importacao)
  {
    if( Gate::denies('config_importacao_create') ){
      return redirect()->back();
    }
    $contas = FinConta::whereNotIn("conta_tipo_id", [4,5,6,7])->whereNull('deleted_at')->get();
    $item = $importacao;
    return view('config.importacao.movimento.create', compact('item', 'contas'));
  }
  public function store(Request $request, FinImportacao $importacao)
  {
    $this->authorize('config_importacao_create');
    if($request->hasFile('import_file')){
      $conta = $request->conta_id;
      $tipo = $request->tipo;
      $path = $request->import_file->path();
      config(['excel.import.startRow' => 15]);
      $data = Excel::load($path, function($reader) { })->get();
      if(!empty($data) && $data->count()){
        $liDesc = [];
        $liCate = [];
        $liValo = [];
        $liValoRe = [];
        $liValoPa = [];
        $liValoBa = [];
        $liDataVe = [];
        $liDataBa = [];
        $error = [];
        $cont = 0;
        foreach($data as $sheet)
        {
          if($request->tipo == 'ReceitaDespesa'){
            $tipo = $sheet->getTitle(); 
          } else {
            if($request->tipo != $sheet->getTitle())
              continue;
          }
          if($tipo != 'Receita' && $tipo != 'Despesa') {
            $error[$cont] = 'O arquivo enviado não está no padrão, por favor faça o download do modelo padrão!';
            $cont++;
          }
          foreach($sheet as $v => $value) {
            if( !$value->cod_categoria && !$value->descricao && !$value->valor){
              continue;
            }
            if( !$value->descricao ){
              $liDesc[$v] = $v+16;
            }
            $cod = (substr($value->cod_categoria, -1) != '.') ? $value->cod_categoria."." : $value->cod_categoria;
            $categoria = FinCategoria::where('cod', $cod)->where('tipo', $tipo)->whereNull('deleted_at')->first();
            if( !$categoria || count($categoria->children)>0 ){
              $liCate[$v] = $v+16;
            }
            if( !$value->valor ){
              $liValo[$v] = $v+16;
            }
            if($value->baixa){
              if($tipo=="Receita"){
                if( $value->valor_recebido && !$value->baixa ){
                  $liValoRe[$v] = $v+16;
                }
                if( !$value->valor_recebido && $value->baixa ){
                  $liValoBa[$v] = $v+16;
                }
              } else {
                if( $value->valor_pago && !$value->baixa ){
                  $liValoPa[$v] = $v+16;
                }
                if( !$value->valor_pago && $value->baixa ){
                  $liValoBa[$v] = $v+16;
                }
              }
            }
            if(isset($value->vencimento) && $value->vencimento ==""){
              if( $value->emissao > $value->vencimento ){
                $liDataVe[$v] = $v+16;
              }
            }
          }
        }
        if( count($liDesc) > 0 ){
          $error[$cont] = 'A coluna Descrição não pode ser nula ou zerada! Na(s) linha(s): '.implode(', ',array_filter($liDesc));
          $cont++;
        }
        if( count($liCate) > 0 ){
          $error[$cont] = 'O Código da Categoria não está de acordo com as do sistema! Na(s) linha(s): '.implode(', ',array_filter($liCate));
          $cont++;
        }
        if( count($liValo) > 0 ){
          $error[$cont] = 'A coluna Valor não pode ser nula ou zerada! Na(s) linha(s): '.implode(', ',array_filter($liValo));
          $cont++;
        }
        $trd = $tipo=="Receita" ? 'Recebido' : 'Pago';
        if( count($liValoRe) > 0 ){
          $error[$cont] = 'A coluna Baixa tem que ser preenchida, pois existe um valor na coluna Valor '.$trd.'! Na(s) linha(s): '.implode(', ',array_filter($liValoRe));
          $cont++;
        }
        if( count($liValoPa) > 0 ){
          $error[$cont] = 'A coluna Baixa tem que ser preenchida, pois existe um valor na coluna Valor '.$trd.'! Na(s) linha(s): '.implode(', ',array_filter($liValoPa));
          $cont++;
        }
        if( count($liValoBa) > 0 ){
          $error[$cont] = 'A coluna Valor '.$trd.' tem que ser preenchida, pois exite um data na coluna Baixa! Na(s) linha(s): '.implode(', ',array_filter($liValoBa));
          $cont++;
        }
        if( count($liDataVe) > 0 ){
          $error[$cont] = 'A coluna Vencimento não pode ter uma data menor que a da coluna Emissão! Na(s) linha(s): '.implode(', ',array_filter($liDataVe));
          $cont++;
        }
        if( count($liDataBa) > 0 ){
          $error[$cont] = 'A coluna Baixa não pode ter uma data menor que a da coluna Emissão! Na(s) linha(s): '.implode(', ',array_filter($liDataBa));
          $cont++;
        }
        if( count($error) > 0 ){
          return redirect()->back()->withErrors([ 'error' => $error]);
        }
        $importacao = new FinImportacao;
        $importacao->nome = 'movimentação de '.$tipo.' '.$data->getTitle().'('.$data->count().')';
        $importacao->tipo_id = 1;
        $saveimp =  $importacao->save();
        if($saveimp){
          $usuario = auth()->user()->id;
          foreach($data as $sheet)
          {
            if($request->tipo == 'ReceitaDespesa'){
              $tipo = $sheet->getTitle(); 
            } else {
              if($request->tipo != $sheet->getTitle())
                continue;
            }
            foreach ($sheet as $value) {
              if( !$value->cod_categoria && !$value->descricao && !$value->valor){
                continue;
              }
              if(isset($value->conta_id))
                $conta = FinConta::where('id', (int)$value->conta_id)->whereNull('deleted_at')->first()->id;
              else
                $conta = FinConta::find($request->conta_id)->id;
              $cod = (substr($value->cod_categoria, -1) != '.') ? $value->cod_categoria."." : $value->cod_categoria;
              $categoria = FinCategoria::where('cod', $cod)->where('tipo', $tipo)->whereNull('deleted_at')->first();

              $fornecedor = null;
              $eti = ($categoria->tipo == 'Despesa') ? 2 : 3;
              if($value->cnpjcpf){
                $cnpj = str_replace('/', '', str_replace('-', '', str_replace('.', '', $value->cnpjcpf)));
                $fornecedor = Empresa::where("empresa_tipo_id", $eti)->where('cnpj', $cnpj)->orderBy('cnpj')->first(); 
              }
              if(!$fornecedor){
                if($value->nome){
                  $fornecedor = Empresa::where("empresa_tipo_id", $eti)->where('nome_fantasia', $value->nome)->first(); 
                  if(!$fornecedor){
                    $nome = $value->nome;
                    $cnpj = isset($value->cnpjcpf) ? str_replace('/', '', str_replace('-', '', str_replace('.', '', $value->cnpjcpf))) : null;
                  }
                } else {
                  $fc = ($categoria->tipo == 'Despesa') ? "Fornecedor Padrão" : "Cliente Padrão";
                  $fornecedor = Empresa::where('nome_fantasia', $fc)->first();
                  if(!$fornecedor){
                    $nome = $fc;
                    $cnpj = null;
                  }
                }
              }
              if(!$fornecedor){
                $fornecedor = new Empresa;
                $fornecedor->nome_fantasia = $nome;
                $fornecedor->razao_social = $nome;
                $fornecedor->cnpj = $cnpj;
                $fornecedor->empresa_tipo_id = $eti;
                $fornecedor->empresa_entidade_id= 1;
                $fornecedor->save();
              }
              if($value->centro_de_custo){
                $centrocusto = FinCentroCusto::where('nome', $value->centro_de_custo)->whereNull('deleted_at')->first();
                if(!$centrocusto){
                  $centrocusto = new FinCentroCusto;
                  $centrocusto->nome = $value->centro_de_custo;
                  $centrocusto->save();
                }
              } else {
                $centrocusto = FinCentroCusto::where('nome', 'Comum')->whereNull('deleted_at')->first();
              }
              $valor = $value->valor;
              $data_emissao = isset($value->emissao) ? $value->emissao->format('Y-m-d') : date('Y-m-d');
              $data_vencimento = isset($value->vencimento) ? $value->vencimento->format('Y-m-d') : $data_emissao;
              if($value->baixa){
                $data_baixa = $value->baixa->format('Y-m-d H:m:s');
                $vr = $tipo=="Receita" ? $value->valor_recebido : $value->valor_pago ;
                $desconto = ( isset($value->desconto) || $value->desconto != '' || $value->desconto != null ) ? (float)$value->desconto : 0;
                $juro = ( isset($value->juro) || $value->juro != '' || $value->juro != null ) ? (float)$value->juro : 0;
              } else {
                $data_baixa = null;
                $vr = 0;
                $desconto = 0;
                $juro = 0;
              }
              FinMovimento::create([
                'user_id' => $usuario,
                'categoria_id' => $categoria->id,
                'importacao_id' => $importacao->id,
                'conta_id' => $conta,
                'fornecedor_id' => $fornecedor->id,
                'centro_custo_id' => $centrocusto->id,
                'descricao' => $value->descricao,
                'data_emissao' => $data_emissao,
                'data_baixa' => $data_baixa,
                'data_vencimento' => $data_vencimento,
                'num_doc' => $value->n0_documento,
                'desconto' => $desconto,
                'juro' => $juro,
                'valor' => $valor,
                'valor_recebido' => $vr,
                'observacao' => $value->observacoes,
              ]);
            }
          }
        }
      }
    } else {
      return redirect()->back()->withErrors([ 'error' => 'Planilha Não encontrda!']);
    }
    return redirect()->route('movimento.index');
  }
  public function destroy($id)
  {
    $this->authorize('config_importacao_delete');
    $iimpo = FinMovimento::where('importacao_id', $id)->update(['deleted_at' => Carbon::now()]);
    $imp = FinImportacao::where('id', $id)->update(['deleted_at' => Carbon::now()]);
    if($iimpo && $imp)
      return redirect()->route('imp.movimento.index');
    else
      return redirect()->route('imp.movimento.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista()
  {
    $this->authorize('config_importacao_read');
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'created_at';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'ASC' ;
    $search = Request('input-search');
    if( $search || $search != '' ){
      $itens = FinImportacao::where('tipo_id', 1)->where('nome', 'LIKE', "%$search%")
      ->orderBy('nome', 'ASC')
      ->paginate(28);
    } else {
      $itens = FinImportacao::where('tipo_id', 1)->orderBy($order, $sort)
      ->paginate(28);
    }
    return response(view('config.importacao.movimento.lista', compact('itens')));
  }
}
