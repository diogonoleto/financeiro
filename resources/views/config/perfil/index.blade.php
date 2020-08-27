@extends('layouts.admin')

@push('style')
<style type="text/css">
  .grid-table:nth-child(odd) {
    background-color: #fbfbfb;
  }
  .grid-table:hover .perfil  {
    display: none;
  }
  .grid-table:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }
  #btn-perfil-tools {
    right: 10px;
    width: 76px;
    height: 50px;
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
  #grid-table-header {
    border: 1px solid #eeeeee;
    border-right: 0;
    border-bottom: 1px solid #ddbd6f;
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
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  #btn-perfil-tools .active{
    color: #ddbd6f;
  }
  .form-group {
    margin-bottom: 15px;
    margin-top: 5px;
  }
  .btn-perfil-create {
    margin-right: 5px!important;
  }

</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1>
  <div class="input-group pull-right hidden-print " id="btn-tools">
    <div class="input-group-btn">
      <a href="#" class="btn-perfil-delete hidden"></a>
      @can('config_perfil_create')
      <a href="#" class="btn btn-default btn-perfil-create" route="{{ route('configPer.perfil.create') }}" data-toggle="tooltip" title="Adicionar perfil" data-placement="bottom"><i class="mdi mdi-plus mdi-20px"></i></a>
      @endcan
      @can('config_perfil_read')
      <a href="{{ route('configPer.perfil.index') }}" style="color:#9C27B0" class="btn btn-default" data-toggle="tooltip" title="Perfis" data-placement="bottom"><i class="mdi mdi-account-key mdi-20px"></i></a>
      @endcan
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
      @endcan
      @can('fin_centro_custo_read')
      <a href="{{ route('centrocusto.index') }}" class="btn btn-default" data-toggle="tooltip" title="Centro de Custos" data-placement="bottom"><i class="mdi mdi-arrange-bring-to-front mdi-20px"></i></a>
      @endcan
      @can('config_importacao_read')
      <a href="{{ route('importacao.index') }}" class="btn btn-default" data-toggle="tooltip" title="Importações" data-placement="bottom"><i class="mdi mdi-import mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" >
    <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
      <div class="col-xs-12 hidden" id="div-search">
        <form id="form-search" action="{{ route('config.perfil.lista') }}">
          {!! method_field('GET') !!}
          {!! csrf_field() !!}
          <input type="hidden" name="tipo" id="tipo">
          <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
        </form>
      </div>
      <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
        <div class="col-sm-6">PERFIL</div>
        <div class="col-sm-6">DESCRIÇÃO</div>
      </div>
      <div id="grid-table-body" class="scrollbar-inner">
      </div>
      <form id="delete-form" action="" method="POST" class="hidden">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <input type="hidden" name="delete_confirmar" id="delete_confirmar" value="0">
      </form>
      <form id="form-perfil-create" action="" class="hidden" method="GET">
        {{ csrf_field() }}
      </form>
    </div>
    <div class="col-sm-4 col-xs-12 hidden" id="div-crud">
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
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">
  moment.locale('pt-br');
  $(document).on('click', '.btn-perfil-create, .btn-perfil-edit, .btn-perfil-delete', function(e){
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $("#input-search").parent().parent().addClass("hidden");
    $("#input-search").val("");
    if( $(this).hasClass('ativo')){
      $(".tools-user").removeClass("hidden");
      $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
      $(".pagination-bottom").removeClass("pag-right");
      $("#div-crud").addClass("hidden");
      $(".ativo").removeClass("ativo");
      $(".se-pre-con").fadeOut();
      $("#div-crud").html("");
      return false;
    }
    var ati = 0;
    $(".ativo").each(function(i, item){
      ati = 1;
    });
    if(ati == 1){
      $(".ativo").removeClass("ativo");
    } else {
      $(".tools-user").toggleClass("hidden");
      $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
      $("#div-crud").toggleClass("hidden");
      $(".pagination-bottom").addClass("pag-right");
    }
    $(this).addClass('ativo');
    if($('.btn-perfil-delete').hasClass('ativo')){
      return false;
    }
    $("#form-perfil-create").attr("action", $(this).attr("route"));
    $("#form-perfil-create").submit();
  });
  $(document).on("click", "#btn-perfil-salvar", function(e) {
    e.preventDefault();
    // $(".se-pre-con").fadeIn();
    $("#btn-perfil-form").trigger('click');
  });
  $(document).on("submit", "#form-perfil-create", function(e) {
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    var url = $(this).attr("action");
    var get = $(this).attr("method");
    $.ajax({
      url: url,
      type: get,
      success: function(data){
        $("#div-crud").html(data);
        $('[data-toggle="popover"]').popover();
        $("#nome").focus();
        $(".se-pre-con").fadeOut();
        var h =  $("body").innerHeight();
            h -= $(".content-header").innerHeight();
        $("#perfil-novo").css("height", 165);
        $("#perfil-novo").css("margin-top", (h/2) - 155);
        $("#div-crud").css("height", h);
      },
      error: function(data){
        console.log(data);
        $("#div-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
        $("#div-crud").toggleClass("hidden");
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on("submit", "#delete-form", function(e) {
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
        $('.div-del').remove();
        var h =  $("body").innerHeight();
            h -= $(".content-header").innerHeight();
            h -= 62;

        $("#div-crud").html(data);
        $("#delete-novo").css("height", h);

        var route = $("#delete-form").attr("action");
        $('.btn-perfil-delete').attr("action", route).trigger("click");
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        console.log(data);
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on("submit", "#deleteForm", function(e) {
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
        $(".has-error").removeClass("has-error").children(".help-block").last().html('');
        if(data.error){
          $.each(data.error , function( key, value ) {
            $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
          });
        } else {
          if($(".form-crud #delete_confirmar").val() == 1){
            $("#form-search").submit();
            $("#div-crud").html("");
            $(".ativo").each(function(i, item){
              $(this).trigger("click");
            });
          }
        }
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        $(".has-error").removeClass("has-error").children(".help-block").last().html('');
        console.log(data);
        $.each( data.responseJSON , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on('click', '.btn-perfil-cancelar, .btn-delete-cancelar', function(e) {
    e.preventDefault();
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $(".pagination-bottom").removeClass("pag-right");
    $("#div-crud").addClass("hidden").html("");
    $(".ativo").removeClass("ativo");
  });
  $(document).on('click', '#btn-perfil-deletar', function(e){
    e.preventDefault();
    $("#deleteForm").submit();
  });
</script>
@endpush
