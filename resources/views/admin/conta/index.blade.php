@extends('layouts.admin')
@push('style')
<style type="text/css">

.grid-table:nth-child(odd) {
  background-color: #fbfbfb;
}
.grid-table:hover .status  {
  display: none;
}
.grid-table:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

.grid-table > div {
  text-align: center;
  padding: 10.78px 15px;
  font-size: 16px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.grid-table > div:nth-child(1) {
  text-align: left;
}

.status {
  padding: 11px 10px!important;
  font-size: 0px!important;
}


#btn-tools-delete-all {
  padding-top: 6.5px;
}


.form-group {
  margin-bottom: 10px;
  margin-top: 10px;
}

.slider:before {
  bottom: 2.0px;
}
.switch {
  margin-bottom: 0px;
}

#grid-table-header div {
  margin-right: 0px;
}


</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1> 
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      <a href="#" class="btn-conta-delete hidden"></a>
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar conta" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      <a href="#" class="btn btn-default btn-conta-create" style="margin-right: 5px" route="{{ route('adminCon.conta.create') }}" data-toggle="tooltip" title="Adicionar conta" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>

      <a href="{{ route('adminCon.conta.index') }}" style="color: green;" class="btn btn-default" data-toggle="tooltip" title="Contas" data-placement="bottom"><i class="mdi mdi-folder-account mdi-20px"></i></a>

      <a href="{{ route('usuario.index') }}" style="color: green;" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
      
<!--       <a href="{{ route('empresa.index') }}" class="btn btn-default" data-toggle="tooltip" title="Config. Empresa" data-placement="bottom"><i class="mdi mdi-store mdi-20px"></i></a>

  <a href="{{ route('fornecedor.index') }}" class="btn btn-default" data-toggle="tooltip" title="Config. Fornecedores" data-placement="bottom"><i class="mdi mdi-truck mdi-20px"></i></a> -->
</div>
</div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('adminCon.conta.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding text-center" id="grid-table-header">
      <div class="col-sm-3 col-xs-3 text-left"><a href="#" class="order" order="nome" sort="ASC" >EMPRESA GRUPO</a></div>
      <div class="col-sm-2 col-xs-2"><a href="#" class="order" order="email" sort="ASC">Q.M. EMPRESA</a></div>
      <div class="col-sm-2 col-xs-2"><a href="#" class="order" order="email" sort="ASC">Q.M. CLIENTE</a></div>
      <div class="col-sm-2 col-xs-2"><a href="#" class="order" order="email" sort="ASC">Q.M. FORNECEDOR</a></div>
      <div class="col-sm-2 col-xs-2">Q.M. FUNCIONÁRIO</div>
      <div class="col-sm-1 col-xs-1">ATIVA</div>
    </div>
    <div id="grid-table-body">
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" id="div-crud" >
  </div>
  <form id="delete-form" action="" method="POST" style="display: none;">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
  </form>
  <form id="form-conta-create" action="" class="hidden" method="GET">
    {{ csrf_field() }}
  </form>
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(window).resize(function() {
      resizediv();
    });
  });
  $(document).on('change', '#ativa', function(e){
    if( $(this).is(':checked') ){
      if($(this).val() == 0){
        $(this).val(1)
      } else{
        $(this).val(0)
      }
    } else {
      if($(this).val() == 0){
        $(this).val(1)
      } else{
        $(this).val(0)
      }
    }
  });

  $(document).on('click', '.btn-conta-create, .btn-conta-edit', function(e){
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
    $("#form-conta-create").attr("action", $(this).attr("route"));
    $("#form-conta-create").submit();
  });

  $(document).on("submit", "#form-conta-create", function(e) {
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


        $('.form-crud').scrollbar({
          "scrollx": "none",
          disableBodyScroll: true,
        });
        resizediv();

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

        $('#telefone').mask('(00) 00000-0000', {
          onKeyPress: function(tel, e, field, options){
            var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
            mask = (tel.length>14) ? masks[0] : masks[1];
            $('#telefone').mask(mask, options);
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
  $(document).on('click', '.btn-conta-cancelar, .btn-delete-cancelar', function(e) {
    e.preventDefault();
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $(".pagination-bottom").removeClass("pag-right");
    $("#div-crud").addClass("hidden").html("");
    $(".ativo").removeClass("ativo");
  });

  $(document).on("submit", "#contaForm", function(e) {
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
          $("#div-crud").html("");
          $(".ativo").each(function(i, item){
            $(this).trigger("click");
          });
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
    $('.form-crud').css("max-height", h).parent().css({ "max-height": h, "width": "100%" });
    $(".se-pre-con").fadeOut();
  }

</script>
@endpush