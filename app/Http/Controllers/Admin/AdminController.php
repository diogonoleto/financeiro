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

class AdminController  extends Controller
{
  public function index()
  {
    $title = "Administração do Sistema";
    if( Gate::denies('admin_usuario_read') )
      return redirect()->back();
    return view('admin.index', compact('title'));
  }

  public function lista(){
    $this->authorize('usuario_read');

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

    $itens = User::orderBy($order, $sort)
      ->where('user_tipo_id', 1)
      ->where('id', '!=', Auth()->user()->id)
      ->with('userContato')
      ->with('regra')
      ->paginate(28);         
    }
    // dd($itens);

    return response(view('admin.usuario.lista', compact('itens')));
  }
}
