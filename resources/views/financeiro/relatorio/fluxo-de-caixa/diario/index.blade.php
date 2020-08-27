@extends('layouts.admin')

@push('style')
<style type="text/css">

  .grid-table:nth-child(odd) {
    background-color: #fbfbfb;
  }

  .grid-table:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }

  #btn-movimento-tools {
    right: 10px;
    width: 76px;
    height: 50px;
    float: right!important;
  }

  #btn-movimento-tools .active{
    color: #ddbd6f;
  }


  #nav-calendar li {
    display: table-cell;
    width: 14.285%;
    text-align: center;
    padding: 0px 10px;
    vertical-align: middle;
  }

  #nav-calendar > li > a {
    color: #e0e0e0;
  }

  #nav-calendar > li > a > div {
    line-height: 1.1;
  }

  #nav-calendar > li > a > div:nth-child(2) {
    font-weight: 600;
    font-size: 16px;
  }

  #nav-calendar > li.active > a, .mdi-checkbox-marked-outline {
    color: #ddbd6f;
  }
  li.active .nav-calendar-top{
    background-color: #ddbd6f;
  }

  #nav-calendar {
    width: 340px;
    padding: 0;
    margin-bottom: 0;
    margin-top: 5px;
  }

  .nav-calendar-top{
    padding: 3px 10px;
    border-radius: 10px;
    background-color: #eee;
    margin-bottom:5px;
  }

  #grid-table-header {
    border: 1px solid #eeeeee;
    border-bottom: 1px solid #ddbd6f;
  }

  #btn-tools {
    width: 150px;
    margin-top: -46px;
    text-align: right;
    margin-right: 20px;
  }

  .input-group-addon {
    padding: 9px 0px;
    color: #555;
    background-color: #fff;
    border: 1px solid #fff;
    border-radius: 0;
  }

  .grid-table > div {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  canvas{
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
  }

  @media print {



    @page {
      margin: 1cm;
    }

    a[href]:after {
      content: "" !important;
    }

    .col-xs-offset-3 {
      margin: 0;
    }
    #grid-table-body, #grid-table-header {
      width: 100%!important;
      height: 100%!important;
    }

    #canvas {
      width: 200%!important;
      margin-top: 15px;
      margin-bottom: 15px;
    }

    .grid-table > div, #grid-table-header > div{
      padding: 5px 15px!important;
      border-bottom: 1px solid #ccc;
      font-size: 16px;
    }
    #title{
      width: 110%;
    }

    .col-sm-offset-3 {
      margin-left: 0!important;
    }
  }




   #movimento-header{
    border-bottom: 1px solid #ddbd6f;
  }

  .bootstrap-datetimepicker-widget table td span.decade {
    background-color: white;
    width: 20px;
    height: 20px;
    line-height: 20px;
  }

  .bootstrap-datetimepicker-widget table td span {
    background-color: white;
    width: 40px;
    height: 40px;
    line-height: 40px;
  }
  .bootstrap-datetimepicker-widget table td {
    height: 40px;
    line-height: 40px;
    width: 40px;
  }

  .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
   padding: 0px;
  }

  .bootstrap-datetimepicker-widget table td span.active {
    background-color: white;
    color: #333;
    text-shadow:none;
  }

  .bootstrap-datetimepicker-widget .datepicker-decades .decade {
    line-height: 1.3em !important;
    width: 80px;
  }

  #movimento-filtro:before, #movimento-filtro:after {
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-bottom: 7px solid #ddbd6f;
    border-bottom-color: #ddbd6f;
    top: -7px;
    left: auto;
    right: 23px;
    background-color: transparent;
    content: '';
    display: inline-block;
    position: absolute;
  }

  #movimento-filtro{
    padding: 0 15px;
  }

  #movimento-filtro {
    height: 0;
    overflow: hidden;
    position: relative;
    transition: height 0.5s cubic-bezier(0.12, -1.99, 0.0, 1.76);
  }

  #movimento-filtro.active{
    overflow: initial;
    height: 216px;
    transition: height 0.5s cubic-bezier(0.12, -1.99, 0.0, 1.76);
  }

  .ftipo.active, .ftipo:active, .ftipo:active:focus, .ftipo:active.focus, .ftipo.active:hover, .ftipo.active:focus {
    color: #ffffff;
    background-color: #ddbd6f;
    border-color: #ddbd6f;
  }

  #movimento-filtro label {
    display: none;
  }

  #movimento-filtro #intervalo label {
    display: block;
  }

  .movimento-filtro-ativo{
    height: 60px!important;
    padding: 10px 0!important;
    overflow: initial!important;
  }

  .movimento-filtro-ativo h3{
    display: none;
  }

  .movimento-filtro-ativo label{
    display: block!important;;
    z-index: 1;
  }

  .movimento-filtro-ativo .ftipo.active, .movimento-filtro-ativo .ftipo:active, .movimento-filtro-ativo .ftipo:active:focus, .movimento-filtro-ativo .ftipo:active.focus, .movimento-filtro-ativo .ftipo.active:hover, .movimento-filtro-ativo .ftipo.active:focus {
    display: block;
    color: #555;
    background-color: #fff;
    box-shadow: none;
    text-align: left;
    border-color: #ccc;
  }

  .movimento-filtro-ativo .fdatah, .movimento-filtro-ativo .fdatas, .movimento-filtro-ativo .fdatat, .movimento-filtro-ativo .ftipo{
    display: none;
  }

  .movimento-filtro-ativo .fdatah.active, .movimento-filtro-ativo .fdatas.active, .movimento-filtro-ativo .fdatat.active {
    display: block;
    width: 100%;
    text-align: left;
    padding: 9px 12px;
    background-color: #fff;
    box-shadow: none;
    border-color: #ccc;
  }

  .fdatah, .fdatas, .fdatat {
      padding: 9px 12px;
  }

  .btn-block+.btn-block {
    margin-top: 0px;
  }

  #intervalo .form-group {
    margin-top: 0!important;
  }

  #filtro-info {
    font-size: 23px;
    font-weight: 200;
    padding: 4px 5px;
    text-align: center;
    border-left: 1px solid #ddbd6f;
    font-family: 'Roboto', sans-serif;
  }

  .ul-conta {
    border: 1px solid #ccc;
    height: 130px;
    position: relative;
    list-style: none;
    padding: 0;
  }

  .movimento-filtro-ativo .ul-conta {
    height: 40px;
  }

  .ul-conta li {
    line-height: 20px;
  }
  .ul-conta li i {
    margin: 6px 5px 0px 5px;
  }
  .ul-conta li a {
    display: block;
  }
  .ul-conta li a span {
    line-height: 36px;
    position: absolute;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    right: 0;
    left: 33px;
  }



  #div-list{
    overflow: hidden;
    position: relative;
    border: 1px solid #ddbd6f;
    background-color: #fff;
    margin-top: -2px;
  }



    .spotlight-lens {
      height: 38px;
      width: 67px;
      margin-top: 67px;
      left: 108px;
      border-radius: 0;
      -webkit-transition: right 1s, margin-top 1s, border-radius 1s;
      transition: right 1s, margin-top 1s, border-radius 1s;
    }

    .spotlight-lens:before {
      height: 36px;
      width: 65px;
      border-radius: 0px;
      -webkit-transition: height 1s, width 1s, border-radius 1s;
      transition: height 1s, width 1s, border-radius 1s;

    }

    .spotlight-lens:after {
      border-radius: 0px;
      -webkit-transition: border-radius 1s;
      transition: border-radius 1s;
    }

    .spotlight-teaser {
      left: 70px!important;
      text-align: left;
    }
</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 id="title" class="hidden-xs">{{ $title }} <div class="visible-print-inline"></div></h1>
  <div class="input-group pull-right hidden-print" id="btn-tools">
    <div class="input-group-btn">
      @can('fin_movimento_read')
      <a href="{{ route('movimento.index') }}" class="btn btn-default" data-toggle="tooltip" title="Movimentações" data-placement="bottom"><i class="mdi mdi-cash-multiple mdi-20px"></i></a>
      @endcan
      @can('fin_categoria_read')
      <a href="{{ route('fin.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" title="Categorias" data-placement="bottom"><i class="mdi mdi-tag-outline mdi-20px"></i></a>
      @endcan
      @can('fin_conta_read')
      <a href="{{ route('conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas" data-placement="bottom"><i class="mdi mdi-bank mdi-20px" aria-hidden="true"></i></a>
      @endcan
      @can('fin_relatorio_read')
      <a href="{{ route('fin.fdc.mensal') }}" class="btn btn-default" data-toggle="tooltip" title="Relatórios" data-placement="bottom"><i class="mdi mdi-library-books mdi-20px" style="color: #d29c54;"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" >
    <div class="col-sm-12 hidden-print" id="movimento-header">
      <div class="input-group pull-left hidden-print" style="width: 15px">
        <div class="input-group-addon">
          <a href="{{ route('fin.fdc.mensal') }}" id="btn-movimento-Mensal" class="btn btn-default">
            Fluxo de Caixa - Mensal
          </a>
        </div>
        <div class="input-group-addon">
          <a href="{{ route('fin.dre.mensal') }}" class="btn btn-default">
            DRE
          </a>
        </div>
      </div>
      <div class="input-group pull-right hidden-print" style="width: 15px">
        <a href="#" id='btn-movimento-filtro'>
          <span class="input-group-addon">
            <i class="mdi mdi-filter-outline mdi-30px"></i>
          </span>
        </a>
      </div>
      <div class="input-group pull-right" id="btn-movimento-tools">
        <div class="input-group-btn">
          <a href="#" id="btn-grafico" style="margin-right: 5px;"><i class="mdi mdi-trending-up mdi-30px"></i></a>
        </div>
        <div class="input-group-btn">
          <a href="#" id="print"><i class="mdi mdi-printer mdi-30px"></i></a>
        </div>
        <ul id="nav-calendar" class="hidden-xs">
          <li>
            <a href="#" class="prne" rel="-1">
              <div></div>
              <div><</div>
              <div></div>
            </a>
          </li>
          <li><a href="#" class="mes mes-1"></a></li>
          <li><a href="#" class="mes mes-2"></a></li>
          <li class="active"><a href="#" class="mes mes-3" rel="0"></a></li>
          <li><a href="#" class="mes mes-4"></a></li>
          <li><a href="#" class="mes mes-5"></a></li>
          <li>
            <a href="#" class="prne" rel="1">
              <div></div>
              <div>></div>
              <div></div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-xs-12 no-padding hidden-print" id="movimento-filtro">
      <div class="col-xs-2" style="border-left: 1px solid #ddbd6f; height: 100%">
        <h3>Contas</h3>
        <label for="conta">Contas</label>
        <ul class="ul-conta" id="conta">
          <li value="todas"><a href="#"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-24px checkbox checkbox-all"></i><span>Todas</span></a></li>
          @foreach($contas as $i)
          <li><a href="#"><input type="checkbox" name="contas[]" value="{{ $i->id }}" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-24px checkbox checkbox-uni"></i><span>{{ $i->descricao }}</span></a></li>
          @endforeach
        </ul>
      </div>
      <div class="col-xs-3" id="filtro-mes" style="border-left: 1px solid #ddbd6f; height: 100%;">
        <h3>Meses</h3>
        <div id="datetimepicker" style="width: 288px;padding: 11px;border: 1px solid #ccc;"></div>
      </div>
      <div class="col-xs-4 hidden" id="filtro-info" style="border-left: 1px solid #ddbd6f; height: 100%;">
      </div>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
      <div class="col-xs-10 hidden col-xs-offset-1" id="grafico" style="padding-top: 20px!important;">
        <canvas id="canvas"></canvas>
      </div>
      <div class="col-sm-6 col-sm-offset-3 no-padding" id="grid-table-header">
        <div class="col-sm-3">Data</div>
        <div class="col-sm-3 text-right">Recebimentos</div>
        <div class="col-sm-3 text-right">Pagamentos</div>
        <div class="col-sm-3 text-right">Saldo Final</div>
      </div>
      <div id="grid-table-body" class="col-sm-offset-3" style="width: 50%; border: 1px solid #eeeeee;">
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
@endsection

@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/print.css') }}"/>
@endpush

@push('scripts')

<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>


<script type="text/javascript" src="{{ asset('plugins/chart/dist/Chart.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/chart/dist/utils.js') }}"></script>

<script type="text/javascript">
  moment.locale('pt-br');
  var chartData =  {};
  var config = {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      tooltips: {
        mode: 'index',
        intersect: true
      }
    }
  };

  $(document).ready(function() {
    var ctx = $("canvas");
    window.myMixedChart = new Chart(ctx, config);

    liCalendar(0);
    $('#datetimepicker').datetimepicker({
      locale: 'pt-BR',
      format: 'MM-YYYY',
      viewMode: 'months',
      inline: true
    });

    $('#conta').scrollbar({
      "scrollx": "none",
      disableBodyScroll: true,
    });

    $('.mes-3').trigger("click");


    // $('.spotlight-header').html("Fluxo de Caixa Diário");
    // $('.spotlight-text').html('O Fluxo de Caixa Mensal é um relatório que apresenta as entradas e saídas financeiras da empresa, indicando qual o saldo em caixa em um determinado período.<br>Para visualizar o relatório, acesse: Relatórios > Financeiro > Fluxo caixa mensal');
    // $('.spotlight-footer').removeClass('hidden');
    // $('.spotlight-lens').removeClass('hidden');


  });

  //Apresentação
  $(document).on('click', '#btn-apresentacao-next, #btn-apresentacao-prev, #btn-apresentacao-fina', function(e) {
    e.preventDefault();
    var indice;
    var ultima;
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
    if (isset(ultima) && indice < ultima-1 ){
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

  $(document).on('click', '#btn-grafico', function(e){
    $("#grafico, #grid-table-body").toggleClass("hidden");
    $(this).children("i").toggleClass("mdi-view-headline").toggleClass("mdi-trending-up");
  });
  $(document).on('click', '#print', function(e){
    e.preventDefault();
    $("#grid-table-body").animate({ scrollTop: 0 }, 0);
    window.print();
  });
  $(document).on('click', '.checkbox-uni', function(e){
    $(this).parent().children().first().css( "background-color", "red") ;
    if($(this).parent().children().first().is(':checked')){
      $(this).parent().children().first().removeAttr('checked');
      $(this).removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
    } else {
      $(this).parent().children().first().attr("checked", "checked");
      $(this).removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
    }
  });
  $(document).on('click', '.checkbox-all', function(e){
    if($("input:checkbox").is(':checked')){
      $('input:checkbox').removeAttr('checked');
      $(".checkbox").removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
    } else {
      $("input:checkbox").attr("checked", "checked");
      $(".checkbox").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
    }
  });
  $(document).on('click', '#btn-movimento-filtro', function(e){
    e.preventDefault();
    $('#movimento-filtro').toggleClass("active").removeClass("movimento-filtro-ativo");
    $("#filtro-mes").removeClass("hidden");
    $("#conta").val("todas");
    $("#filtro-info").addClass("hidden");
    $("input[type='checkbox']").attr("checked", "checked");
    $(".checkbox").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
  });

  $(document).on('dp.update', '#datetimepicker', function(e){
    if(e.change == "M"){
      var data1 = e.viewDate.format("YYYY-MM");
      data1 = data1.split("-");
      var data2 = moment().format('YYYY-MM')
      data2 = data2.split("-");
      var total = (data1[0] - data2[0])*12 + (data1[1] - data2[1]);
      liCalendar(total);
      $('.mes-3').trigger("click");
    }
  });

  $(document).on('click', '.prne', function(e){
    e.preventDefault();
    var n = parseInt($(this).attr("rel"));
    var a = parseInt($(".mes-3").attr("rel"));
    liCalendar(n+a);

    $('.mes-3').trigger("click");
  });
  $(document).on('click', '.mes', function(e){
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $('#movimento-filtro').removeClass("active");
    $(".mes-3").parent().removeClass("active");
    $("#movimento-filtro").addClass("movimento-filtro-ativo");

    var date = $(".mes-3").attr("date");
    var conta = [];
    $("input[name='contas[]']:checked").each(function (i, item){
        conta[i] = ($(this).val()).toString();
    });

    if($(this).hasClass('mes')){
      $(".mes-3").parent().addClass("active");
      $("#filtro-mes").addClass("hidden");
      $("#movimento-filtro").removeClass("movimento-filtro-ativo");
      date = $(this).attr("date");
      var m = parseInt($(this).attr("rel"));
      liCalendar(m);
    }
    $.ajax({
      url: "{{ route('fin.rel.fdc.d.lista') }}"+"?date="+date+"&conta="+conta,
      type: "GET",
      dataType: "json",
      success: function(data){
        $("#title div").html(" - "+moment(date+"-01").format("MMMM / YYYY"));
        $("#grid-table-body").html(data.ob).animate({scrollTop: 0 }, 200);
        $(".se-pre-con").fadeOut();
        config.data.labels.splice(0, config.data.labels.length);
        config.data.datasets.splice(0, config.data.datasets.length);
        window.myMixedChart.update();
        var datasets = {
          type: 'line',
          lineTension: 0,
          label: "Saldo",
          fill: false,
          backgroundColor: '#395b7b',
          borderColor: '#395b7b',
          data: data.tot,
        };
        config.data.datasets.push(datasets);
        var datasets = {
          type: 'bar',
          label: "Recebimentos",
          backgroundColor: '#2E7D32',
          borderColor: '#2E7D32',
          data: data.dis,
        };
        config.data.datasets.push(datasets);
        var datasets = {
          type: 'bar',
          label: "Pagamentos",
          backgroundColor: '#ff0000',
          borderColor: '#ff0000',
          data: data.rec,
        };
        $.each(data.meses,function(i, item){
          config.data.labels.push(item);
        });
        config.data.datasets.push(datasets);
        window.myMixedChart.update();
      },
      error: function(data){
        console.log(data);
        $("#grid-table-body").html("Algo deu errado!!");
        $(".se-pre-con").fadeOut();
      }
    });
  });

  var liCalendar = function(M) {
    $(".mes-1").attr("rel", M-2).attr("date", moment().add(M-2, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M-2, 'months').format('MMM') + '</div><div>' + moment().add(M-2, 'months').format('YYYY') + '</div>');
    $(".mes-2").attr("rel", M-1).attr("date", moment().add(M-1, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M-1, 'months').format('MMM') + '</div><div>' + moment().add(M-1, 'months').format('YYYY') + '</div>');
    $(".mes-3").attr("rel", M).attr("date", moment().add(M, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M, 'months').format('MMM') + '</div><div>' + moment().add(M, 'months').format('YYYY') + '</div>');
    $(".mes-4").attr("rel", M+1).attr("date", moment().add(M+1, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M+1, 'months').format('MMM') + '</div><div>' + moment().add(M+1, 'months').format('YYYY') + '</div>');
    $(".mes-5").attr("rel", M+2).attr("date", moment().add(M+2, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M+2, 'months').format('MMM') + '</div><div>' + moment().add(M+2, 'months').format('YYYY') + '</div>');
  }
</script>
@endpush
