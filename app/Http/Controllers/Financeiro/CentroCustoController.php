<?php
namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financeiro\FinCentroCusto;
use Carbon\Carbon;
use Gate;

class CentroCustoController extends Controller
{
  public function index()
  {
    if( Gate::denies('fin_centro_custo_read') )
      return redirect()->route('financeiro');
      $title = "Centro de Custo";
    return view('financeiro.centrocusto.index', compact('title'));
  }
  public function create(FinCentroCusto $centrocusto)
  {
    if( Gate::denies('fin_centro_custo_create') )
      return redirect()->back();
    $item = $centrocusto;
    return view('financeiro.centrocusto.create', compact('item'));
  }
  public function store(Request $request, FinCentroCusto $centrocusto)
  {
    $this->authorize('fin_centro_custo_create');
    $this->validate($request, [
      'nome'  => 'unique:fin_centro_custos,nome,null,id,deleted_at,NULL,sis_conta_id,'.Auth()->user()->sis_conta_id,
    ]);
    $centrocusto->nome = $request->nome;
    $centrocusto->save();
    return $centrocusto;
  }
  public function edit($id)
  {
    if( Gate::denies('fin_centro_custo_update') )
      return redirecet()->back();
    $item = FinCentroCusto::find($id);
    return view("financeiro.centrocusto.create", compact('item'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('fin_centro_custo_update');
    $this->validate($request, [
      'nome'  => 'unique:fin_centro_custos,nome,'.$id.',id,deleted_at,NULL,sis_conta_id,'.Auth()->user()->sis_conta_id,
    ]);
    $centrocusto = FinCentroCusto::find($id);
    if($centrocusto->nome == 'Comum'){
      $error["error"] = ["centrocusto_padrao" => "O Centro de Custo Comum é padrão não pode ser editado."];
      return $error;
    }
    $centrocusto->nome = $request->nome;
    $centrocusto->update();
    if($centrocusto)
      return redirect()->route('centrocusto.edit', $id);
  }
  public function destroy($id)
  {
    $this->authorize('fin_centro_custo_delete');
    $centrocusto = FinCentroCusto::find($id);

    if($centrocusto == NULL || $centrocusto->nome == 'Comum')
      return redirect()->route('centrocusto.index');

    $centrocusto->deleted_at = Carbon::now();
    $centrocusto->update();
    $delete = $centrocusto;

    if($delete)
      return redirect()->route('centrocusto.index');
    else
      return redirect()->route('centrocusto.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista()
  {
    $this->authorize('fin_centro_custo_read');

    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';

    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'ASC' ;

    $search = Request('input-search');

    if( $search || $search != '' ){
      $itens = FinCentroCusto::where('nome', 'LIKE', "%$search%")
                      ->whereNull('deleted_at')
                      ->orderBy('nome', 'ASC')
                      ->paginate(28);
    } else {
      $itens = FinCentroCusto::whereNull('deleted_at')
                      ->orderBy($order, $sort)
                      ->paginate(28);
    }
    return response(view('financeiro.centrocusto.lista', compact('itens')));
  }
}
