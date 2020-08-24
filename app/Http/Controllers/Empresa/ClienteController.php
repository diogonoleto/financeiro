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

class ClienteController extends Controller
{
  public function index()
  {
    if(Gate::denies('cliente_read')){
      return redirect()->route('financeiro');
    }
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $cliente = Empresa::where('empresa_tipo_id', 3)->whereNull("deleted_at")->get();
    $qtde_empresa = $account->qtde_cliente == NULL ? NULL : ( $account->qtde_cliente - count($cliente) ) ;
    $title = "Clientes";
    return view('cliente.index', compact('title', 'qtde_empresa'));
  }
  public function create(Empresa $cliente)
  {
    if(Gate::denies('cliente_create')){
      return redirect()->back();
    }
    $item = $cliente;
    $bancos = SisBanco::whereNull("deleted_at")->get();
    return view('cliente.create', compact('item', 'bancos'));
  }
  public function store(Request $request, Empresa $cliente)
  {
    $this->authorize('cliente_create');
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $emp = Empresa::where('empresa_tipo_id', 3)->whereNull('deleted_at')->get();
    $qtde_empresa = $account->qtde_cliente == NULL ? 1 : ( $account->qtde_cliente - count($emp) ) ;
    if($qtde_empresa <= 0){
      $error["error"] = ["empresa_limite" => "Não pode adicionar devido ao limite de cliente."];
      return $error;
    }
    $request['cnpj'] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $request->cnpj)));
    $request['nome_fantasia'] = isset($request->nome_fantasia) ? $request->nome_fantasia : $request->razao_social;
    $cnpj = $request->cnpj;
    $this->validate($request, [
      'cnpj'           => 'cpf_cnpj|unique:empresas,cnpj,NULL,id,deleted_at,NULL,empresa_tipo_id,3,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'razao_social'   => 'required|unique:empresas,razao_social,NULL,id,deleted_at,NULL,empresa_tipo_id,3,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'nome_fantasia'  => 'required|unique:empresas,nome_fantasia,NULL,id,deleted_at,NULL,empresa_tipo_id,3,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);

    $cliente->cnpj = strlen($cnpj) > 0 ? $cnpj : NULL;
    $cliente->empresa_tipo_id = 3;
    $cliente->empresa_entidade_id = 1;
    $cliente->razao_social = $request->razao_social;
    $cliente->nome_fantasia = $request->nome_fantasia;
    $respCliente = $cliente->save();
    if($respCliente && $request->telefone){
      $contato = new EmpresaContato;
      $contato->empresa_id  = $cliente->id;
      $contato->principal = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato = 2;
      $contato->descricao = $request->telefone;
      $contato->save();
    }
    if($respCliente && $request->email){
      $contato = new EmpresaContato;
      $contato->empresa_id  = $cliente->id;
      $contato->principal = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato = 1;
      $contato->descricao = $request->email;
      $contato->save();
    }
    if($respCliente && $request->endereco){
      $endereco = new EmpresaEndereco;
      $endereco->empresa_id       = $cliente->id;
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
    if($respCliente && (isset($request->rg) || isset($request->cnh) || isset($request->crea) || isset($request->crm) || isset($request->cro) || isset($request->oab) || isset($request->data_fundacao) )) {
      $maisInfo = new EmpresaMaisInfo;
      $maisInfo->empresa_id = $cliente->id;
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
    if($respCliente && $request->conta){
      $banco = SisBanco::where("id", $request->banco_id)->whereNull("deleted_at")->first();
      if($banco){
        $conta = new EmpresaConta;
        $conta->empresa_id = $cliente->id;
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
      return $cliente;

    return redirect()->route('cliente.edit', ['id' => $cliente->id]);
  }
  public function edit($id)
  {
    if(Gate::denies('cliente_update')){
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
    return view("cliente.create", compact('item', 'bancos'));
  }
  public function update($id, Request $request)
  {
    $this->authorize('cliente_update');
    $cliente = Empresa::find($id);
    if($cliente->razao_social == 'Cliente Padrão'){
      $error["error"] = ["cliente_padrao" => "O Cliente Padrão não pode ser editado."];
      return $error;
    }
    $request['cnpj'] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $request->cnpj)));
    $request['nome_fantasia'] = isset($request->nome_fantasia) ? $request->nome_fantasia : $request->razao_social;
    $cnpj = $request->cnpj;
    $this->validate($request, [
      'cnpj'           => 'cpf_cnpj|unique:empresas,cnpj,'.$id.',id,deleted_at,NULL,empresa_tipo_id,3,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'razao_social'   => 'required|unique:empresas,razao_social,'.$id.',id,deleted_at,NULL,empresa_tipo_id,3,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'nome_fantasia'  => 'required|unique:empresas,nome_fantasia,'.$id.',id,deleted_at,NULL,empresa_tipo_id,3,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $cliente->cnpj = strlen($cnpj) > 0 ? $cnpj : NULL;
    $cliente->razao_social = $request->razao_social;
    $cliente->nome_fantasia = $request->nome_fantasia;
    $cliente->update();
    return $cliente;
  }
  public function destroy($id, Request $request)
  {
    $this->authorize('cliente_delete');
    if($request->acao == "endereco"){
      $cliente = EmpresaEndereco::find($id);
      $cliente->deleted_at = Carbon::now();
      $cliente->update();
      $delete = $cliente;
      if($delete)
        return redirect()->route('cliente.edit', ['id' => $cliente->empresa_id]);
    }
    if($request->acao == "contato"){
      $cliente = EmpresaContato::find($id);
      $cliente->deleted_at = Carbon::now();
      $cliente->update();
      $delete = $cliente;
      if($delete)
        return redirect()->route('cliente.edit', ['id' => $cliente->empresa_id]);
    }
    if($request->acao == "daba"){
      $cliente = EmpresaConta::find($id);
      $cliente->deleted_at = Carbon::now();
      $cliente->update();
      $delete = $cliente;
      if($delete)
        return redirect()->route('cliente.edit', ['id' => $cliente->empresa_id]);
    }

    $cliente = Empresa::find($id);
    if($cliente == NULL)
      return redirect()->route('cliente.index');
    $cliente->deleted_at = Carbon::now();
    $cliente->update();
    $delete = $cliente;
    if($delete)
      return redirect()->route('cliente.index');
  }
  public function lista()
  {
    $this->authorize('cliente_read');
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome_fantasia';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'asc' ;
    $search = Request('input-search');
    $search = str_replace('/', '', str_replace('-', '', str_replace('.', '', $search)));
    $i = Empresa::where('empresa_tipo_id', 3)
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
    return response(view('cliente.lista', compact('itens')));
  }
}
