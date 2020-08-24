<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Mail\UserWelcome;
use App\Models\Admin\FinCentroCusto;
use App\Models\Admin\SisItemPadrao;
use App\Models\Admin\FinCategoria;
use App\Models\Admin\Empresa;
use App\Models\Admin\SisModulo;

use App\Models\User\UserContato;
use App\Models\User\UserEndereco;
use App\Models\User\UserConta;
use App\Models\User\UserMaisInfo;
use App\Models\Config\Banco\SisBanco;

use App\Scopes\TenantScope;
use App\Http\Controllers\Controller;
use App\Models\Config\SisRegra;
use App\Models\Config\SisPermissao;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\SisConta;
use App\User;
use Mail;
use Gate;
use DB;

class ContaController  extends Controller
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
      if( Gate::denies('admin_conta_create') ){
        return redirect()->back();
      }

      $modulo = $modulo->modulo;
      $modulos = SisModulo::where('status', '>', 0)->get();
      return view('admin.conta.create', compact('item', 'modulo', 'modulos'));
    }
    public function store(Request $request, User $user, SisConta $conta, SisRegra $regra)
    {
      $this->authorize('admin_conta_create');

      $this->validate($request, [
        'nome' => 'required | min:5',
        'email' => 'required | email'
      ]);


      $modulos = $request->modulos;
      array_push($modulos, 8);
      $pass = Helper::generatePassword();

      User::flushEventListeners();

      $conta->nome             = $request->nomeeg;
      $conta->qtde_empresa     = $request->qtde_empresa;
      $conta->qtde_funcionario = $request->qtde_funcionario;
      $conta->qtde_cliente     = $request->qtde_cliente;
      $conta->qtde_fornecedor  = $request->qtde_fornecedor;
      $respConta = $conta->save();

      $conta->modulo()->sync($modulos);

      $regra->sis_conta_id = $conta->id;
      $regra->nome         = $conta->id.'_admin_empresa';
      $regra->descricao    = 'Administrador da Empresa';
      $respRegra = $regra->save();

      $regra2 = new SisRegra;
      $regra2->sis_conta_id = $conta->id;
      $regra2->nome         = $conta->id.'_financeiro_senior';
      $regra2->descricao    = 'Financeiro Sênior';
      $regra2->save();

      $regra3 = new SisRegra;
      $regra3->sis_conta_id = $conta->id;
      $regra3->nome         = $conta->id.'_financeiro_junior';
      $regra3->descricao    = 'Financeiro Júnior';
      $regra3->save();

      $user->user_tipo_id = 1;
      $user->sis_conta_id = $conta->id;
      $user->nome         = $request->nome;
      $user->cpf          = str_replace('-', '', str_replace('.', '', $request->cpf));
      $user->password     = bcrypt($pass);
      $user->img          = 'img/avatars/avatar-blank.png';
      $user->email        = $request->email;

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




      $item['user'] = $user;
      $item['regra'] = $regra;
      $user->regra()->sync($regra->id);

      $regra = SisRegra::where('sis_conta_id', $conta->id)->where('nome', $conta->id.'_admin_empresa')->first();
      $permissaos = SisPermissao::whereIn('sis_modulo_id', $modulos)->pluck('id')->toArray();
      $regra->permissao()->sync($permissaos);

      $regra2 = SisRegra::where('sis_conta_id', $conta->id)->where('nome', $conta->id.'_financeiro_senior')->first();
      $permissaos2 = SisPermissao::whereIn('sis_modulo_id', [4])->pluck('id')->toArray();
      $regra2->permissao()->sync($permissaos2);

      $regra3 = SisRegra::where('sis_conta_id', $conta->id)->where('nome', $conta->id.'_financeiro_junior')->first();
      $permissaos3 = SisPermissao::whereIn('area', ['fin_dashboard', 'fin_despesa', 'fin_receita', 'fin_movimento', 'fin_transferencia', 'fin_relatario'])->pluck('id')->toArray();
      $regra3->permissao()->sync($permissaos3);

      $sisitem = SisItemPadrao:: whereNull('filho')->with('children')->get();
      foreach ($sisitem as $i) {
        if($i->tabela == "centro_custo"){
          $centrocusto = new FinCentroCusto;
          $centrocusto->sis_conta_id = $conta->id;
          $centrocusto->nome         = $i->nome;
          $centrocusto->save();
          $item['centrocusto'] = $centrocusto;
        } else if($i->tabela == "empresa"){
          $fornecedor = new Empresa;
          $fornecedor->sis_conta_id        = $conta->id;
          $fornecedor->razao_social        = $i->nome;
          $fornecedor->nome_fantasia       = $i->nome;
          $fornecedor->empresa_tipo_id     = $i->tipo;
          $fornecedor->empresa_entidade_id = 1;
          $fornecedor->save();
          $item['fornecedor'] = $fornecedor;
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
              $item['subcategoria'][$key] = $subcategoria;
            }
          }
        }
      }
      $conta = SisConta::find($conta->id);
      $conta->user_id = $user->id;
      $conta->update();

      Mail::to([$user->email, 'diogonoletodasilva@gmail.com'], $user->nome)->send(new UserWelcome($user->id, $pass));

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

        try {
          DB::beginTransaction();

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


          $item['user'] = $user;
          $regra = SisRegra::where('sis_conta_id', $conta->id)->where('nome', $conta->id.'_admin_empresa')->first();
          $item['regra'] = $regra;

          $permissaos = SisPermissao::whereIn('sis_modulo_id', $modulos)->pluck('id')->toArray();
          $regra->permissao()->sync($permissaos);

          DB::commit();

          Mail::to($user->email, $user->nome)->send(new UserWelcome($user->id, $pass));
        } catch (\Exception $e) {
          DB::rollback();
        }
        return $item;
      }
      public function destroy($id)
      {
        $this->authorize('admin_conta_delete');
        $conta = Empresa::find($id);
        if($conta == NULL || $conta->nome == 'Administrador')
          return redirect()->route('adminCon.conta.index');

        $regra = SisRegra::where('sis_conta_id', $id)->get();
        foreach ($regra as $r) {
          $r->delete();
          $r->user()->sync([]);
          $r->permissao()->sync([]);
        }

        $user = User::where("sis_conta_id", $id)->update(["deleted_at" => Carbon::now()]);
        $conta = SisConta::find($id)->update(["status" => 0, "deleted_at" => Carbon::now()]);

        if($conta)
          return redirect()->route('adminCon.conta.index');
        else
          return redirect()->route('adminCon.conta.index')->with([ 'error' => 'Falha ao deletar!']);
      }
      public function lista()
      {
        $this->authorize('admin_conta_read');
        $order = Request('order');
        $order = Request()->has('order') ? $order : 'nome';
        $sort = Request('sort');
        $sort = Request()->has('sort') ? $sort : 'asc' ;
        $search = Request('input-search');
        if( $search || $search != '' ){
          $itens = User::where('nome', 'LIKE', "%$search%")
          ->orWhere('cargo', 'LIKE', "%$search%")
          ->orderBy('nome', 'asc')
          ->with('userContato')
          ->paginate(28);
        } else {
          $itens = SisConta::orderBy($order, $sort)
          ->paginate(28);
        }
    // dd($itens);
        return response(view('admin.conta.lista', compact('itens')));
      }
    }
