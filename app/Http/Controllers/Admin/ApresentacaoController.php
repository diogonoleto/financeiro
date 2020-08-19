<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Mail\UserWelcome;
use App\Models\Admin\FinCentroCusto;
use App\Models\Admin\SisItemPadrao;
use App\Models\Admin\FinCategoria;
use App\Models\Admin\Empresa;
use App\Models\Admin\SisModulo;
use App\Scopes\TenantScope;
use App\Http\Controllers\Controller;
use App\Models\Config\SisRegra;
use App\Models\Config\SisPermissao;
use Illuminate\Http\Request;
use App\User;
use App\Helpers\Helper;
use App\SisConta;
use Mail;
use Gate;
use DB;

use App\Models\Admin\SisApresentacao;

class ApresentacaoController  extends Controller
{
  public function index()
  {
    $title = "Contas do Sistema";
    if( Gate::denies('admin_conta_read') )
      return redirect()->back();
    return view('admin.conta.index', compact('title'));
  }
  public function create(SisConta $item, SisModulo $modulo)
  {
    if( Gate::denies('admin_conta_create') )
      return redirect()->back();
    $modulo = $modulo->modulo;
    $modulos = SisModulo::where('status', '>', 0)->get();
    return view('admin.conta.create', compact('item', 'modulo', 'modulos'));
  }
  public function store(Request $request, User $user, SisConta $conta, FinCentroCusto $centrocusto, Empresa $fornecedor, SisRegra $regra)
  {
    $this->authorize('admin_conta_create');

    $this->validate($request, [
      'nome' => 'required | min:5',
      'email' => 'required | email'
    ]);

    $modulos = $request->modulos;
    array_push($modulos, 8);
    $pass = Helper::generatePassword();

    $conta->nome             = $request->nomeeg;
    $conta->qtde_empresa     = $request->qtde_empresa;
    $conta->qtde_funcionario = $request->qtde_funcionario;
    $conta->qtde_cliente     = $request->qtde_cliente;
    $conta->qtde_fornecedor  = $request->qtde_fornecedor;
    $respConta = $conta->save();

    if($respConta){
      $conta->modulo()->sync($modulos);
      $regra->sis_conta_id = $conta->id;
      $regra->nome         = 'admin_empresa';
      $regra->descricao    = 'Administrador da Empresa';
      $respRegra = $regra->save();


      $user->user_tipo_id = 1;
      $user->sis_conta_id = $conta->id;
      $user->nome         = $request->nome;
      $user->email        = $request->email;
      $user->telefone     = $request->telefone;
      
      $cpf = str_replace('.', '', $request->cpf);
      $cpf = str_replace('-', '', $cpf);
      $user->cpf          = $cpf;
      $user->cargo        = $request->cargo;
      $user->password     = bcrypt($pass);
      $user->img          = 'img/avatars/avatar-blank.jpg';
      $user->cep          = $request->cep;
      $user->cidade       = $request->cidade;
      $user->logradouro   = $request->logradouro;
      $user->numero       = $request->numero;
      $user->complemento  = $request->complemento;
      $user->bairro       = $request->bairro;
      $respUser = $user->save();
      if($respUser && $respRegra){
        $item['user'] = $user;
        $item['regra'] = $regra;
        $user->regra()->sync($regra->id);
        $permissaos = SisPermissao::whereIn('sis_modulo_id', $modulos)->get();
        foreach ($permissaos as $p) {
          $p->regra()->sync($regra->id);
        }
        $sisitem = SisItemPadrao:: whereNull('filho')->with('children')->get();
        foreach ($sisitem as $i) {
          if($i->tabela == "centro_custo"){
            $centrocusto->sis_conta_id = $conta->id;
            $centrocusto->nome         = $i->nome;
            $centrocusto->save();
            $item['centrocusto'] = $centrocusto;
          } else if($i->tabela == "empresa"){
            $fornecedor->sis_conta_id        = $conta->id;
            $fornecedor->razao_social        = $i->nome;
            $fornecedor->nome_fantasia       = $i->nome;
            $fornecedor->empresa_tipo_id     = $i->tipo;
            $fornecedor->empresa_entidade_id = 1;
            $fornecedor->save();
            $item['fornecedor'] = $centrocusto;
          } else if ($i->tabela == "categoria"){
            $categoria = new FinCategoria;
            $categoria->sis_conta_id = $conta->id;
            $categoria->nome = $i->nome;
            $categoria->tipo = $i->tipo;
            $categoria->descricao = $i->descricao;
            $respCategoria = $categoria->save();
            $item['categoria'] = $categoria;
            if($respCategoria){
              foreach ($i->children as $key => $c) {
                $subcategoria = new FinCategoria;
                $subcategoria->sis_conta_id = $conta->id;
                $subcategoria->categoria_id = $categoria->id;
                $subcategoria->nome = $c->nome;
                $subcategoria->tipo = $c->tipo;
                $subcategoria->descricao = $c->descricao;
                $subcategoria->save();
                $item['subcategoria'][$key] = $categoria;
              }
            }
          }
        }
        $conta = SisConta::find($conta->id);
        $conta->user_id = $user->id;
        $conta->update();
      }
    }
    Mail::to($user->email, $user->name)->send(new UserWelcome($user->id, $pass));

    return $item;
  }
  public function edit($id)
  {
    if( Gate::denies('admin_conta_update') )
      return redirect()->back();

    $item = SisConta::where('id', $id)->with('modulo')->with('user')->first();
    $modulo = $item->modulo;
    $modulos = SisModulo::where('status', '>', 0)->get();

    return view('admin.conta.create', compact('item', 'modulo', 'modulos'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('admin_conta_update');
    $modulos = $request->modulos;
    array_push($modulos, 8);

    $conta = SisConta::find($id);
    if(isset($request->ativa) && $request->ativa == 0){
      $conta->status = 0;
      $conta->update();
      return $conta;
    } else if(isset($request->ativa) && $request->ativa == 1){
      $conta->status = 1;
    }

    $conta->nome             = $request->nomeeg;
    $conta->qtde_empresa     = $request->qtde_empresa;
    $conta->qtde_funcionario = $request->qtde_funcionario;
    $conta->qtde_cliente     = $request->qtde_cliente;
    $conta->qtde_fornecedor  = $request->qtde_fornecedor;
    $respConta = $conta->update();

    if($respConta){
      $item['conta'] = $conta;
      $conta->modulo()->sync($modulos);
      
      $user = User::where('id', $conta->user_id)->first();
      $user->nome         = $request->nome;
      $user->email        = $request->email;
      $user->telefone     = $request->telefone;
      $user->cargo        = $request->cargo;
      $cpf = str_replace('.', '', $request->cpf);
      $cpf = str_replace('-', '', $cpf);
      $user->cpf          = $cpf;
      $user->cep          = $request->cep;
      $user->cidade       = $request->cidade;
      $user->logradouro   = $request->logradouro;
      $user->numero       = $request->numero;
      $user->complemento  = $request->complemento;
      $user->bairro       = $request->bairro;
      $respUser = $user->update();

      if($respUser){
        $item['user'] = $user;
        $regra = SisRegra::where('sis_conta_id', $conta->id)->where('nome', 'admin_empresa')->first();
        $item['regra'] = $regra;
        $permissaos = SisPermissao::all();
        if(count($regra) > 0 ){
          foreach ($permissaos as $p) {
            $p->regra()->detach($regra->id);
          }
        }
        $permissaos = SisPermissao::whereIn('sis_modulo_id', $modulos)->get();
        foreach ($permissaos as $p) {
          $p->regra()->sync($regra->id);
        }
      }
    }
    return $item;
  }
  public function destroy($id)
  {
    $this->authorize('admin_conta_delete');

    $item = User::find($id);
    $delete = $item->delete();
    if($delete)
      return redirect()->route('adminAcc.conta.index');
    else
      return redirect()->route('adminAcc.conta.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista()
  {
    $this->authorize('admin_conta_read');

    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'ASC' ;


    $search = Request('input-search');
    if( $search || $search != '' ){
      $itens = User::where('nome', 'LIKE', "%$search%")
                  ->orWhere('cargo', 'LIKE', "%$search%")
                  ->orderBy('nome', 'ASC')
                  ->with('userContato')
                  ->paginate(28);
    } else {

    $itens = SisConta::orderBy($order, $sort)
      ->paginate(28);         
    }
    // dd($itens);

    return response(view('admin.conta.lista', compact('itens')));
  }

  public function apresentar()
  {
    $itens = SisApresentacao::all();
    return $itens;
  }
}
