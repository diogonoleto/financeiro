<?php

namespace App\Http\Controllers\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config\Doeam\DoeamMateriaTipo;
use App\Models\Config\Doeam\Doeamlayout;
use App\Models\Config\Doeam\DoeamDiario;
use App\Models\Doeam\Materia;
use App\Models\Financeiro\FinConta;
use Gate;

class ConfigController extends Controller
{

  public function index()
  {
    if( Gate::denies('config_read') )
      return redirect()->route('financeiro');
    $contas = FinConta::whereNotIn("conta_tipo_id", [4,5,6,7])->whereNull('deleted_at')->get();
    $title = 'Configurações';
    return view('config.index', compact('title', 'contas'));
  }
  public function store(Request $request, DoeamMateriaTipo $tipo)
  {
    $this->authorize('config_create');
    if($request->table == "tipo"){
      $tipo->nome = $request->nome;
      $tipo->save();
      if($tipo)
        return redirect()->route('config.index');
      else
        return redirect()->route('config.index')->with([ 'error' => 'Falha ao editar!']);
    } else {
      
    }
  }
  public function update(Request $request, $id)
  {
    $this->authorize('config_update');
    if($request->table == "tipo"){
      $tipo = DoeamMateriaTipo::find($id);
      $tipo->nome = $request->nome;
      $tipo->update();
      if($tipo)
        return redirect()->route('config.index');
      else
        return redirect()->route('config.index', $id)->with([ 'error' => 'Falha ao editar!']);
    } else {
    }
  }

  public function destroy(Request $request, $id)
  {
    $this->authorize('config_delete');
    if($request->table_del == "tipo"){
      $tipo = DoeamMateriaTipo::find($id);
      $tipo->deleted_at = date("Y-m-d H:i:s");
      $tipo->update();
      if($tipo)
        return redirect()->route('config.index');
      else
        return redirect()->route('config.index', $id)->with([ 'error' => 'Falha ao editar!']);
    }
  }

  public function lista(){
    if( Gate::denies('tipo_read') )
      return redirect()->back();
    $tipos = DoeamMateriaTipo::all();
    return response(view('config.lista', compact('tipos')));
  }
}
