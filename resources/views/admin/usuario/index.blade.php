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
      <div id="del-group"></div>
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar usuario" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      <a href="#" class="btn btn-default btn-usuario-create" style="margin-right: 5px" route="{{ route('usuario.create') }}" data-toggle="tooltip" title="Adicionar usuario" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>

      <a href="{{ route('adminCon.conta.index') }}" style="color: green;" class="btn btn-default" data-toggle="tooltip" title="Contas" data-placement="bottom"><i class="mdi mdi-folder-account mdi-20px"></i></a>

      <a href="{{ route('usuario.index') }}" style="color: green;" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>

<!--       <a href="{{ route('empresa.index') }}" class="btn btn-default" data-toggle="tooltip" title="Config. Empresa" data-placement="bottom"><i class="mdi mdi-store mdi-20px"></i></a>

  <a href="{{ route('fornecedor.index') }}" class="btn btn-default" data-toggle="tooltip" title="Config. Fornecedores" data-placement="bottom"><i class="mdi mdi-truck mdi-20px"></i></a> -->
</div>
</div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" id="usuario-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('usuario.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
      <div style="width: 40px;padding: 0px  5px 0px;float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox" id="checkbox-all"></i></div>
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="nome" sort="asc" >NOME</a></div>
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="conta" sort="asc" >CONTA</a></div>
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="email" sort="asc">E-MAIL</a></div>
      <div class="col-sm-3 col-xs-3" style="width: calc(25% - 40px);">TELEFONE</div>
    </div>
    <div id="grid-table-body">
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" style="height: 100%; padding: 30px; border-left: 1px solid #8bc34a;" id="usuario-create" >
  </div>

  <form id="delete-form" action="" method="POST" style="display: none;">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
  </form>
  <form id="form-usuario-create" action="" class="hidden" method="GET">
    {{ csrf_field() }}
  </form>
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(window).resize(function() {
      resizediv();
    });
  });
  $(document).on('click', '.btn-tools-edit, .btn-plus, .btn-show', function(e){
    $(".se-pre-con").fadeIn();
  });
  $(document).on('click', '#checkbox-all', function(e){
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
      $("#empresa-novo").animate({scrollTop: $('#empresa-novo').prop("scrollHeight") }, 1000);
      $("#endereco").parent().css("margin-bottom", "0");
    }
  });

  $(document).on('keyup', '#cep', function() {
    var v = $('#cep').val();
    if(v.length > 7){
      $(".se-pre-con").fadeIn();
      var url = "{{ route('empresa.getCEP') }}";
      $.ajax({
        url: url,
        type: 'get',
        data: { cep:v },
        success: function(data){
          console.log(data);
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

  $(document).on('click', '.btn-usuario-create, .btn-usuario-edit', function(e){
    e.preventDefault();
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');

    $(".se-pre-con").fadeIn();
    $("#usuario-create").html("");

    $("#grid-table-header > div:nth-child(7), #grid-table-header > div:nth-child(8), .grid-table > div:nth-child(7), .grid-table > div:nth-child(8)").toggleClass("hidden");

    $("#grid-table-header > div:nth-child(3), .grid-table > div:nth-child(3)").toggleClass("col-sm-2").toggleClass("col-xs-3");
    $("#grid-table-header > div:nth-child(6), .grid-table > div:nth-child(6)").toggleClass("col-sm-1").toggleClass("col-xs-2");

    if( $(this).hasClass('btn-usuario-create')){
      $("#usuario-list").toggleClass("col-sm-8").toggleClass("col-xs-8");
      $("#usuario-create").toggleClass("hidden");
      $("#form-usuario-create").attr("action", $(this).attr("route"));
    } else {
      $("#usuario-list").addClass("col-sm-8").addClass("col-xs-8");
      $("#usuario-create").removeClass("hidden");
      $("#form-usuario-create").attr("action", $(this).attr("route"));
    }
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
        $("#usuario-create").html(data);
        $('#usuario-novo').scrollbar({ "scrollx": "none", disableBodyScroll: true });
        resizediv();
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
          $("#form-search").submit();
          $(".btn-usuario-create").trigger("click");
          $("#usuario-create").html("");
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
  $(document).on('click', '.btn-contato-create, .btn-contato-edit', function(e){
    e.preventDefault();
    $("#form-contato-create").attr("action", $(this).attr("route"));
    $("#div-contato").append('<input type="hidden" name="telefone_id"><div class="col-sm-4" style="padding: 0 0 25px;"><div class="input-group"><span class="input-group-addon"><input type="radio" aria-label="..." name="radio"></span><label for="telefone_tipo" style="left: 37px; z-index: 3;">Tipo</label><input type="text" class="form-control" name="telefone_tipo" required></div></div><div class="col-sm-8" style="padding: 0 0 25px;"><label for="telefone_numero">Telefone</label><input type="text" class="form-control" name="telefone_numero" id="telefone_numero" style="border-left: 0;" required></div><span class="help-block"></span>')
    // $("#form-contato-create").submit();
  });
  $(document).on("submit", "#form-contato-create", function(e) {
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    var url = $(this).attr("action");
    var get = $(this).attr("method");

    var timeNow = moment().format("YYYY-MM-DD HH:mm:ss");
    var now = moment().format("YYYY-MM-DD");
    var setDate = $(".mes-3").attr("date")+"-01";
    if(now > setDate)
      setDate = now;

    var tipo = $("a.tipo.active").attr("id");
    $.ajax({
      url: url+"?tipo="+tipo,
      type: get,
      success: function(data){
        $("#contato-create").html(data);
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

        var h = ($(".content").innerHeight() - 380) / 2;
        $("a[href='#contatoCorrente']").css("margin-top", h);
        $('[data-toggle="popover"]').popover();
        $(".se-pre-con").fadeOut();
        $("#categoria-nova, #contato-novo").draggable();
        $("#valor, #desconto, #juro, #valor_recebido").maskMoney({symbol:'R$ ', showSymbol:true, thousands:'.', decimal:',', symbolStay: true});
        $("#valor").focus();
        $("#desconto").focus();
        $("#juro").focus();
        $("#valor_recebido").focus();
        $("#descricao").focus();
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        console.log(data);
        $(".se-pre-con").fadeOut();
      }
    });
  });

  var resizediv = function (){
    var h =  $("body").innerHeight();
        h -= $(".content-header").innerHeight();
        h -= 70;
    $("#usuario-novo").css("max-height", h);
    $("#form-footer").css("margin-top", h - 53);
    $(".se-pre-con").fadeOut();
  }
</script>
@endpush
