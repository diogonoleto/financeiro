<?php
namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PDV\PontoDeVenda;
use App\Models\Estoque\Estoque;
use App\Models\Config\PDV\Plataforma;

use Gate;
use DB;

class PDVController extends Controller
{
  public function index()
  {
    if( Gate::denies('pdv_read') )
      return redirect()->back();
    $title = 'Ponto de Venda';
    return view('pdv.index', compact('title'));
  }
  public function create(PontoDeVenda $item)
  {
    if( Gate::denies('pdv_create') )
      return redirect()->back();

    $plataformas = Plataforma::all();
    return response(view('pdv.create', compact('item', 'plataformas')));
  }
  public function store(Request $request, PontoDeVenda $pdv, Estoque $estoque)
  {
    $this->authorize('pdv_create');

    $pdv->empresa_id = auth()->user()->empresa_id;


    $pdv->plataforma_id = $request->plataforma_id;
    $pdv->nome = $request->nome;
    $pdv->responsavel = $request->responsavel;
    $pdv->local = $request->local;
    $pdv->uuid = $request->uuid;

    if($request->desconto){
      $pdv->desc_valor_max = $request->desc_valor_max;
      $pdv->desc_perc_max = $request->desc_perc_max;
    } else {
      $pdv->desc_valor_max = 0;
      $pdv->desc_perc_max = 0;
    }

    if( $request->mesa ) {
      $pdv->mesa_qtd = $request->mesa_qtd;
    } else {
      $pdv->mesa_qtd = 0;
    }

    if( $request->imprime == 1 ) {
      $pdv->imprime = $request->imprime;
      $pdv->imprime_ip = $request->imprime_ip;
    } else if ($request->imprime > 1 ){
      $pdv->imprime = $request->imprime;
      $pdv->imprime_ip = $request->imprime_ip;
      $pdv->nfce_ip = $request->nfce_ip;
      $pdv->nfce_num_serie = $request->nfce_num_serie; 
      $pdv->nfce_num_nota = $request->nfce_num_nota;
    } else {
      $pdv->imprime = $request->imprime;
    }

    $pdv->save();
    if($pdv){
      $estoque->empresa_id = auth()->user()->empresa_id;
      $estoque->ponto_de_venda_id = $pdv->id;
      $estoque->estoque_tp_id = 2;
      $estoque->nome = "ESTOQUE PDV - ".$pdv->nome;
      $estoque->save();
    }
    return $pdv;
  }
  public function edit($id)
  {
    if( Gate::denies('pdv_update') )
      return redirect()->back();

    $item = PontoDeVenda::find($id);

    $plataformas = Plataforma::all();

    return response(view('pdv.create', compact('item', 'plataformas')));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('pdv_update');
    $pdv = PontoDeVenda::find($id);

    $pdv->empresa_id = auth()->user()->empresa_id;

    $pdv->plataforma_id = $request->plataforma_id;
    $pdv->nome = $request->nome;
    $pdv->responsavel = $request->responsavel;
    $pdv->local = $request->local;
    $pdv->uuid = $request->uuid;

    if($request->desconto){
      $desc_valor_max = str_replace('.', '', $request->desc_valor_max);
      $pdv->desc_valor_max = str_replace(',', '.', $desc_valor_max);
      $pdv->desc_perc_max = $request->desc_perc_max;
    } else {
      $pdv->desc_valor_max = 0;
      $pdv->desc_perc_max = 0;
    }

    if( $request->mesa ) {
      $pdv->mesa_qtd = $request->mesa_qtd;
    } else {
      $pdv->mesa_qtd = 0;
    }

    if( $request->imprime == 1 ) {
      $pdv->imprime = $request->imprime;
      $pdv->imprime_ip = $request->imprime_ip;
      $pdv->nfce_ip = null;
      $pdv->nfce_num_serie = null; 
      $pdv->nfce_num_nota = null;


    } else if ($request->imprime > 1 ){
      $pdv->imprime = $request->imprime;
      $pdv->imprime_ip = $request->imprime_ip;
      $pdv->nfce_ip = $request->nfce_ip;
      $pdv->nfce_num_serie = $request->nfce_num_serie; 
      $pdv->nfce_num_nota = $request->nfce_num_nota;
    } else {
      $pdv->imprime = $request->imprime;
      $pdv->imprime_ip = null;
      $pdv->nfce_ip = null;
      $pdv->nfce_num_serie = null; 
      $pdv->nfce_num_nota = null;
    }

    $pdv->update();
    return $pdv;

  }

  public function destroy($id)
  {
    $this->authorize('pdv_delete');
    $item = PontoDeVenda::find($id);
    $delete = $item->delete();
    if($delete)
      return redirect()->route('pdv.index');
    else
      return redirect()->route('pdv.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista()
  {
    if( Gate::denies('pdv_read') )
      return redirect()->back();
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'ASC' ;
    $search = Request('input-search');

    if( $order == 'plataforma' ){
      $itens = PontoDeVenda::select(DB::raw('ponto_de_vendas.*, plataformas.nome pnome'))
      ->join('plataformas', 'plataformas.id', '=', 'ponto_de_vendas.plataforma_id')
      ->orderBy('plataformas.nome', $sort)
      ->orderBy('ponto_de_vendas.nome', 'ASC')
      ->with('plataforma')
      ->paginate(28);
    } else if( $search || $search != '' ){
      $itens = PontoDeVenda::select(DB::raw('ponto_de_vendas.*, plataformas.nome pnome'))
      ->join('plataformas', 'plataformas.id', '=', 'ponto_de_vendas.plataforma_id')
      ->where('ponto_de_vendas.nome', 'LIKE', "%$search%")
      ->orWhere('plataformas.nome', 'LIKE', "%$search%")
      ->orWhere('ponto_de_vendas.responsavel', 'LIKE', "%$search%")
      ->orWhere('ponto_de_vendas.uuid', 'LIKE', "%$search%")
      ->orderBy('ponto_de_vendas.nome', 'ASC')
      ->with("plataforma")
      ->paginate(28);
    } else {
      $itens = PontoDeVenda::orderBy($order, $sort)
      ->with("plataforma")
      ->paginate(28);
    }
    return response(view('pdv.lista', compact('itens')));
  }
}