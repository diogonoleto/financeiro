<?php
namespace App\Http\Controllers\Financeiro;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\Financeiro\MovimentoFormRequest;
use App\Http\Controllers\Controller;
use App\Models\Financeiro\FinMovimento;
use App\Models\Financeiro\FinCategoria;
use App\Models\Financeiro\FinConta;
use App\Models\Financeiro\FinCentroCusto;
use App\Models\Financeiro\FinImportacao;
use App\Models\Financeiro\FinContaFatura;
use App\Models\Universal\UniFormaPagamento;
use App\Models\Empresa\Empresa;
use App\Helpers\Helper;
use DB;
use Gate;
use Session;
use Excel;

class MovimentoController extends Controller
{
  public function index()
  {
    if( Gate::denies('fin_movimento_read') ){
      return redirect()->back();
    }
    $contas = FinConta::whereNotIn("conta_tipo_id", [4,5,6,7])->whereNull('deleted_at')->first();
    if(!$contas){
      Session::flash('sconta', "Cadastrar nova conta!");
      return redirect()->route('conta.index');
    }
    $contas = FinConta::whereNotIn("conta_tipo_id", [4,5,6,7])->whereNull('deleted_at')->get();
    $fornecedors = Empresa::where('empresa_tipo_id', 2)->whereNull('deleted_at')->where('razao_social', "!=", 'Fornecedor Padrão')->get();
    $clientes = Empresa::where('empresa_tipo_id', 3)->whereNull('deleted_at')->where('razao_social', "!=", 'Cliente Padrão')->get();
    $categorias = FinCategoria::whereNull('categoria_id')->whereNull('deleted_at')->get();
    $centrocustos = FinCentroCusto::whereNull('deleted_at')->get();
    $title = 'Movimentações - Extrato';
    return view('financeiro.movimento.index', compact('title', 'contas', 'fornecedors', 'clientes', 'categorias', 'centrocustos'));
  }
  public function agemov(Request $request)
  {
    if( Gate::denies('fin_movimento_update') ){
      return redirect()->back();
    }

    $movimento = FinMovimento::find($request->movimento_id);
    $contas = FinConta::whereNotIn("conta_tipo_id", [4,5,6,7])->whereNull('deleted_at')->get();

    $fornecedors = Empresa::where('empresa_tipo_id', 2)->whereNull('deleted_at')->where('razao_social', "!=", 'Fornecedor Padrão')->get();
    $clientes = Empresa::where('empresa_tipo_id', 3)->whereNull('deleted_at')->where('razao_social', "!=", 'Cliente Padrão')->get();
    $categorias = FinCategoria::whereNull('categoria_id')->whereNull('deleted_at')->get();

    $title = 'Movimentações - Extrato';
    return view('financeiro.movimento.index', compact('title', 'contas', 'movimento', 'fornecedors', 'clientes', 'categorias'));
  }
  public function create(Request $request, FinMovimento $movimento)
  {
    // dd($request->tipo);
    $this->authorize('fin_movimento_create');
    $item = $movimento;
    $item2 = $movimento;
    $conta = $request->conta_id ? FinConta::find($request->conta_id) : null;

    $fatura = null;
    if(isset($request->fatura)){
      $fatura = FinContaFatura::select(DB::raw('fin_conta_faturas.*, co.conta_tipo_id, co.descricao'))
      ->join('fin_contas as co', 'fin_conta_faturas.conta_id', '=', 'co.id')
      ->where('fin_conta_faturas.id', $request->fatura)
      ->groupBy('fin_conta_faturas.conta_id', 'fin_conta_faturas.id', 'co.conta_tipo_id', 'co.descricao', 'fin_conta_faturas.data_vencimento', 'fin_conta_faturas.data_fechamento')
      ->first();

      $total = FinContaFatura::select(DB::raw('IFNULL(SUM(IF(ca.tipo = "Receita", -1*mo.valor , 1*mo.valor )),0) as valor'))
      ->join('fin_movimentos as mo','fin_conta_faturas.id', '=', 'mo.conta_fatura_id')
      ->join('fin_categorias as ca', 'ca.id', '=', 'mo.categoria_id')
      ->join('fin_contas as co', 'fin_conta_faturas.conta_id', '=', 'co.id')
      ->whereNull('mo.deleted_at')
      ->where('mo.conta_id', $conta->id)
      ->where('mo.data_baixa', '<=', $fatura->data_fechamento)
      ->first();
    }

    $contas = FinMovimento::select(DB::raw('fin_contas.id, fin_contas.descricao, fin_contas.padrao, SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereNotNull('fin_movimentos.data_baixa')
    ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->whereNull("fin_contas.deleted_at")
    ->groupBy('fin_contas.id', 'fin_contas.descricao', 'fin_contas.padrao')
    ->orderBy('fin_contas.padrao', 'desc')
    ->get();
    $categorias = FinCategoria::where('categoria_id', NULL)
    ->whereNull('deleted_at')
    ->where('tipo', $request->tipo)
    ->where('nome', '!=', 'Transferência de Saída')
    ->where('nome', '!=', 'Transferência de Entrada')
    ->where('nome', '!=', 'Saldo Inicial')
    ->orderBy('cod', 'asc')
    ->with('children')
    ->get();
    $tipo = $request->tipo;
    $fornecedores = Empresa::whereIn('empresa_tipo_id', [2,3])
    ->whereNull('deleted_at')
    ->orderBy('nome_fantasia', 'asc')
    ->get();
    $centrocustos = FinCentroCusto::whereNull('deleted_at')
    ->orderBy('nome', 'asc')
    ->get();
    return view('financeiro.movimento.create', compact('item', 'item2', 'conta', 'contas', 'categorias', 'tipo', 'fornecedores', 'centrocustos', 'fatura', 'total'));
  }
  public function store(Request $request)
  {
    $this->authorize('fin_movimento_create');

    if( $request->repetir ) {
      $repeticoes = $request->repeticoes;
    } else {
      $repeticoes = 1;
    }
    $insert = array();
    $valor = str_replace(',', '.', str_replace('.', '', $request->valor));
    $empresa = $request->empresa_id;
    $usuario = auth()->user()->id;
    $data_emissao = date('Y-m-d', strtotime(str_replace("/", "-", $request->data_emissao)));
    if( $request->pago ) {
      $desconto = str_replace(',', '.', str_replace('.', '', $request->desconto));
      $valor_recebido = str_replace(',', '.', str_replace('.', '', $request->valor_recebido));
      $juro = str_replace(',', '.', str_replace('.', '', $request->juro));
      $data_baixa = date('Y-m-d H:i:s', strtotime(str_replace("/", "-", $request->data_baixa)));
    } else {
      $desconto = 0;
      $valor_recebido = 0;
      $juro = 0;
      $data_baixa = null;
    }
    if($request->tipo == "Receita"){
      $pFornecedor = Empresa::where('razao_social', 'Cliente Padrão')->first();
      $pnome = 'Cliente Padrão';
      $ptipo = 3;
    } else {
      $pFornecedor = Empresa::where('razao_social', 'Fornecedor Padrão')->first();
      $pnome = 'Fornecedor Padrão';
      $ptipo = 2;
    }
    if(!$pFornecedor){
      $pFornecedor = new Empresa;
      $pFornecedor->razao_social        = $pnome;
      $pFornecedor->nome_fantasia       = $pnome;
      $pFornecedor->empresa_tipo_id     = $ptipo;
      $pFornecedor->empresa_entidade_id = 1;
      $pFornecedor->save();
    }
    $pCentroCusto = FinCentroCusto::where('nome', 'Comum')->first();
    if(!$pCentroCusto){
      $pCentroCusto = new FinCentroCusto;
      $pCentroCusto->nome = 'Comum';
      $pCentroCusto->save();
    }
    if(isset($request->outro)) {
      $centro_custo = isset($request->centro_custo_id) ? $request->centro_custo_id : $pCentroCusto->id;
      $observacao = isset($request->observacao) ? $request->observacao : '';
      if($request->tipo == "Receita"){
        $fornecedor = isset($request->cliente_id) ? $request->cliente_id : $pFornecedor->id;
      } else {
        $fornecedor = isset($request->fornecedor_id) ? $request->fornecedor_id : $pFornecedor->id;
      }
      $num_doc = isset($request->num_doc) ? $request->num_doc : NULL;
    } else {
      $centro_custo = $pCentroCusto->id;
      $observacao = '';
      $fornecedor = $pFornecedor->id;
      $num_doc = NULL;
    }

    $movimento_id = NULL;
    $conta = FinConta::where("id",$request->conta_id)->whereNull('deleted_at')->first();

    if($conta){
      $fp = FinContaFatura::where('conta_id', $conta->id)->orderBy('data_vencimento', 'asc')->first();
      $fa = FinContaFatura::where('conta_id', $conta->id)->where('status', 2)->first();
      for ($i=0; $i < $repeticoes; $i++) {
        $fu = FinContaFatura::where('conta_id', $conta->id)->orderBy('data_vencimento', 'desc')->first();
        $n = $i+1;
        if($repeticoes > 1){
          $recorrencia = $n.'/'.$repeticoes;
        } else {
          $recorrencia = '';
        }
        if($request->ciclo == "2months"){
          $ciclo = "+".($i*2)." months";
        } else if($request->ciclo == "3months"){
          $ciclo = "+".($i*3)." months";
        } else if($request->ciclo == "6months"){
          $ciclo = "+".($i*6)." months";
        } else {
          $ciclo = "+".$i." ".$request->ciclo;
        }
        if($request->data_vencimento){
          $data_emissao = Helper::dataRecorrente($request->data_emissao, $ciclo);
          $data_vencimento = Helper::dataRecorrente($request->data_vencimento, $ciclo);
        } else {
          $data_vencimento = date('Y-m-d', strtotime($ciclo, strtotime($data_emissao)));
          if($conta->conta_tipo_id == 4){
            $now = date("Y-m-d");
            if($data_vencimento > $now && $i == 0 ){
              $error = ["error" => ["data_emissao" => "A data de Data Emissão não pode ser superior a data de hoje"]];
              return $error;
            }

            $fatura = FinContaFatura::where('conta_id', $conta->id)
            ->where(function($q) use ($data_vencimento) {
              $q->where('data_inicial','<=', $data_vencimento)
              ->Where('data_fechamento','>=', $data_vencimento);
            })
            ->whereNull('deleted_at')->first();
            if($fatura){
              $conta_fatura_id = $fatura->id;

            } else {
              if($data_vencimento < $fa->data_inicial ){
                $status = 3;
                $dv = date('Y-m-d', strtotime('-1 month', strtotime($fp->data_vencimento)));
                $df = date('Y-m-d', strtotime('-10 day', strtotime($dv)));
                $di = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d', strtotime('-1 month', strtotime($df))))));

                while($df >= $data_vencimento){
                  $fatura = new FinContaFatura;
                  $fatura->conta_id = $request->conta_id;
                  $fatura->data_inicial = $di;
                  $fatura->data_fechamento = $df;
                  $fatura->data_vencimento = $dv;
                  $fatura->status = $status;
                  $fatura->save();

                  $dv = date('Y-m-d', strtotime('-1 month', strtotime($dv)));
                  $df = date('Y-m-d', strtotime('-10 day', strtotime($dv)));
                  $di = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d', strtotime('-1 month', strtotime($df))))));
                }
              } else {
                $status = 1;
                $dv = date('Y-m-d', strtotime('+1 month', strtotime($fu->data_vencimento)));
                $df = date('Y-m-d', strtotime('-10 day', strtotime($dv)));
                $di = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d', strtotime('-1 month', strtotime($df))))));

                \Log::info("dv fi:".$dv);
                $fatura = new FinContaFatura;
                $fatura->conta_id = $request->conta_id;
                $fatura->data_inicial = $di;
                $fatura->data_fechamento = $df;
                $fatura->data_vencimento = $dv;
                $fatura->status = $status;
                $fatura->save();
              }
              $conta_fatura_id = $fatura->id;
            }

            $desconto = 0;
            $juro = 0;
            $valor_recebido = $valor;
            $data_baixa = $data_emissao;
          }
        }
        $conta_fatura_id = isset($conta_fatura_id) ?  $conta_fatura_id : null;
        $rows = array(
          'movimento_id'     => $movimento_id,
          'empresa_id'       => $empresa,
          'categoria_id'     => $request->categoria_id,
          'conta_id'         => $request->conta_id,
          'conta_fatura_id'  => $conta_fatura_id,
          'user_id'          => $usuario,
          'recorrencia'      => $recorrencia,
          'num_doc'          => $num_doc,
          'descricao'        => $request->descricao,
          'flag_pontual'     => $request->flag_pontual ? $request->flag_pontual : 0,
          'observacao'       => $observacao,
          'fornecedor_id'    => $fornecedor,
          'centro_custo_id'  => $centro_custo,
          'valor'            => $valor,
          'desconto'         => $desconto,
          'valor_recebido'   => $valor_recebido,
          'juro'             => $juro,
          'data_baixa'       => $data_baixa,
          'data_emissao'     => $data_emissao,
          'data_vencimento'  => $data_vencimento,
        );
        // $insert[$i] = DB::table('fin_movimentos')->create($rows);
        $insert[$i] = FinMovimento::create($rows);
        $movimento_id = $insert[0]->id;
      }
    }


    return $insert;
  }
  public function edit(Request $request, $id)
  {
    $this->authorize('fin_movimento_create');
    $item = FinMovimento::where("id", $id)->with('fornecedor')->with('centroCusto')->first();
    $item2 = FinMovimento::where("id", $item->movimento_id)->first();
    $conta = $request->conta_id ? FinConta::where("id", $request->conta_id)->whereNull('deleted_at')->first() : null;
    if(isset($conta))
      $fa = FinContaFatura::where('conta_id', $conta->id)->where('status', 2)->first();
    if(isset($request->fatura)){
      $conta = FinContaFatura::select(DB::raw('fin_conta_faturas.id, fin_conta_faturas.conta_id, co.conta_tipo_id, co.descricao, fin_conta_faturas.data_vencimento, IFNULL(SUM(IF(ca.tipo = "Receita", -1*mo.valor , 1*mo.valor )),0) as valor'))
      ->join('fin_movimentos as mo','fin_conta_faturas.id', '=', 'mo.conta_fatura_id')
      ->join('fin_categorias as ca', 'ca.id', '=', 'mo.categoria_id')
      ->join('fin_contas as co', 'fin_conta_faturas.conta_id', '=', 'co.id')
      ->whereNull('mo.deleted_at')
      ->where('ca.nome', '!=', 'Transferência de Entrada')
      ->where('fin_conta_faturas.id', $request->fatura)
      ->groupBy('fin_conta_faturas.conta_id', 'fin_conta_faturas.id')
      ->first();
    }
    $contas = FinMovimento::select(DB::raw('fin_contas.id, fin_contas.descricao, fin_contas.padrao, SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereNotNull('fin_movimentos.data_baixa')
    ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->whereNull("fin_contas.deleted_at")
    ->groupBy('fin_contas.id', 'fin_contas.descricao', 'fin_contas.padrao')
    ->orderBy('fin_contas.padrao', 'desc')
    ->get();
    $categorias = FinCategoria::where('categoria_id', NULL)
    ->whereNull('deleted_at')
    ->where('tipo', $request->tipo)
    ->where('nome', '!=', 'Transferência de Saída')
    ->where('nome', '!=', 'Transferência de Entrada')
    ->where('nome', '!=', 'Saldo Inicial')
    ->orderBy('nome', 'asc')
    ->with('children')
    ->get();
    $tipo = $request->tipo;
    $fornecedores = Empresa::whereIn('empresa_tipo_id', [2,3])
    ->whereNull('deleted_at')
    ->orderBy('nome_fantasia', 'asc')
    ->get();
    $centrocustos = FinCentroCusto::whereNull('deleted_at')
    ->orderBy('nome', 'asc')
    ->get();
    $tipo = $request->tipo;
    return view('financeiro.movimento.create', compact('item', 'item2', 'conta', 'contas', 'categorias', 'tipo', 'fornecedores', 'centrocustos', 'fa'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('fin_movimento_update');
    $item = FinMovimento::find($id);
    $item->categoria_id = $request->categoria_id;
    $item->conta_id = $request->conta_id;
    $item->descricao = $request->descricao;
    $item->flag_pontual = $request->flag_pontual ? $request->flag_pontual : 0;
    $item->valor = str_replace(',', '.', str_replace('.', '', $request->valor));
    $item->data_vencimento = date('Y-m-d', strtotime(str_replace("/", "-", $request->data_vencimento)));
    $item->data_emissao = date('Y-m-d', strtotime(str_replace("/", "-", $request->data_emissao)));
    if($request->tipo == "Receita"){
      $pFornecedor = Empresa::where('razao_social', 'Cliente Padrão')->first();
    } else {
      $pFornecedor = Empresa::where('razao_social', 'Fornecedor Padrão')->first();
    }
    $pCentroCusto = FinCentroCusto::where('nome', 'Comum')->first();
    if(isset($request->outro)) {
      $item->centro_custo_id = isset($request->centro_custo_id) ? $request->centro_custo_id : $pCentroCusto->id;
      $item->observacao = isset($request->observacao) ? $request->observacao : '';
      if($request->tipo == "Receita"){
        $item->fornecedor_id = isset($request->cliente_id) ? $request->cliente_id : $pFornecedor->id;
      } else {
        $item->fornecedor_id = isset($request->fornecedor_id) ? $request->fornecedor_id : $pFornecedor->id;
      }
      $item->num_doc = isset($request->num_doc) ? $request->num_doc : NULL;
    } else {
      $item->centro_custo_id = $pCentroCusto->id;
      $item->observacao = '';
      $item->fornecedor_id = $pFornecedor->id;
    }
    if( $request->pago ) {
      $item->desconto = str_replace('.', '', $request->desconto);
      $item->desconto = str_replace(',', '.', $item->desconto);
      $item->valor_recebido = str_replace('.', '', $request->valor_recebido);
      $item->valor_recebido = str_replace(',', '.', $item->valor_recebido);
      $item->juro = str_replace('.', '', $request->juro);
      $item->juro = str_replace(',', '.', $item->juro);
      $data_baixa = str_replace("/", "-", $request->data_baixa);
      $item->data_baixa = date('Y-m-d H:m:s', strtotime($data_baixa));
    } else {
      $item->desconto = 0;
      $item->valor_recebido = 0;
      $item->juro = 0;
      $item->data_baixa = null;
    }
    $item->update();
    return $item;
  }
  public function destroy($id)
  {
    if( Gate::denies('fin_movimento_delete') ){
      return redirect()->back();
    }
    $item = FinMovimento::where('id', $id)->orWhere('movimento_id', $id)->update(['deleted_at' => Carbon::now()]);
    if($item)
      return redirect()->route('movimento.index');
    else
      return redirect()->route('movimento.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista(Request $request)
  {
    $this->authorize('fin_movimento_read');

    $s = FinMovimento::select(DB::raw('SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor_recebido , -1*fin_movimentos.valor_recebido )) as valor'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
    ->whereNotNull('fin_movimentos.data_baixa')
    ->whereNull("fin_movimentos.deleted_at");

    $i = FinMovimento::select(DB::raw('fin_movimentos.*, fin_categorias.tipo, fin_categorias.nome, empresas.cnpj, empresas.nome_fantasia, empresa_contas.banco_id, empresa_contas.agencia, empresa_contas.conta, fin_centro_custos.nome ccnome, fin_contas.img, fin_contas.conta_tipo_id'))
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->leftJoin('empresas', 'empresas.id', '=', 'fin_movimentos.fornecedor_id')
    ->leftJoin('empresa_contas', function ($q) {
      $q->where('empresas.id', '=', 'empresa_contas.empresa_id')
      ->where('empresa_contas.principal', 1);
    })
    ->leftJoin('fin_centro_custos', 'fin_centro_custos.id', '=', 'fin_movimentos.centro_custo_id')
    ->whereNull("fin_movimentos.deleted_at")
    ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7]);

    $filtro = 0;
    $order = isset($request->order) ? $request->order : 'data_baixa';
    $sort = isset($request->sort) ? $request->sort : 'asc' ;

    $tipo = isset($request->tipo) ? $request->tipo : 'Extrato' ;
    $regime = isset($request->regime) ? $request->regime : 'caixa' ;

    $pontual = isset($request->input_pontual) ? $request->input_pontual : 'todas' ;

    $lancamento = isset($request->lancamento) ? $request->lancamento : NULL;
    $conta = isset($request->conta) ? $request->conta : NULL;
    $fornecedor = isset($request->ffornecedor_id) ? $request->ffornecedor_id : NULL;
    $categoria = isset($request->fcategoria_id) ? $request->fcategoria_id : NULL;
    $centrocusto = isset($request->fcentrocusto_id) ? $request->fcentrocusto_id : NULL;
    $pesquisa = $request->pesquisa;
    $fdate = $request->data;

    $di = isset($request->data_inicio) ? str_replace("/", "-", $request->data_inicio) : date('Y-m');
    $df = str_replace("/", "-", $request->data_fim);

    if(isset($pesquisa) || $pesquisa != ''){
      $i->where(function($q) use ($pesquisa) {
        $dt = date('Y-m-d', strtotime(str_replace("/", "-", $pesquisa)));
        $q->where('fin_movimentos.descricao', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_contas.descricao', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_categorias.nome', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_movimentos.num_doc', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_movimentos.data_vencimento', 'LIKE', "%$dt%")
        ->orWhere('fin_movimentos.data_emissao', 'LIKE', "%$dt%")
        ->orWhere('fin_movimentos.data_baixa', 'LIKE', "%$dt%");
      })->orderBy('fin_movimentos.descricao', 'asc')
      ->orderBy('fin_categorias.nome', 'asc')
      ->orderBy('fin_contas.descricao', 'asc');
    }

    if($fdate == "fdho"){
      $from = date('Y-m-d');
      $to = date('Y-m-d');
      $f = date('d/m/Y', strtotime($from));
      $fdate = "Hoje: ".$f;
      $filtro = 0;
    } else if($fdate == "fdot"){
      $from = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
      $to = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "fdsd"){
      $from = date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d'))));
      $to = date('Y-m-d');
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "fdut"){
      $from = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
      $to = date('Y-m-d');
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if( $fdate == "fdmp"){
      $from = date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-d'))));
      $to = date('Y-m-t', strtotime($from));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "pees"){
      $from =  date('Y-m-d', strtotime($di));
      $to = date('Y-m-d', strtotime($df));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "fdem") {
      $from = date('Y-m-01', strtotime($di));
      $to = date('Y-m-t', strtotime($from));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
    }

    if($lancamento == "pagos"){
      $i->whereNotNull('fin_movimentos.data_baixa');
    } else if($lancamento == "npagos"){
      $i->whereNull('fin_movimentos.data_baixa');
    }

    if($tipo != 'Extrato'){
      $i->where('fin_categorias.tipo', $tipo);
    }

    if($pontual != 'todas'){
      $i->where('fin_movimentos.flag_pontual', $pontual);
    }

    if($categoria){
      $i->where('fin_movimentos.categoria_id', $categoria);
    }

    if($centrocusto){
      $i->where('fin_movimentos.centro_custo_id', $centrocusto);
    }

    if($conta){
      $i->whereIn('fin_movimentos.conta_id', $conta);
      $s->whereIn('fin_movimentos.conta_id', $conta);
    }

    if($fornecedor){
      $i->where('fin_movimentos.fornecedor_id', $fornecedor);
    }

    if(isset($from)){
      $s->where('fin_movimentos.data_baixa', "<", $from);
      $i->where(function($q) use ($from, $to, $regime) {
        if($regime == 'caixa'){
          $q->whereRaw('(fin_movimentos.data_baixa is NUll AND fin_movimentos.data_vencimento BETWEEN ? AND ?) OR (fin_movimentos.data_baixa is NOT NUll AND fin_movimentos.data_baixa BETWEEN ? AND ?)', [$from, $to, date('Y-m-d 00:00:00', strtotime($from)), date('Y-m-d 23:59:59', strtotime($to))]);
        } else if($regime == 'cadastro') {
          $q->whereBetween('fin_movimentos.created_at', [$from, $to]);
        } else {
          $q->whereBetween('fin_movimentos.data_emissao', [$from, $to]);
        }
      });
    }

    if($order == 'descricao'){
      $i->orderBy('fin_movimentos.descricao', $sort);
    } else if( $order == 'tipo'){
      $i->orderBy('fin_categorias.tipo', $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    } else if( $order == 'categoria_nome'){
      $i->orderBy('fin_categorias.nome', $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    } else if( $order == 'nome_fantasia'){
      $i->orderBy('empresas.nome_fantasia', $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    } else {
      $i->orderBy($order, $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    }

    $itens = $i->get();
    $saldo = $s->first();
    $agora = Carbon::today();
    $agora = $agora->toDateString();
    $recebidas = 0;
    $areceber = 0;
    $apagar = 0;
    $rvencidas = 0;
    $dvencidas = 0;
    $total = 0;
    $pagas = 0;
    foreach ($itens as $i) {
      if(isset($i->data_baixa)){
        if($i->tipo == "Receita"){
          $recebidas += $i->valor_recebido;
        } else {
          $pagas += $i->valor_recebido;
        }
      } else if(!isset($i->data_baixa) && $i->data_vencimento >= $agora){
        if($i->tipo == "Receita"){
          $areceber += $i->valor;
        } else {
          $apagar += $i->valor;
        }
      } else if(!isset($i->data_baixa) && $i->data_vencimento < $agora){
        if($i->tipo == "Receita"){
          $rvencidas += $i->valor;
        } else {
          $dvencidas += $i->valor;
        }
      }
      $total = $recebidas - $pagas;
    }
    return response(view('financeiro.movimento.lista', compact('itens', 'agora', 'recebidas', 'areceber','rvencidas', 'pagas', 'apagar', 'dvencidas', 'total', 'tipo', 'fdate', 'saldo')));
  }
  public function transferencia(Request $request)
  {
    $this->authorize('fin_transferencia_create');
    if($request->conta_saida_id == $request->conta_entrada_id){
      $error = ["error" => ["conta_entrada_id" => "A conta de destino não pode ser a mesma conta de origem!"]];
      return $error;
    }
    $saida = new FinMovimento;
    $conta = FinConta::where("id", $request->conta_entrada_id)->whereNull('deleted_at')->first();
    if ($conta->conta_tipo_id == 4) {
      $categoria = FinCategoria::where('nome', 'Pagamento de Fatura')->first();
      if(!$categoria){
        $categoria = new FinCategoria;
        $categoria->tipo = 'Despesa';
        $categoria->nome = 'Pagamento de Fatura';
        $categoria->descricao = 'Pagamento de Fatura';
        $categoria->save();
      }
    } else {
      $categoria = FinCategoria::where('nome', 'Transferência de Saída')->first();
      if(!$categoria){
        $categoria = new FinCategoria;
        $categoria->tipo = 'Despesa';
        $categoria->nome = 'Transferência de Saída';
        $categoria->descricao = 'Transferência de Saída';
        $categoria->save();
      }
    }
    $saida->categoria_id = $categoria->id;
    $saida->user_id  = auth()->user()->id;
    $saida->conta_id = $request->conta_saida_id;
    if($request->descricao)
      $saida->descricao = $request->descricao;
    else
      $saida->descricao = $request->conta_saida_id.'>>'.$request->conta_entrada_id;

    $data = str_replace("/", "-", $request->data_transferencia);
    $saida->data_vencimento = date('Y-m-d', strtotime($data));
    $saida->data_emissao = date('Y-m-d', strtotime($data));
    $saida->data_baixa = date('Y-m-d H:m:s', strtotime($data));
    $valor = str_replace('.', '', $request->valor_transferencia);
    $valor = str_replace(',', '.', $valor);
    $saida->valor = $valor;
    $saida->valor_recebido = $valor;
    $saida->juro = 0;
    $saida->desconto = 0;
    if($request->conta_fatura_id)
      $saida->conta_fatura_id = $request->conta_fatura_id;
    $saida->observacao = isset($request->observacao) ? $request->observacao : '';
    $saida->save();

    $entrada = new FinMovimento;
    $entrada->movimento_id = $saida->id;
    $categoria = FinCategoria::where('nome', 'Transferência de Entrada')->first();
    if(!$categoria){
      $categoria = new FinCategoria;
      $categoria->tipo = 'Receita';
      $categoria->nome = 'Transferência de Entrada';
      $categoria->descricao = 'Transferência de Entrada';
      $categoria->save();
    }
    $entrada->categoria_id = $categoria->id;
    $entrada->user_id  = auth()->user()->id;
    $entrada->conta_id = $request->conta_entrada_id;

    if($request->descricao)
      $entrada->descricao = $request->descricao;
    else
      $entrada->descricao = $request->conta_saida_id.'>>'.$request->conta_entrada_id;

    $entrada->data_vencimento = date('Y-m-d', strtotime($data));
    $entrada->data_emissao = date('Y-m-d', strtotime($data));
    $entrada->data_baixa = date('Y-m-d H:m:s', strtotime($data));
    $entrada->desconto = 0;
    if($request->conta_fatura_id)
      $entrada->conta_fatura_id = $request->conta_fatura_id;
    $entrada->juro = 0;
    $entrada->valor = $valor;
    $entrada->valor_recebido = $valor;
    $entrada->observacao = isset($request->observacao) ? $request->observacao : '';
    $entrada->save();
    $saida->movimento_id = $entrada->id;
    $saida->update();
    $item['saida'] = $saida;
    $item['entrada'] = $entrada;
    return $item;
  }
  public function transferenciaUpdate(Request $request, $id)
  {
    $this->authorize('fin_transferencia_update');
    $saida = FinMovimento::find($id);
    $saida->user_id  = auth()->user()->id;
    $saida->conta_id = $request->conta_saida_id;
    if($request->descricao)
      $saida->descricao = $request->descricao;
    else
      $saida->descricao = $request->conta_saida_id.'>>'.$request->conta_entrada_id;
    $data = str_replace("/", "-", $request->data_transferencia);
    $saida->data_vencimento = date('Y-m-d', strtotime($data));
    $saida->data_emissao = date('Y-m-d', strtotime($data));
    $saida->data_baixa = date('Y-m-d H:m:s', strtotime($data));
    $valor = str_replace('.', '', $request->valor_transferencia);
    $valor = str_replace(',', '.', $valor);
    $saida->valor = $valor;
    $saida->valor_recebido = $valor;
    $saida->observacao = isset($request->observacao) ? $request->observacao : '';
    $saida->update();

    $entrada = FinMovimento::find($saida->movimento_id);
    $entrada->user_id  = auth()->user()->id;
    $entrada->conta_id = $request->conta_entrada_id;
    if($request->descricao)
      $entrada->descricao = $request->descricao;
    else
      $entrada->descricao = $request->conta_saida_id.'>>'.$request->conta_entrada_id;
    $entrada->data_vencimento = date('Y-m-d', strtotime($data));
    $entrada->data_emissao = date('Y-m-d', strtotime($data));
    $entrada->data_baixa = date('Y-m-d H:m:s', strtotime($data));
    $entrada->valor = $valor;
    $entrada->valor_recebido = $valor;
    $entrada->observacao = isset($request->observacao) ? $request->observacao : '';
    $entrada->update();

    $item['saida'] = $saida;
    $item['entrada'] = $entrada;

    return $item;
  }
  public function exportar()
  {



//, empresa_contas.banco_id, empresa_contas.agencia, empresa_contas.conta, fin_centro_custos.nome ccnome, fin_contas.conta_tipo_id

    $i = FinMovimento::select(DB::raw('fin_categorias.tipo AS Tipo, CONCAT(fin_categorias.cod,fin_categorias.nome) AS Categoria, fin_movimentos.descricao AS "Descrição", fin_movimentos.conta_id AS "Conta ID", empresas.cnpj AS CNPJ, empresas.nome_fantasia AS "Nome Fantasia", fin_movimentos.observacao AS "Observações", fin_movimentos.data_emissao AS "Emissão", fin_movimentos.data_baixa AS "Baixa", fin_movimentos.data_vencimento AS "Vencimento", FORMAT(fin_movimentos.desconto, 2, "de_DE") as "Desconto", FORMAT(fin_movimentos.juro, 2, "de_DE") AS "Juro", FORMAT(fin_movimentos.valor, 2, "de_DE") AS "Valor", FORMAT(fin_movimentos.valor_recebido, 2, "de_DE") AS "Valor Recebido"'))
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->leftJoin('empresas', 'empresas.id', '=', 'fin_movimentos.fornecedor_id')
    ->leftJoin('empresa_contas', function ($q) {
      $q->where('empresas.id', '=', 'empresa_contas.empresa_id')
      ->where('empresa_contas.principal', 1);
    })
    ->leftJoin('fin_centro_custos', 'fin_centro_custos.id', '=', 'fin_movimentos.centro_custo_id')
    ->whereNull("fin_movimentos.deleted_at")
    ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7]);

    $filtro = 0;
    $order = $request->order;
    $order = isset($request->order) ? $order : 'data_baixa';

    $sort = $request->sort;
    $sort = isset($request->sort) ? $sort : 'asc' ;

    $tipo = isset($request->tipo) ? $request->tipo : 'Extrato' ;
    $regime = isset($request->regime) ? $request->regime : 'caixa' ;
    $lancamento = isset($request->lancamento) ? $request->lancamento : NULL;
    $conta = isset($request->conta) ? $request->conta : NULL;
    $fornecedor = isset($request->ffornecedor_id) ? $request->ffornecedor_id : NULL;
    $categoria = isset($request->fcategoria_id) ? $request->fcategoria_id : NULL;
    $pesquisa = $request->pesquisa;
    $fdate = $request->data;

    $di = isset($request->data_inicio) ? str_replace("/", "-", $request->data_inicio) : date('Y-m');
    $df = str_replace("/", "-", $request->data_fim);

    if(isset($pesquisa) || $pesquisa != ''){
      $i->where(function($q) use ($pesquisa) {
        $dt = date('Y-m-d', strtotime(str_replace("/", "-", $pesquisa)));
        $q->where('fin_movimentos.descricao', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_contas.descricao', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_categorias.nome', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_movimentos.num_doc', 'LIKE', "%$pesquisa%")
        ->orWhere('fin_movimentos.data_vencimento', 'LIKE', "%$dt%")
        ->orWhere('fin_movimentos.data_emissao', 'LIKE', "%$dt%")
        ->orWhere('fin_movimentos.data_baixa', 'LIKE', "%$dt%");
      })->orderBy('fin_movimentos.descricao', 'asc')
      ->orderBy('fin_categorias.nome', 'asc')
      ->orderBy('fin_contas.descricao', 'asc');
    }

    if($fdate == "fdho"){
      $from = date('Y-m-d');
      $to = date('Y-m-d');
      $f = date('d/m/Y', strtotime($from));
      $fdate = "Hoje: ".$f;
      $filtro = 0;
    } else if($fdate == "fdot"){
      $from = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
      $to = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "fdsd"){
      $from = date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d'))));
      $to = date('Y-m-d');
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "fdut"){
      $from = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
      $to = date('Y-m-d');
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if( $fdate == "fdmp"){
      $from = date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-d'))));
      $to = date('Y-m-t', strtotime($from));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "pees"){
      $from =  date('Y-m-d', strtotime($di));
      $to = date('Y-m-d', strtotime($df));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
      $filtro = 0;
    } else if($fdate == "fdem") {
      $from = date('Y-m-01', strtotime($di));
      $to = date('Y-m-t', strtotime($from));
      $f = date('d/m/Y', strtotime($from));
      $t = date('d/m/Y', strtotime($to));
      $fdate = "De: ".$f." - ".$t;
    }


    if($lancamento == "pagos"){
      $i->whereNotNull('fin_movimentos.data_baixa');
    } else if($lancamento == "npagos"){
      $i->whereNull('fin_movimentos.data_baixa');
    }

    if($tipo != 'Extrato'){
      $i->where('fin_categorias.tipo', $tipo);
    }

    if($categoria){
      $i->where('fin_movimentos.categoria_id', $categoria);
    }

    if($conta){
      $i->whereIn('fin_movimentos.conta_id', $conta);
    }

    if($fornecedor){
      $i->where('fin_movimentos.fornecedor_id', $fornecedor);
    }

    if(isset($from)){
      $i->where(function($q) use ($from, $to, $regime) {
        if($regime == 'caixa'){
          $q->whereRaw('(fin_movimentos.data_baixa is NUll AND fin_movimentos.data_vencimento BETWEEN ? AND ?) OR (fin_movimentos.data_baixa is NOT NUll AND fin_movimentos.data_baixa BETWEEN ? AND ?)', [$from, $to, date('Y-m-d 00:00:00', strtotime($from)), date('Y-m-d 23:59:59', strtotime($to))]);
        } else if($regime == 'cadastro') {
          $q->whereBetween('fin_movimentos.created_at', [$from, $to]);
        } else {
          $q->whereBetween('fin_movimentos.data_emissao', [$from, $to]);
        }
      });
    }

    if($order == 'descricao'){
      $i->orderBy('fin_movimentos.descricao', $sort);
    } else if( $order == 'tipo'){
      $i->orderBy('fin_categorias.tipo', $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    } else if( $order == 'categoria_nome'){
      $i->orderBy('fin_categorias.nome', $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    } else if( $order == 'nome_fantasia'){
      $i->orderBy('empresas.nome_fantasia', $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    } else {
      $i->orderBy($order, $sort)
      ->orderBy('fin_movimentos.descricao', 'asc');
    }


    $export = $i->get();

    $exFile = Excel::load('doc/Movimentacoes_Diretorio_Digital.xlsx', function($excel) use ($export){
      $excel->sheet('Movimentações', function($sheet) use ($export){
        $sheet->fromArray($export, null, 'A4');
      });
    })->string('xlsx');

    $response =  array(
       'name' => "Movimentações", //no extention needed
       'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($exFile) //mime type of used format
     );
    return response()->json($response);
  }
}


