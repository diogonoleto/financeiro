<?php

namespace App\Http\Controllers\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config\SisRegra;
use App\Models\Config\SisPermissao;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User\User;
use Carbon\Carbon;
use Gate;
use DB;
class PerfilController extends Controller
{

  public function index()
  {
    if( Gate::denies('config_perfil_read') )
      return redirect()->route('financeiro');
    $title = 'Configurações de Perfis';
    return view('config.perfil.index', compact('title'));
  }

  public function create(SisRegra $regra)
  {
    $this->authorize('config_perfil_create');
    $item = $regra;
    return view('config.perfil.create', compact('item'));
  }
  public function store(Request $request, SisRegra $regra)
  {
    $this->authorize('config_perfil_create');

    $request['nome'] = Auth()->user()->sis_conta_id.'_'.$request->nome;
    $this->validate($request, [
      'nome' => 'required|min:5|unique:sis_regras,nome,NULL,id,deleted_at,NULL,sis_conta_id,'.Auth()->user()->sis_conta_id
    ]);
    $regra->nome = $request->nome;
    $regra->descricao = $request->descricao;
    $regra->sis_conta_id = Auth()->user()->sis_conta_id;
    $regra->save();
    return redirect()->route('configPer.perfil.edit', $regra->id);
  }
  public function edit($id, SisRegra $regra)
  {
    $this->authorize('config_perfil_create');

    $perfil = $regra->find($id);
    if($perfil == NULL || $perfil->nome == '0_administrador' || $perfil->nome == Auth()->user()->sis_conta_id.'_'.'admin_empresa' )
      return redirect()->route('configPer.perfil.index');

    $permissaos = SisPermissao::select(DB::raw('sis_modulos.nome, sis_permissaos.sis_modulo_id, COUNT(*) as qtde'))
                            ->join('sis_conta_sis_modulo', 'sis_conta_sis_modulo.sis_modulo_id', '=', 'sis_permissaos.sis_modulo_id')
                            ->join('sis_modulos', 'sis_modulos.id', '=', 'sis_permissaos.sis_modulo_id')
                            ->groupBy('sis_modulos.nome', 'sis_permissaos.sis_modulo_id')
                            ->where('sis_conta_sis_modulo.sis_conta_id', Auth()->user()->sis_conta_id)
                            ->with('area')
                            ->get();
    $regra = SisRegra::where('id', $id)
                    ->with('permissaoRegra')
                    ->where('sis_conta_id', Auth()->user()->sis_conta_id)
                    ->first();
    $qtdsci = strlen(Auth()->user()->sis_conta_id)+1;
    return view('config.perfil.edit', compact('perfil', 'permissaos', 'regra', 'qtdsci'));
  }
  public function update(Request $request, $id)
  {
    $this->authorize('config_perfil_update');
    $perfil = SisRegra::where('id', $id)->where('sis_conta_id', Auth()->user()->sis_conta_id)->first();
    if($perfil == NULL || $perfil->nome == '0_administrador' || $perfil->nome == Auth()->user()->sis_conta_id.'_'.'admin_empresa' )
      return redirect()->route('configPer.perfil.index');

    $perfil->permissaoRegra()->sync($request->permissaoRegra);
    return redirect()->route('configPer.perfil.index');
  }
  public function destroy(Request $request, $id)
  {
    $this->authorize('config_perfil_delete');

     $perfil = SisRegra::where('id', $id)
                      ->with('user')
                      ->first();

    if($request->delete_confirmar == 0 || count($perfil->user) > 0){
      $delete_confirmar = 1;
      if(count($perfil->user) > 0){
        $delete_confirmar = 0;
      }
      return response(view('config.perfil.delete', compact('id', 'perfil', 'delete_confirmar')));
    } else {

      if($perfil == NULL || $perfil->nome == '0_administrador' || $perfil->nome == Auth()->user()->sis_conta_id.'_'.'admin_empresa' )
        return redirect()->route('configPer.perfil.index');

      $perfil->deleted_at = Carbon::now();
      $perfil->update();
      $item['perfilDel'] = $perfil;
      return $item;
    }


    return redirect()->route('configPer.perfil.index', $id)->with([ 'error' => 'Falha ao editar!']);
  }
  public function lista()
  {
    $this->authorize('config_perfil_read');
    
    $itens = SisRegra::where('sis_conta_id', Auth()->user()->sis_conta_id)
                      ->whereNull("deleted_at")
                      ->paginate(28);
    $qtdsci = strlen(Auth()->user()->sis_conta_id)+1;
    return response(view('config.perfil.lista', compact('itens','qtdsci')));
  }
}
