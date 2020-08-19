@extends('layouts.admin')
@push('style')
<style type="text/css">
  #btn-tools-delete-all {
    padding-top: 6.5px;
  }

  .grid > div > div {
    padding: 10.78px 15px;
    border-left: 1px solid #fafafa;
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
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Usuário" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      @can('usuario_create')
      <a href="#" class="btn btn-default btn-usuario-create{{ $qtde_usuario <= 0 ? ' hidden': null }}" id="btn-usuario-create" qtde="{{ $qtde_usuario }}" route="{{ route('usuario.create') }}" data-toggle="tooltip" title="Adicionar Usuário" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>{{ $qtde_usuario }}</a>
      @endcan
      <div style="width: 5px;display: inline-block;"></div>
      @can('usuario_create')
      <a href="#" class="btn btn-default btn-usuario-create" route="{{ route('usuario.create') }}" data-toggle="tooltip" title="Adicionar Cliente" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>
      @endcan
      <div style="width: 5px;display: inline-block;"></div>
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" class="btn btn-default" data-toggle="tooltip" title="Empresas" data-placement="bottom"><i class="mdi mdi-store mdi-20px"></i></a>
      @endcan
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" style="color:#2196f3" class="btn btn-default" data-toggle="tooltip" title="Clientes" data-placement="bottom"><i class="mdi mdi-account-box mdi-20px"></i></a>
      @endcan
      @can('fornecedor_read')
      <a href="{{ route('fornecedor.index') }}" class="btn btn-default" data-toggle="tooltip" title="Fornecedores" data-placement="bottom"><i class="mdi mdi-truck mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('usuario.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="nome" sort="ASC" >NOME</a></div>
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="perfil" sort="ASC" >PERFIL</a></div>
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="email" sort="ASC">E-mail</a></div>
      <div class="col-sm-3 col-xs-3">TELEFONE</div>
    </div>
    <div id="grid-table-body">
    </div>
    <form id="delete-form" action="" method="POST" style="display: none;">
      {{ method_field('DELETE') }}
      {{ csrf_field() }}
    </form>
    <form id="form-usuario-create" action="" class="hidden" method="GET">
      {{ csrf_field() }}
    </form>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" id="div-crud" >
  </div>
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/valida_cpf_cnpj.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#form-search-conta").submit();

    $(window).resize(function() {
      var h =  $("body").innerHeight();
          h -= $(".content-header").innerHeight();
          if ($(".pagination-bottom").length) {
            h -= $(".pagination-bottom").innerHeight();
          }
          h -= 62;
      $("#grid-table-body").css("height", h);
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
      $("#usuario-novo").animate({scrollTop: $('#usuario-novo').prop("scrollHeight") }, 1000);
      $("#endereco").parent().css("margin-bottom", "0");
    }
  });
  $(document).on('click', '.btn-usuario-create, .btn-usuario-edit', function(e){
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
    $("#form-usuario-create").attr("action", $(this).attr("route"));
    $("#form-usuario-create").submit();
  });
  $(document).on("submit", "#form-usuario-create", function(e) {
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
        const psg = new PerfectScrollbar('.form-crud', {
          suppressScrollX:true,
        });
        $(".se-pre-con").fadeOut();
        var h = $("body").innerHeight();
            h -= $(".content-header").innerHeight();
            h -= 62;
        $("#usuario-novo").css("height", h);
        $(".form-footer").css("margin-top", h - 58);

        $('#cpf').mask('000.000.000-00', {
          reverse: true,
          placeholder: "000.000.000-00"
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

        $('#telefone_principal').mask('(00) 00000-0000', {
          onKeyPress: function(tel, e, field, options){
            var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
            mask = (tel.length>14) ? masks[0] : masks[1];
            $('#telefone_principal').mask(mask, options);
          },
          placeholder: "(00) 00000-0000"
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
  $(document).on("submit", "#usuarioForm", function(e) {
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    var url = $(this).attr("action");
    var get = $(this).attr("method");
    var data = $(this).serializeArray();
    ob = {};
    $(data).each(function(i, field){
      ob[field.name] = field.value;
    });
    if(!valida_cpf(ob['cpf'])){
      $("#cpf").parent().addClass("has-error").children(".help-block").html('<strong>CPF é inválido</strong>');
      $(".se-pre-con").fadeOut();
      return false;
    }
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
          var qtde = $("#btn-usuario-create").attr("qtde");
          qtde -= 1;
          if(qtde == 0){
            $("#btn-usuario-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>'+qtde).addClass("hidden");
          } else {
            $("#btn-usuario-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>'+qtde);
          }
          $("#btn-usuario-create").attr("qtde", qtde);
          $("#form-search").submit();
          $("#div-crud").html("");
          $(".ativo").each(function(i, item){
            $(this).trigger("click");
          });
        }
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        $(".help-block").html('');
        $(".has-error").removeClass("has-error");
        console.log(data);
        $.each( data.responseJSON , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      }
    });
  });
  $(document).on('click', '.btn-usuario-cancelar', function(e) {
    e.preventDefault();
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $("#div-crud").addClass("hidden").html("");
    $(".pagination-bottom").removeClass("pag-right");
    $(".ativo").removeClass("ativo");
  });

</script>
@endpush