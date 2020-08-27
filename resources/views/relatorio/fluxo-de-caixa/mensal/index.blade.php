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


.rel-1 {
  min-width: 150px;
  max-width: 150px;
  text-align: center;
  display: table-cell;
  border-left: 1px solid #ddbd6f;
}
.rel-7 {
  text-align: right;
  overflow-y: auto;
  position: relative;
  float: left;
  width: calc(100% - 250px)!important;

}

.rel-3 {
  width: 250px;
  text-align: left;
  float: left;
  text-transform: capitalize;
  font-size: 10px;
}

.rel-3 div {
  border: 0;
  padding: 7px 7px;
  border-bottom: 1px solid #ddbd6f;
  border-left: 1px solid #ddbd6f;
  border-right: 1px solid #ddbd6f;
}

.rel-3 div:first-child {
  padding: 27.5px 15px;
  border: 1px solid #ddbd6f;
  text-align: left;
}

.rel-3 .metop {
  text-align: left;

}


.rel-6 {
  padding: 7px 2px;
  text-align: right;
  width: 50%;
  float: left;
  border-bottom: 1px solid #ddbd6f;
  border-right: 1px solid #ddbd6f;
  font-size: 10px;
}

/*  .rel-3, .rel-6 {
    font-size: 14px;
    }*/

    .rel-12 {
      width: 150px;
      padding: 10px;
      border-top: 1px solid #ddbd6f;
      border-bottom: 1px solid #ddbd6f;
      border-right: 1px solid #ddbd6f;
    }

/*  .subcategoria, .sscategoria{
    background-color: #fff;
    }*/
    .rel-3 .scategoria{
      padding-left: 20px!important;
    }
    .rel-3 .sscategoria{
      padding-left: 36px!important;
    }
    .rel-3 .ssscategoria{
      padding-left: 52px!important;
    }
    .rel-3 .sssscategoria{
      padding-left: 68px!important;
    }

/*  .rel-3 .nchi{
    padding-left: 15px!important;
    }*/

    .rmcateg, .rmcategdiv {
      background-color: #e3d8bd1c;
      font-weight: 500;
    }

    .tot {
      background-color: #f2e2b9;
      font-weight: 500;
    }
    .metop {
      background-color: #ddbd6f40;
      text-align: center;
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
    display: block!important;
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

  #grid-table-body{
    width: 100%;
    overflow-x: hidden;
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
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
    .rel-6, .rel-3 {
      font-size: 8.3px!important;
    }
    .rel-12 {
      width: 82px!important;
    }
    .rel-3 {
      width: 90px!important;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    .rmcateg, .rmcategdiv{
      height: 25px!important;
      line-height: 10px!important;
    }
    .rel-3 div, .rel-6 {
      padding-left: 2px!important;
    }
    .rel-7 {
      width: calc(100% - 90px)!important;
    }
    #div-list {
      border: 0;
      height:100%!important;
    }
    .content-header h1 {
      padding-right: 0;
    }
    .rel-3 .subcategoria {
      padding-left: 15px!important;
    }
  }


  .ps__rail-x {
    top: 0px;
    bottom: auto; /* If using `top`, there shouldn't be a `bottom`. */
  }

  .ps__thumb-x {
    top: 2px;
    bottom: auto; /* If using `top`, there shouldn't be a `bottom`. */
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
          <a href="{{ route('fin.fdc.diario') }}" id="btn-movimento-diario" class="btn btn-default">
            Fluxo de Caixa - Diário
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
      <div class="input-group pull-right hidden-print" id="btn-movimento-tools">
        <div class="input-group-btn">
          <a href="#" id="btn-grafico" style="margin-right: 5px;"><i class="mdi mdi-trending-up mdi-30px"></i></a>
        </div>
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
      <div class="col-xs-10 hidden col-xs-offset-1" id="grafico" style="padding-top: 20px!important;">
        <canvas id="canvas"></canvas>
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
  var psg;
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
    var ctx = $("#canvas");
    window.MensalChart = new Chart(ctx, Mconfig);

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

    $("#div-list").css("height", h);
    $("#grid-table-body").css("height", h);
    $('.ano-3').trigger("click");

    $('#conta').scrollbar({
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
      url: '{{ route('fin.rel.fdc.m.lista') }}'+"?date="+date+"-01-01&lancamento="+lancamento+"&conta="+conta,
      type: "GET",
      dataType: "json",
      success: function(data){
        var h =  $("body").innerHeight();
        h -= $(".content-header").innerHeight();
        h -= 52;

        $("#grid-table-body").html(data.ob).animate({scrollTop: 0 }, 200);

        $(document).on('click', '.rmcateg', function(e){
          e.preventDefault();
          var id = $(this).attr("rel");
          if($(this).hasClass('active')){
            $(this).removeClass("active").children("i").addClass("mdi-chevron-right").removeClass("mdi-chevron-down");;
            $(".chi-"+id).addClass("hidden").removeClass("active").children("i").addClass("mdi-chevron-right").removeClass("mdi-chevron-down");
          } else {
            $(this).addClass("active");
            $(".chi-"+id).toggleClass("active").toggleClass("hidden").children("i").toggleClass("mdi-chevron-right").toggleClass("mdi-chevron-down");
          }

          if($("#grid-table-body").outerHeight() >= $(".rel-3").outerHeight()){
            var di = $("#grid-table-body").outerHeight() - $(".rel-3").outerHeight();
            var rmc = $(".rmcateg").length;
            di = di / rmc;
            var hat = $(".rmcateg").outerHeight() + di;
            $(".rmcateg, .rmcategdiv").css({ "height": hat, "line-height": (hat-7) +'px' });
          } else {
            if($(".rel-3 .subcategoria:visible").length > 0){
              var sub = $(".subcategoria").outerHeight() * $(".rel-3 .subcategoria:visible").length;
            } else {
              var sub = $(".subcategoria").outerHeight();
            }
            var di = $("#grid-table-body").outerHeight() - $(".rel-3").outerHeight() - sub;
            var rmc = $(".rmcateg").length;
            di = di / rmc;
            var hat = $(".rmcateg").outerHeight() + di;
            if(hat < 35){
              $(".rmcateg, .rmcategdiv").css({ "height": 35, "line-height": (35-7) +'px' });
            } else {
              $(".rmcateg, .rmcategdiv").css({ "height": hat, "line-height": (hat-7) +'px' });
            }
          }
        });

        Mconfig.data.labels.splice(0, Mconfig.data.labels.length);
        Mconfig.data.datasets.splice(0, Mconfig.data.datasets.length);
        window.MensalChart.update();
        var datasets = {
          type: 'bar',
          label: "Recebimentos realizados",
          backgroundColor: '#2E7D32',
          borderColor: '#2E7D32',
          data: data.RReal,
        };
        Mconfig.data.datasets.push(datasets);
        var datasets = {
          type: 'bar',
          label: "Recebimentos previstos",
          backgroundColor: 'rgb(153, 237, 139)',
          borderColor: 'rgb(153, 237, 139)',
          data: data.RPrev,
        };
        Mconfig.data.datasets.push(datasets);
        var datasets = {
          type: 'bar',
          label: "Pagamentos realizados",
          backgroundColor: 'rgb(181, 72, 72)',
          borderColor: 'rgb(181, 72, 72)',
          data: data.DReal,
        };
        Mconfig.data.datasets.push(datasets);
        var datasets = {
          type: 'bar',
          label: "Pagamentos previstos",
          backgroundColor: 'rgb(251, 169, 168)',
          borderColor: 'rgb(251, 169, 168)',
          data: data.DPrev,
        };
        Mconfig.data.datasets.push(datasets);
        var datasets = {
          type: 'line',
          lineTension: 0,
          label: "Saldo realizado",
          fill: false,
          backgroundColor: '#395b7b',
          borderColor: '#395b7b',
          data: data.Rtotal,
        };
        Mconfig.data.datasets.push(datasets);
        var datasets = {
          type: 'line',
          lineTension: 0,
          label: "Saldo previsto",
          fill: false,
          backgroundColor: 'rgb(160, 179, 195)',
          borderColor: 'rgb(160, 179, 195)',
          data: data.Ptotal,
        };
        Mconfig.data.datasets.push(datasets);
        $.each(data.meses,function(i, item){
          Mconfig.data.labels.push(item);
        });
        window.MensalChart.update();
        $(".se-pre-con").fadeOut();

        $('.rel-7').scrollbar({
          "scrollx": "none",
          disableBodyScroll: true,
        });

        if($("#grid-table-body").outerHeight() > $(".rel-3").outerHeight()){
          var di = $("#grid-table-body").outerHeight() - $(".rel-3").outerHeight();
          var rmc = $(".rmcateg").length;
          di = di / rmc;
          var hat = $(".rmcateg").outerHeight() + di;
          $(".rmcateg, .rmcategdiv").css({ "height": hat, "line-height": (hat-7) +'px' });
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
