<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Panel\UsuarioFormRequest;
use App\Http\Controllers\Mail\UserWelcome;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\SisRegra;
use App\Models\User\UserContato;
use App\Models\Empresa\Empresa;
use App\User;
use App\SisConta;
use Mail;
use Gate;
use DB;

class UsuarioController  extends Controller
{
  public function index()
  {
    $title = "Usuários do Sistema";
    if( Gate::denies('admin_usuario_read') )
      return redirect()->back();
    return view('admin.usuario.index', compact('title'));
  }
  public function create(User $user)
  {
    if( Gate::denies('usuario_create') )
      return redirect()->back();
    $item = $user;
    $regras = SisRegra::where('sis_conta_id', Auth()->user()->sis_conta_id)->get();
    $users = User::orderBy('nome')->get();
    $empresas = Empresa::where('empresa_tipo_id', 1)->get();
    return view('admin.usuario.create', compact('item', 'users', 'regras', 'empresas'));
  }
  public function store(Request $request, User $item, UserContato $contato)
  {
    $this->authorize('usuario_create');

    $this->validate($request, [
      'nome' => 'required | min:5',
      'email_principal' => 'required | email'
    ]);

    $account = SisConta::find(Auth()->user()->sis_conta_id);
    $user = User::where('user_tipo_id', 1)->get();
    $qtde_usuario = $account->qtde_funcionario - count($user);

    if($qtde_usuario <= 0){
      $error = ["error" => ["nome" => "O nome já existe."]];
      return $error;
    }

    $item->user_tipo_id = 1;
    $item->nome = $request->nome;
    $item->email = $request->email_principal;
    $item->telefone = $request->telefone_principal;
    $item->cargo = $request->cargo;
    $item->password = bcrypt('112233');
    $item->img = 'img/avatars/avatar'.random_int(1, 5).'.png';

    if($request->endereco){
      $empresa->cep         = $request->cep;
      $empresa->cidade      = $request->cidade;
      $empresa->logradouro  = $request->logradouro;
      $empresa->numero      = $request->numero;
      $empresa->complemento = $request->complemento;
      $empresa->bairro      = $request->bairro;
    }

    $item->save();
    $item->regra()->sync($request->regra_id);

    // if($request->telefone_id){
    //   $contato = UserContato::find($request->telefone_id);
    //   $contato->tipo = $request->telefone_tipo;
    //   $contato->descricao = $request->telefone_numero;
    //   $contato->update();
    // } else {
    //   if($contato->numero !="" || $contato->numero != NULL ){
    //     $contato->tipo_id = 1;
    //     $contato->tipo = $request->telefone_tipo;
    //     $contato->descricao = $request->telefone_numero;
    //     $item->userContato()->save($contato);
    //   }
    // }

    // if($request->email_id){
    //   $contato = Email::find($request->email_id);
    //   $contato->tipo = $request->email_tipo;
    //   $contato->descricao = $request->email_email;
    //   $contato->update();
    // } else {
    //   $contato->tipo_id = 2;
    //   $contato->tipo = $request->email_tipo;
    //   $contato->descricao = $request->email_email;

    //   $item->userContato()->save($contato);
    // }

    Mail::to($item->email)->send(new UserWelcome($item));
    return $item;

  }

  public function edit($id)
  {
    if( Gate::denies('usuario_update') )
      return redirect()->back();
    $item = User::with('userContato')->with('regra')->find($id);
    $regras = SisRegra::where('sis_conta_id', Auth()->user()->sis_conta_id)->get();
    $empresas = Empresa::where('empresa_tipo_id', 1)->get();
    $users = User::orderBy('nome')->get();

    return view('admin.usuario.create', compact('item', 'users', 'regras', 'empresas'));
  }

  public function update(Request $request, $id, UserContato $Contato)
  {
    $this->authorize('usuario_update');
    $item = User::find($id);
    // $item->empresa_id = $request->empresa_id;
    $item->nome = $request->nome;
    $item->email = $request->email_principal;
    $item->telefone = $request->telefone_principal;
    $item->cargo = $request->cargo;

    if($request->endereco){
      $empresa->cep         = $request->cep;
      $empresa->cidade      = $request->cidade;
      $empresa->logradouro  = $request->logradouro;
      $empresa->numero      = $request->numero;
      $empresa->complemento = $request->complemento;
      $empresa->bairro      = $request->bairro;
    }

    $item->update();
    $item->regra()->sync($request->regra_id);

    // if($request->telefone_id){
    //   $Contato = UserContato::find($request->telefone_id);
    //   $contato->tipo = $request->telefone_tipo;
    //   $contato->descricao = $request->telefone_numero;
    //   $contato->update();

    // } else {
    //   if($contato->numero !="" || $telefone->numero != NULL ){
    //     $contato->tipo = $request->telefone_tipo;
    //     $contato->descricao = $request->telefone_numero;
    //     $item->userContato()->save($contato);
    //   }
    // }

    // if($request->email_id){
    //   $contato = UserContato::find($request->email_id);
    //   $contato->tipo = $request->email_tipo;
    //   $contato->descricao = $request->email_email;
    //   $contato->update();

    // } else {
    //   $contato->tipo = $request->email_tipo;
    //   $contato->descricao = $request->email_email;
    //   $item->userContato()->save($email);
    // }
    return $item;
  }
  public function destroy($id)
  {
    // $this->authorize('usuario_delete');
    $item = User::find($id);
    $this->authorize('usuario_read');
    $delete = $item->delete();
    if($delete)
      return redirect()->route('usuario.index');
    else
      return redirect()->route('usuario.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function contato(){
    return view('admin.usuario.contato', compact());
  }
  public function lista(){
    $this->authorize('usuario_read');

    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'asc' ;


    $search = Request('input-search');

    $i = User::select(DB::raw('users.*, sis_contas.nome AS cnome'))
              ->join('sis_contas', 'sis_contas.id', '=', 'users.sis_conta_id')
              ->where('user_tipo_id', 1)
              ->where('users.id', '!=', Auth()->user()->id)
              ->with('regra');

    if( $search || $search != '' ){
      $i->where('nome', 'LIKE', "%$search%")
        ->orWhere('cargo', 'LIKE', "%$search%")
        ->orderBy('nome', 'asc');
    } else {
      $i->orderBy($order, $sort);
    }

    $itens = $i->paginate(28);

    // dd($itens);

    return response(view('admin.usuario.lista', compact('itens')));
  }
}
