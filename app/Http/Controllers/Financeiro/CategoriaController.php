<?php
namespace App\Http\Controllers\Financeiro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Financeiro\FinCategoria;
use App\Models\Financeiro\FinMovimento;
use App\Models\Financeiro\FinDre;
use App\Http\Requests\Financeiro\CategoriaFormRequest;
use Gate;
use DB;
class CategoriaController extends Controller
{
  public function index()
  {
    if( Gate::denies('fin_categoria_read') ){
      return redirect()->back();
    }
    $title = 'Categorias';
    return view('financeiro.categoria.index', compact('title'));
  }
  public function store(Request $request, FinCategoria $categoria)
  {
    $this->authorize('fin_categoria_create');

    // $request['nome'] = strtolower($request->nome);
    // $request['nome'] = $request->nome;
    if($request->categoria_id){
      $this->validate($request, [
        'nome' => 'required|min:3|max:150|unique:fin_categorias,nome,NULL,id,tipo,'.$request->tipo.',sis_conta_id,'.Auth()->user()->sis_conta_id.',deleted_at,NULL,categoria_id,'.$request->categoria_id
      ]);
    } else {
      $this->validate($request, [
        'nome' => 'required|min:3|max:150|unique:fin_categorias,nome,NULL,id,tipo,'.$request->tipo.',sis_conta_id,'.Auth()->user()->sis_conta_id.',deleted_at,NULL'
      ]);
    }

    $categoria->nome = $request->nome;
    $categoria->descricao = $request->descricao;
    $categoria->tipo = $request->tipo;
    if($request->categoria_id){
      $cat = FinCategoria::where('categoria_id', $request->categoria_id)->whereNull("deleted_at")->orderBy('id', 'DESC')->first();
      if($cat){
        $qtde = explode('.', $cat->cod, -1);
        if(count($qtde) == 1){
          $categoria->cod = ($qtde[0]+1).".";
        } if(count($qtde) == 2){
          $categoria->cod = $qtde[0].".".($qtde[1]+1).".";
        } if(count($qtde) == 3){
          $categoria->cod = $qtde[0].".".$qtde[1].".".($qtde[2]+1).".";
        } if(count($qtde) == 4){
          $categoria->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".".($qtde[3]+1).".";
        } if(count($qtde) == 5){
          $categoria->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".".($qtde[4]+1).".";
        }
      } else {
        $icat = FinCategoria::where('id', $request->categoria_id)->whereNull("deleted_at")->first();
        if($icat){
          $categoria->cod = $icat->cod."1.";
        } else {
          $categoria->cod = "1.";
        } 
      }
      $categoria->categoria_id = $request->categoria_id;
    } else {
      $cat = FinCategoria::whereNull("categoria_id")->whereNull("deleted_at")->orderBy('id', 'DESC')->first();
      if($cat){
        $qtde = explode('.', $cat->cod, -1);
        if(count($qtde) == 1){
          $categoria->cod = ($qtde[0]+1).".";
        } if(count($qtde) == 2){
          $categoria->cod = $qtde[0].".".($qtde[1]+1).".";
        } if(count($qtde) == 3){
          $categoria->cod = $qtde[0].".".$qtde[1].".".($qtde[2]+1).".";
        } if(count($qtde) == 4){
          $categoria->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".".($qtde[3]+1).".";
        } if(count($qtde) == 5){
          $categoria->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".".($qtde[4]+1).".";
        }
      } else {
        $categoria->cod = "1.";
      }
    }
    $categoria->save();
    return $categoria;
  }
  public function update($id, Request $request)
  {
    $this->authorize('fin_categoria_update');
    // $request['nome'] = strtolower($request->nome);
    $this->validate($request, [
      'nome' => 'required|min:3|max:150|unique:fin_categorias,nome,'.$id.',id,deleted_at,NULL,categoria_id,'.$request->categoria_id.',tipo,'.$request->tipo.',sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $categoria = FinCategoria::find($id);
    $categoria->nome = $request->nome;
    $categoria->descricao = $request->descricao;
    if($request->categoria_id)
      $categoria->categoria_id = $request->categoria_id;
    $categoria->save();
    return $categoria;
  }
  public function destroy($id, Request $request)
  {
    if( Gate::denies('fin_categoria_delete')){
      return redirect()->back();
    }

    $item = FinCategoria::where('id', $id)
    ->orWhere('categoria_id', $id)
    ->whereNull("deleted_at")
    ->orderBy("id")
    ->get();

    if($request->delete_confirmar == 0){
      $child = array();
      foreach ($item as $i) {
        if( $i->categoria_id == null ){
          $idc = $id;
          $idcp = $id;
          $cat = 'div-subcat';
        } else {
          $idc = $id;
          $idcp = $i->categoria_id;
          $cat = 'div-cat';

          $movimento = FinMovimento::where('categoria_id', $i->id)->whereNull("deleted_at")->first();
          if(isset($movimento)){
            $child[] = $i->id;
          }
        }
      }
      if( count($child) > 0 ){
        return redirect()->back()->with([ 'id' => $idc, 'child' => json_encode($child), 'cat' => $cat, 'idcp' => $idcp ]);
      }
      foreach ($item as $i) {
        $delete = $i->delete();
      }
      if($delete)
        return redirect()->route('fin.categoria.index')->with([ 'success' => 'Item deletado com sucesso!!']);
      else
        return redirect()->route('fin.categoria.index')->with([ 'error' => 'Falha ao deletar!']);
    } else {
      foreach ($item as $i) {
        $i->deleted_at = Carbon::today();
        $delete = $i->update();
      }
      if($delete)
        return redirect()->route('fin.categoria.index');
      else
        return redirect()->route('fin.categoria.index')->with([ 'error' => 'Falha ao deletar!']);
    }
  }

  public function sortable(Request $request)
  {
    $sort = Request('sort');
    foreach ($sort as $key => $id) {
      $categoria = FinCategoria::find($id);
      $categoria->ordem = $key;
      $categoria->update();
    }
    return $sort;
  }

  public function dre(Request $request)
  {
    $this->authorize('fin_dre_create');
    if(isset($request->dre_id)){
      $item = FinCategoria::find($request->categoria_id);
      $item->dre_id = $request->dre_id;
      $item->update();
      if(!$item->categoria_id)
        $item->childrendre()->update(['dre_id' => $request->dre_id]); 
      return redirect()->route('fin.categoria.lista');
    } else {
      $item = FinCategoria::find($request->categoria_id);
      $dres = FinDre::where('tipo', $item->tipo)->whereNotNull("dre_id")->whereNull("deleted_at")->get();
      return response(view('financeiro.categoria.create', compact('item', 'dres')));
    }
    return false;
  }

  public function lista()
  {
    $this->authorize('fin_categoria_read');
    $categorias = FinCategoria::select(DB::raw('fin_categorias.id, fin_categorias.cod, fin_categorias.descricao as "desc", fin_categorias.nome, fin_categorias.tipo, fin_dres.descricao'))
    ->leftJoin('fin_dres', 'fin_dres.id', '=', 'fin_categorias.dre_id')
    ->whereNull('categoria_id')
    ->where('fin_categorias.nome', '!=' ,'Saldo Inicial')
    ->where('fin_categorias.nome', '!=' ,'Transferência de Saída')
    ->where('fin_categorias.nome', '!=' ,'Transferência de Entrada')
    ->where('fin_categorias.nome', '!=' ,'Pagamento de Fatura')
    ->orderBy('fin_categorias.tipo', 'ASC')
    ->orderBy('fin_categorias.id', 'ASC')
    ->orderBy('fin_categorias.nome', 'ASC')
    ->whereNull("fin_categorias.deleted_at")
    ->get();
    return response(view('financeiro.categoria.lista', compact('categorias')));
  }
}
