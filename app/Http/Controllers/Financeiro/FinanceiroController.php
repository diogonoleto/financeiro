<?php

namespace App\Http\Controllers\Financeiro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Financeiro\FinConta;
use App\Models\Financeiro\FinMovimento;
use Carbon\Carbon;
use DB;
use Gate;

class FinanceiroController extends Controller
{
  public function index()
  {
    // if( Gate::denies('financeiro_read') )
    //   return redirect()->back();
    $title = 'Sistema Financeiro';

    $agora = Carbon::now();
    $agora = date('Y-m-d', strtotime($agora));
    $from = date('Y-m-01', strtotime($agora));
    $to = date('Y-m-t', strtotime($from));
    
    $contas = FinMovimento::select(DB::raw('fin_contas.img, fin_contas.descricao, fin_contas.padrao, SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor_recebido , -1*fin_movimentos.valor_recebido )) as valor'))
                        ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
                        ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                        ->whereNotNull('fin_movimentos.data_baixa')
                        ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
                        ->whereNull("fin_movimentos.deleted_at")
                        ->whereNull("fin_contas.deleted_at")
                        ->groupBy('fin_contas.descricao', 'fin_contas.img', 'fin_contas.padrao')
                        ->orderBy('fin_contas.padrao', 'DESC')
                        ->get();

    $matu = FinMovimento::select(DB::raw('
                          IFNULL(SUM(IF(fin_categorias.tipo = "Receita", fin_movimentos.valor,0)),0) as RPrevisto,
                          IFNULL(SUM(IF(fin_categorias.tipo = "Despesa", fin_movimentos.valor,0)),0) as DPrevisto,
                          IFNULL(SUM(IF(fin_categorias.tipo = "Receita" AND fin_movimentos.data_baixa is not null, fin_movimentos.valor_recebido, 0 )),0) as RRealizado,
                          IFNULL(SUM(IF(fin_categorias.tipo = "Despesa" AND fin_movimentos.data_baixa is not null, fin_movimentos.valor_recebido, 0 )),0) as DRealizado,
                          IFNULL(SUM(IF(fin_categorias.tipo = "Receita" AND fin_movimentos.data_baixa is null, fin_movimentos.valor, 0 )),0) as RFalta,
                          IFNULL(SUM(IF(fin_categorias.tipo = "Despesa" AND fin_movimentos.data_baixa is null, fin_movimentos.valor, 0 )),0) as DFalta
                        '))
                          ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
                          ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                          ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
                          ->whereNull("fin_movimentos.deleted_at")
                          ->where('fin_categorias.nome', '!=', 'Transferência de Entrada')
                          ->where('fin_categorias.nome', '!=', 'Transferência de Saída')
                          ->whereBetween('fin_movimentos.data_vencimento', [$from, $to])
                          ->first();

    $from = date('Y-m-d', strtotime('-1 months', strtotime($from)));
    $to = date('Y-m-d', strtotime('-1 months', strtotime($to))); 

    $mant = FinMovimento::select(DB::raw('
                          IFNULL(SUM(IF(fin_categorias.tipo = "Receita", fin_movimentos.valor , 0 )),0) as ARPrevisto,
                          IFNULL(SUM(IF(fin_categorias.tipo = "Despesa", fin_movimentos.valor , 0 )),0) as ADPrevisto'
                        ))
                        ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
                        ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                        ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
                        ->whereNull("fin_movimentos.deleted_at")
                        ->where('fin_categorias.nome', '!=', 'Transferência de Entrada')
                        ->where('fin_categorias.nome', '!=', 'Transferência de Saída')
                        ->whereBetween('fin_movimentos.data_vencimento', [$from, $to])
                        ->first();


    $seme = date('m', strtotime($agora));
    $date = date('Y');
    if($seme < "6"){
      $from = date('Y-01-01', strtotime($date));
      $to = date('Y-06-31', strtotime($date));
    } else {
      $from = date('Y-07-01', strtotime($date));
      $to = date('Y-12-31', strtotime($date));
    }

    $tot = FinMovimento::select(DB::raw('
                          IFNULL(SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor_recebido , -1*fin_movimentos.valor_recebido )),0) as valor,
                          YEAR(fin_movimentos.data_baixa) as baixa'))
                        ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
                        ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                        ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
                        ->whereNull("fin_movimentos.deleted_at")
                        ->where('fin_categorias.nome', '!=', 'Transferência de Entrada')
                        ->where('fin_categorias.nome', '!=', 'Transferência de Saída')
                        ->where('fin_movimentos.data_baixa', "<", $from)
                        ->groupBy('baixa')
                        ->get();

    $Ptotal = 0;
    $Rtotal = 0;
    foreach($tot as $t){
      if($t->baixa){
        $Rtotal = floatval($t->valor);
      } else {
        $Ptotal = floatval($t->valor);
      }
    }

    $itens = FinMovimento::select(DB::raw('
                            fin_categorias.tipo, MONTH(fin_movimentos.data_vencimento) as mes,
                            MONTH(fin_movimentos.data_baixa) as baixa ,
                            SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor_recebido, -1*fin_movimentos.valor_recebido )) as valor'))
                          ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
                          ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                          ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
                          ->whereNull("fin_movimentos.deleted_at")
                          ->where('fin_categorias.nome', '!=', 'Transferência de Entrada')
                          ->where('fin_categorias.nome', '!=', 'Transferência de Saída')
                          ->whereBetween('fin_movimentos.data_vencimento', [$from, $to])
                          ->groupBy('fin_categorias.tipo', 'mes', 'baixa')
                          ->get();

    // $categs = FinMovimento::select(DB::raw('fin_categorias.tipo, fin_categorias.nome'))
    //                       ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    //                       ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    //                       ->whereNotIn("fin_contas.conta_tipo_id", [4,5,6,7])
    //                       ->whereNull("fin_movimentos.deleted_at")
    //                       ->where('fin_categorias.nome', '!=', 'Transferência de Entrada')
    //                       ->where('fin_categorias.nome', '!=', 'Transferência de Saída')
    //                       ->whereBetween('fin_movimentos.data_vencimento', [$from, $to])
    //                       ->groupBy('fin_categorias.tipo', 'fin_categorias.nome')
    //                       ->get();

    // dd($itens);

    $mes_qtde = 6;

    for ($i=0; $i < $mes_qtde; $i++) {
      $dtf = Carbon::parse($from)->addMonth($i)->toDateString();
      $meses[] = Carbon::parse($dtf)->format("m-Y");

      if($seme > 6)
        $s = 7;
      else
        $s = 1;

      $semRPrevisto=0;
      $semRRealizado=0;
      $semDPrevisto=0;
      $semDRealizado=0;
      
      foreach($itens as $j){
        if($j->mes == $i+$s){
          if($j->tipo == "Receita"){
            $semRPrevisto += $j->valor;
            $Ptotal += $j->valor;
            if($j->baixa != null){
              $semRRealizado += $j->valor_recebido;
              $Rtotal += $j->valor_recebido;
            }
          } else {
            $semDPrevisto += $j->valor;
            $Ptotal -= $j->valor;
            if($j->baixa != null){
              $semDRealizado += $j->valor_recebido;
              $Rtotal -= $j->valor_recebido;
            }
          }
        }
      }

      $totP[$i] = $Ptotal;
      $totR[$i] = $Rtotal;
      $RPrev[] = $semRPrevisto;
      $RReal[] = $semRRealizado;
      $DPrev[] = $semDPrevisto;
      $DReal[] = $semDRealizado;
    }


    $semMeses = $meses;
    $semRPrev = $RPrev;
    $semRReal = $RReal;
    $semDPrev = $DPrev;
    $semDReal = $DReal;
    $semPtotal = $totP;
    $semRtotal = $totR;


    return view('financeiro.index', compact('title', 'contas', 'movimentos', 'mant', 'matu', 'semMeses', 'semRPrev', 'semRReal', 'semDPrev', 'semDReal', 'semPtotal', 'semRtotal', 'agmovimentos', 'atmovimentos'));
  }
  public function agenda()
  {

    $agora = Carbon::now();
    $agora = date('Y-m-d', strtotime($agora));
    $date = Request('date');

    $agmovimentos = FinMovimento::select(DB::raw('fin_movimentos.*, fin_categorias.tipo, fin_categorias.nome'))
                              ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                              ->whereNull("fin_movimentos.deleted_at")
                              ->whereNull("fin_movimentos.data_baixa")
                              ->where('fin_categorias.sis_conta_id', auth()->user()->sis_conta_id)
                              ->where('fin_movimentos.data_vencimento','=', $date)
                              ->get();

    $atmovimentos = FinMovimento::select(DB::raw('fin_movimentos.*, fin_categorias.tipo, fin_categorias.nome'))
                              ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
                              ->whereNull("fin_movimentos.deleted_at")
                              ->whereNull("fin_movimentos.data_baixa")
                              ->where('fin_categorias.sis_conta_id', auth()->user()->sis_conta_id)
                              ->where('fin_movimentos.data_vencimento','<', $date)
                              ->orderBy('fin_movimentos.data_vencimento', 'ASC')
                              ->get();

    return response(view('financeiro.agenda', compact('agmovimentos', 'atmovimentos', 'date', 'agora')));
  }

}
