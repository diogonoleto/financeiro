<?php
namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa\Empresa;
use App\Models\Empresa\EmpresaContato;
use App\Models\Empresa\EmpresaEndereco;
use App\Models\Empresa\EmpresaConta;
use App\Models\Empresa\EmpresaMaisInfo;
use App\Models\Config\Banco\SisBanco;
use Carbon\Carbon;
use App\SisConta;
use Gate;

class FornecedorController extends Controller
{
  public function index()
  {
    if(Gate::denies('fornecedor_read')){
      return redirect()->route('financeiro');
    }
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $fornecedor = Empresa::where('empresa_tipo_id', 2)->whereNull("deleted_at")->get();
    $qtde_empresa = $account->qtde_fornecedor == NULL ? NULL : ( $account->qtde_fornecedor - count($fornecedor) ) ;
    $title = "Fornecedores";
    return view('fornecedor.index', compact('title', 'qtde_empresa'));
  }
  public function create(Empresa $fornecedor)
  {
    if(Gate::denies('fornecedor_create')){
      return redirect()->back();
    }
    $item = $fornecedor;
    $bancos = SisBanco::whereNull("deleted_at")->get();
    return view('fornecedor.create', compact('item', 'bancos'));
  }
  public function store(Request $request, Empresa $fornecedor)
  {
    $this->authorize('fornecedor_create');
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $emp = Empresa::where('empresa_tipo_id', 2)->whereNull('deleted_at')->get();
    $qtde_empresa = $account->qtde_fornecedor == NULL ? 1 : ( $account->qtde_fornecedor - count($emp) ) ;
    if($qtde_empresa <= 0){
      $error["error"] = ["fornecedor_limite" => "Não pode adicionar devido ao limite de de fornecedor."];
      return $error;
    }
    $request['cnpj'] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $request->cnpj)));
    $request['nome_fantasia'] = isset($request->nome_fantasia) ? $request->nome_fantasia : $request->razao_social;
    $cnpj = $request->cnpj;
    $this->validate($request, [
      'cnpj'           => 'cpf_cnpj|unique:empresas,cnpj,NULL,id,deleted_at,NULL,empresa_tipo_id,2,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'razao_social'   => 'required|unique:empresas,razao_social,NULL,id,deleted_at,NULL,empresa_tipo_id,2,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'nome_fantasia'  => 'required|unique:empresas,nome_fantasia,NULL,id,deleted_at,NULL,empresa_tipo_id,2,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);

    $fornecedor->cnpj = strlen($cnpj) > 0 ? $cnpj : NULL;
    $fornecedor->empresa_tipo_id = 2;
    $fornecedor->empresa_entidade_id = 1;
    $fornecedor->razao_social = $request->razao_social;
    $fornecedor->nome_fantasia = $request->nome_fantasia;
    $respFornecedor = $fornecedor->save();
    if($respFornecedor && $request->telefone){
      $contato = new EmpresaContato;
      $contato->empresa_id  = $fornecedor->id;
      $contato->principal = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato = 2;
      $contato->descricao = $request->telefone;
      $contato->save();
    }
    if($respFornecedor && $request->email){
      $contato = new EmpresaContato;
      $contato->empresa_id  = $fornecedor->id;
      $contato->principal = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato = 1;
      $contato->descricao = $request->email;
      $contato->save();
    }
    if($respFornecedor && $request->endereco){
      $endereco = new EmpresaEndereco;
      $endereco->empresa_id    = $fornecedor->id;
      $endereco->principal     = 1;
      $endereco->tipo_endereco = 1;
      $endereco->cep           = $request->cep;
      $endereco->cidade        = $request->cidade;
      $endereco->logradouro    = $request->logradouro;
      $endereco->numero        = $request->numero;
      $endereco->complemento   = $request->complemento;
      $endereco->bairro        = $request->bairro;
      $endereco->save();
    }
    if($respFornecedor && (isset($request->rg) || isset($request->cnh) || isset($request->crea) || isset($request->crm) || isset($request->cro) || isset($request->oab) || isset($request->data_fundacao) )) {
      $maisInfo = new EmpresaMaisInfo;
      $maisInfo->empresa_id = $fornecedor->id;
      $maisInfo->rg = isset($request->rg) ? $request->rg : NULL;
      $maisInfo->oerg = (isset($request->oerg) && isset($request->rg)) ? $request->oerg : NULL;
      $maisInfo->cnh = isset($request->cnh) ? $request->cnh : NULL;
      $maisInfo->crea = isset($request->crea) ? $request->crea : NULL;
      $maisInfo->crm = isset($request->crm) ? $request->crm : NULL;
      $maisInfo->cro = isset($request->cro) ? $request->cro : NULL;
      $maisInfo->oab = isset($request->oab) ? $request->oab : NULL;
      $maisInfo->data_fundacao = isset($request->data_fundacao) ? date('Y-m-d', strtotime(str_replace("/", "-", $request->data_fundacao))) : NULL;
      $maisInfo->save();
    }
    if($respFornecedor && $request->conta){
      $banco = SisBanco::where("id", $request->banco_id)->whereNull("deleted_at")->first();
      if($banco){
        $conta = new EmpresaConta;
        $conta->empresa_id = $fornecedor->id;
        $conta->principal  = 1;
        $conta->tipo_conta = 1;
        $conta->banco_id   = $banco->id;
        $conta->codigo     = $banco->codigo;
        $conta->agencia    = $request->agencia;
        $conta->conta      = $request->conta;
        $conta->save();
      }
    }
    if($request->movimento == 1)
      return $fornecedor;
    return redirect()->route('fornecedor.edit', ['id' => $fornecedor->id]);
  }
  public function edit($id)
  {
    if(Gate::denies('fornecedor_update')){
      return redirect()->back();
    }
    $item = Empresa::where('id', $id)->with([
      'maisInfo',
      'contatos' => function($q){
        $q->orderBy('principal')->whereNull('deleted_at');;
      },
      'enderecos' => function($q){
        $q->orderBy('principal')->whereNull('deleted_at');;
      },
      'contas'=> function($q){
        $q->orderBy('principal')->whereNull('deleted_at');;
      }
    ])->first();
    $bancos = SisBanco::whereNull("deleted_at")->get();
    return view("fornecedor.create", compact('item', 'bancos'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('fornecedor_update');
    $fornecedor = Empresa::find($id);
    if($fornecedor->razao_social == 'Fornecedor Padrão'){
      $error["error"] = ["fornecedor_padrao" => "O Cliente Padrão não pode ser editado."];
      return $error;
    }
    $request['cnpj'] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $request->cnpj)));
    $request['nome_fantasia'] = isset($request->nome_fantasia) ? $request->nome_fantasia : $request->razao_social;
    $cnpj = $request->cnpj;
    $this->validate($request, [
      'cnpj'           => 'cpf_cnpj|unique:empresas,cnpj,'.$id.',id,deleted_at,NULL,empresa_tipo_id,2,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'razao_social'   => 'required|unique:empresas,razao_social,'.$id.',id,deleted_at,NULL,empresa_tipo_id,2,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'nome_fantasia'  => 'required|unique:empresas,nome_fantasia,'.$id.',id,deleted_at,NULL,empresa_tipo_id,2,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $fornecedor->cnpj = strlen($cnpj) > 0 ? $cnpj : NULL;
    $fornecedor->razao_social = $request->razao_social;
    $fornecedor->nome_fantasia = $request->nome_fantasia;
    $fornecedor->update();
    return redirect()->route('fornecedor.edit', ['id' => $id]);
  }
  public function destroy($id, Request $request)
  {
    $this->authorize('fornecedor_delete');
    if($request->acao == "endereco"){
      $fornecedor = EmpresaEndereco::find($id);
      $fornecedor->deleted_at = Carbon::now();
      $fornecedor->update();
      $delete = $fornecedor;
      if($delete)
        return redirect()->route('fornecedor.edit', ['id' => $fornecedor->empresa_id]);
    }
    if($request->acao == "contato"){
      $fornecedor = EmpresaContato::find($id);
      $fornecedor->deleted_at = Carbon::now();
      $fornecedor->update();
      $delete = $fornecedor;
      if($delete)
        return redirect()->route('fornecedor.edit', ['id' => $fornecedor->empresa_id]);
    }
    if($request->acao == "daba"){
      $fornecedor = EmpresaConta::find($id);
      $fornecedor->deleted_at = Carbon::now();
      $fornecedor->update();
      $delete = $fornecedor;
      if($delete)
        return redirect()->route('fornecedor.edit', ['id' => $fornecedor->empresa_id]);
    }
    $fornecedor = Empresa::find($id);
    if($fornecedor == NULL)
      return redirect()->route('fornecedor.index');
    $fornecedor->deleted_at = Carbon::now();
    $fornecedor->update();
    $delete = $fornecedor;
    if($delete)
      return redirect()->route('fornecedor.index');
  }
  public function lista()
  {
    $this->authorize('fornecedor_read');
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome_fantasia';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'asc' ;
    $search = Request('input-search');
    $search = str_replace('/', '', str_replace('-', '', str_replace('.', '', $search)));
    $i = Empresa::where('empresa_tipo_id', 2)
    ->whereNull('deleted_at');
    if( $search || $search != '' ){
      $i->where(function($q) use ($search) {
        $q->where('nome_fantasia', 'LIKE', "%$search%")
        ->orWhere('nome_fantasia', 'LIKE', "%$search%")
        ->orWhere('razao_social', 'LIKE', "%$search%")
        ->orWhere('cnpj', 'LIKE', "%$search%");
      })->orderBy('nome_fantasia', 'asc')
      ->orderBy('razao_social', 'asc');
    } else {
      $i->orderBy($order, $sort);
    }
    $itens = $i->with([
      'maisInfo',
      'contatos' => function($q){
        $q->orderBy('principal')->whereNull('deleted_at');;
      },
      'enderecos' => function($q){
        $q->orderBy('principal')->whereNull('deleted_at');;
      },
      'contas'=> function($q){
        $q->orderBy('principal')->whereNull('deleted_at');;
      }
    ])->paginate(28);
    return response(view('fornecedor.lista', compact('itens')));
  }
}
