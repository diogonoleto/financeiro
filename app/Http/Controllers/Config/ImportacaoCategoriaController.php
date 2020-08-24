<?php
namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financeiro\FinImportacao;
use App\Models\Financeiro\FinConta;
use App\Models\Financeiro\FinCategoria;
use App\Models\Financeiro\FinMovimento;
use App\Models\Financeiro\FinCentroCusto;
use App\Models\Empresa\Empresa;
use Carbon\Carbon;
use Gate;
use Input;
use Excel;

class ImportacaoCategoriaController extends Controller
{
  public function index()
  {
    if( Gate::denies('config_importacao_read') ){
      return redirect()->route('financeiro');
    }
    $title = "Importação de Categorias";
    return view('config.importacao.categoria.index', compact('title'));
  }
  public function create(FinImportacao $importacao)
  {
    if( Gate::denies('config_importacao_create') ){
      return redirect()->back();
    }
    $contas = FinConta::whereNotIn("conta_tipo_id", [4,5,6,7])->whereNull('deleted_at')->get();
    $item = $importacao;
    return view('config.importacao.categoria.create', compact('item', 'contas'));
  }
  public function store(Request $request, FinImportacao $importacao)
  {
    $this->authorize('config_importacao_create');
    if($request->hasFile('import_file')){
      $conta = $request->conta_id;
      $tipo = $request->tipo;
      $path = $request->import_file->path();
      config(['excel.import.startRow' => 15]);
      $data = Excel::load($path, function($reader) { })->get();
      if(!empty($data) && $data->count()){
        $importacao = new FinImportacao;
        $importacao->nome = 'Categoria de '.$tipo.' '.$data->getTitle().'('.$data->count().')';
        $importacao->tipo_id = 2;
        $saveimp =  $importacao->save();
        if($saveimp){
          $usuario = auth()->user()->id;
          foreach($data as $sheet)
          {
            $tipo = $sheet->getTitle();
            if($tipo != 'Receita' && $tipo != 'Despesa') {
              $iimpo = FinCategoria::where('importacao_id', $importacao->id)->delete();
              $imp = FinImportacao::where('id', $importacao->id)->delete();
              return redirect()->back()->withErrors([ 'error' => 'O arquivo enviado não está no padrão, por favor faça o download do modelo padrão!']);
            }
            if($request->tipo == 'ReceitaDespesa'){
              $tipo = $sheet->getTitle();
            } else {
              if($request->tipo != $sheet->getTitle())
                continue;
            }
            foreach ($sheet as $value) {
              $cod = str_replace(' ', '', $value->cod_categoria);
              $qtde = (substr($cod, -1) == ".") ? explode('.', $cod , -1) : explode('.', $cod);
              if(count($qtde) == 1){
                $c = FinCategoria::where("cod", $qtde[0].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$c){
                  $c = new FinCategoria;
                  $c->cod = $qtde[0].".";
                  $c->tipo = $tipo;
                  $c->nome = $value->nome;
                  $c->descricao = $value->descricao;
                  $c->importacao_id = $importacao->id;
                  $c->save();
                }
              } if(count($qtde) == 2){
                $c = FinCategoria::where("cod", $qtde[0].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$c){
                  $c = new FinCategoria;
                  $c->cod = $qtde[0].".";
                  $c->tipo = $tipo;
                  $c->nome = $value->nome;
                  $c->descricao = $value->descricao;
                  $c->importacao_id = $importacao->id;
                  $c->save();
                }
                $ca = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$ca){
                  $ca = new FinCategoria;
                  $ca->cod = $qtde[0].".".$qtde[1].".";
                  $ca->tipo = $tipo;
                  $ca->nome = $value->nome;
                  $ca->categoria_id = $c->id;
                  $ca->descricao = $value->descricao;
                  $ca->importacao_id = $importacao->id;
                  $ca->save();
                }
              } if(count($qtde) == 3){
                $c = FinCategoria::where("cod", $qtde[0].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$c){
                  $c = new FinCategoria;
                  $c->cod =  $qtde[0].".";
                  $c->tipo = $tipo;
                  $c->nome = $value->nome;
                  $c->descricao = $value->descricao;
                  $c->importacao_id = $importacao->id;
                  $c->save();
                }
                $ca = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$ca){
                  $ca = new FinCategoria;
                  $ca->cod = $qtde[0].".".$qtde[1].".";
                  $ca->tipo = $tipo;
                  $ca->nome = $value->nome;
                  $ca->categoria_id = $c->id;
                  $ca->descricao = $value->descricao;
                  $ca->importacao_id = $importacao->id;
                  $ca->save();
                }
                $cat = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".".$qtde[2].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$cat){
                  $cat = new FinCategoria;
                  $cat->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".";
                  $cat->tipo = $tipo;
                  $cat->nome = $value->nome;
                  $cat->categoria_id = $ca->id;
                  $cat->descricao = $value->descricao;
                  $cat->importacao_id = $importacao->id;
                  $cat->save();
                }
              } if(count($qtde) == 4){
                $c = FinCategoria::where("cod", $qtde[0].".")->where("tipo", $tipo)->where("importacao_id", $importacao->id)->first();
                if(!$c){
                  $c = new FinCategoria;
                  $c->cod = $qtde[0].".";
                  $c->tipo = $tipo;
                  $c->nome = $value->nome;
                  $c->descricao = $value->descricao;
                  $c->importacao_id = $importacao->id;
                  $c->save();
                }
                $ca = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$ca){
                  $ca = new FinCategoria;
                  $ca->cod =  $qtde[0].".".$qtde[1].".";
                  $ca->tipo = $tipo;
                  $ca->nome = $value->nome;
                  $ca->categoria_id = $c->id;
                  $ca->descricao = $value->descricao;
                  $ca->importacao_id = $importacao->id;
                  $ca->save();
                }
                $cat = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".".$qtde[2].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$cat){
                  $cat = new FinCategoria;
                  $cat->cod =  $qtde[0].".".$qtde[1].".".$qtde[2].".";
                  $cat->tipo = $tipo;
                  $cat->nome = $value->nome;
                  $cat->categoria_id = $ca->id;
                  $cat->descricao = $value->descricao;
                  $cat->importacao_id = $importacao->id;
                  $cat->save();
                }
                $cate = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$cate){
                  $cate = new FinCategoria;
                  $cate->cod =  $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".";
                  $cate->tipo = $tipo;
                  $cate->nome = $value->nome;
                  $cate->categoria_id = $cat->id;
                  $cate->descricao = $value->descricao;
                  $cate->importacao_id = $importacao->id;
                  $cate->save();
                }
              } if(count($qtde) == 5){
                $c = FinCategoria::where("cod", $qtde[0].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$c){
                  $c = new FinCategoria;
                  $c->cod = $qtde[0].".";
                  $c->tipo = $tipo;
                  $c->nome = $value->nome;
                  $c->descricao = $value->descricao;
                  $c->importacao_id = $importacao->id;
                  $c->save();
                }
                $ca = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$ca){
                  $ca = new FinCategoria;
                  $ca->cod = $qtde[0].".".$qtde[1].".";
                  $ca->tipo = $tipo;
                  $ca->nome = $value->nome;
                  $ca->categoria_id = $c->id;
                  $ca->descricao = $value->descricao;
                  $ca->importacao_id = $importacao->id;
                  $ca->save();
                }
                $cat = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".".$qtde[2].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$cat){
                  $cat = new FinCategoria;
                  $cat->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".";
                  $cat->tipo = $tipo;
                  $cat->nome = $value->nome;
                  $cat->categoria_id = $ca->id;
                  $cat->descricao = $value->descricao;
                  $cat->importacao_id = $importacao->id;
                  $cat->save();
                }
                $cate = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$cate){
                  $cate = new FinCategoria;
                  $cate->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".";
                  $cate->tipo = $tipo;
                  $cate->nome = $value->nome;
                  $cate->categoria_id = $cat->id;
                  $cate->descricao = $value->descricao;
                  $cate->importacao_id = $importacao->id;
                  $cate->save();
                }
                $categ = FinCategoria::where("cod", $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".".$qtde[4].".")->where("importacao_id", $importacao->id)->where("tipo", $tipo)->first();
                if(!$categ){
                  $categ = new FinCategoria;
                  $categ->cod = $qtde[0].".".$qtde[1].".".$qtde[2].".".$qtde[3].".".$qtde[4].".";
                  $categ->tipo = $tipo;
                  $categ->nome = $value->nome;
                  $categ->categoria_id = $cate->id;
                  $categ->descricao = $value->descricao;
                  $categ->importacao_id = $importacao->id;
                  $categ->save();
                }
              }
            }
          }
        }
      }
    } else {
      return redirect()->back()->withErrors([ 'error' => 'Falha ao importar dados!']);
    }
    return redirect()->route('fin.categoria.index');
  }
  public function destroy($id)
  {
    $this->authorize('config_importacao_delete');
    $iimpo = FinCategoria::where('importacao_id', $id)->update(['deleted_at' => Carbon::now()]);
    $imp = FinImportacao::where('id', $id)->update(['deleted_at' => Carbon::now()]);
    if($iimpo && $imp)
      return redirect()->route('imp.categoria.index');
    else
      return redirect()->route('imp.categoria.index')->with([ 'error' => 'Falha ao deletar!']);
  }
  public function lista()
  {
    $this->authorize('config_importacao_read');
    $order = Request('order');
    $order = Request()->has('order') ? $order : 'created_at';
    $sort = Request('sort');
    $sort = Request()->has('sort') ? $sort : 'asc' ;
    $search = Request('input-search');
    if( $search || $search != '' ){
      $itens = FinImportacao::where('tipo_id', 2)->where('nome', 'LIKE', "%$search%")
      ->orderBy('nome', 'asc')
      ->paginate(28);
    } else {
      $itens = FinImportacao::where('tipo_id', 2)->orderBy($order, $sort)
      ->paginate(28);
    }
    return response(view('config.importacao.categoria.lista', compact('itens')));
  }
}
