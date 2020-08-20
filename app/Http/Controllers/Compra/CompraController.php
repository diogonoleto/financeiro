<?php
namespace App\Http\Controllers\Compra;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra\Compra;
use App\Models\Compra\CompraItem;
use App\Models\Empresa\Empresa;
use App\Models\Estoque\Estoque;
use App\Models\Estoque\EstoquePrdt;
use App\Models\Estoque\EstoqueMvmt;
use App\Models\Estoque\EstoquePrdtPrcvl;
use App\Models\Produto\Produto;
use App\Models\Produto\UnidadeMedida;
use DB;
use Gate;
use Carbon\Carbon;

class CompraController extends Controller
{
  public function index()
  {
    if( Gate::denies('compra_read') )
      return redirect()->action('HomeController@index');
    
    $title = 'Compras';
    return view('compra.index', compact('title'));
  }
  public function create(Compra $compra)
  {
    if( Gate::denies('compra_create') )
      return false;

    $itens = Compra::all();
    $item = $compra;
    $empresas = Empresa::where('empresa_tipo_id', 1)->get();
    $fornecedores = Empresa::where('empresa_tipo_id', 2)->get();
    $estoques = Estoque::all();

    return view('compra.create', compact('itens', 'item', 'empresas', 'fornecedores', 'estoques'));
  }
  public function store(Request $request, Compra $compra, CompraItem $item)
  {
    if( Gate::denies('compra_create') )
      return redirect()->route('compra.index');

    if($request->fornecedor_id){

      $cate = Compra::where("documento_tipo", $request->documento_tipo)
                    ->where("documento_num", $request->documento_num)
                    ->where("fornecedor_id", $request->fornecedor_id)
                    ->where("empresa_id", $request->empresa_id)
                    ->whereNull("deleted_at")
                    ->get();

      if($cate->count() > 0){
        $error = ["error" => ["nome" => "The nome has already been taken."]];
        return $error;
      }

      $compra->fornecedor_id  = $request->fornecedor_id;
      $compra->empresa_id     = $request->empresa_id;
      $compra->estoque_id     = $request->estoque_id;

      $data_emissao           = str_replace("/", "-", $request->data_emissao);
      $compra->data_emissao   = date('Y-m-d', strtotime($data_emissao));

      $compra->documento_tipo = $request->documento_tipo;
      $compra->documento_num  = $request->documento_num;

      $valor                  = str_replace('.', '', $request->valor);
      $compra->valor          = str_replace(',', '.', $valor);

      $compra->qtde           = $request->qtde;

      $compra->save();

      if($compra)
        return redirect()->route('compra.edit', $compra->id);
      else
        return redirect()->route('compra.index')->with([ 'error' => 'Falha ao deletar!']);
    } else if ($request->produto_id){
      $item->compra_id         = $request->compra_id;
      $item->produto_id        = $request->produto_id;
      $item->unidade_medida_id = $request->unidade_medida_id;
      $item->qtde              = $request->qtde;    
      $valor                   = str_replace('.', '', $request->valor);
      $item->valor             = str_replace(',', '.', $valor);
      $data_validade           = str_replace("/", "-", $request->data_validade);
      $item->data_validade     = date('Y-m-d', strtotime($data_validade));

      $item->save();

      if($item)
        return redirect()->route('compra.edit', $item->compra_id);
      else
        return redirect()->route('compra.index')->with([ 'error' => 'Falha ao deletar!']);
    }
  }
  public function edit($id, $iid = null, CompraItem $item)
  {
    if( Gate::denies('compra_update') )
      return redirect()->route('compra.index');

    $compra = Compra::find($id);
    if($compra == null || $compra->status == 1 )
      return redirect()->route('compra.index');



    $empresas = Empresa::where('empresa_tipo_id', 1)->get();
    $fornecedores = Empresa::where('empresa_tipo_id', 2)->get();
    $estoques = Estoque::all();



    $itens = CompraItem::select(DB::raw('compra_items.*, compra_items.qtde*compra_items.valor as vtotal'))
                      ->where('compra_id', $id)
                      ->with('produto')
                      ->with('unidadeMedida')
                      ->get();
    $item = isset($iid) ? CompraItem::find($iid) : $item ;
    
    $produtos = Produto::where("tipo", "PRINCIPAL")->get();
    $unidades = UnidadeMedida::all();
    return view('compra.edit', compact('itens', 'item', 'produtos', 'unidades', 'compra', 'empresas', 'fornecedores', 'estoques'));
  }

  public function update(Request $request, $id, $iid=null, EstoqueMvmt $emv, EstoquePrdtPrcvl $epp)
  {
    if( Gate::denies('compra_update') )
      return false;

    if($iid && $iid != 0){

      $item = CompraItem::find($iid);

      $item->produto_id        = $request->produto_id;
      $item->unidade_medida_id = $request->unidade_medida_id;
      $item->qtde              = $request->qtde;    
      $valor                   = str_replace('.', '', $request->valor);
      $item->valor             = str_replace(',', '.', $valor);
      $data_validade           = str_replace("/", "-", $request->data_validade);
      $item->data_validade     = date('Y-m-d', strtotime($data_validade));

      $item->update();

      if($item)
        return redirect()->route('compra.edit', $item->compra_id);
      else
        return redirect()->route('compra.edit', $id)->with([ 'error' => 'Falha ao deletar!']);

    } else {
      $compra = Compra::where('id', $id)->whereNull('finalized_at')->first();
      if(!$compra){
        $errors['compra'] = 'compra ja cadastrada e finalizada';
          return redirect()->route('compra.edit', $id)->withErrors($errors);
      }


      if($request->data_emissao){

        $cate = Compra::where("documento_tipo", $request->documento_tipo)
                      ->where("documento_num", $request->documento_num)
                      ->where("fornecedor_id", $request->fornecedor_id)
                      ->where("empresa_id", $request->empresa_id)
                      ->whereNull("deleted_at")
                      ->get();

        if($cate->count() > 0){
          $error = ["error" => ["nome" => "The nome has already been taken."]];
          return $error;
        }

        $data_emissao           = str_replace("/", "-", $request->data_emissao);
        $compra->data_emissao   = date('Y-m-d', strtotime($data_emissao));
        $compra->documento_tipo = $request->documento_tipo;
        $compra->documento_num  = $request->documento_num;
        $valor                  = str_replace('.', '', $request->valor);
        $compra->valor          = str_replace(',', '.', $valor);
        $compra->qtde           = $request->qtde;
        $compra->update();

        if($compra)
          return redirect()->route('compra.edit', $compra->id);
        else
          return redirect()->route('compra.edit', $compra->id)->with([ 'error' => 'Falha ao editar!']);
        
      } else {
        $compraItens = CompraItem::select(DB::raw('compra_items.produto_id, compra_items.qtde, compra_items.valor, compra_items.unidade_medida_id, compra_items.data_validade, compra_items.qtde*compra_items.valor as vtotal, produtos.controla_estoque'))
                                  ->join('produtos', 'produtos.id', '=', 'compra_items.produto_id')
                                  ->where('compra_id', $id)
                                  ->get();
        foreach ($compraItens as $ci) {
          $errors = array();
          if( $compra->qtde != $compraItens->count() ){
            $errors['item'] = 'Existe(m) Pendência(s) na quantidade de itens';
          }

          if( $compra->valor != $compraItens->sum('vtotal') ){ 
            $errors['valor'] = 'Existe(m) Pendência(s) no valor global dos itens';
          }

          if($errors){
            return redirect()->route('compra.edit', $id)->withErrors($errors);
          }

          $compra->status = 1;
          $compra->finalized_at = Carbon::now();
          $upcompra = $compra->update();

          if(!$upcompra){
            $errors['compra'] = 'erro na gravacao de dados';
            return redirect()->route('compra.edit', $id)->withErrors($errors);
          }

          if($ci->controla_estoque == 1){
            $ePrdt = EstoquePrdt::select(DB::raw('id, saldo'))
                                ->where('estoque_id', $compra->estoque_id)
                                ->where('produto_id', $ci->produto_id)
                                ->whereNull('deleted_at')
                                ->first();

            $ePrdt->saldo = $ePrdt->saldo + $ci->qtde;
            $upestpro = $ePrdt->update();

            var_dump($ci->controla_estoque);

            if(!$upestpro){
              $errors['upestpro'] = 'erro na gravacao de dados';
              return redirect()->route('compra.edit', $id)->withErrors($errors);
            }
            
            $dvl = ($ci->data_validade == null) ? null : $ci->data_validade;

            $emv->estoque_id         = $compra->estoque_id;
            $emv->produto_id         = $ci->produto_id;
            $emv->estoque_prdt_id    = $ePrdt->id;
            $emv->unidade_medida_id  = $ci->unidade_medida_id;
            $emv->compra_id          = $compra->id;
            $emv->estoque_mvmt_tp_id = 1;
            $emv->operacao           = 1;
            $emv->data               = Carbon::now();
            $emv->qtde               = $ci->qtde;
            $emv->custo              = $ci->valor;
            $emv->saldo              = $ePrdt->saldo;
            $emv->data_validade      = $dvl;

            $upemv = $emv->save();

            if(!$upemv){
              $errors['upemv'] = 'erro na gravacao de dados';
              return redirect()->route('compra.edit', $id)->withErrors($errors);
            }

            $produto = EstoquePrdt::select(DB::raw('produtos.perecivel, estoque_prdts.id'))
                                          ->join('produtos', 'produtos.id', '=', 'estoque_prdts.produto_id')
                                          ->where('produtos.perecivel', 1)
                                          ->where('estoque_prdts.estoque_id', $compra->estoque_id)
                                          ->where('estoque_prdts.produto_id', $ci->produto_id)
                                          ->whereNull('produtos.deleted_at')
                                          ->whereNull('estoque_prdts.deleted_at')
                                          ->first();

            if(count($produto) > 0 ){
              $epp->estoque_prdt_id = $ePrdt->id;
              $epp->estoque_mvmt_id = $emv->id;
              $epp->qtde            = $ci->qtde;
              $epp->saldo           = $ci->qtde;
              $epp->data_validade   = $ci->data_validade;

              $upepp = $epp->save();

              if(!$upepp){
                $errors['upepp'] = 'erro na gravacao de dados';
                return redirect()->route('compra.edit', $id)->withErrors($errors);
              }
            }
          }
        }
        return redirect()->route('compra.index');
      }
    }
  }
  public function destroy($id, $iid=null)
  {
    if( Gate::denies('compra_delete') )
      return false;

    if($iid){
      $item = CompraItem::find($iid);

      $compra_id = $item->compra_id;
      $delete = $item->delete();
      if($delete)
        return redirect()->route('compra.edit', $compra_id);
      else
        return redirect()->route('compra.edit', $compra_id)->with([ 'error' => 'Falha ao deletar!']);
    } else {

      $compra = Compra::where('id', $id)->whereNull("finalized_at")->first();

      if(!$compra){
        return redirect()->route('compra.index');
      }

      $compra->compraItem()->delete();
      $delete = $compra->delete();

      if($delete)
        return redirect()->route('compra.index');
      else
        return redirect()->route('compra.index')->with([ 'error' => 'Falha ao deletar!']);
    }
  }

  public function lista()
  {
    if( Gate::denies('compra_read') )
      return false;
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'status';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'ASC' ;
    $search = Request('input-search');

    if( $order == 'fornecedor' ){
      $itens = Compra::select(DB::raw('compras.*, empresas.nome_fantasia'))
      ->join('empresas', 'empresas.id', '=', 'compras.fornecedor_id')
      ->orderBy('empresas.nome_fantasia', $sort)
      ->orderBy('compras.status', 'ASC')
      ->with('fornecedor')
      ->paginate(28);
    } else if( $search || $search != '' ){
      $itens = Compra::select(DB::raw('compras.*, empresas.nome_fantasia'))
      ->join('empresas', 'empresas.id', '=', 'compras.fornecedor_id')
      ->where('compras.documento_num', 'LIKE', "%$search%")
      ->orWhere('compras.data_emissao', 'LIKE', "%$search%")
      ->orWhere('compras.valor', 'LIKE', "%$search%")
      ->orWhere('empresas.nome_fantasia', 'LIKE', "%$search%")
      ->orderBy('compras.status', 'ASC')
      ->with("fornecedor")
      ->paginate(28);
    } else {
      $itens = Compra::orderBy($order, $sort)
      ->with("fornecedor")
      ->paginate(28);
    }
    return response(view('compra.lista', compact('itens')));
  }
}
