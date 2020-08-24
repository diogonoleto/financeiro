@extends('layouts.admin')
@push('style')
<style type="text/css">
  #btn-tools-delete-all {
    padding-top: 6.5px;
  }
  .grid-table:nth-child(odd) {
    background-color: #fbfbfb;
  }
  .grid-table:hover .telefone  {
    display: none;
  }
  .grid-table:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }

  .grid-table > div {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .form-group {
    margin-bottom: 10px;
    margin-top: 10px;
  }


  .slider:before {
    bottom: 2.5px;
  }

</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1>
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      @can('fin_centro_custo_create')
      <a href="#" class="btn btn-default btn-centrocusto-create" route="{{ route('centrocusto.create') }}" data-toggle="tooltip" title="Adicionar Centro de Custo" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>
      @endcan
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Centro de Custo" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      <div style="width: 5px;display: inline-block;"></div>
      @can('config_perfil_read')
      <a href="{{ route('configPer.perfil.index') }}" class="btn btn-default" data-toggle="tooltip" title="Perfis" data-placement="bottom"><i class="mdi mdi-account-key mdi-20px"></i></a>
      @endcan
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" class="btn btn-default" data-toggle="tooltip" title="Usuarios" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
      @endcan
      @can('fin_centro_custo_read')
      <a href="{{ route('centrocusto.index') }}" style="color:#E91E63" class="btn btn-default" data-toggle="tooltip" title="Centro de Custos" data-placement="bottom"><i class="mdi mdi-arrange-bring-to-front mdi-20px"></i></a>
      @endcan
      @can('config_importacao_read')
      <a href="{{ route('importacao.index') }}" class="btn btn-default" data-toggle="tooltip" title="Importações" data-placement="bottom"><i class="mdi mdi-import mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('centrocusto.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
      <div class="col-sm-3 col-xs-6"><a href="#" class="order" order="razao_social" sort="asc">NOME</a></div>
    </div>
    <div id="grid-table-body" class="scrollbar-inner">
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" id="div-crud">
  </div>
  <form id="delete-form" action="" method="POST" class="hidden">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
  </form>
  <form id="form-centrocusto-create" action="" class="hidden" method="GET">
    {{ csrf_field() }}
  </form>
</section>
<!-- /.content -->
@endsection
@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
@endpush
@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(window).resize(function() {
      resizediv();
    });
  });
  $(document).on('change', '#endereco', function(e){
    if( !$('#endereco').is(':checked') ){
      $(".endereco-div").addClass("hidden");
      $(this).val(0);
      $("#endereco").parent().css("margin-bottom", "40px");
    } else {
      $(".endereco-div").removeClass("hidden");
      $("#cep").focus();
      $(this).val(1);
      $("#centrocusto-novo").animate({scrollTop: $('#centrocusto-novo').prop("scrollHeight") }, 1000);
      $("#endereco").parent().css("margin-bottom", "0");
    }
  });
  $(document).on('click', '.btn-centrocusto-create, .btn-centrocusto-edit', function(e){
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $("#input-search").val("").parent().parent().addClass("hidden");

    if( $(this).hasClass('ativo')){
      $(".tools-user").removeClass("hidden");
      $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
      $(".pagination-bottom").removeClass("pag-right");
      $("#div-crud").addClass("hidden").html("");
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
      $(".tools-user").toggleClass("hidden");
      $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
      $("#div-crud").toggleClass("hidden");
      $(".pagination-bottom").addClass("pag-right");
    }
    $(this).addClass('ativo');
    $("#form-centrocusto-create").attr("action", $(this).attr("route"));
    $("#form-centrocusto-create").submit();
  });
  $(document).on("submit", "#form-centrocusto-create", function(e) {
    e.preventDefault();
    var url = $(this).attr("action");
    var get = $(this).attr("method");
    var data = $(this).serializeArray();
    $.ajax({
      url: url,
      type: get,
      data: data,
      success: function(data){
        $("#div-crud").html(data);
        $('.form-crud').scrollbar({ "scrollx": "none", disableBodyScroll: true });
        resizediv();

        $('#data_fundacao').datetimepicker({
          locale: 'pt-BR',
          format: 'DD/MM/YYYY',
          viewMode: 'years',
          widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
          }
        });
        $('#cnpj').mask('00.000.000/0000-00', {
          reverse: true,
          placeholder: '0.000.000/0000-00'
        });
        $('#telefone').mask('(00) 00000-0000', {
          onKeyPress: function(tel, e, field, options){
            var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
            mask = (tel.length>14) ? masks[0] : masks[1];
            $('#telefone').mask(mask, options);
          },
          placeholder: "(00) 00000-0000"
        });
        $('#cep').mask('00.000-000', {
          onComplete: function(cep) {
            $(".se-pre-con").fadeIn();
            var v = $('#cep').val();
            var url = "{{ route('empresa.getCEP') }}";
            $.ajax({
              url: url,
              type: 'GET',
              data: { cep:v },
              success: function(data){
                alert(data);
                $("#logradouro").val(data.logradouro);
                $("#cidade").val(data.cidade);
                $("#bairro").val(data.bairro);
                $("#numero").focus();
                $(".se-pre-con").fadeOut();
              },
              error: function(data){
                console.log(data);
                $(".se-pre-con").fadeOut();
              }
            });
          }
        });
      },
      error: function(data){
        console.log(data);
        $("#movimento-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
        $("#movimento-create").toggleClass("hidden");
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on("submit", "#centrocustoForm", function(e) {
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
        $(".help-block").html('');
        $(".has-error").removeClass("has-error");
        if(data.error){
          $.each(data.error , function( key, value ) {
            $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
          });
        } else {
          $("#form-search").submit();
          $("#div-crud").html("");
          $(".ativo").each(function(i, item){
            $(this).trigger("click");
          });
        }
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        console.log(data);
        $(".help-block").html('');
        $(".has-error").removeClass("has-error");
        $.each( data.responseJSON , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on('click', '.btn-centrocusto-cancelar', function(e) {
    e.preventDefault();
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $("#div-crud").addClass("hidden").html("");
    $(".pagination-bottom").removeClass("pag-right");
    $(".ativo").removeClass("ativo");
  });
  var resizediv = function (){
    var h =  $("body").outerHeight();
        h -= $(".content-header").outerHeight(true);
    $('.form-crud').css("max-height", h).parent().css({ "max-height": h, "width": "100%" });
    $(".se-pre-con").fadeOut();
  }
</script>
@endpush
