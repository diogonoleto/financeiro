<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pagamento\Pagamento;
use Gate;

class PagamentoController extends Controller
{

  public function index()
  {
    if( Gate::denies('pagamento_read') )
      return redirect()->back();
    $title = 'Pagamentos';
    return view('pagamento.index', compact('title'));
  }

  public function create(Pagemento $pagamento)
  {

    if( Gate::denies('pagamento_create') )
      return redirect()->back();
   

    return view('pagamento.create', compact());
  }

  public function store(Request $request, Pagamento $pagamento)
  {

    $this->authorize('pagamento_create');

    $pagamento->saldo = 1;


    $pagamento->save();

    if($pagamento)
      return redirect()->route('pagamento.index');
    else
      return redirect()->route('pagamento.edit', $id)->with([ 'error' => 'Falha ao editar!']);
  }

  public function edit($id)
  {
    if( Gate::denies('pagamento_update') )
      return redirect()->back();
    $item = Pagamento::find($id);
   

    if( Gate::denies('pagamento', $item) )
      return redirect()->back();

    return view("pagamento.create", compact());
  }

  public function update(Request $request, $id)
  {

  }

  public function destroy($id)
  {

  }

  public function lista(){
    if( Gate::denies('pagamento_read') )
      return redirect()->back();
    
    $itens = Pagamento::orderBy('pagamento_tipo_id', 'ASC')->paginate('28');

    return response(view('pagamento.lista', compact('itens')));
  }


}
