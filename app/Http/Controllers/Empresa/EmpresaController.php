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
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use App\SisConta;
use Correios;
use Storage;
use Gate;
use File;

class EmpresaController extends Controller
{
  public function index()
  {
    if(Gate::denies('empresa_read')){
      return redirect()->route('financeiro');
    }
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $empresa = Empresa::where('empresa_tipo_id', 1)->whereNull("deleted_at")->get();
    $qtde_empresa = $account->qtde_empresa == NULL ? NULL : ( $account->qtde_empresa - count($empresa) ) ;
    $title = "Empresas";
    return view('empresa.index', compact('title', 'qtde_empresa'));
  }
  public function create(Empresa $empresa)
  {
    if(Gate::denies('empresa_create')){
      return redirect()->back();
    }
    $item = $empresa;
    $bancos = SisBanco::whereNull("deleted_at")->get();
    return view('empresa.create', compact('item', 'bancos'));
  }
  public function store(Request $request, Empresa $empresa)
  {
    $this->authorize('empresa_create');
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $emp = Empresa::where('empresa_tipo_id', 1)->whereNull('deleted_at')->get();
    $qtde_empresa = $account->qtde_empresa == NULL ? 1 : ( $account->qtde_empresa - count($emp) ) ;
    if($qtde_empresa <= 0){
      $error["error"] = ["empresa_limite" => "Não pode adicionar devido ao limite de de empresa."];
      return $error;
    }
    $request['cnpj'] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $request->cnpj)));
    $request['nome_fantasia'] = isset($request->nome_fantasia) ? $request->nome_fantasia : $request->razao_social;
    $cnpj = $request->cnpj;
    $this->validate($request, [
      'cnpj'           => 'cpf_cnpj|unique:empresas,cnpj,NULL,id,deleted_at,NULL,empresa_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'razao_social'   => 'required|unique:empresas,razao_social,NULL,id,deleted_at,NULL,empresa_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'nome_fantasia'  => 'required|unique:empresas,nome_fantasia,NULL,id,deleted_at,NULL,empresa_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);

    $empresa->cnpj = strlen($cnpj) > 0 ? $cnpj : NULL;
    $empresa->empresa_tipo_id = 1;
    $empresa->empresa_entidade_id = 1;
    $empresa->razao_social = $request->razao_social;
    $empresa->nome_fantasia = $request->nome_fantasia;
    $respEmpresa = $empresa->save();
    if($respEmpresa && $request->telefone){
      $contato = new EmpresaContato;
      $contato->empresa_id  = $empresa->id;
      $contato->principal = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato = 2;
      $contato->descricao = $request->telefone;
      $contato->save();
    }
    if($respEmpresa && $request->email){
      $contato = new EmpresaContato;
      $contato->empresa_id  = $empresa->id;
      $contato->principal = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato = 1;
      $contato->descricao = $request->email;
      $contato->save();
    }
    if($respEmpresa && $request->endereco){
      $endereco = new EmpresaEndereco;
      $endereco->empresa_id       = $empresa->id;
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
    if($respEmpresa && (isset($request->rg) || isset($request->cnh) || isset($request->crea) || isset($request->crm) || isset($request->cro) || isset($request->oab) || isset($request->data_fundacao) )) {
      $maisInfo = new EmpresaMaisInfo;
      $maisInfo->empresa_id = $empresa->id;
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
    if($respEmpresa && $request->conta){
      $banco = SisBanco::where("id", $request->banco_id)->whereNull("deleted_at")->first();
      if($banco){
        $conta = new EmpresaConta;
        $conta->empresa_id = $empresa->id;
        $conta->principal  = 1;
        $conta->tipo_conta = 1;
        $conta->banco_id   = $banco->id;
        $conta->codigo     = $banco->codigo;
        $conta->agencia    = $request->agencia;
        $conta->conta      = $request->conta;
        $conta->save();
      }
    }
    return redirect()->route('empresa.edit', ['id' => $empresa->id]);
  }
  public function edit($id)
  {
    if(Gate::denies('empresa_update')){
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
    return view("empresa.create", compact('item', 'bancos'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('empresa_update');
    $empresa = Empresa::find($id);
    $request['cnpj'] = str_replace('/', '', str_replace('-', '', str_replace('.', '', $request->cnpj)));
    $request['nome_fantasia'] = isset($request->nome_fantasia) ? $request->nome_fantasia : $request->razao_social;
    $cnpj = $request->cnpj;
    $this->validate($request, [
      'cnpj'           => 'cpf_cnpj|unique:empresas,cnpj,'.$id.',id,deleted_at,NULL,empresa_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'razao_social'   => 'required|unique:empresas,razao_social,'.$id.',id,deleted_at,NULL,empresa_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id,
      'nome_fantasia'  => 'required|unique:empresas,nome_fantasia,'.$id.',id,deleted_at,NULL,empresa_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $empresa->cnpj = strlen($cnpj) > 0 ? $cnpj : NULL;
    $empresa->razao_social = $request->razao_social;
    $empresa->nome_fantasia = $request->nome_fantasia;
    $empresa->update();
    return $empresa;
  }
  public function destroy($id, Request $request)
  {
    $this->authorize('empresa_delete');
    if($request->acao == "endereco"){
      $empresa = EmpresaEndereco::find($id);
      $empresa->deleted_at = Carbon::now();
      $empresa->update();
      $delete = $empresa;
      if($delete)
        return redirect()->route('empresa.edit', ['id' => $empresa->empresa_id]);
    }
    if($request->acao == "contato"){
      $empresa = EmpresaContato::find($id);
      $empresa->deleted_at = Carbon::now();
      $empresa->update();
      $delete = $empresa;
      if($delete)
        return redirect()->route('empresa.edit', ['id' => $empresa->empresa_id]);
    }
    if($request->acao == "daba"){
      $empresa = EmpresaConta::find($id);
      $empresa->deleted_at = Carbon::now();
      $empresa->update();
      $delete = $empresa;
      if($delete)
        return redirect()->route('empresa.edit', ['id' => $empresa->empresa_id]);
    }
    $empresa = Empresa::find($id);
    if($empresa == NULL)
      return redirect()->route('empresa.index');
    $empresa->deleted_at = Carbon::now();
    $empresa->update();
    $delete = $empresa;
    if($delete)
      return redirect()->route('empresa.index');
  }
  public function lista()
  {
    $this->authorize('empresa_read');
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome_fantasia';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'ASC' ;
    $search = Request('input-search');
    $search = str_replace('/', '', str_replace('-', '', str_replace('.', '', $search)));
    $i = Empresa::where('empresa_tipo_id', 1)
    ->whereNull('deleted_at');
    if( $search || $search != '' ){
      $i->where(function($q) use ($search) {
        $q->where('nome_fantasia', 'LIKE', "%$search%")
        ->orWhere('nome_fantasia', 'LIKE', "%$search%")
        ->orWhere('razao_social', 'LIKE', "%$search%")
        ->orWhere('cnpj', 'LIKE', "%$search%");
      })->orderBy('nome_fantasia', 'ASC')
      ->orderBy('razao_social', 'ASC');
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
    return response(view('empresa.lista', compact('itens')));
  }
  public function getCEP(Request $request)
  {
    $cep = Correios::cep($request->cep);
    return $cep;
  }
  public function autocomplete(Request $request)
  {
    $data = EmpresaCnae::select("cnae_desc as name")->where("cnae_desc","LIKE","%{$request->input('query')}%")->get();
    return response()->json($data);
  }
  public function endereco(Request $request, EmpresaEndereco $endereco)
  {
    if($request->logradouro){
      if($request->id){
        $endereco = EmpresaEndereco::find($request->id);
        $endereco->empresa_id    = $request->empresa_id;
        $endereco->principal     = $request->principal ? 1 : 0;
        $endereco->tipo_endereco = $request->tipo_endereco;
        $endereco->cep         = $request->cep;
        $endereco->cidade      = $request->cidade;
        $endereco->logradouro  = $request->logradouro;
        $endereco->numero      = $request->numero;
        $endereco->complemento = $request->complemento;
        $endereco->bairro      = $request->bairro;
        $endereco->update();
      } else {
        $endereco->empresa_id    = $request->empresa_id;
        $endereco->principal     = $request->principal ? 1 : 0;
        $endereco->tipo_endereco = $request->tipo_endereco;
        $endereco->cep           = $request->cep;
        $endereco->cidade        = $request->cidade;
        $endereco->logradouro    = $request->logradouro;
        $endereco->numero        = $request->numero;
        $endereco->complemento   = $request->complemento;
        $endereco->bairro        = $request->bairro;
        $endereco->save();
      }
    }
    return redirect()->route('empresa.edit', ['id' => $request->empresa_id]);
  }
  public function contato(Request $request, EmpresaContato $contato)
  {
    if($request->id){
      $this->validate($request, [
        'descricao' => 'unique:empresa_contatos,descricao,'.$request->id.',id,deleted_at,NULL,empresa_id,'.$request->empresa_id
      ]);
      $contato = EmpresaContato::find($request->id);
      $contato->empresa_id    = $request->empresa_id;
      $contato->principal     = $request->principal ? 1 : 0;
      $contato->tipo_cadastro = $request->tipo_cadastro;
      $contato->tipo_contato  = $request->tipo_contato;
      $contato->descricao     = $request->descricao;
      $contato->detalhe       = $request->detalhe;
      $contato->update();
    } else {
      $this->validate($request, [
        'descricao' => 'unique:empresa_contatos,descricao,NULL,id,deleted_at,NULL,empresa_id,'.$request->empresa_id
      ]);
      $contato->empresa_id    = $request->empresa_id;
      $contato->principal     = $request->principal ? 1 : 0;
      $contato->tipo_cadastro = $request->tipo_cadastro;
      $contato->tipo_contato  = $request->tipo_contato;
      $contato->descricao     = $request->descricao;
      $contato->detalhe       = $request->detalhe;
      $contato->save();
    }
    return redirect()->route('empresa.edit', ['id' => $contato->empresa_id]);
  }
  public function conta(Request $request, EmpresaConta $conta)
  {
    $banco = SisBanco::where("id", $request->banco_id)->whereNull("deleted_at")->first();
    if(!$banco){
      return redirect()->route('empresa.edit', ['id' => $request->empresa_id]);
    }
    if($request->conta_id){
      $conta = EmpresaConta::find($request->conta_id);
      $conta->empresa_id = $request->empresa_id;
      $conta->principal  = $request->principal ? 1 : 0;
      $conta->tipo_conta = $request->tipo_conta;
      $conta->banco_id   = $banco->id;
      $conta->codigo     = $banco->codigo;
      $conta->agencia    = $request->agencia;
      $conta->conta      = $request->conta;
      $conta->update();
    } else {
      $conta->empresa_id = $request->empresa_id;
      $conta->principal  = $request->principal ? 1 : 0;
      $conta->tipo_conta = $request->tipo_conta;
      $conta->banco_id   = $banco->id;
      $conta->codigo     = $banco->codigo;
      $conta->agencia    = $request->agencia;
      $conta->conta      = $request->conta;
      $conta->save();
    }
    return redirect()->route('empresa.edit', ['id' => $conta->empresa_id]);
  }
  public function maisInfo(Request $request, EmpresaMaisInfo $maisInfo)
  {
    if(!$request->data_fundacao && !$request->suframa && !$request->inscricao_municipal && !$request->inscricao_estadual && !$request->registro_geral && !$request->profissao && !$request->nome_mae && !$request->nome_pai && !$request->estado_civil && !$request->sexo){
      $maisInfo = EmpresaMaisInfo::where("empresa_id", $request->empresa_id)->first();
      if($maisInfo){
        $maisInfo->delete();
      }
      return redirect()->route('empresa.edit', ['id' => $request->empresa_id]);
    }
    $maisInfo = EmpresaMaisInfo::where("empresa_id", $request->empresa_id)->first();
    if($maisInfo){
      $maisInfo->empresa_id          = $request->empresa_id;
      $maisInfo->data_fundacao       = isset($request->data_fundacao) ? date('Y-m-d', strtotime(str_replace("/", "-", $request->data_fundacao))) : NULL;
      $maisInfo->suframa             = isset($request->suframa) ? $request->suframa : NULL;
      $maisInfo->inscricao_municipal = isset($request->inscricao_municipal) ? $request->inscricao_municipal : NULL;
      $maisInfo->inscricao_estadual  = isset($request->inscricao_estadual) ? $request->inscricao_estadual : NULL;
      $maisInfo->rg = isset($request->rg) ? $request->rg : NULL;
      $maisInfo->oerg = (isset($request->oerg) && isset($request->rg)) ? $request->oerg : NULL;
      $maisInfo->cnh = isset($request->cnh) ? $request->cnh : NULL;
      $maisInfo->crea = isset($request->crea) ? $request->crea : NULL;
      $maisInfo->crm = isset($request->crm) ? $request->crm : NULL;
      $maisInfo->cro = isset($request->cro) ? $request->cro : NULL;
      $maisInfo->oab = isset($request->oab) ? $request->oab : NULL;
      $maisInfo->profissao           = isset($request->profissao) ? $request->profissao : NULL;
      $maisInfo->nome_mae            = isset($request->nome_mae) ? $request->nome_mae : NULL;
      $maisInfo->nome_pai            = isset($request->nome_pai) ? $request->nome_pai : NULL;
      $maisInfo->estado_civil        = isset($request->estado_civil) ? $request->estado_civil : NULL;
      $maisInfo->sexo                = isset($request->sexo) ? $request->sexo : NULL;
      $maisInfo->update();
    } else {
      $maisInfo = new EmpresaMaisInfo;
      $maisInfo->empresa_id          = $request->empresa_id;
      $maisInfo->data_fundacao       = isset($request->data_fundacao) ? date('Y-m-d', strtotime(str_replace("/", "-", $request->data_fundacao))) : NULL;
      $maisInfo->suframa             = isset($request->suframa) ? $request->suframa : NULL;
      $maisInfo->inscricao_municipal = isset($request->inscricao_municipal) ? $request->inscricao_municipal : NULL;
      $maisInfo->inscricao_estadual  = isset($request->inscricao_estadual) ? $request->inscricao_estadual : NULL;
      $maisInfo->rg = isset($request->rg) ? $request->rg : NULL;
      $maisInfo->oerg = (isset($request->oerg) && isset($request->rg)) ? $request->oerg : NULL;
      $maisInfo->cnh = isset($request->cnh) ? $request->cnh : NULL;
      $maisInfo->crea = isset($request->crea) ? $request->crea : NULL;
      $maisInfo->crm = isset($request->crm) ? $request->crm : NULL;
      $maisInfo->cro = isset($request->cro) ? $request->cro : NULL;
      $maisInfo->oab = isset($request->oab) ? $request->oab : NULL;
      $maisInfo->profissao           = isset($request->profissao) ? $request->profissao : NULL;
      $maisInfo->nome_mae            = isset($request->nome_mae) ? $request->nome_mae : NULL;
      $maisInfo->nome_pai            = isset($request->nome_pai) ? $request->nome_pai : NULL;
      $maisInfo->estado_civil        = isset($request->estado_civil) ? $request->estado_civil : NULL;
      $maisInfo->sexo                = isset($request->sexo) ? $request->sexo : NULL;
      $maisInfo->save();
    }
    return redirect()->route('empresa.edit', ['id' => $maisInfo->empresa_id]);
  }
  public function getEndereco($id)
  {
    return EmpresaEndereco::find($id);
  }
  public function getContato($id)
  {
    return EmpresaContato::find($id);
  }
  public function logoEmpresa(Request $request)
  {
    $empresa = Empresa::find($request->empresa_id);
    if($empresa->img)
      File::delete($empresa->img);
    if($request->avatar){

      $d = date('YmdHis');
      $upload_success = Storage::put('public/logos/'.$request->empresa_id.'/logo'.$d.'.png', Storage::disk('avatar_file')->get($request->avatar));
      $img = 'storage/logos/'.$request->empresa_id.'/logo'.$d.'.png';
    } else {
      $this->validate($request, [
        'img'    => 'required|mimes:png,gif,jpeg,jpg,bmp'
      ]);
      $file = $request->file('img');
      $ext = $file->getClientOriginalExtension();
      $d = date('YmdHis');
      $upload_success = $file->storeAs('public/logos/'.$request->empresa_id, '/logo'.$d.'.'.$ext);
      $img = 'storage/logos/'.$request->empresa_id.'/logo'.$d.'.'.$ext;
    }

    if($upload_success) {
      $empresa->img = $img;
      $resp = $empresa->update();
      if($resp){
        $red = $empresa->empresa_tipo_id == 1 ? "empresa.edit" : $empresa->empresa_tipo_id == 2 ? "fornecedor.edit" : "cliente.edit" ;
        return redirect()->route($red, ['id' => $request->empresa_id]);
      }
    }

    $empresa->img = $img;
    $empresa->update();
    $red = $empresa->empresa_tipo_id == 1 ? "empresa.edit" : $empresa->empresa_tipo_id == 2 ? "fornecedor.edit" : "cliente.edit" ;

    return redirect()->route($red, ['id' => $request->empresa_id]);
  }
}
