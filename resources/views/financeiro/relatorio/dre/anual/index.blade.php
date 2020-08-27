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
  line-height: 1.3;
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
  margin-top: 10px;
}

.nav-calendar-top{
  padding: 3px 10px;
  border-radius: 10px;
  background-color: #eee;
  margin-bottom:5px;
}

#btn-tools {
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

#div-list{
  overflow: hidden;
  position: relative;
  border: 1px solid #ddbd6f;
  background-color: #fff;
  margin-top: -2px;
}

canvas{
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}

.subcategoria{
  background-color: #fff;
  border: 0;
  padding: 7px 10px;
  border-bottom: 1px solid #ddbd6f;
  border-right: 1px solid #ddbd6f;
}

.col-xs-3 .subcategoria {
  padding-left: 35px!important;
}
.rmcateg, .rmcategdiv {
  background-color: #e3d8bd1c;
  font-weight: 500;
  padding: 7px 5px 7px 12px;
  border-bottom: 1px solid #ddbd6f;
  border-right: 1px solid #ddbd6f;
}

.col-xs-1 .rmcateg,.col-xs-1 .rmcategdiv, .col-xs-1 .tot, .col-xs-1 .subcategoria  {
  text-align: right;
}

.div-left {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.tot {
  background-color: #f2e2b9;
  font-weight: 600;
  padding: 10px 5px 10px 12px;
  border-bottom: 1px solid #ddbd6f;
  border-right: 1px solid #ddbd6f;
}
.metop {
  background-color: #ddbd6f40;
  text-align: center;
  font-weight: 600;
  padding: 10px 5px 10px 12px!important;
  border-top: 1px solid #ddbd6f;
  border-bottom: 1px solid #ddbd6f;
  border-right: 1px solid #ddbd6f;
  text-transform: uppercase;
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

.lancamento.active, .lancamento:active, .lancamento:active:focus, .lancamento:active.focus, .lancamento.active:hover, .lancamento.active:focus, .ftipo.active, .ftipo:active, .ftipo:active:focus, .ftipo:active.focus, .ftipo.active:hover, .ftipo.active:focus {
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

.movimento-filtro-ativo .lancamento.active, .movimento-filtro-ativo .lancamento:active, .movimento-filtro-ativo .lancamento:active:focus, .movimento-filtro-ativo .lancamento:active.focus, .movimento-filtro-ativo .lancamento.active:hover, .movimento-filtro-ativo .lancamento.active:focus {
  display: block;
  color: #555;
  background-color: #fff;
  box-shadow: none;
  text-align: left;
  border-color: #ccc;
}

.movimento-filtro-ativo .lancamento{
  display: none;
}

.btn-block+.btn-block {
  margin-top: 0px;
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

.red{
  color: red;
}

#grid-table-body{
  width: 100%;
  overflow-x: hidden;
  font-family: 'Roboto', sans-serif;
  font-weight: 400;
}

#grid-table-header div {
  margin-right: 0px;
  border-left: 0;
}


.text-right {
  text-align: right!important;
}

@media print {
  #grid-table-header{
    height: 100%;
  }
  #grid-table-body {
    height:100%!important;
    width: 100%!important;
  }
  @page {
    margin: 0.5cm;
    size: auto;
  }
  a[href]:after {
    content: "" !important;
  }
  .main {
    width: 100%;
    margin-left: 0px;
    margin-top: -1px;
    height: 100%;
  }

  #div-list {
    border: 0;
    height:100%!important;
  }
  .content-header h1 {
    padding-right: 0;
  }
  .div-left .subcategoria {
    padding-left: 15px!important;
  }
}
</style>

@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="hidden-xs">{{ $title }}</h1>
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
    <div class="col-xs-12" id="movimento-header">
      <div class="input-group pull-left hidden-print" style="width: 15px">
        <div class="input-group-addon">
          <a href="{{ route('fin.fdc.mensal') }}" id="btn-movimento-Mensal" class="btn btn-default">
            Fluxo de Caixa - Mensal
          </a>
        </div>
        <div class="input-group-addon">
          <a href="{{ route('fin.fdc.diario') }}" id="btn-movimento-diario" class="btn btn-default">
            Fluxo de Caixa - Anual
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
      <div class="input-group pull-right hidden-print" id="btn-movimento-tools">
        <div class="input-group-btn">
          <a href="#" id="btn-print" style="margin-right: 5px;"><i class="mdi mdi-printer mdi-30px"></i></a>
        </div>
        <ul id="nav-calendar">
          <li>
            <a href="#" class="prne" rel="-1">
              <div></div>
              <div><</div>
              <div></div>
            </a>
          </li>
          <li><a href="#" class="ano ano-1"></a></li>
          <li><a href="#" class="ano ano-2"></a></li>
          <li class="active"><a href="#" class="ano ano-3" rel="0"></a></li>
          <li><a href="#" class="ano ano-4"></a></li>
          <li><a href="#" class="ano ano-5"></a></li>
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
        <!--       <div class="col-xs-3" style="width: 14%;">
        <h3 class="text-center">Lançamentos</h3>
        <label for="lancamento">Lançamentos</label>
        <div>
          <a href="#" rel="todos" class="btn btn-default btn-block lancamento active" style="padding: 9px 12px; margin-bottom: 5px;">Previsto e Realizado</a>
          <a href="#" rel="pagos" class="btn btn-default btn-block lancamento" style="padding: 9px 12px; margin-bottom: 5px;">Previsto</a>
          <a href="#" rel="npagos" class="btn btn-default btn-block lancamento" style="padding: 9px 12px; margin-bottom: 5px;">Realizado</a>
        </div>
      </div> -->
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
      <div class="col-xs-3" id="filtro-ano" style="border-left: 1px solid #ddbd6f; height: 100%;">
        <h3>Ano</h3>
        <div id="datetimepicker" style="width: 288px;padding: 11px;border: 1px solid #ccc;"></div>
      </div>
      <div class="col-xs-4 hidden" id="filtro-info" style="border-left: 1px solid #ddbd6f; height: 100%;">
      </div>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="div-list" >
      <div id="grid-table-header">
      </div>
      <div id="grid-table-body" class="scrollbar-inner">
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
  var MchartData =  {};
  var Mconfig = {
    type: 'bar',
    data: MchartData,
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
      }
    }
  };

  $(document).ready(function() {

    liCalendar(0);
    $('#datetimepicker').datetimepicker({
      locale: 'pt-BR',
      format: 'YYYY',
      viewMode: 'years',
      showClear: false,
      inline: true,
    });
    $('#datetimepicker').on('dp.change', function(e){
      console.log(e.date._d);
    });

    var h =  $("body").outerHeight();
    h -= $(".content-header").innerHeight();
    h -= $("#movimento-header").innerHeight();
    h -= $("#grid-table-header").innerHeight();
    $("#div-list").css("height", h);

    $('.ano-3').trigger("click");



    $("#conta").scrollbar({
      "scrollx": "none",
      disableBodyScroll: true,
    });


  });

  $(document).on('click', '#btn-print', function(e){
    e.preventDefault();
    $("#grid-table-body").animate({ scrollTop: 0 }, 0);
    window.print();
  });

  $(document).on('click', '#btn-grafico', function(e){
    $("#grafico, #grid-table-body").toggleClass("hidden");
    $(this).children("i").toggleClass("mdi-view-headline").toggleClass("mdi-trending-up");
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
    $("#filtro-ano").removeClass("hidden");
    $("#conta").val("todas");
    $(".lancamento").removeClass("active").removeClass("hidden");
    $(".lancamento:first-child").addClass("active");
    $("#filtro-info").addClass("hidden");
    $("input[type='checkbox']").attr("checked", "checked");
    $(".checkbox").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
  });
  $(document).on('click', '.lancamento', function(e){
    e.preventDefault();
    $('.lancamento').removeClass('active');
    $(this).addClass('active');
  });

  $(document).on('click', '.ano', function(e){
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $('#movimento-filtro').removeClass("active");
    $(".ano-3").parent().removeClass("active");
    $("#movimento-filtro").addClass("movimento-filtro-ativo");

    var date = $(".ano-3").attr("date");
    var lancamento = $("a.lancamento.active").attr("rel");
    var conta = [];
    $("input[name='contas[]']:checked").each(function (i, item){
      conta[i] = ($(this).val()).toString();
    });

    if($(this).hasClass('ano')){
      $(".ano-3").parent().addClass("active");
      $("#filtro-ano").addClass("hidden");
      $("#movimento-filtro").removeClass("movimento-filtro-ativo");
      date = $(this).attr("date");
      var m = parseInt($(this).attr("rel"));
      liCalendar(m);
    }

    $.ajax({
      url: "{{ route('fin.rel.dre.a.lista') }}"+"?date="+date+"-01-01&lancamento="+lancamento+"&conta="+conta,
      type: "GET",
      dataType: "json",
      success: function(data){
        $("#grid-table-header").html(data.hob);
        $("#grid-table-body").html(data.ob).animate({scrollTop: 0 }, 200);
        $(document).on('click', '.rmcateg', function(e){
          e.preventDefault();
          var id = $(this).attr("rel");
          $(".chi-"+id).toggleClass("hidden");
          $(this).children("i").toggleClass("mdi-chevron-right").toggleClass("mdi-chevron-down");

          if($("#grid-table-body").outerHeight() >= $(".div-left").outerHeight()){
            var di = $("#grid-table-body").outerHeight() - $(".div-left").outerHeight();
            var rmc = $(".rmcateg").length;
            di = di / rmc;
            var hat = $(".rmcateg").outerHeight() + di;
            $(".rmcateg, .rmcategdiv").css({ "height": hat, "line-height": (hat-14) +'px' });
          } else {
            if($(".div-left .subcategoria:visible").length > 0){
              var sub = $(".subcategoria").outerHeight() * $(".div-left .subcategoria:visible").length;
            } else {
              var sub = $(".subcategoria").outerHeight();
            }
            var di = $("#grid-table-body").outerHeight() - $(".div-left").outerHeight() - sub;
            var rmc = $(".rmcateg").length;
            di = di / rmc;
            var hat = $(".rmcateg").outerHeight() + di;
            if(hat < 35){
              $(".rmcateg, .rmcategdiv").css({ "height": 35, "line-height": (35-14) +'px' });
            } else {
              $(".rmcateg, .rmcategdiv").css({ "height": hat, "line-height": (hat-14) +'px' });
            }
          }
        });

        $(".se-pre-con").fadeOut();



        $(".div-center").scrollbar({
        "scrollx": "none",
        disableBodyScroll: true,
      });


        if($("#grid-table-body").outerHeight() > $(".div-left").outerHeight()){
          var di = $("#grid-table-body").outerHeight() - $(".div-left").outerHeight();
          var rmc = $(".rmcateg").length;
          di = di / rmc;
          var hat = $(".rmcateg").outerHeight() + di;
          $(".rmcateg, .rmcategdiv").css({ "height": hat, "line-height": (hat-14) +'px' });
        }
      },
      error: function(data){
        console.log(data);
        $("#grid-table-body").html("Algo deu errado!!");
        $(".se-pre-con").fadeOut();
      }
    });
  });

  $(document).on('click', '.prne', function(e){
    e.preventDefault();
    var n = parseInt($(this).attr("rel"));
    var a = parseInt($(".ano-3").attr("rel"));
    liCalendar(n+a);

    $('.ano-3').trigger("click");
  });

  $(document).on('dp.update', '#datetimepicker', function(e){
    if(e.change == "YYYY"){
      var data1 = e.viewDate.format("YYYY");
      var data2 = moment().format('YYYY')
      var total = (data1 - data2);
      liCalendar(total);
      $('.ano-3').trigger("click");
    }
  });

  var liCalendar = function(M) {
    $(".ano-1").attr("rel", M-2).attr("date", moment().add(M-2, 'years').format('YYYY')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M-2, 'years').format('YYYY') + '</div>');
    $(".ano-2").attr("rel", M-1).attr("date", moment().add(M-1, 'years').format('YYYY')).html('<div class="nav-calendar-top"></div><div class="uppercase"><div>' + moment().add(M-1, 'years').format('YYYY') + '</div>');
    $(".ano-3").attr("rel", M).attr("date", moment().add(M, 'years').format('YYYY')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M, 'years').format('YYYY') + '</div>');
    $(".ano-4").attr("rel", M+1).attr("date", moment().add(M+1, 'years').format('YYYY')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M+1, 'years').format('YYYY') + '</div>');
    $(".ano-5").attr("rel", M+2).attr("date", moment().add(M+2, 'years').format('YYYY')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M+2, 'years').format('YYYY') + '</div>');
  }
</script>
@endpush
