<?php
namespace App\Http\Controllers\Estoque;
use App\Http\Requests\Panel\EstoqueFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estoque\Estoque;
use App\Models\Empresa\Empresa;
use DB;
use Gate;

class EstoqueController extends Controller
{
  public function index()
  {
    $title = 'Estoques';
    return view('estoque.index', compact('title'));
  }
  public function create(Estoque $estoque)
  {
    $itens = Estoque::all();

    $fornecedores = Empresa::where('empresa_tipo_id', 2)->get();
    $item = $estoque;
    return view('estoque.create', compact('itens', 'item', 'fornecedores'));
  }
  public function store(EstoqueFormRequest $request, Estoque $estoque)
  {
    $estoque->company_id = 1;
    $estoque->provider_id = 1;
    $estoque->estoque_categoria_id = 1;
    $estoque->estoque_color_id = 1;
    $estoque->ncm_id = 1;
    $estoque->cest_id = 1;
    $estoque->cfop_id = 1;
    $estoque->icms_group_id = 1;
    $estoque->pis_confis_group_id = 1;
    $estoque->ipi_group_id = 1;
    $estoque->status = 1;
    $estoque->name = $request->name;
    $estoque->price = $request->price;
    $estoque->save();

    if($estoque)
      return redirect()->route('estoque.index');
    else
      return redirect()->route('estoque.crete');
  }
  public function show($id)
  {
    $item = Estoque::find($id);
    return view('estoque.show', compact('item'));
  }
  public function edit($id)
  {
    $item = Estoque::find($id);
    return view("estoque.create", compact('item'));
  }
  public function update(EstoqueFormRequest $request, $id)
  {
    $estoque = Estoque::find($id);
    $estoque->name = $request->name;
    $estoque->price = $request->price;
    $estoque->update();

    if($estoque)
      return redirect()->route('estoque.index');
    else
      return redirect()->route('estoque.edit', $id);
  }
  public function destroy($id)
  {
    $item = Estoque::find($id);

    $delete = $item->delete();
    if($estoque)
      return redirect()->route('estoque.index');
    else
      return redirect()->route('estoque.show', $id)->with([ 'error' => 'Falha ao deletar!']);
  }

  public function lista()
  {
    if( Gate::denies('estoque_read') )
      return redirect()->back();
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'DESC' ;
    $search = Request('input-search');

    if( $order == 'tipo_estoque' ){
      $itens = Estoque::select(DB::raw('fin_estoques.*, fin_estoque_tipos.nome'))
      ->join('fin_estoque_tipos', 'fin_estoque_tipos.id', '=', 'fin_estoques.estoque_tipo_id')
      ->orderBy('fin_estoque_tipos.nome', $sort)
      ->orderBy('fin_estoques.descricao', 'ASC')
      ->paginate(28);
    } else if( $search || $search != '' ){
      $itens = Estoque::select(DB::raw('fin_estoques.*, fin_estoque_tipos.nome'))
      ->join('fin_estoque_tipos', 'fin_estoque_tipos.id', '=', 'fin_estoques.estoque_tipo_id')
      ->where('fin_estoques.descricao', 'LIKE', "%$search%")
      ->orWhere('fin_estoque_tipos.nome', 'LIKE', "%$search%")
      ->orWhere('fin_estoques.agencia', 'LIKE', "%$search%")
      ->orWhere('fin_estoques.estoque', 'LIKE', "%$search%")
      ->orderBy('fin_estoques.descricao', 'ASC')
      ->paginate(28);
    } else {
      $itens = Estoque::orderBy($order, $sort)
      ->orderBy('nome', 'ASC')
      ->paginate(28);
    }
    return response(view('estoque.lista', compact('itens')));
  }
}
