@extends('layouts.admin')

@push('style')
<style type="text/css">

  .grid-table:nth-child(odd) {
    background-color: #fbfbfb;
  }

  .grid-table:hover .produto  {
    display: none;
  }
  .grid-table:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }

  #btn-produto-tools {
    right: 10px;
    width: 76px;
    height: 50px;
    float: right!important;
  }

  #content-central, #content-right {
    height: calc(100% - 59px);
    overflow: hidden!important;
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


  
  #grid-table-header {
    border: 1px solid #eeeeee;
    border-right: 0;
    border-bottom: 1px solid #8BC34A;
  }

  #btn-tools {
    text-align: right;
    margin-right: 20px;
  }

  #btn-search {
    margin-right: 5px;
  }

  .btn-produto-create{
    color: green;
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

  .grid-table > div:first-child {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: initial;
    text-overflow: initial;
  }

  .btn-success{
    margin-bottom: 15px!important;
  }

  .col-xs-offset-2 {
    margin-left: 15%;
  }

  #btn-produto-tools .active{
    color: #8BC34A;
  }

  .form-group {
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .ul-categoria {
    left: 15px;
    right: 15px;
    border: 1px solid #ccc;
    border-top: transparent;
    max-height: 220px;
    overflow-y: auto;
    margin-top: -1px;
    padding: 0;
    list-style: none;
    z-index: 3;
    background-color: #fff;
    position: absolute;
  }

  .ul-categoria > li {
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

  .ul-categoria > li > div {
    padding: 5px 10px;
    display: inline-block;
  }

  .ul-categoria > li > a:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }

  #categoria_input:focus + .ul-categoria > li, .ul-categoria:hover > li{
    display: block!important;
  }

/*  #ul-categoria:not(:focus) + li {
    display: none!important;
  }*/


  #categoria-nova {
    border: 1px solid #8BC34A;
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


  .slider:before {
    bottom: 2px;
  }

  hr {
    margin-top: 10px;
    margin-bottom: 10px;
  }

</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1> 
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      <a href="#" class="btn btn-default btn-produto-create" route="{{ route('produto.create') }}" data-toggle="tooltip" title="Adicionar produto" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Itens" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px" aria-hidden="true"></i></a>

      <a href="{{ route('produto.index') }}" class="btn btn-default active" data-toggle="tooltip" data-placement="bottom" title="Produtos"><i class="mdi mdi-archive mdi-20px"></i></a>
      <a href="{{ route('produto.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Categorias"><i class="mdi mdi-tag-outline mdi-20px" style="color: brown;"></i></a>

    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" >
    <div class="col-sm-12 col-xs-12 no-padding" id="produto-list">
      <div class="col-xs-12 hidden">
        <form id="form-search" action="{{ route('produto.lista') }}">
          {!! method_field('GET') !!}
          {!! csrf_field() !!}
          <input type="hidden" name="tipo" id="tipo">
          <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
        </form>
      </div>
      <div class="col-xs-12 no-padding" id="grid-table-header">
        <div style="width: 40px; padding: 0px 5px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox" id="checkbox-all"></i></div>
        <div class="col-sm-2 col-xs-4" style="width: calc(16.66666667% - 30px);"><a href="#" class="order" order="data_vencimento" sort="ASC" >CÓD.BARRA</a></div>
        <div class="col-sm-3 col-xs-4"><a href="#" class="order" order="descricao" sort="ASC" >PRODUTO</a></div>
        <div class="col-sm-3 hidden-xs"><a href="#" class="order" order="categoria_nome" sort="ASC">FORNECEDOR</a></div>
        <div class="col-sm-2 col-xs-4">CATEGORIA</div>
        <div class="col-sm-2 hidden-xs">PREÇO</div>
      </div>
      <div id="grid-table-body">
      </div>
      <form id="delete-form" action="" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
      </form>
      <form id="delete-form" action="" method="POST" class="hidden">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
      </form>
      <form id="form-produto-create" action="" class="hidden" method="GET">
        {{ csrf_field() }}
      </form>
    </div>
    <div class="col-sm-4 col-xs-4 no-padding hidden" style="height: 100%; padding: 30px; border-left: 1px solid #8bc34a;" id="produto-create" >
    </div>
  </div>

</section>
<!-- /.content -->
@endsection

@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>

@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('plugins/maskMoney/jquery.maskMoney.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>

<script type="text/javascript">
  // $(document).on('click', '.btn-tools-edit, .btn-plus, .btn-show', function(e){
  //   $(".se-pre-con").fadeIn();
  // });

  moment.locale('pt-br');

  $(document).ready(function() {


  });

  $(document).on('click', '#btn-search', function(e){
    e.preventDefault();
    $("#tipo").val($("a.tipo.active").attr("id"));
  });

  $(document).on('click', '.btn-produto-create, .btn-produto-edit', function(e){
    e.preventDefault();
    $(".grid-table, #grid-table-header").children('div:nth-child(1), div:nth-child(4)').toggleClass("hidden");
    $(".grid-table, #grid-table-header").children('div:nth-child(3)').toggleClass("col-sm-4").toggleClass("col-sm-5");
    $(".grid-table, #grid-table-header").children('div:nth-child(5)').toggleClass("col-sm-4").toggleClass("col-sm-2");
    $(".tools-user").toggleClass("hidden");

    $(".has-error").removeClass("has-error").children(".help-block").last().html('');

    $(".se-pre-con").fadeIn();
    $("#produto-create").html("");
    if( $(this).hasClass('btn-produto-create')){
      $("#produto-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
      $("#produto-create").toggleClass("hidden");
      $("#form-produto-create").attr("action", $(this).attr("route")); 
    } else {
      $("#produto-list").addClass("col-sm-8").addClass("col-xs-8");
      $("#produto-create").removeClass("hidden");
      $("#form-produto-create").attr("action", $(this).attr("route")); 
    }
    $("#form-produto-create").submit();
  });
  $(document).on("submit", "#produtoForm", function(e) {
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
        console.log(data);
        if(data.error){
          $.each(data.error , function( key, value ) {
            $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
          });
        } else {
          $("a.tipo.active").trigger("click");
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
  $(document).on("click", "#btn-produto-salvar", function(e) {
    e.preventDefault();
    $("#btn-produto-form").trigger('click');
  });
  $(document).on("submit", "#form-produto-create", function(e) {
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    var url = $(this).attr("action");
    var get = $(this).attr("method");

    var timeNow = moment().format("YYYY-MM-DD HH:mm:ss");
    var now = moment().format("YYYY-MM-DD");
    var setDate = $(".mes-3").attr("date")+"-01";
    if(now > setDate)
      setDate = now;

    $.ajax({
      url: url,
      type: get,
      success: function(data){
        $("#produto-create").html(data);

        $(".tab-pane.fade.in.active").each(function(){
          $(this).find("input[type='text']").focus().select();
        });

        $('#data_vencimento').datetimepicker({
          locale: 'pt-BR',
          defaultDate: setDate,
        });

        $('#data_baixa').datetimepicker({
          locale: 'pt-BR',
          format: 'DD-MM-YYYY HH:mm:ss',
          defaultDate: timeNow,
        });

        $('#data_emissao').datetimepicker({
          locale: 'pt-BR',
          format: 'DD-MM-YYYY',
          defaultDate: timeNow,
        });

        $("a[href='#produtoCorrente']").css("margin-top", h);
        $('[data-toggle="popover"]').popover();
        $(".se-pre-con").fadeOut();
        $("#categoria-nova, #produto-novo").draggable();
        $("#valor, #desconto, #juro, #valor_recebido").maskMoney({symbol:'R$ ', showSymbol:true, thousands:'.', decimal:',', symbolStay: true});
        $("#valor").focus();
        $("#desconto").focus();
        $("#juro").focus();
        $("#valor_recebido").focus();
        $("#descricao").focus();
        $(".se-pre-con").fadeOut();

        var h =  $("body").innerHeight();
            h -= $(".content-header").innerHeight();
            h -= 60;
        $("#produto-novo").css("height", h);
        $("#form-footer").css("margin-top", h - 53);

      },
      error: function(data){
        console.log(data);
        $(".se-pre-con").fadeOut();
      }
    });
  });

  $(document).on('change', '#fornecedor', function(e){
    if( !$('#fornecedor').is(':checked') ){
      $(".fornecedor-div").addClass("hidden");
      $(this).val(0);
    } else {
      $(".fornecedor-div").removeClass("hidden");
      $("#fornecedor_id").focus();
      $(this).val(1);
    }
  });

  $(document).on('change', '#custo_fixo', function(e){
    if( !$('#custo_fixo').is(':checked') ){
      $(".custo_fixo-div").addClass("hidden");
      $(this).val(0);
    } else {
      $(".custo_fixo-div").removeClass("hidden");
      $("#valor_custo_fixo").focus();
      $(this).val(1);
    }
  });

  $(document).on('change', '#controla_estoque', function(e){
    if( !$('#controla_estoque').is(':checked') ){
      $(".controla_estoque-div").addClass("hidden");
      $(this).val(0);
    } else {
      $(".controla_estoque-div").removeClass("hidden");
      $("#aviso_validade").focus();
      $(this).val(1);
    }
  });


  $(document).on('change', '#fiscal', function(e){
    if( !$('#fiscal').is(':checked') ){
      $(".fiscal-div").addClass("hidden");
      $(this).val(0);
    } else {
      $(".fiscal-div").removeClass("hidden");
      $("#descricao_ncm").focus();
      $(this).val(1);
      $("#produto-novo").animate({scrollTop: $('#produto-novo').prop("scrollHeight") }, 0);
    }
  });


  $(document).on('keyup', '#categoria_input', function() {
    var v = $('#categoria_input').val();
    var rex = new RegExp($(this).val(), 'i');
    $("#categoria_id").val();
    
    if(v.length == 0){
      $('.ul-categoria li, .ul-categoria li a').show();
    } else {
      $('.ul-categoria li').hide();
      $('.ul-categoria li').filter(function () {
        return rex.test($(this).text());
      }).show();
      $('.ul-categoria li a').hide();
      $('.ul-categoria li a').filter(function () {
        return rex.test($(this).text());
      }).show();
    }
  });

  $(document).on('focusout', '#categoria_input', function(e) {
    e.preventDefault();
    if($('.ul-categoria li a:visible').length == 1){
      $('.ul-categoria li a:visible').trigger("click");
    } else if($('.ul-categoria li a:visible').length == 0){
      $('#categoria_input').val("");
      $('.ul-categoria li a').show();
    }
  });
  // $(document).on('focusin', '#categoria_input', function(e) {
  //   e.preventDefault();
  //   $('.ul-categoria li').show();
  //   $("#categoria_input").select();
  // });
  $(document).on('click', '.btn-categoria', function(e) {
    e.preventDefault();
    var te = $(this).text();
    var re = $(this).attr('rel');
    $("#categoria_id").val(re);

    $("#categoria_input").val(te);
    $('.ul-categoria li').hide();
  });
  $(document).on('click', '.btn-categoria-create', function(e) {
    e.preventDefault();
    var re = $(this).attr('rel');
    $("#categoria_id").val(re);

    $("#categoria-nova").removeClass("hidden");
    $("#form-categoria input[name='descricao']").val($(this).attr('desc'));
    $("#form-categoria input[name='nome']").focus();
    $('.ul-categoria li').hide(); 
  });
  $(document).on('click', '.btn-categoria-cancelar', function(e) {
    e.preventDefault();
    $("#categoria-nova").addClass("hidden");
    $("#categoria_input").focus();
  });
  $(document).on("submit", "#form-categoria", function(e) {
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
        $(".has-error").removeClass("has-error").children(".help-block").last().html('');
        if(data.error){
          $.each(data.error , function( key, value ) {
            $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
          });
        } else {
          $("#categoria_id").val(data.id);
          $(".ul-categoria li[rel='"+data.categoria_id+"']").append('<a href="#" class="btn-categoria uppercase" rel="'+data.id+'">'+data.nome+'</a>');
          $("#categoria_input").val(data.nome);
          $("#categoria-nova").addClass("hidden");
        }
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        $(".has-error").removeClass("has-error").children(".help-block").last().html('');
        $.each( data.responseJSON , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on("change", "#form-categoria select[name='categoria_id']", function(e) {
    e.preventDefault();
    $( "select option:selected" ).each(function() {
      $("#form-categoria input[name='descricao']").val($(this).attr('desc'));
    });
  });
</script>
@endpush