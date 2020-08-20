<?php

namespace App\Http\Controllers\Produto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Produto\ProdutoCategoria;
use Gate;
class CategoriaController extends Controller
{
  public function index()
  {
    if( Gate::denies('produto_categoria_read') )
      return redirect()->back();
    $title = 'Produto Categoria';
    return view('produto.categoria.index', compact('title'));
  }

  public function store(Request $request, ProdutoCategoria $categoria)
  {
    if( Gate::denies('produto_categoria_read') )
      return redirect()->back();
    $cate = ProdutoCategoria::where("nome", $request->nome)
                      ->where("tipo", $request->tipo)
                      ->get();

    if($cate->count() > 0){
      $error = ["error" => ["nome" => "The nome has already been taken."]];
      return $error;
    } else {
      $categoria->nome = $request->nome;
      $categoria->tipo = $request->tipo;
      $categoria->pdv = $request->pdv;
      $categoria->nivel = $request->nivel;
      if($request->produto_categoria_id)
        $categoria->produto_categoria_id = $request->produto_categoria_id;

      $categoria->save();
    }

    return $categoria;
  }

  public function update(Request $request, $id)
  {
    if( Gate::denies('produto_categoria_edit'))
      return redirect()->back();
    
    $cate = ProdutoCategoria::where("nome", $request->nome)
                    ->where("tipo", $request->tipo)
                    ->get();

    if($cate->count() > 0){
      $error = ["error" => ["nome" => "The nome has already been taken."]];
      return $error;
    } else {
      $categoria = ProdutoCategoria::find($id);
      $categoria->nome = $request->nome;
      $categoria->pdv = $request->pdv;
      $categoria->nivel = $request->nivel;
      if($request->produto_categoria_id)
        $categoria->produto_categoria_id = $request->produto_categoria_id;
      $categoria->save();
    }
    return $categoria;
  }

  public function destroy($id)
  {
    if( Gate::denies('produto_categoria_delete'))
      return redirect()->back();
    $item = ProdutoCategoria::find($id);
    $item->children()->delete();
    $delete = $item->delete();
    if($delete)
      return redirect()->route('categoria.index');
    else
      return redirect()->route('categoria.index')->with([ 'error' => 'Falha ao deletar!']);
  }

  public function lista(){
    if( Gate::denies('produto_categoria_read') )
      return redirect()->back();

    $itens = ProdutoCategoria::where('produto_categoria_id', null)
                      ->orderBy('tipo', 'ASC')
                      ->orderBy('nome', 'ASC')
                      ->with('children')
                      ->get();

    return response(view('produto.categoria.lista', compact('itens')));
  }
}
