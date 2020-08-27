@extends('layouts.admin')
@push('style')
<style type="text/css">
fieldset {
  border: 1px solid #ccc;
  text-align: left;
  position: relative;
  margin-bottom: 15px;
  margin-top: 15px;
}

legend {
  font-weight: 300;
  text-align: left;
  margin-left: 10px;
  font-size: 14px;
  color: #9E9E9E;
  width: initial;
  border: 0;
  position: absolute;
  margin-top: -10px;
  background-color: #fff;
}

#grid-table-header, .grid-table{
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

#grid-table-header div, .grid-table div {
  padding: 11px 11px;
  margin-right: 0;
  -ms-flex-preferred-size: 0;
  flex-basis: 0;
  -ms-flex-positive: 1;
  flex-grow: 1;
  max-width: 100%;
  position: relative;
  width: 100%;
}

.col-md-auto {
  -ms-flex: 0 0 auto;
  flex: 0 0 auto;
  width: auto;
  max-width: none;
}

.grid-table:nth-child(odd) {
  background-color: #fbfbfb;
}

.grid-table:hover .movimento  {
  display: none;
}
.grid-table:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

#btn-movimento-tools {
  right: 10px;
  width: 76px;
  height: 44px;
  float: right!important;
}

.pagination-bottom {
  bottom: -50px!important;
}

.list-group-horizontal li.active {
  background: #fafafa;
}
.list-group-horizontal {
  margin-bottom: 0px;
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

#btn-tools {
  text-align: right;
  margin-right: 20px;
}

#btn-search {
  margin-right: 5px;
}

.input-group-addon {
  padding: 9px 0px;
  color: #555;
  background-color: #fff;
  border: 1px solid #fff;
  border-radius: 0;
}

.grid-table > div {
  padding: 10.78px 10px;
  font-size: 16px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  border-left: 1px solid #fff;
}

.display-total {
  text-align: right;
  float: right;
  max-width: 150px;
}

.display-total > div {
  text-align: right;
  font-family: 'Roboto',sans-serif;
  display: inline-block;
  font-weight: 300;
}
.display-total > div > span:nth-child(1){
  font-size: 15px;
  display: block;
  margin-right: 3px;
  margin-bottom: -5px;
  width: 100%;
}

.display-total > div > span:nth-child(2){
  font-size: 10px;
  display: inline-flex;
}

.display-total > div > span:nth-child(3){
  font-size: 25px;
  float: right;
  font-weight: 300;
}

.col-xs-offset-2 {
  margin-left: 15%;
}

#btn-movimento-tools .active{
  color: #ddbd6f;
}

.form-group {
  margin-bottom: 15px;
  margin-top: 5px;
}

.recorencia{
  color: #aaa;
}

.ul-categoria {
  display: none;
  position: relative;
  border: 1px solid #ccc;
  max-height: 220px;
  overflow-y: hidden;
  margin-top: -2px;
  padding: 0;
  list-style: none;
  z-index: 3;
  background-color: #fff;
  max-height: 153px;
}

.ul-categoria > li {
  display: block;
  position: relative;
  overflow: visible;
  margin: 0;
  padding: 0;
  list-style: none;
  background-color: rgb(238, 238, 238)!important;
}
.ul-categoria > li > a {
  padding: 5px 20px;
  width: 100%;
  height: 30px;
  line-height: 20px;
  display: block;
  color: #555;
  background-color: #FFF;
}

.ul-categoria > li > span {
  padding: 5px 0 0 10px;
  display: inline-block;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.ul-categoria > li > a:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

.ul-fornecedor {
  position:relative;
  border: 1px solid #ccc;
  max-height: 220px;
  overflow-y: auto;
  margin-top: -2px;
  padding: 0;
  list-style: none;
  z-index: 3;
  background-color: #fff;
  max-height: 153px;
}
.ul-fornecedor > li {
  display: block;
  position: relative;
  overflow: visible;
  margin: 0;
  padding: 0;
  list-style: none;
  background-color: rgb(238, 238, 238)!important;
}
.ul-fornecedor > li > a {
  padding: 5px 11px;
  width: 100%;
  height: 30px;
  line-height: 20px;
  display: block;
  color: #555;
  background-color: #FFF;
}
.ul-fornecedor > li > a:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

.ul-forma-pagamento {
  display: none;
  position: relative;
  border: 1px solid #ccc;
  max-height: 220px;
  overflow-y: hidden;
  margin-top: -2px;
  padding: 0;
  list-style: none;
  z-index: 3;
  background-color: #fff;
  max-height: 153px;
}

.ul-forma-pagamento > li {
  display: block;
  position: relative;
  overflow: visible;
  margin: 0;
  padding: 0;
  list-style: none;
  background-color: rgb(238, 238, 238)!important;
}
.ul-forma-pagamento > li > a {
  padding: 5px 20px;
  width: 100%;
  height: 30px;
  line-height: 20px;
  display: block;
  color: #555;
  background-color: #FFF;
}

.ul-forma-pagamento > li > span {
  padding: 5px 0 0 10px;
  display: inline-block;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.ul-forma-pagamento > li > a:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

.ul-centro-custo {
  left: 15px;
  right: 15px;
  border: 1px solid #ccc;
  max-height: 220px;
  overflow-y: auto;
  margin-top: -2px;
  padding: 0;
  list-style: none;
  z-index: 3;
  background-color: #fff;
  max-height: 153px;
}
.ul-centro-custo > li {
  display: block;
  position: relative;
  overflow: visible;
  margin: 0;
  padding: 0;
  list-style: none;
  background-color: rgb(238, 238, 238)!important;
}
.ul-centro-custo > li > a {
  padding: 5px 20px;
  width: 100%;
  height: 30px;
  line-height: 20px;
  display: block;
  color: #555;
  background-color: #FFF;
}
.ul-centro-custo > li > a:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

.content-header {
  border-bottom: 1px solid #eeeeee;
}

#categoria-nova {
  border: 1px solid #ddbd6f;
  margin-top: 5px;
  background-color: #fff;
  position: fixed;
  margin: auto;
  left: 17%;
  right: -80px;
  top: 0;
  bottom: 0;
  width: 350px;
  height: 305px;
  z-index: 4;
}

#fornecedor-nova {
  border: 1px solid #ddbd6f;
  margin-top: 0px;
  background-color: #fff;
  position: fixed;
  margin: auto;
  left: 17%;
  right: -80px;
  top: 0;
  bottom: 0;
  width: 350px;
  height: 215px;
  z-index: 4;
}

#centro-custo-nova {
  border: 1px solid #ddbd6f;
  margin-top: 0px;
  background-color: #fff;
  position: fixed;
  margin: auto;
  left: 17%;
  right: -80px;
  top: 0;
  bottom: 0;
  width: 350px;
  height: 190px;
  z-index: 4;
}

.active > svg > path#first-path {
  fill: #ddbd6f;
}
a > svg > path#first-path {
  fill: #ccc;
}

a:hover > svg > path#first-path{
  fill: #23527c;
}

#movimento-footer{
  border-bottom: 1px solid #ddbd6f;
  border-top: 1px solid #ddbd6f;
  position: fixed;
  bottom: 0;
  padding: 5px;
  width: calc(100% - 80px);
  background-color: #fff;
}

.grid-table > div {
  padding: 12px 10px 4px;
}

@media print {
  #movimento-footer{
    width: 100%!important;
    bottom: 0px!important;
    height: 75px!important;
  }
  #grid-table-header{
    height: 100%;
  }

  @page {
    margin: 1cm;
    size: auto;
  }

  a[href]:after {
    content: "" !important;
  }

  .grid-table > div {
    padding: 10px 10px;
  }

  #grid-table-header div:nth-child(2), .grid-table > div:nth-child(2) {
    width: 15%;
  }
  #grid-table-header div:nth-child(3), .grid-table > div:nth-child(3) {
    width: 33.33333333%;
  }
  #grid-table-header > div.col-sm-3:nth-child(4), .grid-table > div.col-sm-3:nth-child(4) {
    width: 26.66666667%;
  }
  #grid-table-header > div.col-sm-4, .grid-table > div.col-sm-4 {
    width: 41.66666667%;
  }
}

@media (max-width: 768px) {
  .grid-table > div {
    padding: 2px 7px!important;
  }
  #grid-table-header div {
    padding: 3px 10px;
  }

  #movimento-footer {
    height: 50px;
  }

  .display-total > div > span:nth-child(1) {
    display: inline-block;
  }

  #grid-table-header div:nth-child(2), .grid-table > div:nth-child(2){
    text-align: right;
  }

}


.slider:before {
  bottom: 2.5px;
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


#div-list{
  background-color: #fff;
  margin-top: -1px;
}



.btn-block+.btn-block {
  margin-top: 0px;
}


#port:before, #port:after {
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  border-bottom: 7px solid #9e9e9e;
  border-bottom-color: #9e9e9e;
  top: -7px;
  left: 21px;
  right: auto;
  background-color: transparent;
  content: '';
  display: inline-block;
  position: absolute;
}
.dropdown-menu-right {
  right: -86px;
  top: 6px;
  left: auto;
}

.dropdown-menu {
  min-width: 130px;
}

.div-del {
  position: absolute!important;
  top: 0;
  bottom: 0;
}
</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 id="title" class="hidden-xs">{{ $title }}</h1>
  <div class="input-group pull-right hidden-print" id="btn-tools">
    <div class="input-group-btn">
      @can('fin_movimento_create')
      <a href="#" class="btn btn-default btn-movimento-create" id="btnmc" route="{{ route('movimento.create') }}" data-toggle="tooltip" title="Adicionar lançamento" data-placement="left"><i class="mdi mdi-plus mdi-20px"></i></a>
      @endcan
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Itens" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      @can('fin_movimento_read')
      <a href="{{ route('movimento.index') }}" class="btn btn-default" data-toggle="tooltip" title="Movimentações" data-placement="bottom"><i class="mdi mdi-cash-multiple mdi-20px"></i></a>
      @endcan
      @can('fin_categoria_read')
      <a href="{{ route('fin.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" title="Categorias" data-placement="bottom"><i class="mdi mdi-tag-outline mdi-20px"></i></a>
      @endcan
      @can('fin_conta_read')
      <a href="{{ route('conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas Bancárias" data-placement="bottom" style="color: grey;"><i class="mdi mdi-bank mdi-20px"></i></a>
      @endcan
      @can('fin_relatorio_read')
      <a href="{{ route('fin.fdc.mensal') }}" class="btn btn-default" data-toggle="tooltip" title="Relatórios" data-placement="bottom"><i class="mdi mdi-library-books mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 no-padding" >
    <div class="col-sm-12 hidden-print" id="movimento-header">
      <div class="btn-group pull-left" id="div-btn-pag">
      </div>
      <div class="input-group pull-right" id="btn-movimento-tools">
        <!--         <div class="input-group-btn">
          <div class="btn-group" style="display: inline;">
            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-file-excel mdi-30px" id="btnport"></i></a>
            <a href="{{ route('financeiro.movimento.exportar') }}"><i class="mdi mdi-export"></i> Exportar</a>
          </div>
          <a href="#" id="print"><i class="mdi mdi-printer mdi-30px"></i></a>
        </div> -->
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
    <div id="div-list" class="col-xs-12 col-sm-12 no-padding">
      <div class="col-xs-12 hidden" id="div-search">
        <form id="form-search" action="{{ route('financeiro.conta.contalista') }}">
          {{ method_field('GET') }}
          {{ csrf_field() }}
          <input type="hidden" name="conta_id" value="{{ $item->id }}">
          <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
        </form>
      </div>
      <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">

        @if($item->conta_tipo_id == 4)
        <div class="hb-data" style="max-width: 70px; min-width: 70px; text-align: center;"><a href="#" class="order-mov active" order="data_baixa" sort="asc" >DATA</a></div>
        <div class="hidden-xs col-md-auto"><a href="#" class="order-mov" order="categoria_nome" sort="asc" >CATEGORIA</a> / <a href="#" class="order-mov" order="descricao" sort="asc" >DESCRIÇÃO</a></div>
        <div class="visible-xs col-md-auto"><a href="#" class="order-mov" order="categoria_nome" sort="asc" >CAT</a> / <a href="#" class="order-mov" order="descricao" sort="asc" >DESC</a></div>
        <div class="col-md-auto" style=""></div>
        <div class="hb-valo hidden-xs text-right" style="max-width: 125px; min-width: 125px;">VALOR (R$)</div>
        <div class="visible-xs text-right">VALOR(R$)</div>

        @else
        <div class="hb-data" style="max-width: 70px; min-width: 70px; text-align: center;">DATA</div>
        <div class="col-md-auto">DESCRIÇÃO</div>
        <div class="text-right" style="max-width: 135px; min-width: 135px;">CRÉDITO (R$)</div>
        <div class="text-right" style="max-width: 135px; min-width: 135px;">DÉBITO (R$)</div>
        <div class="text-right" style="max-width: 135px; min-width: 135px;">SALDO (R$)</div>
        @endif

      </div>
      <div id="grid-table-body" class="scrollbar-inner">
      </div>
      <form id="delete-form" action="" method="POST" class="hidden">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
      </form>
      <form id="form-movimento-create" action="" class="hidden" method="GET">
        {{ csrf_field() }}
        <input type="hidden" name="conta_id" value="{{ $item->id }}">
        <input type="hidden" name="fatura" id="fatura" value="">
        <input type="hidden" name="tipo" value="Despesa">
      </form>
      <form id="form-conta-create" action="" class="hidden" method="GET">
        {{ csrf_field() }}
      </form>
      <a href="#" id="btn-agenda-movimento-edit" class="hidden"><i class="mdi mdi-pencil mdi-24px"></i></a>
    </div>
    <div id="div-crud" class="col-xs-12 col-sm-4 hidden">
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
<script type="text/javascript" src="{{ asset('plugins/maskMoney/jquery.maskMoney.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/valida_cpf_cnpj.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/movimento_conta.js') }}"></script>

<script type="text/javascript">
  moment.locale('pt-br');
  $(document).ready(function() {
    liCalendar(0);

    @if(isset($movimento))
    $("#btn-agenda-movimento-edit").attr({ "route": "{{ route('movimento.edit', $movimento->id) }}", "tipo": "{{ $movimento->categoria->tipo }}" });
    $("#btn-agenda-movimento-edit").trigger("click");
    @endif

    $(window).resize(function() {
      resizediv();
    });
    $('#data_inicio, #data_fim').datetimepicker({
      locale: 'pt-BR',
      format: 'DD/MM/YYYY',
      widgetPositioning: {
        horizontal: 'right',
        vertical: 'bottom'
      }
    });
  });
  $(document).on('click', '.mes', function(e){
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $("#input-search").parent().parent().addClass("hidden");
    $("#input-search").val("");
    var date = $(".mes-3").attr("date");
    var url = $("#form-search").attr("action");

    $(".mes").parent().removeClass("active");
    $(".mes-3").parent().addClass("active");

    date = $(this).attr("date");
    var m = parseInt($(this).attr("rel"));
    liCalendar(m);
    if(!$("#div-crud").hasClass("hidden")){
      $(".se-pre-con").fadeIn();
      $(".tools-user").toggleClass("hidden");
      $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
      $("#div-crud").toggleClass("hidden");
    }

    var data = $('#form-search').serializeArray();
    $.ajax({
      url: url+"?date="+date,
      data: data,
      type: "GET",
      success: function(data){
        $("#grid-table-body").html(data);
        $(".se-pre-con").fadeOut();
      },
      error:function (xhr, ajaxOptions, thrownError){
        $("#grid-table-body").html("Algo deu errado!!");
        if(data.status==404 || data.status==401) {
          window.location.reload(true);
        } else if (data.status==403){
          $("#grid-table-body").html("Você não tem acesso a essa informações!!");
        } else {
          window.location.reload(true);
        }
        $(".se-pre-con").fadeOut();
      }
    });
  });

  $(document).on('click', '.btn-movimento-create, .btn-movimento-edit, .btn-transferencia-create', function(e){
    e.preventDefault();
    $("#div-crud").html("");
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $("#input-search").parent().parent().addClass("hidden");
    $("#input-search").val("");
    if( $(this).hasClass('ativo')){
      $(".se-pre-con").fadeIn();
      $(".tools-user").removeClass("hidden");
      $(".grid-table, #grid-table-header").children('div:nth-child(2)').removeClass("hidden");
      $(".grid-table, #grid-table-header").children('div:nth-child(6)').removeClass("hidden");
      $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
      $("#div-crud").addClass("hidden");
      var tipo = $("a.tipo.active").attr("id");
      if(tipo == "Extrato"){
        $(".grid-table, #grid-table-header").children('div:nth-child(1)').addClass("col-sm-1").removeClass("col-sm-2");
        $(".grid-table, #grid-table-header").children('div:nth-child(3)').addClass("col-sm-3").removeClass("col-sm-4");
        $(".grid-table, #grid-table-header").children('div:nth-child(5)').addClass("col-sm-2").removeClass("col-sm-3");
      } else {
        $(".grid-table, #grid-table-header").children('div:nth-child(3)').addClass("col-sm-4").removeClass("col-sm-5");
        $(".grid-table, #grid-table-header").children('div:nth-child(4)').addClass("col-sm-3").removeClass("col-sm-4");
        $(".grid-table, #grid-table-header").children('div:nth-child(5)').addClass("col-sm-2").removeClass("col-sm-3");
      }
      $(".ativo").removeClass("ativo");
      $(".se-pre-con").fadeOut();
      return false;
    }
    var ati = 0;
    $(".ativo").each(function(i, item){
      ati = 1;
    });
    if(ati == 1){
      $(".ativo").removeClass("ativo");
    } else {
      $(".se-pre-con").fadeIn();
      $(".tools-user").toggleClass("hidden");
      $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
      $("#div-crud").toggleClass("hidden");
    }

    if($(this).hasClass('btn-transferencia-create')){
      $("#fatura").val($(this).attr("rel"));
    }

    $(this).addClass('ativo');

    $("#form-movimento-create").attr("action", $(this).attr("route"));
    $("#form-movimento-create").attr("tipo", $(this).attr("tipo"));
    $("#form-movimento-create").submit();
  });
  $(document).on("submit", "#movimentoForm", function(e) {
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    var url = $(this).attr("action");
    var get = $(this).attr("method");
    var data = $(this).serializeArray();
    $.ajax({
      url: url,
      type: get,
      data: data,
      success: function(data){
        if(data.error){
          $.each(data.error , function( key, value ) {
            $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
          });
        } else {
          $(".mes-3").trigger("click");
          $(".ativo").removeClass("ativo");
        }
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        console.log(data);
        $.each( data.responseJSON , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on("submit", "#form-movimento-create", function(e) {
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    var url = $(this).attr("action");
    var get = $(this).attr("method");
    var timeNow = moment().format("YYYY-MM-DD HH:mm:ss");
    var now = moment().format("YYYY-MM-DD");
    var data = $(this).serializeArray();
    $.ajax({
      url: url,
      type: get,
      data: data,
      success: function(data){
        $("#div-crud").html(data);


        $('.form-crud, .ul-categoria, .ul-fornecedor').scrollbar({
          "scrollx": "none",
          disableBodyScroll: true,
        });


        var day = moment().format("DD");
        var timeComp = $(".mes-3").attr('date');
        timeComp = moment(timeComp+"-"+day).format("YYYY-MM-DD");
        today = moment().format("YYYY-MM-DD");
        // var dmax = $("#data_emissao").attr("fechamento");

        $('#data_emissao').datetimepicker({
          locale: 'pt-BR',
          format: 'DD/MM/YYYY',
          maxDate: today,
          widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
          }
        }).val('');

        $(document).on("focusin", "#data_emissao", function(e) {
          if($(this).length > 0)
            $(this).val(today);
        });

        $('#data_transferencia').datetimepicker({
          locale: 'pt-BR',
          format: 'DD/MM/YYYY',
          defaultDate: timeComp,
          widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
          }
        });
        $('[data-toggle="popover"]').popover();
        $('#valor, #valor_transferencia').maskMoney({ prefix:'R$ ', allowZero:true, allowNegative: false, thousands:'.', decimal:',', affixesStay: false });

        $('input[name=cnpj]').mask('000.000.000-00#', {
          onKeyPress: function(cn, e, field, options){
            var masks = ['00.000.000/0000-00', '000.000.000-00#'];
            mask = (cn.length>14) ? masks[0] : masks[1];
            field.mask(mask, options);
          },
          placeholder: "000.000.000-00"
        });

        $(".ativo").each(function(i, item){
          if($(this).hasClass("btn-transferencia-create")){
            $("#movimento-novo").addClass("hidden");
            $("#transferencia-nova").removeClass("hidden");
            $("#conta_saida_id").focus();
          } else {
            $("#movimento-novo").removeClass("hidden");
            $("#transferencia-nova").addClass("hidden");
            $("#descricao").focus();
          }
        });
        resizediv();
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        console.log(data);
        $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
        $("#div-crud").toggleClass("hidden");
        $(".se-pre-con").fadeOut();
      }
    });
  });


</script>
@endpush
