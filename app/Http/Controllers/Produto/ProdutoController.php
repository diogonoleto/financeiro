<?php
namespace App\Http\Controllers\Produto;
use App\Http\Requests\Panel\ProdutoFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto\Produto;
use App\Models\Produto\ProdutoCategoria;
use App\Models\Produto\UnidadeMedida;
use App\Models\Empresa\Empresa;
use DB;
use Gate;

class ProdutoController extends Controller
{
  public function index()
  {
    if( Gate::denies('produto_read') )
      return redirect()->back();

    $itens = Produto::all();
    $title = 'Produtos';
    return view('produto.index', compact('itens', 'title'));
  }
  public function create(Produto $produto)
  {
    if( Gate::denies('produto_create') )
      return redirect()->back();
    $itens = Produto::all();
    $categorias = ProdutoCategoria::where('produto_categoria_id', NULL)
                                  ->orderBy('nome', 'ASC')
                                  ->with('children')
                                  ->get();
    $fornecedores = Empresa::where('empresa_tipo_id', 2)->get();
    $unidades = UnidadeMedida::all();
    $item = $produto;
    return view('produto.create', compact('itens', 'item', 'categorias', 'fornecedores', 'unidades'));
  }
  public function store(Request $request, Produto $produto)
  {

    $this->authorize('produto_create');

    $produto->empresa_id = auth()->user()->empresa_id;

    $produto->produto_categoria_id = $request->categoria_id;
    $produto->nome = $request->nome;
    $produto->rotulo = $request->rotulo;
    $produto->ean = $request->referencia;
    $produto->unidade_medida_id = $request->unidade_medida_id;
    $produto->capacidade = $request->capacidade;

    $preco = str_replace('.', '', $request->preco);
    $produto->preco = str_replace(',', '.', $preco);

    $produto->destinacao = $request->destinacao;

    if($request->peso_liquido)
      $produto->peso_liquido = $request->peso_liquido;

    if($request->peso_bruto)
      $produto->peso_bruto = $request->peso_bruto;

    $produto->ordem = $request->ordem;

    if($request->fornecedor)
      $produto->fornecedor_id = $request->fornecedor_id;

    if($request->disponivel_venda){
      $produto->controla_estoque = $request->controla_estoque;
      $produto->aviso_validade = $request->aviso_validade;
    }

    if($request->disponivel_venda)
      $produto->disponivel_venda = $request->disponivel_venda;

    if($request->perecivel)
      $produto->perecivel = $request->perecivel;

    if($request->fracionado)
      $produto->fracionado = $request->fracionado;

    if($request->tipo_insumo)
      $produto->tipo_insumo = $request->tipo_insumo;

    if($request->usado_composicao)
      $produto->usado_composicao = $request->usado_composicao;

    if($request->principal_dashboard)
      $produto->principal_dashboard = $request->principal_dashboard;

    if($request->grade_item)
      $produto->grade_item = $request->grade_item;

    if($request->custo_fixo)
      $produto->valor_custo_fixo = $request->valor_custo_fixo;

    if($request->fiscal){
      $produto->ncm_id = $request->ncm_id;
      // $produto->origem_mercadoria_id = $request->origem_mercadoria_id;
      $produto->cfop_id = $request->cfop_id;
      $produto->icms_id = $request->icms_id;
      $produto->pis_cofins_id = $request->pis_cofins_id;
      $produto->ipi_id = $request->ipi_id;
    }
    
    $produto->save();

    if($produto)
      return redirect()->route('produto.index');
    else
      return redirect()->route('produto.crete');
  }
  public function show($id)
  {
    $item = Produto::find($id);
    return view('produto.show', compact('item'));
  }
  public function edit($id)
  {
    $itens = Produto::all();
    $item = Produto::find($id);
    $categorias = ProdutoCategoria::where('produto_categoria_id', NULL)
                                  ->orderBy('nome', 'ASC')
                                  ->with('children')
                                  ->get();
    $fornecedores = Empresa::where('empresa_tipo_id', 2)->get();
    $unidades = UnidadeMedida::all();
    return view('produto.create', compact('itens', 'item', 'categorias', 'fornecedores', 'unidades'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('produto_create');

    $produto = Produto::find($id);

    $produto->empresa_id = auth()->user()->empresa_id;

    $produto->produto_categoria_id = $request->categoria_id;
    $produto->nome = $request->nome;
    $produto->rotulo = $request->rotulo;
    $produto->ean = $request->referencia;
    $produto->unidade_medida_id = $request->unidade_medida_id;
    $produto->capacidade = $request->capacidade;

    $preco = str_replace('.', '', $request->preco);
    $produto->preco = str_replace(',', '.', $preco);

    $produto->destinacao = $request->destinacao;

    if($request->peso_liquido)
      $produto->peso_liquido = $request->peso_liquido;

    if($request->peso_bruto)
      $produto->peso_bruto = $request->peso_bruto;

    $produto->ordem = $request->ordem;

    if($request->fornecedor)
      $produto->fornecedor_id = $request->fornecedor_id;

    if($request->disponivel_venda){
      $produto->controla_estoque = $request->controla_estoque;
      $produto->aviso_validade = $request->aviso_validade;
    }

    if($request->disponivel_venda)
      $produto->disponivel_venda = $request->disponivel_venda;

    if($request->perecivel)
      $produto->perecivel = $request->perecivel;

    if($request->fracionado)
      $produto->fracionado = $request->fracionado;

    if($request->tipo_insumo)
      $produto->tipo_insumo = $request->tipo_insumo;

    if($request->usado_composicao)
      $produto->usado_composicao = $request->usado_composicao;

    if($request->principal_dashboard)
      $produto->principal_dashboard = $request->principal_dashboard;

    if($request->grade_item)
      $produto->grade_item = $request->grade_item;

    if($request->custo_fixo)
      $produto->valor_custo_fixo = $request->valor_custo_fixo;

    if($request->fiscal){
      $produto->ncm_id = $request->ncm_id;
      // $produto->origem_mercadoria_id = $request->origem_mercadoria_id;
      $produto->cfop_id = $request->cfop_id;
      $produto->icms_id = $request->icms_id;
      $produto->pis_cofins_id = $request->pis_cofins_id;
      $produto->ipi_id = $request->ipi_id;
    }
    
    $produto->update();

    if($produto)
      return redirect()->route('produto.index');
    else
      return redirect()->route('produto.edit', $id);
  }
  public function destroy($id)
  {
    $item = Produto::find($id);

    $delete = $item->delete();
    if($produto)
      return redirect()->route('produto.index');
    else
      return redirect()->route('produto.show', $id)->with([ 'error' => 'Falha ao deletar!']);
  }

  public function lista()
  {
    if( Gate::denies('produto_read') )
      return redirect()->back();
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'nome';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'DESC' ;
    $search = Request('input-search');

    if( $order == 'tipo_produto' ){
      $itens = Produto::select(DB::raw('fin_produtos.*, fin_produto_tipos.nome'))
      ->join('fin_produto_tipos', 'fin_produto_tipos.id', '=', 'fin_produtos.produto_tipo_id')
      ->orderBy('fin_produto_tipos.nome', $sort)
      ->orderBy('fin_produtos.descricao', 'ASC')
      ->with('produtoCategoria')
      ->with("fornecedor")
      ->paginate(28);
    } else if( $search || $search != '' ){
      $itens = Produto::select(DB::raw('fin_produtos.*, fin_produto_tipos.nome'))
      ->join('fin_produto_tipos', 'fin_produto_tipos.id', '=', 'fin_produtos.produto_tipo_id')
      ->where('fin_produtos.descricao', 'LIKE', "%$search%")
      ->orWhere('fin_produto_tipos.nome', 'LIKE', "%$search%")
      ->orWhere('fin_produtos.agencia', 'LIKE', "%$search%")
      ->orWhere('fin_produtos.produto', 'LIKE', "%$search%")
      ->orderBy('fin_produtos.descricao', 'ASC')
      ->with("produtoCategoria")
      ->with("fornecedor")
      ->paginate(28);
    } else {
      $itens = Produto::orderBy($order, $sort)
      ->orderBy('nome', 'ASC')
      ->with("produtoCategoria")
      ->with("fornecedor")
      ->paginate(28);
    }
    return response(view('produto.lista', compact('itens')));
  }
}
