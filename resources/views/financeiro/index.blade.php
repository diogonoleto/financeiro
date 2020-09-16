@extends('layouts.admin')
@push('style')
<style type="text/css">
.help-icon {
  margin: 5px;
}
#tableDreSummary {
  width: 100%;
  font-size: 0.9em;
}
#tableDreSummary tbody tr:first-child td {
  border: none;
  height: 20px;
}
#tableDreSummary td {
  height: 20px;
  padding: 0px 3px 3px 22px;
  color: #babec5;
}
.panel {
  margin-top: 10px;
}
#tableDreSummary .column-price {
  width: 90px;
  text-align: right;
  font-weight: 600;
  padding: 3px;
  white-space: nowrap;
}
.green {
  color: #2ca01c;
}
.red {
  color: #d52b1e;
}
#tableDreSummary .result td {
  font-weight: 600;
  text-transform: uppercase;
  border-top: 1px solid #eceef1;
  color: #6b6c72;
  padding-top: 5px;
}
.progress-bar-red {
  background: #d52b1e;
}

.h3, h3 {
    font-size: 18px;
}

.panel-body {
  padding: 0px 15px;
  line-height: 1;
}
#panelComparison .progress, #panelComparisonDummy .progress {
  margin: 0;
  height: 25px;
}
.progress {
  height: 18px;
  margin-bottom: 20px;
  overflow: hidden;
  background: #d4d7dc repeat-x;
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
  font-size: 0;
}
#panelComparison .indicator, #panelComparisonDummy .indicator {
  margin: -10px 50% 0;
  height: 10px;
  border-left: 1px solid black;
  opacity: 0.5;
}
#panelComparison .reference, #panelComparisonDummy .reference {
  position: absolute;
  margin-left: 0px;
  left: 50%;
  text-transform: lowercase;
  background: #f4f5f8;
  border: 1px solid #babec5;
  color: #8d9096;
  font-size: 0.9em;
  line-height: 1em;
  padding: 3px;
  border-radius: 0 3px 3px 3px;
  margin-top: -2px;
  text-align: center;
}
#panelComparison li, #panelComparisonDummy li {
  margin: 0 0 12px;
  position: relative;
  color: #6b6c72;
}
#panelComparison .reference:before, #panelComparisonDummy .reference:before {
  top: -7px;
  left: -1px;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
  border-style: solid;
  border-color: transparent transparent transparent #babec5;
  border-width: 7px 0 0 7px;
}
#panelComparison .reference:after, #panelComparisonDummy .reference:after {
  top: -5px;
  left: 0;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
  border-style: solid;
  border-color: transparent transparent transparent #f4f5f8;
  border-width: 5px 0 0 5px;
}

.panel h3 {
  font-weight: 300;
  font-size: 18px;
  margin-top: 10px;
  margin-bottom: 0;
}
[class^="progress-bar-"], [class*=" progress-bar-"] {
  display: inline-block;
  width: 0;
  height: 100%;
  font-size: 12px;
  font-weight: 500;
  line-height: 18px;
  color: #fff;
  overflow: hidden;
  text-align: center;
  vertical-align: top;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: width 0.5s ease;
  -moz-transition: width 0.5s ease;
  -o-transition: width 0.5s ease;
  transition: width 0.5s ease;
}
.progress-bar-yellow {
  background: #fb0;
}
[class^="progress-bar-"], [class*=" progress-bar-"] {
  display: inline-block;
  width: 0;
  height: 100%;
  font-size: 12px;
  font-weight: 500;
  line-height: 18px;
  color: #fff;
  overflow: hidden;
  text-align: center;
  vertical-align: top;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: width 0.5s ease;
  -moz-transition: width 0.5s ease;
  -o-transition: width 0.5s ease;
  transition: width 0.5s ease;
}
.panel span.detail {
  margin: 0;
  padding: 0 15px 5px;
  display: block;
  font-size: 1em;
  font-style: italic;
  font-weight: normal;
  color: #8d9096;
}
#panelComparison, #panelComparisonDummy {
  margin: 25px 0 25px;
  padding: 0;
  list-style: none;
  font-size: 0.95em;
}
#panelComparison li, #panelComparisonDummy li {
  margin: 20px 0 20px;
  position: relative;
  color: #6b6c72;
}
#panelComparison li p, #panelComparisonDummy li p {
  margin: 0 0 4px;
}
#panelComparison li.detail, #panelComparisonDummy li.detail {
  padding-left: 20px;
}
#overviewIncomes, #overviewExpenses {
  margin: 0;
  padding: 0;
  list-style: none;
  color: #babec5;
  font-size: 14px;
}
#overviewIncomes span, #overviewExpenses span {
  font-weight: 600;
  color: #6b6c72;
}

.content{
  overflow-y: hidden;
  position: relative;
}

#calendarBox {
  font-size: 0;
}
#calendarBoxMonth {
  text-transform: uppercase;
  text-align: center;
  font-weight: bold;
  color: #8d9096;
  font-size: 12px;
}
#calendarBoxWeekdays {
  margin: 0;
  padding: 0;
  list-style: none;
  text-align: center;
  text-transform: uppercase;
  color: #8d9096;
}
#calendarBoxWeekdays .weekday {
  display: inline-block;
  font-size: 10px;
  width: 14%;
}
#calendarBoxWeekdays .weekday:first-child {
  margin-left: 1%;
}
#calendarBoxWeekdays .weekday:last-child {
  margin-right: 1%;
}
#calendarBoxDays button[data-weekday="4"][data-begin-month="begin"] {
  margin-left: 57%;
}
#calendarBoxDays button {
  display: inline-block;
  width: 14%;
  height: 30px;
  background: none;
  color: #8d9096;
  border: none;
  font-size: 11px;
  position: relative;
  border-radius: 4px;
  outline: none;
  cursor: pointer;
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  -ms-transition: background-color 0.2s;
  -o-transition: background-color 0.2s;
  transition: background-color 0.2s;
}
#calendarBoxDays button {
  display: inline-block;
  width: 14%;
  height: 30px;
  background: none;
  color: #8d9096;
  border: none;
  font-size: 11px;
  position: relative;
  border-radius: 4px;
  outline: none;
  cursor: pointer;
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  -ms-transition: background-color 0.2s;
  -o-transition: background-color 0.2s;
  transition: background-color 0.2s;
}
#calendarBoxDays button[data-weekday="0"], #calendarBoxDays button[data-weekday="6"] {
  color: #babec5;
}
#calendarBoxDays button.active {
  background: #53b700;
  color: #fff;
  font-weight: 600;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
#calendarBoxDays button:hover {
  background: #d4d7dc;
  color: #6b6c72;
}
#calendarBoxDays button .flagBox {
  position: absolute;
  bottom: 0;
  right: 0;
  left: 0;
  line-height: 8px;
  display: block;
  width: 100%;
  text-align: center;
}
#calendarBoxDays button .flagBox span {
  display: inline-block;
  width: 4px;
  height: 4px;
  margin: 0 1px 0 0;
  border-radius: 3px;
  border: 1px solid #f4f5f8;
}
#calendarBoxDays button .flagBox span.outcome {
  background-color: #d52b1e;
}
#calendarBoxLegend {
  text-align: center;
  color: #8d9096;
  font-size: 0.8em;
  margin: 0;
}
#calendarBoxLegend span {
  margin-left: 18px;
  position: relative;
}
#calendarBoxLegend span:before {
  content: '';
  display: block;
  position: absolute;
  width: 4px;
  height: 4px;
  border-radius: 2px;
  top: 5px;
  left: -8px;
  background: #8d9096;
}
#calendarBoxLegend span.in:before {
  background: #53b700;
}
#calendarBoxLegend span.ex:before {
  background: #d52b1e;
}
#calendarBoxLegend span.bi:before {
  background: #ff8000;
}

#companyBalanceCurrent:hover, #companyBalanceCurrent.active {
  background: #eceef1;
  border-color: #d4d7dc;
  color: #8d9096;
}
#companyBalanceCurrent {
  background: none;
  border: none;
  border-radius: 2px;
  display: block;
  width: 100%;
  padding: 5px 10px;
  color: #8d9096;
  text-decoration: none;
  font-size: 1em;
  margin: -10px 0 0;
  outline: none;
  cursor: pointer;
}
#companyBalanceCurrent dl dt {
  font-weight: normal;
  line-height: 1.5em;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
#companyBalanceCurrent dl {
  margin: 5px 0 0 22px;
  text-align: left;
}
#companyBalanceCurrent dl dd {
  font-family: "Geogrotesque","Calibri","Trebuchet MS",sans-serif;
  margin: 0;
  font-size: 22px;
  font-weight: 300;
  line-height: 1.6em;
}
#companyBalance {
  position: relative;
  margin: 0 0 10px auto;
}
#companyBalanceCurrent .caret {
  margin-top: 12px;
  float: left;
  border-color: #393a3d transparent;
}
.caret {
  display: inline-block;
  width: 0;
  margin-left: 4px;
  margin-right: -5px;
  margin-bottom: 1px;
  vertical-align: middle;
  border-top: 5px dashed;
  border-top: 5px solid \9;
  border-right: 5px solid transparent;
  border-left: 5px solid transparent;
}
ul.timeline {
  position: relative;
  list-style: none;
  margin: 15px 15px 15px;
  padding: 15px 0 0 15px;
  border-left: 1px dotted #6b6c72;
}
ul.timeline:before {
  content: '';
  width: 7px;
  height: 7px;
  background: #6b6c72;
  position: absolute;
  top: -4px;
  left: -4px;
  border-radius: 4px;
}
ul.timeline li {
  margin: 0 0 10px;
  position: relative;
  line-height: 1.4em;
}
ul.timeline li:before {
  content: '';
  width: 5px;
  height: 5px;
  background: #6b6c72;
  position: absolute;
  top: 6px;
  left: -18px;
  border-radius: 4px;
}
ul.timeline li:after {
  content: '';
  width: 12px;
  height: 2px;
  border-top: 1px dotted #6b6c72;
  position: absolute;
  top: 8px;
  left: -15px;
  border-radius: 4px;
}


ul.timeline li.Receita:before {
  background-color: #ddbd6f;
}

ul.timeline li.Despesa:before {
  background-color: #d52b1e;
}

ul.timeline li p {
  margin: 0;
}
#btn-search{
  margin-right: 5px;
}
.dial{
  border: 0px!important;
  color:#fff;
}
h4 {
  font-family: 'Roboto', sans-serif;
  font-weight: 100;
  font-size: 18px;
  margin-top: 0px;
  margin-bottom: 5px;
}
hr {
  margin-top: 0px;
  margin-bottom: 10px;
}
h3 {
  font-weight: 300;
  margin-right: 10px;
  margin-top: -10px;
  margin-bottom: 0px;
  padding-bottom: 0px;
}
canvas {
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}

#agenda-hist {
  overflow: hidden;
  position: relative;
  height: 385px;
}

.recebida {
  color: green;
}

.areceber {
  color: #22307d;
}

.display-total {
  font-family: 'Roboto', sans-serif;
}
.display-total > span:nth-child(1){
  font-weight: 100;
  font-size: 15px;
  display: block;
  margin-right: 3px;
  margin-bottom: -5px;
  width: 100%;
}

.display-total > span:nth-child(2){
  vertical-align: -webkit-baseline-middle;
  font-size: 10px;
  display: inline-flex;
}

.display-total > span:nth-child(3){
  font-size: 20px;
  float: right;
  font-weight: 300;
}
</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="hidden-xs">{{ $title }}</h1>
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      @can('fin_movimento_read')
      <a href="{{ route('movimento.index') }}" class="btn btn-default" data-toggle="tooltip" title="Movimentações" data-placement="bottom"><i class="mdi mdi-cash-multiple mdi-20px"></i></a>
      @endcan
      @can('fin_categoria_read')
      <a href="{{ route('fin.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" title="Categorias" data-placement="bottom"><i class="mdi mdi-tag-outline mdi-20px"></i></a>
      @endcan
      @can('fin_conta_read')
      <a href="{{ route('conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas Bancárias" data-placement="bottom"><i class="mdi mdi-bank mdi-20px"></i></a>
      @endcan
      @can('fin_relatorio_read')
      <a href="{{ route('fin.fdc.mensal') }}" class="btn btn-default" data-toggle="tooltip" title="Relatórios" data-placement="bottom"><i class="mdi mdi-library-books mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content scrollbar-inner">
  @can('fin_conta_read')
  <div class="row" style="margin: 5px 10px 0">
    <div class="col-xs-12 col-sm-12" style="border-bottom: 1px solid #ddbd6f; position:relative;">
      <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Aqui você pode alternar entre as contas que você está gerenciando e cadastrar novas contas (conta corrente, dinheiro, cartão de crédito, poupança, investimentos etc)." data-original-title="Contas"></i>
      <h4>Contas</h4>
      @php($ctot = 0)
      @forelse($contas as $c)
      @php($ctot += $c->valor)
      <div class="col-xs-12 col-sm-6 col-md-2 no-padding" style="margin-bottom: 5px; {{ $c->padrao == 1 ? 'border-left: 2px solid #ddbd6f' : null }}">
        <div class="col-xs-9 no-padding" style="border-left: 2px solid #eee;">
          <p style="margin-left: 10px; margin-right: 10px; text-align: right; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ isset($c->descricao) ? $c->descricao : 0 }} - Saldo</p>
          <h3 class="text-right {{ $c->valor < 0 ? 'red' : 'green'}}"><span style="vertical-align: super; font-size: 11px; display: inline-flex;">R$</span>{{ isset($c->valor) ? number_format($c->valor, 2, ',', '.') : '0,00' }}</h3>
        </div>
        <div class="col-xs-3 dimgc">
          <img src="{{ url($c->img) }}"  class="img-responsive img-thumbnail" style="{{ $c->padrao == 1 ? 'border: 2px solid #ddbd6f' : null }}">
        </div>
      </div>
      @empty
      <div class="col-xs-12 text-center">
        @can('fin_conta_read')
        <a href="{{ route('conta.index') }}" style="margin-bottom: 15px;"  class="btn btn-default"><i class="mdi mdi-bank mdi-20px"></i> Adicionar contas bancárias</a>
        @endcan
      </div>
      @endforelse
      <div class="col-xs-12">
        <div class="pull-right display-total">
          <span>Saldo total</span>
          <span>R$</span>
          <span class="green">{{ number_format($ctot, 2, ',', '.') }}</span>
        </div>
      </div>
    </div>
  </div>
  @endcan
  @can('financeiro_read')
  <div class="row" style="margin: 5px 10px 0">
    <div class="col-xs-12 col-sm-12 col-md-9 no-padding-left">
      <div class="col-sm-6 xs-no-padding">
        <div class="panel panel-default" style="height: 270px;">
          <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Aqui você pode ver o quanto você já pagou e recebeu neste mês, e o quanto ainda falta a pagar e receber." data-original-title="Previsto / Realizado"></i>
          <div class="panel-body">
            <h3>Previsto / Realizado</h3>
            <span class="detail account-name-detail">Mes Atual</span>
            <div class="row">
              <div class="col-sm-6 col-sm-6 col-xs-6 no-padding">
                <div class="col-sm-12 text-center" style="height: 130px;">
                  <input type="text" value="{{ $matu->RPrevisto != 0 ? number_format((($matu->RRealizado*100)/$matu->RPrevisto),2) : 0 }}" class="dial" readonly data-fgColor="#ddbd6f" data-width="100" data-thickness=".2" >
                </div>
                <div class="col-sm-12">
                  <h3>Recebimentos</h3>
                  <ul id="overviewIncomes">
                    <li>Realizado: <span id="spIncome" class="green pull-right sp_total_incoming_current_pano">{{ isset($matu->RRealizado) ? number_format($matu->RRealizado, 2, ',', '.') : '0,00' }}</span></li>
                    <li>Falta: <span id="spIncomeLeft" class="pull-right sp_total_incoming_current_left">{{ isset($matu->RFalta) ? number_format($matu->RFalta, 2, ',', '.') : '0,00' }}</span></li>
                    <li class="result">Previsto: <span id="spIncomePredict" class="pull-right sp_total_incoming_current_future">{{ isset($matu->RPrevisto) ? number_format($matu->RPrevisto, 2, ',', '.') : '0,00' }}</span></li>
                  </ul>
                </div>
              </div>
              <div class="col-sm-6 col-sm-6 col-xs-6 no-padding">
                <div class="col-sm-12 text-center" style="height: 130px;">
                  <input type="text" value="{{ $matu->DPrevisto != 0 ?number_format((($matu->DRealizado*100)/$matu->DPrevisto),2) : 0 }}" class="dial" readonly data-fgColor="#ddbd6f" data-width="100" data-thickness=".2" >
                </div>
                <div class="col-sm-12">
                  <h3>Despesas</h3>
                  <ul id="overviewExpenses">
                    <li>Realizado: <span id="spIncome" class="red pull-right sp_total_expenses_current_pano">{{ isset($matu->DRealizado) ? number_format($matu->DRealizado, 2, ',', '.') : '0,00' }}</span></li>
                    <li>Falta: <span id="spIncomeLeft" class="pull-right sp_total_expenses_current_left">{{ isset($matu->DFalta) ? number_format($matu->DFalta, 2, ',', '.') : '0,00' }}</span></li>
                    <li class="result">Previsto: <span id="spIncomePredict" class="pull-right sp_total_expenses_current_future">{{ isset($matu->DPrevisto) ? number_format($matu->DPrevisto, 2, ',', '.') : '0,00' }}</span></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 no-padding">
        <div class="panel panel-default" style="height: 270px;">
          <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Aqui você pode acompanhar a evolução do seu faturamento, despesas e saldo nos últimos seis meses." data-original-title="Histórico de fluxo de caixa"></i>
          <div class="panel-body">
            <h3>Fluxo de caixa do semestre</h3>
            <span class="detail account-name-detail">Semestre Atual</span>
            <div class="col-xs-12">
              <canvas id="canvas" ></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 xs-no-padding">
        <div class="panel panel-default" style="height: 245px;">
          <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Aqui você pode ver o quanto suas despesas e receitas aumentaram ou diminuíram em relação ao mês passado." data-original-title="Comparativo com o mês passado"></i>
          <div class="panel-body">
            <h3>Comparativo mês a mês</h3>
            <span class="detail account-name-detail">Todas as Contas</span>
            <ul id="panelComparison">
              <li class="incomes">
                <p>
                  <span class="uppercase">Recebimentos</span>
                  <span class="pull-right">

                    @if($mant->ARPrevisto > $matu->RPrevisto)
                    {{ $mant->ARPrevisto == 0 ? '100%' : number_format((($matu->RPrevisto*100)/$mant->ARPrevisto) - 100, 2) }}% (R$ {{ number_format($matu->RPrevisto-$mant->ARPrevisto,2,',','.')  }})
                    <i class="mdi mdi-arrow-down-bold red"></i>
                    @elseif($mant->ARPrevisto < $matu->RPrevisto)
                    +{{ $mant->ARPrevisto == 0 ? '100%' : number_format((($matu->RPrevisto*100)/$mant->ARPrevisto) - 100, 2) }}% (R$ {{ number_format($matu->RPrevisto-$mant->ARPrevisto,2,',','.')  }})
                    <i class="mdi mdi-arrow-up-bold green"></i>
                    @elseif($mant->ARPrevisto - $matu->RPrevisto == '0')
                    Sem alteração <i class="mdi mdi-arrow-left"></i>
                    @endif
                  </span>
                </p>
                <div class="progress">
                  @if($mant->ARPrevisto > $matu->RPrevisto)
                  <div class="progress-bar progress-bar-success" style="width:{{ $mant->ARPrevisto == 0 ? '100' : (50 -(100 - number_format( (($matu->RPrevisto*100)/$mant->ARPrevisto), 2))*0.5) }}%;"></div>
                  @elseif($mant->ARPrevisto < $matu->RPrevisto)
                  {{ (50 +(number_format( (($matu->RPrevisto*100)/$mant->ARPrevisto) - 100, 2))*0.5) }}
                  <div class="progress-bar progress-bar-success" style="width:{{ $mant->ARPrevisto == 0 ? '100' : '' }}%;"></div>
                  @else
                  <div class="progress-bar progress-bar-success" style="width:0;"></div>
                  @endif
                </div>
                <div class="indicator"></div>
              </li>
              <li class="expenses">
                <p>
                  <span class="uppercase">Despesas</span>
                  <span class="pull-right">
                    @if($mant->ADPrevisto > $matu->DPrevisto)
                    {{ $mant->ADPrevisto == 0 ? '100%' : number_format( (($matu->DPrevisto*100)/$mant->ADPrevisto) - 100, 2) }}% (R$ {{ number_format($matu->DPrevisto-$mant->ADPrevisto,2,',','.')  }})
                    <i class="mdi mdi-arrow-down-bold red"></i>
                    @elseif($mant->ADPrevisto < $matu->DPrevisto)
                    +{{ $mant->ADPrevisto == 0 ? '100%' : number_format((($matu->DPrevisto*100)/$mant->ADPrevisto) - 100, 2) }}% (R$ {{ number_format($matu->DPrevisto-$mant->ADPrevisto,2,',','.')  }})
                    <i class="mdi mdi-arrow-up-bold green"></i>
                    @elseif($mant->ADPrevisto - $matu->DPrevisto == '0')
                    Sem alteração <i class="mdi mdi-arrow-left"></i>
                    @endif
                  </span>
                </p>
                <div class="progress">

                </div>
                <div class="indicator"></div>
              </li>
              <li class="reference">Mês anterior</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 no-padding-left">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Aqui você pode ver em quais dias do mês há movimentações programadas, além de checar movimentações atrasadas. Clique em qualquer dia para ver as movimentações agendadas." data-original-title="Agenda do dia"></i>
        <div class="panel-body" id="fin-agenda">
          <h3>Agenda</h3>
          <div class="col-sm-12 no-padding">
            <div class="col-sm-12 no-padding" style="margin-bottom: 15px">
              <div id="datetimepicker12"></div>
            </div>
          </div>
          <div class="col-sm-12 no-padding scrollbar-inner" id="agenda-hist">
          </div>
          <form id="form-moviento-edit" action="{{ route('financeiro.movimento.agemov') }}" class="hidden" method="POST">
            <input type="text" name="movimento_id" id="movimento_id">
            {{ method_field('put') }}
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
  @endcan
</section>
<!-- /.content -->

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('plugins/jquery-knob/jquery.knob.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/chart/dist/Chart.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/chart/dist/utils.js') }}"></script>
<script type="text/javascript">

  moment.locale('pt-br');
  var mes = [];
  @foreach($semMeses as $k => $m)
  mes[{{ $k }}] = '{{ $m }}';
  @endforeach

  var semRReal = [];
  @foreach($semRReal as $k => $s)
  semRReal[{{ $k }}] = '{{ $s }}';
  @endforeach

  var semRPrev = [];
  @foreach($semRPrev as $k => $s)
  semRPrev[{{ $k }}] = '{{ $s }}';
  @endforeach

  var semDReal = [];
  @foreach($semDReal as $k => $s)
  semDReal[{{ $k }}] = '{{ $s }}';
  @endforeach

  var semDPrev = [];
  @foreach($semDPrev as $k => $s)
  semDPrev[{{ $k }}] = '{{ $s }}';
  @endforeach

  var semRtotal = [];
  @foreach($semRtotal as $k => $s)
  semRtotal[{{ $k }}] = '{{ $s }}';
  @endforeach

  var semPtotal = [];
  @foreach($semPtotal as $k => $s)
  semPtotal[{{ $k }}] = '{{ $s }}';
  @endforeach
  var chartData = {
    labels: mes,
    datasets: [
    {
      type: 'bar',
      label: "Rec. realizados",
      backgroundColor: '#2E7D32',
      borderColor: '#2E7D32',
      data: semRReal ,
    },{
      type: 'bar',
      label: "Rec. previstos",
      backgroundColor: 'rgb(153, 237, 139)',
      borderColor: 'rgb(153, 237, 139)',
      data: semRPrev ,
    },{
      type: 'bar',
      label: "Pag. realizados",
      backgroundColor: 'rgb(181, 72, 72)',
      borderColor: 'rgb(181, 72, 72)',
      data: semDReal,
    },{
      type: 'bar',
      label: "Pag. previstos",
      backgroundColor: 'rgb(251, 169, 168)',
      borderColor: 'rgb(251, 169, 168)',
      data: semDPrev,
    },{
      type: 'line',
      lineTension: 0,
      label: "Saldo realizado",
      fill: false,
      backgroundColor: '#395b7b',
      borderColor: '#395b7b',
      data: semRtotal,
    },{
      type: 'line',
      lineTension: 0,
      label: "Saldo previsto",
      fill: false,
      backgroundColor: 'rgb(160, 179, 195)',
      borderColor: 'rgb(160, 179, 195)',
      data: semPtotal,
    }
    ]
  };
  var config = {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      tooltips: {
        mode: 'index',
        intersect: true
      },
      responsive: true,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: false
        }]
      },
      legend: {
        position: 'top',
        labels: {
          boxWidth: 10,
          padding: 5,
        }
      }
    }
  };
  $(document).ready(function() {
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myMixedChart = new Chart(ctx, config);
    $('[data-toggle="popover"]').popover();
    $(".dial").knob({
      'format' : function (value) {
       return value + '%';
     },
     'draw': function() {
      $(this.i).css('font-size', '10pt');
    }
  });
    $('#datetimepicker12').datetimepicker({
      locale: 'pt-BR',
      format: 'DD/MM/YYYY',
      inline: true,
    });
    $(".datepicker-days table tbody tr").addClass("hidden");
    $(".today").parent().removeClass("hidden");
    $(".dial").each(function(i, item){
      var initval = $(this).val();
      $({value: 0}).animate({value: initval}, {
        duration: 2000,
        easing:'swing',
        step: function() {
          $(item).val(this.value).trigger('change');
        }
      });
    });
    @if(Auth()->user()->apresentacao == 1)
    $.ajax({
      url: "{{ route('adminApr.apresentacao.apresentar') }}",
      type: "GET",
      success: function(data){
        vdata = data;
        var indice = vdata.indexOf(vdata[0]);
        var ultima = vdata.length;
        if (indice > 0 ){
          $('.spotlight-lens').attr("data-prev",indice-1);
          $('#btn-apresentacao-prev').removeAttr("disabled");
        } else {
          $('.spotlight-lens').attr("data-prev", indice);
          $('#btn-apresentacao-prev').attr("disabled","disabled");
        }
        if (indice < ultima ){
          $('.spotlight-lens').attr("data-next", indice+1);
          $('#btn-apresentacao-next').removeAttr("disabled");
        } else {
          $('.spotlight-lens').attr("data-next", indice);
          $('#btn-apresentacao-next').attr("disabled","disabled");
        }
        $('.spotlight-header').html(vdata[0].titulo);
        $('.spotlight-text').html(vdata[0].descricao);
        $('.spotlight-footer').removeClass('hidden');
        $('.spotlight-lens').removeClass('hidden');
        $(".se-pre-con").fadeOut();
      }
    });
    @endif

    var h =  $("body").innerHeight();
        h -= $(".content-header").outerHeight(true);
        h -= $("#btn-movimento-tools").length ? $("#btn-movimento-tools").outerHeight(true) : 0;

    $('.content').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": h, "width": "100%"});

  });
  $(document).on('click', '.btn-movimento-edit', function(e){
    e.preventDefault();
    $("#movimento_id").val($(this).attr("rel"));
    $("#form-moviento-edit").submit();
  });
  $(document).on('dp.change', '#datetimepicker12', function(e){
    var date = e.date.format("YYYY-MM-DD");
    $(".datepicker-days table tbody tr").addClass("hidden");
    $('.day.active').parent().removeClass("hidden");
    $.ajax({
      url: "{{ route('financeiro.agenda') }}?date="+date,
      type: "GET",
      success: function(data){
        $("#agenda-hist").html(data);

        $('#agenda-hist').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": "385px", "width": "100%"});
      },
      error: function(data){
        $("#agenda-hist").html("Algo deu errado!!");
      }
    });
  });
  $(document).on('click', '#btn-apresentacao-next, #btn-apresentacao-prev, #btn-apresentacao-fina', function(e) {
    e.preventDefault();
    var indice;
    var ultima = vdata.length;
    if(e.target.id == "btn-apresentacao-next"){
      indice = parseInt($(".spotlight-lens").attr("data-next"));
    } else if(e.target.id == "btn-apresentacao-prev"){
      indice = parseInt($(".spotlight-lens").attr("data-prev"));
    } else {
      $(".spotlight-lens").addClass("hidden");
      if( $("input#apresentacao").is(':checked') ){
        $.ajax({
          url: "{{ route('usuario.apresentacao') }}",
          type: "GET",
          success: function(data){
            console.log(data);
          },
          error: function(data){
            console.log(data);
          }
        });
      }
      return false;
    }
    if (indice > 0 ){
      $('.spotlight-lens').attr("data-prev",indice-1);
      $('#btn-apresentacao-prev').removeAttr("disabled");
    } else {
      $('.spotlight-lens').attr("data-prev", indice);
      $('#btn-apresentacao-prev').attr("disabled","disabled");
    }
    if (indice < ultima-1 ){
      $('.spotlight-lens').attr("data-next", indice+1);
      $('#btn-apresentacao-next').removeAttr("disabled");
    } else {
      $('.spotlight-lens').attr("data-next", indice);
      $('#btn-apresentacao-next').attr("disabled","disabled");
    }
    $('#apresentacao').html(vdata[indice].style);
    $('.spotlight-teaser').fadeOut(100).addClass('spotlight-teaser-mov').delay(1000).fadeIn();
    $('.spotlight-header').html(vdata[indice].titulo);
    $('.spotlight-text').html(vdata[indice].descricao);
    $('.spotlight-footer').removeClass('hidden');
  });

</script>
@endpush


