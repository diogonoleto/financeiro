<?php
namespace App\Http\Controllers\Financeiro;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\Financeiro\MovimentoFormRequest;
use App\Http\Controllers\Controller;
use App\Models\Financeiro\FinMovimento;
use App\Models\Financeiro\FinCategoria;
use App\Models\Financeiro\FinConta;
use App\Models\Financeiro\FinDre;
// use Maatwebsite\Excel\Facades\Excel;
use DB;
use Gate;

class RelatorioController extends Controller
{
  public function index()
  {
    if( Gate::denies('fin_movimento_read') ){
      return redirect()->back();
    }
    return redirect()->route('fin.fdc.mensal');
    // $title = 'Relatórios';
    // return view('financeiro.relatorio.index', compact('title'));
  }
  public function diario()
  {
    if( Gate::denies('fin_movimento_read') ){
      return redirect()->back();
    }
    $title = 'Fluxo de Caixa Diário';
    $contas = FinConta::whereNotIn("conta_tipo_id", [5,6,7])->whereNull("deleted_at")->get();
    return view('financeiro.relatorio.fluxo-de-caixa.diario.index', compact('title', 'contas'));
  }
  public function diarioLista()
  {
    $this->authorize('fin_movimento_read');
    $date = Request('date');
    $date = Request()->has('date') ? $date : date('Y-m');
    $from = date('Y-m-01', strtotime($date));
    $to = date('Y-m-t', strtotime($from));
    $conta = Request('conta');


    $t = FinMovimento::select(DB::raw('SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereNotNull('fin_movimentos.data_baixa')
    ->whereNotIn("conta_tipo_id", [5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->where('fin_movimentos.data_vencimento', "<", $from);



    if($conta){
      $tconta = explode(",", $conta);
      $t->whereIn('fin_movimentos.conta_id', $tconta);
    }
    $tot = $t->first();

    $i = FinMovimento::select(DB::raw('fin_movimentos.data_vencimento, fin_categorias.tipo, IFNULL(SUM(fin_movimentos.valor),0) as valor'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereBetween('fin_movimentos.data_vencimento', [$from, $to])
    ->whereNotIn("conta_tipo_id", [5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->whereNotNull('fin_movimentos.data_baixa');

    if($conta){
      $tconta = explode(",", $conta);
      $i->whereIn('fin_movimentos.conta_id', $tconta);
    }

    $i->groupBy('fin_movimentos.data_vencimento', 'fin_categorias.tipo');
    $itens = $i->get();

    $total = floatval($tot->valor);
    $ob = "";
    $dia_qtde = Carbon::parse($from)->daysInMonth;
    for ($i=0; $i < $dia_qtde; $i++) {
      $mes[$i] = Carbon::parse($from)->addDays($i)->toDateString();
      $meses[] = $i+1;
      $dia = Carbon::parse($from)->addDays($i)->toDateString();
      $d = 0;
      $r = 0;
      $ob .= '<div class="col-xs-12 grid-table">';
      $ob .= '<div class="col-sm-3">';
      $dt = Carbon::parse($mes[$i])->format("d/m/Y");
      $ob .= $dt;
      $ob .= '</div>';
      $ob .= '<div class="col-sm-3 text-right">';
      foreach($itens as $j){
        if($j->data_vencimento == $dia && $j->tipo == 'Receita'){
          $d = floatval($j->valor);
          $total += $d;
        }
      }
      $ob .= number_format($d, 2, ',', '.');
      $ob .= '</div>';
      $ob .= '<div class="col-sm-3 text-right">';
      foreach($itens as $j){
        if($j->data_vencimento == $dia && $j->tipo == 'Despesa'){
          $r = floatval(-$j->valor);
          $total += $r;
        }
      }
      $ob .= number_format($r, 2, ',', '.');
      $ob .= '</div>';
      $ob .= '<div class="col-sm-3 text-right">';
      $ob .= number_format($total, 2, ',', '.');
      $ob .= '</div>';
      $ob .= '</div>';
      $dis[$i] = $d;
      $rec[$i] = $r;
      $toti[$i] = $total;
    }
    $item['meses'] = $meses;
    $item['dis'] = $dis;
    $item['rec'] = $rec;
    $item['tot'] = $toti;
    $item['itens'] = $itens;
    $item['mes'] = $mes;
    $item['ob']  = $ob;

    return $item;
  }

  public function mensal()
  {
    if( Gate::denies('fin_movimento_read') ){
      return redirect()->back();
    }

    $date = date('2018-m-d');
    $from = date('Y-01-01', strtotime($date));
    $to = date('Y-12-31', strtotime($date));


    $title = 'Fluxo de Caixa Mensal';
    $contas = FinConta::whereNotIn("conta_tipo_id", [5,6,7])->whereNull("deleted_at")->get();
    return view('financeiro.relatorio.fluxo-de-caixa.mensal.index', compact('title', 'contas'));
  }
  public function mensalLista()
  {
    $this->authorize('fin_movimento_read');

    $lancamento = Request()->has('lancamento') ? Request('lancamento') : NULL;
    $conta = Request('conta');

    $date = Request('date');
    $from = date('Y-01-01', strtotime($date));
    $to = date('Y-12-31', strtotime($date));

    $t = FinMovimento::select(DB::raw('IFNULL(SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )),0) as valor, YEAR(fin_movimentos.data_baixa) as baixa'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereNotIn("conta_tipo_id", [4,5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->where('fin_movimentos.data_vencimento', "<", $from);

    if($conta){
      $tconta = explode(",", $conta);
      $t->whereIn('fin_movimentos.conta_id', $tconta);
    }
    if($lancamento == "pagos"){
      $t->whereNotNull('fin_movimentos.data_baixa');
    } else if($lancamento == "npagos"){
      $t->whereNull('fin_movimentos.data_baixa');
    }
    $t->groupBy('baixa');

    $tot = $t->get();

    $Ptotal = 0;
    $Rtotal = 0;
    foreach($tot as $t){
      if($t->baixa){
        $Rtotal = floatval($t->valor_recebido);
      } else {
        $Ptotal = floatval($t->valor);
      }
    }

    $i = FinMovimento::select(DB::raw('fin_categorias.tipo, MONTH(fin_movimentos.data_vencimento) as mes , MONTH(fin_movimentos.data_baixa) as baixa , SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor, fin_categorias.nome'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->whereNotIn("conta_tipo_id", [4,5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->whereBetween('fin_movimentos.data_vencimento', [$from, $to]);

    if($conta){
      $iconta = explode(",", $conta);
      $i->whereIn('fin_movimentos.conta_id', $iconta);
    }
    if($lancamento == "pagos"){
      $i->whereNotNull('fin_movimentos.data_baixa');
    } else if($lancamento == "npagos"){
      $i->whereNull('fin_movimentos.data_baixa');
    }
    $i->groupBy('fin_categorias.tipo', 'mes', 'baixa', 'fin_categorias.nome');
    $itens = $i->get();



    $categs = FinCategoria::whereNull('categoria_id')
                          // ->where('nome', '!=', 'Saldo Inicial')
    ->where('nome', '!=' ,'Transferência de Saída')
    ->where('nome', '!=' ,'Transferência de Entrada')
    ->orderBy('fin_categorias.tipo', 'asc')
    ->orderBy('fin_categorias.ordem', 'asc')
    ->orderBy('fin_categorias.nome', 'asc')
    ->with('childrenfca')
    ->whereNull("fin_categorias.deleted_at")
    ->get();

    $mes_qtde = 12;

    $li = array();

    $row["r1"][] = 'Categorias de Lançamento';
    $row["r2"][] = 'Saldo Do Mês Anterior';
    $row["r3"][] = 'Total De Recebimentos';

    $ob = "";


    for ($i=0; $i < $mes_qtde; $i++) {
      $dtf = Carbon::parse($from)->addMonth($i)->toDateString();
      $row["r1"][] = Carbon::parse($dtf)->formatLocalized("%b/%y");

      $row["r2"][] = number_format($Ptotal, 2, ',', '.');
      $row["r2"][] = number_format($Rtotal, 2, ',', '.');


      $RPrevisto=0;
      $RRealizado=0;
      $DPrevisto=0;
      $DRealizado=0;
      foreach($itens as $j){
        if($j->mes == $i+1){
          if($j->tipo == "Receita"){
            $RPrevisto += $j->valor;
            $Ptotal += $j->valor;
            if($j->baixa != null){
              $RRealizado += $j->valor;
              $Rtotal += $j->valor;
            }
          } else if($j->tipo == "Despesa"){
            $DPrevisto += $j->valor;
            $Ptotal += $j->valor;
            if($j->baixa != null){
              $DRealizado += $j->valor;
              $Rtotal += $j->valor;
            }
          }
        }
      }

      $totP[$i] = $Ptotal;
      $totR[$i] = $Rtotal;

      $RPrev[] = $RPrevisto;
      $RReal[] = $RRealizado;
      $DPrev[] = $DPrevisto;
      $DReal[] = $DRealizado;
      $obc2 = '';
      $catsp=0;
      $catsr=0;
      foreach($categs as $ca){

        if($ca->tipo == "Receita"){
          $casp=0;
          $casr=0;
          $obc1 = '';

        //   $row["r".$i+4][] = $ca->nome;


          foreach($itens as $it){
            if ($ca->nome == $it->nome ) {
              if($it->mes == $i+1){
                $casp += $it->valor;
                if($it->baixa != null){
                  $casr += $it->valor;
                }
              }
            }
          }
          foreach($ca->childrenfca as $ch){
            $chsp=0;
            $chsr=0;
            $obc = '';
            foreach($itens as $it){
              if ($ch->nome == $it->nome ) {
                if($it->mes == $i+1){
                  $chsp += $it->valor;
                  if($it->baixa != null){
                    $chsr += $it->valor;
                  }
                }
              }
            }
            foreach($ch->childrenfca as $chi){
              $chisp=0;
              $chisr=0;
              $cobc = '';
              foreach($itens as $it){
                if ($chi->nome == $it->nome ) {
                  if($it->mes == $i+1){
                    $chisp += $it->valor;
                    if($it->baixa != null){
                      $chisr += $it->valor;
                    }
                  }
                }
              }
              foreach($chi->childrenfca as $cchi){
                $cchisp=0;
                $cchisr=0;
                $ccobc = '';
                foreach($itens as $it){
                  if ($cchi->nome == $it->nome ) {
                    if($it->mes == $i+1){
                      $cchisp += $it->valor;
                      if($it->baixa != null){
                        $cchisr += $it->valor;
                      }
                    }
                  }
                }
                foreach($cchi->childrenfca as $ccchi){
                  $ccchisp=0;
                  $ccchisr=0;
                  foreach($itens as $it){
                    if ($ccchi->nome == $it->nome ) {
                      if($it->mes == $i+1){
                        $ccchisp += $it->valor;
                        if($it->baixa != null){
                          $ccchisr += $it->valor;
                        }
                      }
                    }
                  }
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                }
                $chisp += $cchisp;
                $chisr += $cchisr;
                $rmcategdiv = $cchi->childrenfca->count()>0 ? "rmcategdiv" : null;
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                $cobc .= $ccobc;
              }
              $chsp += $chisp;
              $chsr += $chisr;
              $rmcategdiv = $chi->childrenfca->count()>0 ? "rmcategdiv" : null;
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisp, 2, ',', '.').'</div>';
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisr, 2, ',', '.').'</div>';
              $obc .= $cobc;
            }
            $casp += $chsp;
            $casr += $chsr;
            $rmcategdiv = $ch->childrenfca->count()>0 ? "rmcategdiv" : null;
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsp, 2, ',', '.').'</div>';
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsr, 2, ',', '.').'</div>';
            $obc1 .= $obc;
          }
          $catsp += $casp;
          $catsr += $casr;
          $rmcategdiv = $ca->childrenfca->count()>0 ? "rmcategdiv" : null;
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casp, 2, ',', '.').'</div>';
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casr, 2, ',', '.').'</div>';
          $obc2 .= $obc1;
        }
      }

      $row["r3"][] = number_format($catsp, 2, ',', '.');
      $row["r3"][] = number_format($catsr, 2, ',', '.');
      $ob .= $obc2;

      $obc2 = '';
      $catsp=0;
      $catsr=0;
      foreach($categs as $ca){
        if($ca->tipo == "Despesa"){
          $casp=0;
          $casr=0;
          $obc1 = '';
          foreach($itens as $it){
            if ($ca->nome == $it->nome ) {
              if($it->mes == $i+1){
                $casp += $it->valor;
                if($it->baixa != null){
                  $casr += $it->valor;
                }
              }
            }
          }
          foreach($ca->childrenfca as $ch){
            $chsp=0;
            $chsr=0;
            $obc = '';
            foreach($itens as $it){
              if ($ch->nome == $it->nome ) {
                if($it->mes == $i+1){
                  $chsp += $it->valor;
                  if($it->baixa != null){
                    $chsr += $it->valor;
                  }
                }
              }
            }
            foreach($ch->childrenfca as $chi){
              $chisp=0;
              $chisr=0;
              $cobc = '';
              foreach($itens as $it){
                if ($chi->nome == $it->nome ) {
                  if($it->mes == $i+1){
                    $chisp += $it->valor;
                    if($it->baixa != null){
                      $chisr += $it->valor;
                    }
                  }
                }
              }
              foreach($chi->childrenfca as $cchi){
                $cchisp=0;
                $cchisr=0;
                $ccobc = '';
                foreach($itens as $it){
                  if ($cchi->nome == $it->nome ) {
                    if($it->mes == $i+1){
                      $cchisp += $it->valor;
                      if($it->baixa != null){
                        $cchisr += $it->valor;
                      }
                    }
                  }
                }
                foreach($cchi->childrenfca as $ccchi){
                  $ccchisp=0;
                  $ccchisr=0;
                  foreach($itens as $it){
                    if ($ccchi->nome == $it->nome ) {
                      if($it->mes == $i+1){
                        $ccchisp += $it->valor;
                        if($it->baixa != null){
                          $ccchisr += $it->valor;
                        }
                      }
                    }
                  }
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                }
                $chisp += $cchisp;
                $chisr += $cchisr;
                $rmcategdiv = $cchi->childrenfca->count()>0 ? "rmcategdiv" : null;
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                $cobc .= $ccobc;
              }
              $chsp += $chisp;
              $chsr += $chisr;
              $rmcategdiv = $chi->childrenfca->count()>0 ? "rmcategdiv" : null;
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisp, 2, ',', '.').'</div>';
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisr, 2, ',', '.').'</div>';
              $obc .= $cobc;
            }
            $casp += $chsp;
            $casr += $chsr;
            $rmcategdiv = $ch->childrenfca->count()>0 ? "rmcategdiv" : null;
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsp, 2, ',', '.').'</div>';
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsr, 2, ',', '.').'</div>';
            $obc1 .= $obc;
          }
          $catsp += $casp;
          $catsr += $casr;
          $rmcategdiv = $ca->childrenfca->count()>0 ? "rmcategdiv" : null;
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casp, 2, ',', '.').'</div>';
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casr, 2, ',', '.').'</div>';
          $obc2 .= $obc1;
        }
      }

      $ob .= '<div class="rel-6 tot">'.number_format($catsp, 2, ',', '.').'</div>';
      $ob .= '<div class="rel-6 tot">'.number_format($catsr, 2, ',', '.').'</div>';
      $ob .= $obc2;

      $ob .= '<div class="rel-6 metop text-right">'.number_format($Ptotal, 2, ',', '.').'</div>';
      $ob .= '<div class="rel-6 metop text-right">'.number_format($Rtotal, 2, ',', '.').'</div>';

      $ob .= '</div>';



    }



    $ob  = '<div class="rel-3">';
    $ob .= '<div class="metop">';
    $ob .= 'Categorias de Lançamento';
    $ob .= '</div>';

    $ob .= '<div class="metop">';
    $ob .= 'Saldo do Mês Anterior';
    $ob .= '</div>';

    $ob .= '<div class="tot">';
    $ob .= 'Total de Recebimentos';
    $ob .= '</div>';
    foreach($categs as $c){
      if($c->tipo == "Receita"){
        if($c->childrenfca->count()>0)
          $ob .= '<div class="rmcateg" rel="'.$c->id.'"><i class="mdi mdi-chevron-right"></i> ';
        else
          $ob .= '<div class="nchi"><i class="mdi mdi-minus"></i> ';
        $ob .= $c->nome;
        $ob .= '</div>';
        foreach($c->childrenfca as $d){
          if($d->childrenfca->count()>0)
            $ob .= '<div class="scategoria chi-'.$d->categoria_id.' hidden rmcateg" rel="'.$d->id.'"><i class="mdi mdi-chevron-right"></i> ';
          else
            $ob .= '<div class="scategoria chi-'.$d->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
          $ob .= $d->nome;
          $ob .= '</div>';
          foreach($d->childrenfca as $e){
            if($e->childrenfca->count()>0)
              $ob .= '<div class="sscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' hidden rmcateg" rel="'.$e->id.'"><i class="mdi mdi-chevron-right"></i> ';
            else
              $ob .= '<div class="sscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
            $ob .= $e->nome;
            $ob .= '</div>';
            foreach($e->childrenfca as $f){
              if($f->childrenfca->count()>0)
                $ob .= '<div class="ssscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' chi-'.$f->categoria_id.' hidden rmcateg" rel="'.$f->id.'"><i class="mdi mdi-chevron-right"></i> ';
              else
                $ob .= '<div class="ssscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' chi-'.$f->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
              $ob .= $f->nome;
              $ob .= '</div>';
              foreach($f->childrenfca as $g){
                $ob .= '<div class="sssscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' chi-'.$f->categoria_id.' chi-'.$g->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
                $ob .= $f->nome;
                $ob .= '</div>';
              }
            }
          }
        }
      }
    }
    $ob .= '<div class="tot">';
    $ob .= 'Total de Pagamento';
    $ob .= '</div>';
    foreach($categs as $c){
      if($c->tipo == "Despesa"){
        if($c->childrenfca->count()>0)
          $ob .= '<div class="rmcateg" rel="'.$c->id.'"><i class="mdi mdi-chevron-right"></i> ';
        else
          $ob .= '<div class="nchi"><i class="mdi mdi-minus"></i> ';
        $ob .= $c->nome;
        $ob .= '</div>';
        foreach($c->childrenfca as $d){
          if($d->childrenfca->count()>0)
            $ob .= '<div class="scategoria chi-'.$d->categoria_id.' hidden rmcateg" rel="'.$d->id.'"><i class="mdi mdi-chevron-right"></i> ';
          else
            $ob .= '<div class="scategoria chi-'.$d->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
          $ob .= $d->nome;
          $ob .= '</div>';
          foreach($d->childrenfca as $e){
            if($e->childrenfca->count()>0)
              $ob .= '<div class="sscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' hidden rmcateg" rel="'.$e->id.'"><i class="mdi mdi-chevron-right"></i> ';
            else
              $ob .= '<div class="sscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
            $ob .= $e->nome;
            $ob .= '</div>';
            foreach($e->childrenfca as $f){
              if($f->childrenfca->count()>0)
                $ob .= '<div class="ssscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' chi-'.$f->categoria_id.' hidden rmcateg" rel="'.$f->id.'"><i class="mdi mdi-chevron-right"></i> ';
              else
                $ob .= '<div class="ssscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' chi-'.$f->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
              $ob .= $f->nome;
              $ob .= '</div>';
              foreach($f->childrenfca as $g){
                $ob .= '<div class="sssscategoria chi-'.$d->categoria_id.' chi-'.$e->categoria_id.' chi-'.$f->categoria_id.' chi-'.$g->categoria_id.' hidden"><i class="mdi mdi-minus"></i> ';
                $ob .= $f->nome;
                $ob .= '</div>';
              }
            }
          }
        }
      }
    }

    $ob .= '<div class="metop">';
    $ob .= 'Saldo Final de Caixa';
    $ob .= '</div>';


    $ob .= '</div>';
    $ob .= '<div class="rel-7 scrollbar-inner">';

    for ($i=0; $i < $mes_qtde; $i++) {
      $ob .= '<div class="rel-1">';
      $ob .= '<div class="rel-12 metop">';
      $dtf = Carbon::parse($from)->addMonth($i)->toDateString();
      $ob .= Carbon::parse($dtf)->formatLocalized("%b/%y");

      $meses[] = Carbon::parse($dtf)->formatLocalized("%b/%y");
      $ob .= '</div>';
      $ob .= '<div class="rel-6 metop">Previsto</div>';
      $ob .= '<div class="rel-6 metop">Realizado</div>';

      $ob .= '<div class="rel-6 metop text-right">'.number_format($Ptotal, 2, ',', '.').'</div>';
      $ob .= '<div class="rel-6 metop text-right">'.number_format($Rtotal, 2, ',', '.').'</div>';

      $RPrevisto=0;
      $RRealizado=0;
      $DPrevisto=0;
      $DRealizado=0;
      foreach($itens as $j){
        if($j->mes == $i+1){
          if($j->tipo == "Receita"){
            $RPrevisto += $j->valor;
            $Ptotal += $j->valor;
            if($j->baixa != null){
              $RRealizado += $j->valor;
              $Rtotal += $j->valor;
            }
          } else if($j->tipo == "Despesa"){
            $DPrevisto += $j->valor;
            $Ptotal += $j->valor;
            if($j->baixa != null){
              $DRealizado += $j->valor;
              $Rtotal += $j->valor;
            }
          }
        }
      }

      $totP[$i] = $Ptotal;
      $totR[$i] = $Rtotal;

      $RPrev[] = $RPrevisto;
      $RReal[] = $RRealizado;
      $DPrev[] = $DPrevisto;
      $DReal[] = $DRealizado;
      $obc2 = '';
      $catsp=0;
      $catsr=0;
      foreach($categs as $ca){
        if($ca->tipo == "Receita"){
          $casp=0;
          $casr=0;
          $obc1 = '';
          foreach($itens as $it){
            if ($ca->nome == $it->nome ) {
              if($it->mes == $i+1){
                $casp += $it->valor;
                if($it->baixa != null){
                  $casr += $it->valor;
                }
              }
            }
          }
          foreach($ca->childrenfca as $ch){
            $chsp=0;
            $chsr=0;
            $obc = '';
            foreach($itens as $it){
              if ($ch->nome == $it->nome ) {
                if($it->mes == $i+1){
                  $chsp += $it->valor;
                  if($it->baixa != null){
                    $chsr += $it->valor;
                  }
                }
              }
            }
            foreach($ch->childrenfca as $chi){
              $chisp=0;
              $chisr=0;
              $cobc = '';
              foreach($itens as $it){
                if ($chi->nome == $it->nome ) {
                  if($it->mes == $i+1){
                    $chisp += $it->valor;
                    if($it->baixa != null){
                      $chisr += $it->valor;
                    }
                  }
                }
              }
              foreach($chi->childrenfca as $cchi){
                $cchisp=0;
                $cchisr=0;
                $ccobc = '';
                foreach($itens as $it){
                  if ($cchi->nome == $it->nome ) {
                    if($it->mes == $i+1){
                      $cchisp += $it->valor;
                      if($it->baixa != null){
                        $cchisr += $it->valor;
                      }
                    }
                  }
                }
                foreach($cchi->childrenfca as $ccchi){
                  $ccchisp=0;
                  $ccchisr=0;
                  foreach($itens as $it){
                    if ($ccchi->nome == $it->nome ) {
                      if($it->mes == $i+1){
                        $ccchisp += $it->valor;
                        if($it->baixa != null){
                          $ccchisr += $it->valor;
                        }
                      }
                    }
                  }
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                }
                $chisp += $cchisp;
                $chisr += $cchisr;
                $rmcategdiv = $cchi->childrenfca->count()>0 ? "rmcategdiv" : null;
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                $cobc .= $ccobc;
              }
              $chsp += $chisp;
              $chsr += $chisr;
              $rmcategdiv = $chi->childrenfca->count()>0 ? "rmcategdiv" : null;
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisp, 2, ',', '.').'</div>';
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisr, 2, ',', '.').'</div>';
              $obc .= $cobc;
            }
            $casp += $chsp;
            $casr += $chsr;
            $rmcategdiv = $ch->childrenfca->count()>0 ? "rmcategdiv" : null;
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsp, 2, ',', '.').'</div>';
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsr, 2, ',', '.').'</div>';
            $obc1 .= $obc;
          }
          $catsp += $casp;
          $catsr += $casr;
          $rmcategdiv = $ca->childrenfca->count()>0 ? "rmcategdiv" : null;
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casp, 2, ',', '.').'</div>';
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casr, 2, ',', '.').'</div>';
          $obc2 .= $obc1;
        }
      }

      $ob .= '<div class="rel-6 tot">'.number_format($catsp, 2, ',', '.').'</div>';
      $ob .= '<div class="rel-6 tot">'.number_format($catsr, 2, ',', '.').'</div>';
      $ob .= $obc2;

      $obc2 = '';
      $catsp=0;
      $catsr=0;
      foreach($categs as $ca){
        if($ca->tipo == "Despesa"){
          $casp=0;
          $casr=0;
          $obc1 = '';
          foreach($itens as $it){
            if ($ca->nome == $it->nome ) {
              if($it->mes == $i+1){
                $casp += $it->valor;
                if($it->baixa != null){
                  $casr += $it->valor;
                }
              }
            }
          }
          foreach($ca->childrenfca as $ch){
            $chsp=0;
            $chsr=0;
            $obc = '';
            foreach($itens as $it){
              if ($ch->nome == $it->nome ) {
                if($it->mes == $i+1){
                  $chsp += $it->valor;
                  if($it->baixa != null){
                    $chsr += $it->valor;
                  }
                }
              }
            }
            foreach($ch->childrenfca as $chi){
              $chisp=0;
              $chisr=0;
              $cobc = '';
              foreach($itens as $it){
                if ($chi->nome == $it->nome ) {
                  if($it->mes == $i+1){
                    $chisp += $it->valor;
                    if($it->baixa != null){
                      $chisr += $it->valor;
                    }
                  }
                }
              }
              foreach($chi->childrenfca as $cchi){
                $cchisp=0;
                $cchisr=0;
                $ccobc = '';
                foreach($itens as $it){
                  if ($cchi->nome == $it->nome ) {
                    if($it->mes == $i+1){
                      $cchisp += $it->valor;
                      if($it->baixa != null){
                        $cchisr += $it->valor;
                      }
                    }
                  }
                }
                foreach($cchi->childrenfca as $ccchi){
                  $ccchisp=0;
                  $ccchisr=0;
                  foreach($itens as $it){
                    if ($ccchi->nome == $it->nome ) {
                      if($it->mes == $i+1){
                        $ccchisp += $it->valor;
                        if($it->baixa != null){
                          $ccchisr += $it->valor;
                        }
                      }
                    }
                  }
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                  $ccobc .= '<div class="rel-6 ssscategoria chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' chi-'.$ccchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                }
                $chisp += $cchisp;
                $chisr += $cchisr;
                $rmcategdiv = $cchi->childrenfca->count()>0 ? "rmcategdiv" : null;
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisp, 2, ',', '.').'</div>';
                $cobc .= '<div class="rel-6 ssscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' chi-'.$cchi->categoria_id.' hidden">'.number_format($cchisr, 2, ',', '.').'</div>';
                $cobc .= $ccobc;
              }
              $chsp += $chisp;
              $chsr += $chisr;
              $rmcategdiv = $chi->childrenfca->count()>0 ? "rmcategdiv" : null;
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisp, 2, ',', '.').'</div>';
              $obc .= '<div class="rel-6 sscategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' chi-'.$chi->categoria_id.' hidden">'.number_format($chisr, 2, ',', '.').'</div>';
              $obc .= $cobc;
            }
            $casp += $chsp;
            $casr += $chsr;
            $rmcategdiv = $ch->childrenfca->count()>0 ? "rmcategdiv" : null;
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsp, 2, ',', '.').'</div>';
            $obc1 .= '<div class="rel-6 scategoria '.$rmcategdiv.' chi-'.$ch->categoria_id.' hidden">'.number_format($chsr, 2, ',', '.').'</div>';
            $obc1 .= $obc;
          }
          $catsp += $casp;
          $catsr += $casr;
          $rmcategdiv = $ca->childrenfca->count()>0 ? "rmcategdiv" : null;
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casp, 2, ',', '.').'</div>';
          $obc2 .= '<div class="rel-6 '.$rmcategdiv.'">'.number_format($casr, 2, ',', '.').'</div>';
          $obc2 .= $obc1;
        }
      }

      $ob .= '<div class="rel-6 tot">'.number_format($catsp, 2, ',', '.').'</div>';
      $ob .= '<div class="rel-6 tot">'.number_format($catsr, 2, ',', '.').'</div>';
      $ob .= $obc2;

      $ob .= '<div class="rel-6 metop text-right">'.number_format($Ptotal, 2, ',', '.').'</div>';
      $ob .= '<div class="rel-6 metop text-right">'.number_format($Rtotal, 2, ',', '.').'</div>';

      $ob .= '</div>';
    }
    $ob .= '</div>';


    $item['ob']  = $ob;
    $item['meses']  = $meses;
    $item['RPrev']  = $RPrev;
    $item['RReal']  = $RReal;
    $item['DPrev']  = $DPrev;
    $item['DReal']  = $DReal;
    $item['Ptotal'] = $totP;
    $item['Rtotal'] = $totR;

    return $item;
  }

  public function dremensal()
  {
    if( Gate::denies('fin_movimento_read') ){
      return redirect()->back();
    }

    $title = 'Demonstrativo de Resultado do Exercício Gerencial';
    $contas = FinConta::whereNotIn("conta_tipo_id", [5,6,7])->whereNull("deleted_at")->get();
    return view('financeiro.relatorio.dre.mensal.index', compact('title', 'contas'));
  }
  public function dremensalLista()
  {
    $this->authorize('fin_movimento_read');
    $lancamento = Request()->has('lancamento') ? Request('lancamento') : NULL;
    $conta = Request('conta');

    $date = Request('date');
    $from = date('Y-01-01', strtotime($date));
    $to = date('Y-12-31', strtotime($date));

    $i = FinMovimento::select(DB::raw('fin_categorias.dre_id, MONTH(fin_movimentos.data_emissao) as mes, SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor, fin_dres.descricao'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->join('fin_dres', 'fin_dres.id', '=', 'fin_categorias.dre_id')
    ->whereNotIn("conta_tipo_id", [5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->whereBetween('fin_movimentos.data_emissao', [$from, $to]);

    if($conta){
      $iconta = explode(",", $conta);
      $i->whereIn('fin_movimentos.conta_id', $iconta);
    }
    if($lancamento == "pagos"){
      $i->whereNotNull('fin_movimentos.data_baixa');
    } else if($lancamento == "npagos"){
      $i->whereNull('fin_movimentos.data_baixa');
    }
    $i->groupBy('fin_categorias.dre_id', 'mes', 'fin_dres.descricao');
    $itens = $i->get();

    $dres = FinDre::whereNull('dre_id')->whereNull('dre_id')->with('childrendre')->get();

    $mes_qtde = 12;

    $ob  = '<div class="col-xs-3 no-padding div-left">';
    $ob .= '<div class="metop">';
    $ob .= 'Categoria';
    $ob .= '</div>';

    foreach($dres as $j){
      if($j->descricao == "Receitas Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Receita Bruta de Vendas';
        $ob .= '</div>';
      } else if($j->descricao == "Deduções da Receita Brutal"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Receita Líquida de Vendas';
        $ob .= '</div>';
      } else if($j->descricao == "Custos Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro Bruto';
        $ob .= '</div>';
      } else if($j->descricao == "Despesas Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro / Prejuízo Operacional';
        $ob .= '</div>';
      } else if($j->descricao == "Receitas e Despesas Financeiras"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro / Prejuízo Líquido';
        $ob .= '</div>';
      } else if($j->descricao == "Outras Receitas e Despesas Não Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
      } else if($j->descricao == "Despesas com Investimentos e Empréstimos"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro / Prejuízo Final';
        $ob .= '</div>';
      }
    }

    $ob .= '</div>';
    $ob .= '<div class="col-xs-8 no-padding div-center  scrollbar-inner">';

    for ($i=0; $i < $mes_qtde; $i++) {
      $ob .= '<div class="col-xs-1 no-padding">';
      $ob .= '<div class="metop">';
      $dtf = Carbon::parse($from)->addMonth($i)->toDateString();
      $ob .= Carbon::parse($dtf)->formatLocalized("%b/%y");

      $meses[] = Carbon::parse($dtf)->formatLocalized("%b/%y");
      $ob .= '</div>';


      $RBV=0;
      $RLV=0;
      $LB=0;
      $DO=0;
      $ORDNO=0;
      $DIE=0;

      foreach($dres as $ca){
        if($ca->descricao == "Receitas Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $RBV += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $RBV += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rmcategdiv">'.number_format($RBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Deduções da Receita Brutal"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $RLV += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $RLV += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV + $RLV, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Custos Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $LB += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $LB += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Despesas Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $DO += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $DO += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB + $DO, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Receitas e Despesas Financeiras"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $ORDNO += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $ORDNO += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }
          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB + $DO + $ORDNO, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Outras Receitas e Despesas Não Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $ORDNO += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $ORDNO += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }
        } else if($ca->descricao == "Despesas com Investimentos e Empréstimos"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                  $DIE += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->mes == $i+1){
                    $CRBV += $it->valor;
                    $DIE += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->mes == $i+1){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }
          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB + $DO + $ORDNO + $DIE, 2, ',', '.').'</div>';
        }
      }
      $ob .= '</div>';
    }
    $ob .= '</div>';


    $ob .= '<div class="col-xs-1 no-padding text-right">';
    $ob .= '<div class="metop">';
    $ob .= 'Total';
    $ob .= '</div>';


    $RBV=0;
    foreach($dres as $ca){
      if($ca->descricao == "Outras Receitas e Despesas Não Operacionais"){
        $CRBV=0;
        if($ca->childrendre->count()==0){
          foreach($itens as $it){
            if ($ca->descricao == $it->descricao ) {
              $CRBV += $it->valor;
              $RBV += $it->valor;
            }
          }
        } else {
          foreach($ca->childrendre as $chsoma){
            foreach($itens as $it){
              if ($chsoma->descricao == $it->descricao ) {
                $CRBV += $it->valor;
                $RBV += $it->valor;
              }
            }
          }
        }
        $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
        foreach($ca->childrendre as $ch){
          $CHRBV=0;
          foreach($itens as $it){
            if ($ch->descricao == $it->descricao ) {
              if($it->mes == $i+1){
                $CHRBV += $it->valor;
              }
            }
          }
          $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CHRBV, 2, ',', '.').'</div>';
        }
      } else if ($ca->descricao == "Receitas Operacionais"){

        $CRBV=0;
        if($ca->childrendre->count()==0){
          foreach($itens as $it){
            if ($ca->descricao == $it->descricao ) {
              $CRBV += $it->valor;
              $RBV += $it->valor;
            }
          }
        } else {
          foreach($ca->childrendre as $chsoma){
            foreach($itens as $it){
              if ($chsoma->descricao == $it->descricao ) {
                $CRBV += $it->valor;
                $RBV += $it->valor;
              }
            }
          }
        }
        $ob .= '<div class="rel-12 rmcategdiv">'.number_format($CRBV, 2, ',', '.').'</div>';
        foreach($ca->childrendre as $ch){
          $CHRBV=0;
          foreach($itens as $it){
            if ($ch->descricao == $it->descricao ) {
              $CHRBV += $it->valor;
            }
          }
          $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CHRBV, 2, ',', '.').'</div>';
        }
        $ob .= '<div class="tot">'.number_format($RBV, 2, ',', '.').'</div>';
      } else {

        $CRBV=0;
        if($ca->childrendre->count()==0){
          foreach($itens as $it){
            if ($ca->descricao == $it->descricao ) {
              $CRBV += $it->valor;
              $RBV += $it->valor;
            }
          }
        } else {
          foreach($ca->childrendre as $chsoma){
            foreach($itens as $it){
              if ($chsoma->descricao == $it->descricao ) {
                $CRBV += $it->valor;
                $RBV += $it->valor;
              }
            }
          }
        }
        $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
        foreach($ca->childrendre as $ch){
          $CHRBV=0;
          foreach($itens as $it){
            if ($ch->descricao == $it->descricao ) {
              $CHRBV += $it->valor;
            }
          }
          $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CHRBV, 2, ',', '.').'</div>';
        }
        $ob .= '<div class="tot">'.number_format($RBV, 2, ',', '.').'</div>';
      }
    }

    $ob .= '</div>';


    $item['ob']  = $ob;

    $item['item']  = $itens;


    return $item;
  }


  public function dreAnual()
  {
    if( Gate::denies('fin_movimento_read') ){
      return redirect()->back();
    }

    $title = 'Demonstrativo de Resultado do Exercício Gerencial Anual';
    $contas = FinConta::whereNotIn("conta_tipo_id", [5,6,7])->whereNull("deleted_at")->get();
    return view('financeiro.relatorio.dre.anual.index', compact('title', 'contas'));
  }
  public function dreAnualLista()
  {
    // $this->authorize('fin_movimento_read');
    $lancamento = Request()->has('lancamento') ? Request('lancamento') : NULL;
    $conta = Request('conta');

    $date = Request('date');
    $from = date('Y', strtotime("-1 year", strtotime($date)));
    $to = date('Y', strtotime($date));


    $i = FinMovimento::select(DB::raw('fin_categorias.dre_id, YEAR(fin_movimentos.data_emissao) as ano, SUM(IF(fin_categorias.tipo = "Receita", 1*fin_movimentos.valor , -1*fin_movimentos.valor )) as valor, fin_dres.descricao'))
    ->join('fin_contas', 'fin_contas.id', '=', 'fin_movimentos.conta_id')
    ->join('fin_categorias', 'fin_categorias.id', '=', 'fin_movimentos.categoria_id')
    ->join('fin_dres', 'fin_dres.id', '=', 'fin_categorias.dre_id')
    ->whereNotIn("conta_tipo_id", [5,6,7])
    ->whereNull("fin_movimentos.deleted_at")
    ->whereBetween('fin_movimentos.data_emissao', [$from, $to]);

    if($conta){
      $iconta = explode(",", $conta);
      $i->whereIn('fin_movimentos.conta_id', $iconta);
    }
    if($lancamento == "pagos"){
      $i->whereNotNull('fin_movimentos.data_baixa');
    } else if($lancamento == "npagos"){
      $i->whereNull('fin_movimentos.data_baixa');
    }
    $i->groupBy('fin_categorias.dre_id', 'ano', 'fin_dres.descricao');
    $itens = $i->get();

    $dres = FinDre::whereNull('dre_id')->whereNull('dre_id')->with('childrendre')->get();

    $ano_qtde = 2;

    $ob  = '<div class="col-xs-3 no-padding div-left">';
    $ob .= '<div class="metop">';
    $ob .= 'Categoria';
    $ob .= '</div>';

    foreach($dres as $j){
      if($j->descricao == "Receitas Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Receita Bruta de Vendas';
        $ob .= '</div>';
      } else if($j->descricao == "Deduções da Receita Brutal"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Receita Líquida de Vendas';
        $ob .= '</div>';
      } else if($j->descricao == "Custos Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro Bruto';
        $ob .= '</div>';
      } else if($j->descricao == "Despesas Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro / Prejuízo Operacional';
        $ob .= '</div>';
      } else if($j->descricao == "Receitas e Despesas Financeiras"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro / Prejuízo Líquido';
        $ob .= '</div>';
      } else if($j->descricao == "Outras Receitas e Despesas Não Operacionais"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
      } else if($j->descricao == "Despesas com Investimentos e Empréstimos"){
        $ob .= '<div class="rmcateg" rel="'.$j->id.'"><i class="mdi mdi-chevron-right"></i> ';
        $ob .= $j->descricao;
        $ob .= '</div>';
        foreach($j->childrendre as $j){
          $ob .= '<div class="scategoria chi-'.$j->dre_id.' hidden">';
          $ob .= $j->descricao;
          $ob .= '</div>';
        }
        $ob .= '<div class="tot">';
        $ob .= 'Lucro / Prejuízo Final';
        $ob .= '</div>';
      }
    }

    $ob .= '</div>';
    $ob .= '<div class="col-xs-8 no-padding div-center  scrollbar-inner">';

    for ($i=0; $i < $ano_qtde; $i++) {
      $ob .= '<div class="col-xs-1 no-padding">';
      $ob .= '<div class="metop">';
      $dtf = Carbon::parse($from)->addYear($i)->toDateString();
      $ob .= Carbon::parse($dtf)->formatLocalized("%Y");

      $ob .= '</div>';

      $d = date('Y', strtotime("+".$i." year", strtotime($from)));
      $RBV=0;
      $RLV=0;
      $LB=0;
      $DO=0;
      $ORDNO=0;
      $DIE=0;

      foreach($dres as $ca){
        if($ca->descricao == "Receitas Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $RBV += $it->valor;
                }
              }
            }
          } else {

            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $RBV += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rmcategdiv">'.number_format($RBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Deduções da Receita Brutal"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $RLV += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $RLV += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV + $RLV, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Custos Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $LB += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $LB += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Despesas Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $DO += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $DO += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }

          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB + $DO, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Receitas e Despesas Financeiras"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $ORDNO += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $ORDNO += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }
          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB + $DO + $ORDNO, 2, ',', '.').'</div>';
        } else if($ca->descricao == "Outras Receitas e Despesas Não Operacionais"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $ORDNO += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $ORDNO += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }
        } else if($ca->descricao == "Despesas com Investimentos e Empréstimos"){
          $CRBV=0;
          if($ca->childrendre->count()==0){
            foreach($itens as $it){
              if ($ca->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                  $DIE += $it->valor;
                }
              }
            }
          } else {
            foreach($ca->childrendre as $chsoma){
              foreach($itens as $it){
                if ($chsoma->descricao == $it->descricao ) {
                  if($it->ano == $d){
                    $CRBV += $it->valor;
                    $DIE += $it->valor;
                  }
                }
              }
            }
          }
          $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
          foreach($ca->childrendre as $ch){
            $CRBV=0;
            foreach($itens as $it){
              if ($ch->descricao == $it->descricao ) {
                if($it->ano == $d){
                  $CRBV += $it->valor;
                }
              }
            }
            $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CRBV, 2, ',', '.').'</div>';
          }
          $ob .= '<div class="tot">'.number_format($RBV + $RLV + $LB + $DO + $ORDNO + $DIE, 2, ',', '.').'</div>';
        }
      }
      $ob .= '</div>';
    }
    $ob .= '</div>';


    $ob .= '<div class="col-xs-1 no-padding text-right">';
    $ob .= '<div class="metop">';
    $ob .= 'Total';
    $ob .= '</div>';


    $RBV=0;
    foreach($dres as $ca){
      if($ca->descricao == "Outras Receitas e Despesas Não Operacionais"){
        $CRBV=0;
        if($ca->childrendre->count()==0){
          foreach($itens as $it){
            if ($ca->descricao == $it->descricao ) {
              $CRBV += $it->valor;
              $RBV += $it->valor;
            }
          }
        } else {
          foreach($ca->childrendre as $chsoma){
            foreach($itens as $it){
              if ($chsoma->descricao == $it->descricao ) {
                $CRBV += $it->valor;
                $RBV += $it->valor;
              }
            }
          }
        }
        $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
        foreach($ca->childrendre as $ch){
          $CHRBV=0;
          foreach($itens as $it){
            if ($ch->descricao == $it->descricao ) {
              if($it->ano == $d){
                $CHRBV += $it->valor;
              }
            }
          }
          $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CHRBV, 2, ',', '.').'</div>';
        }
      } else if ($ca->descricao == "Receitas Operacionais"){

        $CRBV=0;
        if($ca->childrendre->count()==0){
          foreach($itens as $it){
            if ($ca->descricao == $it->descricao ) {
              $CRBV += $it->valor;
              $RBV += $it->valor;
            }
          }
        } else {
          foreach($ca->childrendre as $chsoma){
            foreach($itens as $it){
              if ($chsoma->descricao == $it->descricao ) {
                $CRBV += $it->valor;
                $RBV += $it->valor;
              }
            }
          }
        }
        $ob .= '<div class="rel-12 rmcategdiv">'.number_format($CRBV, 2, ',', '.').'</div>';
        foreach($ca->childrendre as $ch){
          $CHRBV=0;
          foreach($itens as $it){
            if ($ch->descricao == $it->descricao ) {
              $CHRBV += $it->valor;
            }
          }
          $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CHRBV, 2, ',', '.').'</div>';
        }
        $ob .= '<div class="tot">'.number_format($RBV, 2, ',', '.').'</div>';
      } else {

        $CRBV=0;
        if($ca->childrendre->count()==0){
          foreach($itens as $it){
            if ($ca->descricao == $it->descricao ) {
              $CRBV += $it->valor;
              $RBV += $it->valor;
            }
          }
        } else {
          foreach($ca->childrendre as $chsoma){
            foreach($itens as $it){
              if ($chsoma->descricao == $it->descricao ) {
                $CRBV += $it->valor;
                $RBV += $it->valor;
              }
            }
          }
        }
        $ob .= '<div class="rel-12rmcategdivred">'.number_format($CRBV, 2, ',', '.').'</div>';
        foreach($ca->childrendre as $ch){
          $CHRBV=0;
          foreach($itens as $it){
            if ($ch->descricao == $it->descricao ) {
              $CHRBV += $it->valor;
            }
          }
          $ob .= '<div class="scategoria chi-'.$ch->dre_id.' hidden">'.number_format($CHRBV, 2, ',', '.').'</div>';
        }
        $ob .= '<div class="tot">'.number_format($RBV, 2, ',', '.').'</div>';
      }
    }

    $ob .= '</div>';


    $item['ob']  = $ob;

    $item['item']  = $itens;


    return $item;
  }
}


