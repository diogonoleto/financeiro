@extends('layouts.admin')

@push('style')
<style type="text/css">
.ca-slimbox {
  position: relative;
  height: 100px;
  display: inline-block;
  margin: 5px;
  width: 200px;
  padding: 0;
  text-align: center;
  transition: all .5s cubic-bezier(0.12, -1.99, 0.57, 2.76);
  box-shadow: 0 0 10px 0 rgba(201,211,221,.4);
  border: 1px solid #c9d3dd;
}
.ca-slimbox .stronger {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 5px;
  font-size: 13px;
}
.ca-slimbox img {
  position: absolute;
  margin: auto;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
}

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

#bandeira {
  margin-top: 10px;
  height: 60px;
}

.bandeira {
  position: absolute;
  border: 1px solid #eee;
  height: 50px;
  width: 50px;
  margin: auto;
  left: 0;
  right: 0;
}

.bandeira img {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  margin: auto;
}

.bandeira.active {
  border: 2px solid green;
}

h2, h3 {
  margin-top: 10px;
  text-align: left;
}


.btn-lg {
  padding: 13px 19px;
  font-size: 18px;
  font-weight: 300;
  font-family: 'Roboto', sans-serif;
}

.grid > div > div {
  padding: 12px 10px 4px;
  border-left: 1px solid #fafafa;
  font-size: 16px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.fisica-juridica {
  color:#555;
}


.grid-table:hover .conta  {
  display: none;
}

label span {
  color:red;
}

#NovaConta {
  text-align: center;
}

.div-del-cat .div-cat {
  line-height: 1.4;
  padding: 3px 10px;
}

.form-group {
  margin-bottom: 10px;
  margin-top: 10px;
}

.tab-content {
  height: 100%;
  border: 1px solid #ddbd6f;
  background-color: #fff;
  padding: 0;
}

.tab-content > .active {
  height: 100%;
  width: 100%;
}

#grid-table-header div, #grid-table-header div a {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

#grid-table-header div:nth-child(1), .grid-table > div:nth-child(2) > div:nth-child(1){
  width: 80px;
  padding: 11px 0 0;
  float: left;
  text-align: center;
}

#grid-table-header div:nth-child(3), .grid-table > div:nth-child(2) > div:nth-child(3){
  width: calc(25% - 80px);
  float: left;
}

.ul-banco {
  border: 1px solid #ccc;
  border-top: 0;
  margin-top: -11px;
  padding: 0;
  list-style: none;
  background-color: transparent;
  max-height: 200px;
  margin-bottom: 0;
}
.ul-banco > li {
  display: block;
  margin: 0;
  padding: 0;
  list-style: none;
}
.ul-banco > li > a {
  padding: 3px 15px;
  width: 100%;
  height: 25px;
  line-height: 20px;
  display: block;
  color: #555;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;

}
.ul-banco > li > a:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

#del-alert{
  position: absolute;
  z-index: 1;
  top: 0px;
  bottom: 0;
  left: 0;
  right: 0;
  max-width: 340px;
  max-height: 100px;
  margin: auto;
  padding: 0;
}
#del-alert h3 {
  margin: 36px;
  margin-left: 20px;
  margin-right: 0px;
}

.alert-dismissable .close, {
  top: 5px;
  right: 8px;
}

#grid-table-header div {
  margin-right: 0px;
}

.grid-table a:hover {
  color: #ddbd6f;
}

#ContaCorrente div.title {
  margin-top: -30px!important;
}

@media (max-width: 768px) {
/*    #grid-table-header div:nth-child(1), .grid-table > div:nth-child(2) > div:nth-child(1) {
      width: 40px!important;
      }*/
      #grid-table-header div:nth-child(3), .grid-table > div:nth-child(2) > div:nth-child(3){
        width: calc(100% - 80px)!important;
      }
      #div-crud {
        padding: 15px 0px!important;
        border: 0px!important;
        overflow: auto;
      }
      .form-control {
        height: 50px;
        font-size: 16px;
        box-shadow: none;
      }
      #NovaConta .title, #ContaCorrente .title {
        font-size: 22px!important;
      }
/*      #NovaConta, #ContaCorrente {
        height: initial!important;
        }*/
        .form-group {
          margin-bottom: 25px;
          margin-top: 25px;
        }
        .form-footer{
          position:relative;
          width: 100%;
          margin-top: -40px!important;
          padding: 15px;
          margin-left: 0;
        }
        .pagination-bottom {
          position: absolute;
          right: 15px;
        }
        .content {
          height: calc(100% - 50px);
          overflow: hidden;
        }
        .tab-content{
          border: 0;
          border-top: 1px solid #ddbd6f
        }
      }
    </style>
    @endpush
    @section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="hidden-xs">{{ $title }}</h1>
      <div class="input-group pull-right" id="btn-tools">
        <div class="input-group-btn">
          <a href="#" class="btn-conta-delete hidden"></a>
          @can('fin_movimento_create')
          <a href="#" class="btn btn-default btn-conta-create" route="{{ route('conta.create') }}" data-toggle="tooltip" title="Adicionar Conta" data-placement="bottom"><i class="mdi mdi-plus mdi-20px"></i></a>
          @endcan
          <!-- <a href="#" class="btn btn-default hidden" id="btn-grid-table"><i class="mdi mdi-view-module mdi-20px" aria-hidden="true"></i></a> -->
          <a href="#" class="btn btn-default" id="btn-search" style="margin-right: 5px" data-toggle="tooltip" title="Pesquisar Itens" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
          @can('fin_movimento_read')
          <a href="{{ route('movimento.index') }}" class="btn btn-default" data-toggle="tooltip" title="Movimentações" data-placement="bottom"><i class="mdi mdi-cash-multiple mdi-20px"></i></a>
          @endcan
          @can('fin_categoria_read')
          <a href="{{ route('fin.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" title="Categorias" data-placement="bottom"><i class="mdi mdi-tag-outline mdi-20px"></i></a>
          @endcan
          @can('fin_conta_read')
          <a href="{{ route('conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas Bancárias" data-placement="bottom"><i class="mdi mdi-bank mdi-20px" style="color: grey;"></i></a>
          @endcan
          @can('fin_relatorio_read')
          <a href="{{ route('fin.fdc.mensal') }}" class="btn btn-default" data-toggle="tooltip" title="Relatórios" data-placement="bottom"><i class="mdi mdi-library-books mdi-20px"></i></a>
          @endcan
        </div>
      </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="col-xs-12 col-sm-12 no-padding" id="div-list">
        <div class="col-sm-12 no-padding">
          <div class="col-xs-12 hidden" id="div-search">
            <form id="form-search-conta" action="{{ route('financeiro.conta.lista') }}">
              {{ method_field('GET') }}
              {{ csrf_field() }}
            </form>
            <form id="form-search" action="{{ route('financeiro.conta.lista') }}">
              {{ method_field('GET') }}
              {{ csrf_field() }}
              <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
            </form>
          </div>
          <div class="col-xs-12 no-padding" id="grid-table-header">
            <div title="PADRÃO"><a href="#" class="order" order="padrao" sort="asc">PADRÃO</a></div>
            <div class="col-xs-1 col-sm-1 hidden-xs" title="IDENTIFIÇÃO DA CONTA"><a href="#" class="order" order="id" sort="asc">ID CONTA</a></div>
            <div class="col-xs-12 col-sm-5"><a href="#" class="order" order="descricao" sort="asc" title="CONTA">CONTA</a> / <a href="#" class="order" order="tipo_conta" sort="asc" title="CONTA">TIPO DE CONTA</a></div>
            <div class="col-xs-6 col-sm-3" title="BANCO"><a href="#" class="order" order="banco" sort="asc">BANCO</a></div>
            <div class="col-xs-1 col-sm-1 cl-cimg hb-cimg hidden-xs" style="max-width: 43px; min-width: 43px;"></div>
            <div class="col-xs-2 col-sm-2 hidden-xs" title="SALDO INICIAL">SALDO INICIAL</div>
          </div>
          <div id="grid-table-body" class="scrollbar-inner">
          </div>
          <form id="delete-form" action="" method="POST" class="hidden">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="hidden" name="delete_confirmar" id="delete_confirmar" value="0">
          </form>
          <form id="padrao-form" action="" method="POST" class="hidden">
            {{ method_field('PUT') }}
            {{ csrf_field() }}
          </form>
          <form id="form-conta-create" action="" class="hidden" method="GET">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4 hidden" id="div-crud">
      </div>
    </section>
    <!-- /.content -->
    @endsection

    @push('link')
    <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
    @endpush

    @push('scripts')
    <script type="text/javascript" src="{{ asset('plugins/maskMoney/jquery.maskMoney.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#form-search-conta").submit();

        @if(session('sconta'))
        $(".btn-conta-create").trigger("click");
        @endif

        $(window).resize(function() {
          resizediv();
        });





      });


      var resizediv = function (){
        var h = $("body").innerHeight();
        h -= $(".content-header").innerHeight();
        h -= 110;
        $(".form-crud").css("height", h);






      // var h =  $("body").innerHeight();
      // h -= $(".content-header").innerHeight();
      // if ($(".pagination-bottom").length) {
      //   h -= $(".pagination-bottom").innerHeight();
      // }
      // h -= 62;
      // $("#delete-novo, #NovaConta, #ContaCorrente, #Poupanca, #CartaoDeCredito, #AplicacaoAutomatica, #MeioDeRecebimento, #Investimento, #Caixinha, #Outro").css("height", h);

      // h -= 62;
      // $("#movimento-novo, #transferencia-nova, #NovaConta, #ContaCorrente, #Poupanca, #CartaoDeCredito, #AplicacaoAutomatica, #MeioDeRecebimento, #Investimento, #Caixinha, #Outro").css({ "height": h, 'overflow': 'auto' });

      $(".se-pre-con").fadeOut();

    }

    $(document).on("click", ".ca-slimbox", function(e){
      e.preventDefault();
      var id = $(this).attr('rel');
      $("#conta_tipo_id").val(id);
      $(".div-ccorrente, .div-poupanca, .div-caixinha, .div-ccredito").addClass("hidden");
      $(".ContaForm").trigger("reset");
      $("#dia_vencimento").prop("required", false);
      $("input[name='bandeira'], input[name='tipo_pessoa']").prop("required", false);


      if(id == 1){
        $(".div-ccorrente").removeClass("hidden");
        $(".title").html("Nova Conta Corrente");
        $("#conta").mask("#######0000A", { reverse: true, placeholder:'N° da Conta: ex. 0000000000000' });
        $("input[name='tipo_pessoa']").prop("required", true);
        $("#dia_vencimento").val("")
      } else if(id == 2) {
        $(".div-poupanca").removeClass("hidden");
        $(".title").html("Nova Conta Poupança");
        $("#conta").mask("#######0000A", { reverse: true, placeholder:'N° da Conta: ex. 0000000000000' });
        $("#dia_vencimento").val("")
      } else if(id == 4) {
        $(".div-ccredito").removeClass("hidden");
        $(".title").html("Novo Cartão de Crédito");
        $("#conta").mask("###0", { reverse: true, placeholder:'Ex: 1234' });
        $("#dia_vencimento").prop("required", true);
        $("input[name='bandeira']").prop("required", true);
      } else if(id == 8) {
        $(".div-caixinha").removeClass("hidden");
        $(".title").html("Nova Caixinha");
        $("#dia_vencimento").val("")
      }
    });

    $(document).on("click", "#btn-banco-img-editar, #btn-image-cancelar", function(e){
      e.preventDefault();
      $("#div-image").toggleClass("hidden");
    });
    $(document).on("change", "#img", function(e){
      e.preventDefault();
      var tmppath = URL.createObjectURL(e.target.files[0]);
      $("#icone-img").fadeIn("fast").attr('src',tmppath);
      $("#icone").val("");
    });
    $(document).on("click", "#btn-icone", function(e){
      e.preventDefault();
      $("#icone-img").fadeIn("fast").attr('src',$(this).attr('src'));
      $("#icone").val($(this).attr('rel'));
    });
    $(document).on('click', '.btn-tools-edit, .btn-plus, .btn-show', function(e){
      $(".se-pre-con").fadeIn();
    });
    $(document).on('click', '.btn-conta-create, .btn-conta-edit, .btn-conta-delete', function(e){
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

      if($(this).hasClass("btn-conta-edit")){
        $("#form-conta-create").attr("tipo", $(this).attr("tipo"));
      } else {
        $("#form-conta-create").removeAttr("tipo");
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
      if($('.btn-conta-delete').hasClass('ativo')){
        return false;
      }
      $("#form-conta-create").attr("action", $(this).attr("route"));
      $("#form-conta-create").submit();
    });
    $(document).on('click', '.btn-conta-cancelar, .btn-delete-cancelar', function(e) {
      e.preventDefault();
      $(".tools-user").removeClass("hidden");
      $(".delete-border").removeClass("delete-border");
      $(".delete-border-top").removeClass("delete-border-top");
      $(".delete-border-bottom").removeClass("delete-border-bottom");
      $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
      $(".pagination-bottom").removeClass("pag-right");
      $("#div-crud").addClass("hidden").html("");
      $(".ativo").removeClass("ativo");
    });
    $(document).on("click", "#btn-conta-deletar", function(e) {
      e.preventDefault();
      $("#deleteForm").submit();
    });
    $(document).on('click', '.checkbox-padrao', function(e){
      e.preventDefault();
      if($(this).children("#padrao").is(':checked')){
        $(this).children("#padrao").removeAttr('checked');
        $(this).children("i").removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
      } else {
        $(this).children("#padrao").attr("checked", "checked");
        $(this).children("i").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
      }
    });
    $(document).on('click', '.fisica-juridica', function(e){
      e.preventDefault();
      $("input[name='tipo_pessoa']").removeAttr('checked');
      $(".radio").removeClass("mdi-checkbox-marked-circle-outline").addClass("mdi-checkbox-blank-circle-outline");
      $(this).children('input').attr('checked', 'checked');
      $(this).children('.radio').removeClass("mdi-checkbox-blank-circle-outline").addClass("mdi-checkbox-marked-circle-outline");
    });
    $(document).on('click', '.bandeira', function(e){
      e.preventDefault();
      $("input[name='bandeira']").removeAttr('checked');
      $(".bandeira").removeClass("active");
      $(this).children('input').attr('checked', 'checked');
      $(this).addClass("active");
    });
    $(document).on('click', '.radio-padrao', function(e){
      e.preventDefault();
      $("#padrao-form").attr("action", $(this).attr("route"));
      $("#padrao-form").submit();
    });
    $(document).on("submit", ".ContaForm", function(e) {
      e.preventDefault();
      var tab = "#"+$(this).closest('.tab-pane').attr("id");
      $(".se-pre-con").fadeIn();
      var url = $(this).attr("action");
      var get = $(this).attr("method");
      // var data = $(this).serializeArray();
      // var data = new FormData(this);
      var data = new FormData($(this)[0]);
      $.ajax({
        url: url,
        type: get,
        data: data,
        cache:false,
        contentType: false,
        processData: false,
        success: function(data){
          $(".has-error").removeClass("has-error").children(".help-block").last().html('');
          if(data.error){
            $.each(data.error , function( key, value ) {
              $(tab+" input[name='"+key+"']").parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
            });
          } else {
            $("#form-search-conta").submit();
            $("#div-crud").html("");
            $(".ativo").each(function(i, item){
              $(this).trigger("click");
            });
          }
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          $(".has-error").removeClass("has-error").children(".help-block").last().html('');
          console.log(data);
          $.each( data.responseJSON , function( key, value ) {
            $(tab+" input[name='"+key+"']").parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
          });
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
          if(data.error){
            $("body").append('<div id="del-alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>'+ data.error.conta +'</h3></div>');
          } else {
            $('.div-del').remove();
            var h =  $("body").innerHeight();
            h -= $(".content-header").innerHeight();
            h -= 62;
            $("#div-crud").html(data);
            $("#delete-novo").css("height", h);
            var route = $("#delete-form").attr("action");
            $('.btn-conta-delete').attr("action", route).trigger("click");

          }
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          $("#grid-table-body").html("Algo deu errado!!");
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          console.log(data);
          $(".se-pre-con").fadeOut();
        }
      });
    });
    $(document).on('click', '.btn-conta-transferencia', function(e){
      e.preventDefault();
      $('.btn-conta-transferencia').children('img').removeAttr( 'style' );
      $(this).children('img').css("border", '1px solid red');
      $("#conta_transferencia_id").val($(this).attr('rel'))
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
          $(".has-error").removeClass("has-error").children(".help-block").last().html('');
          if(data.error){
            $.each(data.error , function( key, value ) {
              $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
            });
          } else {
            $("#form-search-conta").submit();
            $("#div-crud").html("");
            $(".ativo").each(function(i, item){
              $(this).trigger("click");
            });
          }
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          $("#grid-table-body").html("Algo deu errado!!");
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          $(".has-error").removeClass("has-error").children(".help-block").last().html('');
          $.each( data.responseJSON , function( key, value ) {
            $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
          });
          $(".se-pre-con").fadeOut();
        }
      });
    });
    $(document).on("submit", "#form-conta-create", function(e) {
      e.preventDefault();
      var url = $(this).attr("action");
      var get = $(this).attr("method");
      var tipo = $(this).attr("tipo");
      var data = $(this).serializeArray();
      $.ajax({
        url: url,
        type: get,
        data: data,
        success: function(data){
          $("#div-crud").html(data);
          $('.form-crud, .ul-banco').scrollbar({ "scrollx": "none", disableBodyScroll: true });

          $("#banco_input").focus().select();
          $('#saldo_data').datetimepicker({
            locale: 'pt-BR',
            format: 'DD/MM/YYYY',
            defaultDate: 'moment',
            widgetPositioning: {
              horizontal: 'right',
              vertical: 'top'
            }
          });
          $('#saldo').maskMoney({ prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false, allowZero: true });
          $('#limite').maskMoney({ prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false, allowZero: true });
          $('#agencia').mask("#######0000", { reverse: true, placeholder:'N° da Agência: ex. 000000' });
          $(".content").animate({scrollTop: $("#div-crud").offset().top }, 1000);
          $('[data-toggle="popover"]').popover();

          if(tipo){
            $("#NovaConta .ca-slimbox[rel='"+tipo+"']").trigger("click");
          }
          resizediv();
        },
        error: function(data){
          $("#grid-table-body").html("Algo deu errado!!");
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          $(".btn-conta-create").trigger("click");
        }
      });
    });
    $(document).on("submit", "#form-search-conta", function(e) {
      e.preventDefault();
      var url = $(this).attr("action");
      var get = "GET";
      var data = $(this).serializeArray();
      $.ajax({
        url: url,
        type: get,
        data: data,
        success: function(data){
          $("#grid-table-body").html(data);
          resizeind();

          $(".grid-table").removeClass("hidden");
          @if (session('id'))
          $(".grid-table").addClass("hidden");
          var iddel = "{{ session('id') }}";
          var cat = "{{ session('cat') }}";
          var idcp = "{{ session('idcp') }}";
          $(".grid-"+idcp).removeClass("hidden");
          $(".del-"+iddel).trigger("click");
          $(".del-obs").html("Existe movimentação dependente dessa conta! Deseja realmente excluir assim mesmo?").addClass(cat);
          $("#delete_confirmar").val(1);
          @endif
          @if (session('child'))
          $.each({{ session('child') }}, function(i, item){
            if($(".del-"+this).parent().parent().prev().hasClass('delete-border')){
              $(".del-"+this).parent().parent().addClass("delete-border-botton");
            } else if($(".del-"+this).parent().parent().next().hasClass('delete-border')){
              $(".del-"+this).parent().parent().addClass("delete-border-top");
            } else if($(".del-"+this).parent().parent().first() && $(".del-"+this).parent().parent().last()){
              $(".del-"+this).parent().parent().addClass("delete-border-top");
            } else {
              $(".del-"+this).parent().parent().addClass("delete-border");
            }
          });
          @endif
          $(".se-pre-con").fadeOut();
        }
      });
    });

    $(document).on('keyup', '#banco_input', function() {
      var v = $(this).val();
      var rex = new RegExp($(this).val(), 'i');
      if(v.length == 0){
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children("li").show();
      } else {
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li').hide();
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li').filter(function () {
          return rex.test($(this).text());
        }).show();
      }
    });

    $(document).on('focusout', '#banco_input', function(e) {
      e.preventDefault();
      if($(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').length == 1){
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').children('a').trigger("click");
      }
    });
    $(document).on('focusin', '#banco_input', function(e) {
      e.preventDefault();
      $(this).select();

      var v = $(this).val();
      var rex = new RegExp($(this).val(), 'i');
      if(v.length == 0){
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children("li").show();
      } else {
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li').hide();
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li').filter(function () {
          return rex.test($(this).text());
        }).show();
      }
      if($(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').length == 1){
        $(this).parent().parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').children('a').trigger("click");
      }
    });
    $(document).on('click', '.btn-banco', function(e) {
      e.preventDefault();
      var te = $(this).text();
      var re = $(this).attr('rel');

      var im = "{{ url('') }}/";
      $("#icone-img").prop("src", im+$(this).attr('img'));
      $("#banco_id").val(re);
      $("#banco_input").val(te);
      $(this).parent().parent().children('li').hide();
    });

    $(document).on('keyup keypress blur change', '#dia_vencimento', function(e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }else{
        if( $(this).val().length >= parseInt($(this).attr('maxlength')) && (e.which != 8 && e.which != 0)){
          return false;
        }
      }
    });

  </script>
  @endpush
