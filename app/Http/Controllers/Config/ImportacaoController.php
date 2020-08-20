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

class ImportacaoController extends Controller
{
  public function index()
  {
    if( Gate::denies('config_importacao_read') )
      return redirect()->route('financeiro');
      $title = "Importações";
      return view('config.importacao.index', compact('title'));
  }
}
