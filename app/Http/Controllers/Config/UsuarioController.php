<?php
namespace App\Http\Controllers\Config;

use App\Http\Requests\Panel\UsuarioFormRequest;
use App\Http\Controllers\Mail\UserWelcome;
use App\Http\Controllers\Controller;
use App\Scopes\TenantScope;
use Illuminate\Http\Request;
use App\Models\Config\SisRegra;
use App\Models\Empresa\Empresa;
use App\Models\User\UserContato;
use App\Models\User\UserEndereco;
use App\Models\User\UserConta;
use App\Models\User\UserMaisInfo;
use App\Models\Config\Banco\SisBanco;
use App\Models\User\User;
use App\Helpers\Helper;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use App\SisConta;
use Session;
use Mail;
use Gate;
use File;
use Storage;
use DB;

class UsuarioController  extends Controller
{
  public function index()
  {
    if(Gate::denies('usuario_read')){
      return redirect()->route('financeiro');
    }
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $user = User::where('user_tipo_id', 1)->whereNull("deleted_at")->get();
    $qtde_usuario = isset($account->qtde_funcionario) ? ( $account->qtde_funcionario - count($user) ) : NULL ;
    $title = "Usuários do Sistema";
    return view('usuario.index', compact('title', 'qtde_usuario'));
  }
  public function create(User $user)
  {
    if(Gate::denies('usuario_create')){
      return redirect()->back();
    }
    $item = $user;
    $regras = SisRegra::where('sis_conta_id', Auth()->user()->sis_conta_id)->whereNull("deleted_at")->get();
    $empresas = Empresa::where('empresa_tipo_id', 1)->whereNull("deleted_at")->get();
    $qtdsci = strlen(Auth()->user()->sis_conta_id)+1;
    $bancos = SisBanco::whereNull("deleted_at")->get();
    return view('usuario.create', compact('item', 'regras', 'empresas', 'qtdsci', 'bancos'));
  }
  public function store(Request $request, User $user)
  {
    $this->authorize('usuario_create');
    $pass = Helper::generatePassword();
    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $us = User::where('user_tipo_id', 1)->whereNull("deleted_at")->get();
    $qtde_usuario = $account->qtde_funcionario == NULL ? 1 : ( $account->qtde_funcionario - count($us) ) ;
    if($qtde_usuario <= 0){
      $error = ["error" => ["usuario_limite" => "Não pode adicionar devido ao limite de usuário"]];
      return $error;
    }
    $request['cpf'] = str_replace('-', '', str_replace('.', '', $request->cpf));
    $cpf = $request->cpf;
    $this->validate($request, [
      'nome'  => 'required|min:5',
      'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
      'cpf'   => 'required|cpf|unique:users,cpf,NULL,id,deleted_at,NULL,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $perfil = SisRegra::where('id', $request->regra_id)->where('sis_conta_id', Auth()->user()->sis_conta_id)->whereNull("deleted_at")->get();
    if(count($perfil) <= 0 ){
      $error = ["error" => ["regra_id" => "Perfil invalido!"]];
      return $error;
    }
    $user->user_tipo_id = 1;
    $user->nome = $request->nome;
    $user->email = $request->email;
    $user->cpf = $cpf;
    $user->password = bcrypt($pass);
    $user->img = 'img/avatars/avatar-blank.png';
    $respUser = $user->save();
    if($respUser && $request->telefone){
      $contato = new UserContato;
      $contato->user_id       = $user->id;
      $contato->principal     = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato  = 2;
      $contato->descricao     = $request->telefone;
      $contato->save();
    }
    if($respUser && $request->email){
      $contato = new UserContato;
      $contato->user_id       = $user->id;
      $contato->principal     = 1;
      $contato->tipo_cadastro = 1;
      $contato->tipo_contato  = 1;
      $contato->descricao     = $request->email;
      $contato->save();
    }
    if($respUser && $request->endereco){
      $endereco = new UserEndereco;
      $endereco->user_id       = $user->id;
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
    if($respUser && ($request->rg || $request->oerg || $request->cnh || $request->crea || $request->crm || $request->cro || $request->oab )) {
      $maisInfo = new UserMaisInfo;
      $maisInfo->user_id = $user->id;
      $maisInfo->rg = isset($request->rg) ? $request->rg : NULL;
      $maisInfo->oerg = (isset($request->oerg) && isset($request->rg)) ? $request->oerg : NULL;
      $maisInfo->cnh = isset($request->cnh) ? $request->cnh : NULL;
      $maisInfo->crea = isset($request->crea) ? $request->crea : NULL;
      $maisInfo->crm = isset($request->crm) ? $request->crm : NULL;
      $maisInfo->cro = isset($request->cro) ? $request->cro : NULL;
      $maisInfo->oab = isset($request->oab) ? $request->oab : NULL;
      $maisInfo->save();
    }
    if($respUser && $request->conta){
      $banco = SisBanco::where("id", $request->banco_id)->whereNull("deleted_at")->first();
      if($banco){
        $conta = new UserConta;
        $conta->user_id = $user->id;
        $conta->principal  = 1;
        $conta->tipo_conta = 1;
        $conta->banco_id   = $banco->id;
        $conta->codigo     = $banco->codigo;
        $conta->agencia    = $request->agencia;
        $conta->conta      = $request->conta;
        $conta->save();
      }
    }
    $user->regra()->sync($request->regra_id);
    Mail::to([$user->email, 'diogonoletodasilva@gmail.com'], $user->nome)->send(new UserWelcome($user->id, $pass));
    return redirect()->route('usuario.edit', ['id' => $user->id]);
  }
  public function edit($id)
  {
    if(Gate::denies('usuario_update')){
      return redirect()->back();
    }
    $item = User::where('id', $id)->with([
      'maisInfo',
      'regra',
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
    $regras = SisRegra::where('sis_conta_id', Auth()->user()->sis_conta_id)->whereNull("deleted_at")->get();
    $empresas = Empresa::where('empresa_tipo_id', 1)->get();
    $bancos = SisBanco::whereNull("deleted_at")->get();
    $qtdsci = strlen(Auth()->user()->sis_conta_id)+1;
    return view('usuario.create', compact('item', 'users', 'regras', 'empresas', 'bancos', 'qtdsci'));
  }
  public function update($id, Request $request)
  {
    $this->authorize('usuario_update');
    $usuario = User::find($id);
    $request['cpf'] = str_replace('-', '', str_replace('.', '', $request->cpf));
    $cpf = $request->cpf;
    $this->validate($request, [
      'nome'  => 'required|min:5',
      'cpf'  => 'required|cpf|unique:users,cpf,'.$id.',id,deleted_at,NULL,user_tipo_id,1,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $usuario->cpf = strlen($cpf) > 0 ? $cpf : NULL;
    $usuario->nome = $request->nome;
    $usuario->update();
    return $usuario;
    $item->update();
    $item->regra()->sync($request->regra_id);
    return $item;
  }
  public function destroy($id, Request $request)
  {
    $this->authorize('usuario_delete');
    if($request->acao == "endereco"){
      $usuario = UserEndereco::find($id);
      $usuario->deleted_at = Carbon::now();
      $usuario->update();
      $delete = $usuario;
      if($delete)
        return redirect()->route('usuario.edit', ['id' => $usuario->user_id]);
    }
    if($request->acao == "contato"){
      $usuario = UserContato::find($id);
      $usuario->deleted_at = Carbon::now();
      $usuario->update();
      $delete = $usuario;
      if($delete)
        return redirect()->route('usuario.edit', ['id' => $usuario->user_id]);
    }
    if($request->acao == "daba"){
      $usuario = UserConta::find($id);
      $usuario->deleted_at = Carbon::now();
      $usuario->update();
      $delete = $usuario;
      if($delete)
        return redirect()->route('usuario.edit', ['id' => $usuario->user_id]);
    }
    $usuario = User::where('id',$id)->where('id', '!=', Auth()->user()->id);
    $delete = $usuario->update(['deleted_at' => Carbon::now()]);
    if($delete)
      return redirect()->route('usuario.index');
    else
      return redirect()->route('usuario.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista()
  {
    $this->authorize('usuario_read');
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'asc' ;
    $search = Request('input-search');
    $i = User::where('user_tipo_id', 1)
    ->whereNull('deleted_at');
    if( $search || $search != '' ){
      $i->where(function($q) use ($search) {
        $q->where('nome', 'LIKE', "%$search%")
        ->orWhere('cargo', 'LIKE', "%$search%");
      })->orderBy('nome', 'asc');
    } else {
      $i->orderBy($order, $sort);
    }
    $itens = $i->paginate(28);
    $qtdsci = strlen(Auth()->user()->sis_conta_id)+1;
    return response(view('usuario.lista', compact('itens', 'qtdsci')));
  }
  public function apresentacao()
  {
    $item = User::find(Auth()->user()->id);
    $item->apresentacao = 0;
    $item->update();
    return $item;
  }
  public function cadastro()
  {
    Session::flash('cid', \Auth::user()->id);
    return redirect()->route('usuario.index');
  }
  public function endereco(Request $request, UserEndereco $endereco)
  {
    if($request->logradouro){
      if($request->id){
        $endereco = UserEndereco::find($request->id);
        $endereco->user_id    = $request->user_id;
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
        $endereco->user_id    = $request->user_id;
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
    return redirect()->route('usuario.edit', ['id' => $request->user_id]);
  }
  public function contato(Request $request, UserContato $contato)
  {
    if($request->id){
      $this->validate($request, [
        'descricao' => 'unique:user_contatos,descricao,'.$request->id.',id,deleted_at,NULL,user_id,'.$request->user_id
      ]);
      $contato = UserContato::find($request->id);
      $contato->user_id    = $request->user_id;
      $contato->principal     = $request->principal ? 1 : 0;
      $contato->tipo_cadastro = $request->tipo_cadastro;
      $contato->tipo_contato  = $request->tipo_contato;
      $contato->descricao     = $request->descricao;
      $contato->detalhe       = $request->detalhe;
      $contato->update();
    } else {
      $this->validate($request, [
        'descricao' => 'unique:user_contatos,descricao,NULL,id,deleted_at,NULL,user_id,'.$request->user_id
      ]);
      $contato->user_id    = $request->user_id;
      $contato->principal     = $request->principal ? 1 : 0;
      $contato->tipo_cadastro = $request->tipo_cadastro;
      $contato->tipo_contato  = $request->tipo_contato;
      $contato->descricao     = $request->descricao;
      $contato->detalhe       = $request->detalhe;
      $contato->save();
    }
    return redirect()->route('usuario.edit', ['id' => $contato->user_id]);
  }
  public function conta(Request $request, UserConta $conta)
  {
    $banco = SisBanco::where("id", $request->banco_id)->whereNull("deleted_at")->first();
    if(!$banco){
      return redirect()->route('usuario.edit', ['id' => $request->user_id]);
    }
    if($request->conta_id){
      $conta = UserConta::find($request->conta_id);
      $conta->user_id = $request->user_id;
      $conta->principal  = $request->principal ? 1 : 0;
      $conta->tipo_conta = $request->tipo_conta;
      $conta->banco_id   = $banco->id;
      $conta->codigo     = $banco->codigo;
      $conta->agencia    = $request->agencia;
      $conta->conta      = $request->conta;
      $conta->update();
    } else {
      $conta->user_id = $request->user_id;
      $conta->principal  = $request->principal ? 1 : 0;
      $conta->tipo_conta = $request->tipo_conta;
      $conta->banco_id   = $banco->id;
      $conta->codigo     = $banco->codigo;
      $conta->agencia    = $request->agencia;
      $conta->conta      = $request->conta;
      $conta->save();
    }
    return redirect()->route('usuario.edit', ['id' => $conta->user_id]);
  }
  public function maisInfo(Request $request, UserMaisInfo $maisInfo)
  {
    if(!$request->data_nascimento && !$request->suframa && !$request->inscricao_municipal && !$request->inscricao_estadual && !$request->rg && !$request->profissao && !$request->nome_mae && !$request->nome_pai && !$request->estado_civil && !$request->sexo){
      $maisInfo = UserMaisInfo::where("user_id", $request->user_id)->first();
      if($maisInfo){
        $maisInfo->delete();
      }
      return redirect()->route('usuario.edit', ['id' => $request->user_id]);
    }
    $maisInfo = UserMaisInfo::where("user_id", $request->user_id)->first();
    if($maisInfo){
      $maisInfo->user_id = $request->user_id;
      $maisInfo->data_nascimento = isset($request->data_nascimento) ? date('Y-m-d', strtotime(str_replace("/", "-", $request->data_nascimento))) : NULL;
      $maisInfo->rg = isset($request->rg) ? $request->rg : NULL;
      $maisInfo->oerg = (isset($request->oerg) && isset($request->rg)) ? $request->oerg : NULL;
      $maisInfo->cnh = isset($request->cnh) ? $request->cnh : NULL;
      $maisInfo->crea = isset($request->crea) ? $request->crea : NULL;
      $maisInfo->crm = isset($request->crm) ? $request->crm : NULL;
      $maisInfo->cro = isset($request->cro) ? $request->cro : NULL;
      $maisInfo->oab = isset($request->oab) ? $request->oab : NULL;
      $maisInfo->profissao = isset($request->profissao) ? $request->profissao : NULL;
      $maisInfo->nome_mae = isset($request->nome_mae) ? $request->nome_mae : NULL;
      $maisInfo->nome_pai = isset($request->nome_pai) ? $request->nome_pai : NULL;
      $maisInfo->estado_civil = isset($request->estado_civil) ? $request->estado_civil : NULL;
      $maisInfo->sexo = isset($request->sexo) ? $request->sexo : NULL;
      $maisInfo->update();
    } else {
      $maisInfo = new UserMaisInfo;
      $maisInfo->user_id = $request->user_id;
      $maisInfo->data_nascimento = isset($request->data_nascimento) ? date('Y-m-d', strtotime(str_replace("/", "-", $request->data_nascimento))) : NULL;
      $maisInfo->rg = isset($request->rg) ? $request->rg : NULL;
      $maisInfo->oerg = (isset($request->oerg) && isset($request->rg)) ? $request->oerg : NULL;
      $maisInfo->cnh = isset($request->cnh) ? $request->cnh : NULL;
      $maisInfo->crea = isset($request->crea) ? $request->crea : NULL;
      $maisInfo->crm = isset($request->crm) ? $request->crm : NULL;
      $maisInfo->cro = isset($request->cro) ? $request->cro : NULL;
      $maisInfo->oab = isset($request->oab) ? $request->oab : NULL;
      $maisInfo->profissao = isset($request->profissao) ? $request->profissao : NULL;
      $maisInfo->nome_mae = isset($request->nome_mae) ? $request->nome_mae : NULL;
      $maisInfo->nome_pai = isset($request->nome_pai) ? $request->nome_pai : NULL;
      $maisInfo->estado_civil = isset($request->estado_civil) ? $request->estado_civil : NULL;
      $maisInfo->sexo = isset($request->sexo) ? $request->sexo : NULL;
      $maisInfo->save();
    }
    return redirect()->route('usuario.edit', ['id' => $maisInfo->user_id]);
  }
  public function getEndereco($id)
  {
    return UserEndereco::find($id);
  }
  public function getContato($id)
  {
    return UserContato::find($id);
  }
  public function avatarUser(Request $request)
  {
    $usuario = User::find($request->id);
    if($usuario->img){
      File::delete($usuario->img);
    }
    $sci = auth()->user()->sis_conta_id;
    if($request->avatar){
      $d = date('YmdHis');
      $upload_success = Storage::put('public/contas/'.$sci.'/img/avatars/'.$usuario->id.'/avatar'.$d.'.png', Storage::disk('avatar_file')->get($request->avatar));
      $img = 'storage/contas/'.$sci.'/img/avatars/'.$usuario->id.'/avatar'.$d.'.png';
    } else {
      $this->validate($request, [
        'img' => 'required|mimes:png,gif,jpeg,jpg,bmp'
      ]);
      $file = $request->file('img');
      $ext = $file->getClientOriginalExtension();
      $d = date('YmdHis');
      $upload_success = $file->storeAs('public/contas/'.$sci.'/img/avatars/'.$usuario->id, 'avatar'.$d.'.'.$ext);
      $img = 'storage/contas/'.$sci.'/img/avatars/'.$usuario->id.'/avatar'.$d.'.'.$ext;
    }
    if($upload_success) {
      $usuario->img = $img;
      $resp = $usuario->update();
      if($resp){
        $red = $usuario->user_tipo_id == 1 ? "usuario.edit" : $usuario->user_tipo_id == 2 ? "usuario.edit" : "usuario.edit" ;
        return redirect()->route($red, ['id' => $request->id]);
      }
    }
  }
}
