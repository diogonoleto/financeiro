@extends('layouts.admin')
@push('link')
<style type="text/css">

input::-webkit-input-placeholder{
  text-align: left;
}

span.del-obs {
  border-left: 1px solid #eee;
  white-space: initial;
  background-color: #fff!important;
  position: absolute;
  color: red;
  margin-top: -41px;
  padding: 10px 10px 10px 10px;
  right: 215px;
  text-align: left;
  width: 412px;
  height: 41px;
  top: initial;
}

.desc {
  color: #607d8b;
  margin-right: 30px;
  font-size: 11px;
  white-space: pre-line;
  text-align: justify;
}

.btn-delete {
  top: initial;
  margin-top: -41px;
  line-height: 23px;
  height: 41px;
}
.btn-delete-nao {
  top: initial;
  margin-top: -41px;
  line-height: 23px;
  height: 41px;
}

ul{
  margin-bottom: 0;
}

li{
  list-style: none;
}

li a {
  padding: 10px;
}

h4 {
  padding-left: 10px;
  padding-top: 15px;
  padding-bottom: 5px;
  text-transform: uppercase;
  margin: 0;
}
.groupReceita {
  color: #8BC34A;
  border-bottom: 1px solid #8BC34A!important;
}
.groupDespesa {
  color: #ff5252;
  border-bottom: 1px solid #ff5252!important;
}

.div-del-cat .div-cat {
  line-height: 1.4;
  padding: 10px;
}

.div-del-sub .div-cat {
  line-height: initial!important;
  padding: 4px 10px!important;
}

.categorias a.btn-delete, .categorias a.btn-delete-nao {
  line-height: 61px;
  height: 61px;
  width: 100px;
}

.grid-table a.btn-delete, .grid-table a.btn-delete-nao {
  line-height: 43px;
  height: 43px;
  width: 100px;
}

a.btn-categoria-create, a.btn-subcategoria-create, a.btn-categoria-delete {
  border: 1px solid #ccc;
}

a.btn-categoria-create, a.btn-subcategoria-create {
  margin-right: 10px;
}

.menubar-item:hover a:hover + .tools-categoria a, .tools-categoria:hover a {
  display: inline-block;
}

.menubar-item:hover a:hover + .tools-categoria.not-active a, .tools-categoria.not-active:hover a {
  display: none!important;
}


.grid-table:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}
.tools-categoria {
  right: 10px;
  width: 217px;
  position: absolute;
  height: 32px;
  float: right;
  z-index: 1;
  margin-top: -37px;
}
.btn-subcategoria-create, .btn-categoria-delete, .btn-categoria-create, .btn-subcategoria-edit, .btn-sort, .btn-dre-edit {
  display: none;
  margin-left: 5px;
  padding: 4px 4px 1px;
}

.form-control{
  border-top: 0px;
  border-left: 0px;
  border-right: 0px;
  box-shadow: none;
}

.form-group {
  margin-bottom: 10px;
  margin-top: 20px;
}

.btn-subcategoria-create:hover {
  color: green!important;
  background-color: #fff;
  border: 1px solid green!important;
}
.btn-categoria-delete:hover {
  color:#d20e00!important;
  background-color: #fff;
  border: 1px solid #d20e00!important;
}

.tools-categoria a, .tools-subcategoria a {
  color: #546E7A;
}
hr{
  width: 100%;
  margin: 0;
}
label{
  margin-top: -15px;
  left: 15px;
}

#input-search-local {
  font-size: 36px;
  height: 60px;
  border: 0;
  box-shadow: none;
  font-family: 'Roboto', sans-serif;
  font-weight: 100;
  text-shadow: none;
}
#btn-search-local {
  margin-right: 5px;
}
.msg-alert{
  width: 50%;
  margin: auto;
  position: absolute;
  left: 0;
  right: 0;
  top: 16px;
  z-index: 100;
}
.alert-success {
  border-color: #3c763d;
  background-color: #f0ffea;
}
.alert-danger {
  border-color: #f0c36d;
}

#dre-edit {
  background-color: rgba(255, 255, 255, 0.7);
  position: fixed;
  z-index: 1;
  left: 0;
  right: -80px;
  top: 60px;
  bottom: 0;
}
#dre-edit form{
  border: 1px solid #8BC34A;
  margin-top: 5px;
  background-color: #fff;
  position: fixed;
  margin: auto;
  left: 0;
  right: -80px;
  top: 0;
  bottom: 0;
  width: 450px;
  height: 252px;
  z-index: 4;
}

.menubar {
  padding: 0px;
}
.menubar .menubar-item, .nova-categoria{
  border-bottom: 1px solid  #8bc34a75!important;
}
/*.menubar .menubar-item, .menubar .submenu-item {
  display: grid;
  grid-template-columns: auto auto minmax(220px, 220px);
  line-height: 44px;
}
a.menu-dropdown, a.submenu-target {
  grid-column: 1 / span 2;
}
.submenu.collapse.in {
  grid-column: 1 / span 3;
}

.nova-subcategoria {
  grid-column: 1 / span 3;
  }*/
  .menubar .menubar-item, .menubar .menubar-target {
    font-size: 15px;
    font-weight: 400;
    background-color: #fff;
    display: block;

  }
  .menubar-item {
    position: relative;
    display: inline-block;
    cursor: pointer;
  }
  .menu-dropdown {
    background-color: #fff;
    border-left: 1px solid #8BC34A;
    margin-left: -1px;
  }
  .submenu-item:last-child .menu-dropdown {
   margin-left: 0px;
 }
 .submenu-item:last-child .menu-dropdown.collapsed {
   border-left: 0;
   margin-left: 1px;
 }
 .menubar .submenu-item {
  border-bottom: 1px dotted #8BC34A;
  border-left: 1px solid #8BC34A;
  margin-left: -20px;
}
.menubar .submenu-item:last-child {
  border: 0;
}

.menu-dropdown,.menubar-target,.submenu-target {
  display: inline-table;
  white-space: nowrap;
  color: #000;
  width: 100%;
}
.submenu-target {
  padding-right: 10px;
  padding-left: 41px!important;
}
.waves-effect {
  position: relative;
  cursor: pointer;
  display: inline-block;
  overflow: hidden;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  vertical-align: middle;
  will-change: opacity,transform;
  -webkit-transition: all .3s ease-out;
  -o-transition: all .3s ease-out;
  transition: all .3s ease-out;
}
.submenu {
  background-color: #fbfbfb!important;
  margin-left: -15px;
  display: none;
}
.menu-dropdown.collapsed e:after {
  position: absolute;
  margin-left: 0px;
  margin-top: -16px;
  content: "\f496";
  font-family: MaterialDesignIcons;
}
/*.submenu-target:before {
  margin-left: -11px!important;
}
*/
.menubar-item >.waves-effect{
  margin-left: 31px!important;
}

/*.submenu-item >.waves-effect{
  padding-left: 31px!important;
  }*/
  e {
    width: 31px;
    display: inline-flex;
  }
  e:after {
    position: absolute;
    margin-left: 0px;
    margin-top: -16px;
    content: "\f408";
    font-family: MaterialDesignIcons;
  }
  .treeview .submenu-item:before {
    margin-left: 0px;
  }
  .menu-dropdown, .submenu-target {
    padding-left: 10px!important;
  }
  .submenu.collapse.in {
    margin-left: 0px;
    border-top: 1px solid #8BC34A!important;
  }
  .treeview .submenu .menu-dropdown:before, .treeview .submenu .submenu-target:before {
    content: "";
    position: absolute;
    width: 8px;
    height: 24px;
    margin-top: -13px;
    margin-left: -10px;
    border-bottom: 1px solid #8bc34a!important;
  }

  .nova-subcategoria:before {
    content: "";
    position: absolute;
    margin-top: 22px;
    width: 8px;
    margin-left: -10px;
    border-bottom: 1px solid #8bc34a!important;
  }

  .semul:before{
    margin-top: 0px;
  }

  .nova-subcategoria.edit:before{
    margin-top: 23px;
    margin-left: 0px;
  }

  .nova-subcategoria.edit{
    height: 44px;
  }

  .submenu .help-block {
    display: block;
    margin-top: 5px;
    margin-bottom: 5px;
    color: #737373;
  }

  .submenu input, .nova-subcategoria input{
    margin-top: 2px;
    background-color: transparent;
  }

  .nova-subcategoria a{
    margin-top: 5px;
  }
  .nova-subcategoria a:first-child{
    margin-right: 5px;
  }


  .not-active {
    pointer-events: none;
    cursor: default;
  }

/*  .ullc:before {
    position: relative;
    }*/

    .ullc .llc:before {
      content: "";
      position: absolute;
      margin-top: -10px;
      width: 8px;
      height: 24px;
      border-left: 1px solid #8bc34a;
      margin-left: -10px;
      border-bottom: 1px solid #8bc34a!important;
    }


  </style>
  @endpush

  @section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="hidden-xs">{{ $title }}</h1> 
    <div class="input-group pull-right" id="btn-tools">
      <div class="input-group-btn">
        @can('fin_movimento_create')
        <a href="#" class="btn btn-default" id="btn-categoria-create" data-toggle="tooltip" title="Adicionar Categoria" data-placement="bottom"><i class="mdi mdi-plus mdi-20px"></i></a>
        @endcan
        <a href="#" class="btn btn-default" id="btn-search-local" data-toggle="tooltip" title="Pesquisar Itens" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px" aria-hidden="true"></i></a>
        @can('fin_movimento_read')
        <a href="{{ route('movimento.index') }}" class="btn btn-default" data-toggle="tooltip" title="Movimentações" data-placement="bottom"><i class="mdi mdi-cash-multiple mdi-20px" ></i></a>
        @endcan
        @can('fin_categoria_read')
        <a href="{{ route('fin.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" title="Categorias" data-placement="bottom"><i class="mdi mdi-tag-outline mdi-20px" style="color: brown;"></i></a>
        @endcan
        @can('fin_conta_read')
        <a href="{{ route('conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas Bancárias" data-placement="bottom"><i class="mdi mdi-bank mdi-20px" aria-hidden="true"></i></a>
        @endcan
        @can('fin_relatorio_read')
        <a href="{{ route('fin.fdc.mensal') }}" class="btn btn-default" data-toggle="tooltip" title="Relatórios" data-placement="bottom"><i class="mdi mdi-library-books mdi-20px"></i></a>
        @endcan
      </div>
    </div>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="col-sm-12 col-xs-12 no-padding">
      <div class="col-sm-12 no-padding">
        <div class="col-xs-12 hidden">
          <input type="text" name="input-search-local" id="input-search-local" class="form-control" value="" autocomplete="off">
        </div>
        <form id="form-categoria" action="" method="POST" class="hidden">
          <div class="col-sm-12 nova-categoria no-padding">
            <div class="col-sm-7">
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="categoria_nome" autocomplete="off" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="tipo">Tipo</label>
                <select class="form-control" name="tipo" id="categoria_tipo" required>
                  <option value="Receita">Receita</option>
                  <option value="Despesa">Despesa</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3 text-right">
              <div class="form-group">
                {{ csrf_field() }}
                <div id="method"></div>
                <input type="hidden" name="categoria_id" id="categoria_id">
                <input type="hidden" name="descricao" id="categoria_descricao">
                <a href="#" class="btn btn-default" id="btn-categoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a>
                <a href="#" class="btn btn-success" id="btn-categoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a>
              </div>
            </div>
          </div>
        </form>
        <div id="grid-table-body" class="scrollbar-inner">
        </div>
        <form id="create-form" action="" method="GET" style="display: none;">
          {{ method_field('GET') }}
          {{ csrf_field() }}
        </form>
        <form id="delete-form" action="" method="POST" style="display: none;">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}
          <input type="hidden" name="delete_confirmar" id="delete_confirmar" value="0">
        </form>
      </div>
    </div>
  </section>
  <div class="hidden" id="dre-edit">
  </div>
  <div class="msg-alert"></div>
  <!-- /.content -->
  @endsection
  @push('link')
  <!-- <link rel="stylesheet" href="{{ asset('plugins\jquery-ui\jquery-ui.min.css') }}s"> -->
  @endpush
  @push('scripts')
  <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
  <script type="text/javascript" src="{{ asset('plugins\jquery-ui\jquery-ui.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      listaGet();
    });
    $(document).on('click', '.btn-delete', function(e){
      $(".se-pre-con").fadeIn();
    });
    $(document).on("click", ".menu-dropdown ", function(){
      if($(this).hasClass("collapsed")){
        $(this).parent().find('.collapse').collapse('hide');
      } else {
        $(this).parent().find('.collapse').collapse('toggle');
      }
    });
    $(document).on('click', '#btn-categoria-create', function(e){
      e.preventDefault();
      $("#form-categoria").attr("action", "{{ route('fin.categoria.store') }}");
      $("#method").html("");
      $("#categoria_tipo").parent().removeClass("hidden");
      $(".nova-subcategoria").remove();
      $("#subcategoria_nome, #subcategoria_descricao").val("");
      $("#form-categoria, .categorias").removeClass("hidden");
      $(".has-error").removeClass("has-error").children(".help-block").last().html('');
      $(".grid-table, .btn-subcategoria-create, .tools-categoria").addClass("hidden");
      $("#categoria_nome").val("").focus();
      $("#categoria_descricao").val("");
      $("#categoria_id").val("");
      $("#input-search-local").parent().addClass("hidden");
      $("#input-search-local").val("");
      $('.categorias').show();
      $("#grid-table-body").animate({ scrollTop: 0 }, 0);
    });
    $(document).on('keyup', '#categoria_nome', function() {
      var v = $('#categoria_nome').val();
      var rex = new RegExp($(this).val(), 'i');
      if(v.length == 0){
        $('.categorias').show();
      } else {
        $('.categorias').hide();
        $('.categorias').filter(function () {
          return rex.test($(this).text());
        }).show();
      }
    });
    $(document).on('click', '#btn-categoria-cancelar', function(e){
      e.preventDefault();
      $("#form-categoria").addClass("hidden");
      $(".grid-table, .btn-subcategoria-create, .tools-categoria, .tools-subcategoria").removeClass("hidden");
      $(".tools-categoria").removeClass("not-active");
      $('.categorias').show();
      $("#categoria_nome, #categoria_id").val("");
    });
    $(document).on('click', '#btn-categoria-salvar', function(e){
      e.preventDefault();
      $("#form-categoria").submit();
    });
    $(document).on("submit", "#form-categoria", function(e) {
      e.preventDefault();
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
              $("#categoria_"+key+", #subcategoria_"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
            });
          } else {
            listaGet();
            $("#btn-categoria-cancelar, .btn-subcategoria-cancelar").trigger("click");
            $(".has-error").removeClass("has-error").children(".help-block").last().html('');
            $(".msg-alert").css("display", "block").html('<div class="msg-alert alert alert-success" role="alert">Cadastro realizado com sucesso!!</div>').delay(1000).fadeOut(3000);
            setTimeout(function() {
              $('.msg-alert').html("");
            }, 5000);
          }
        },
        error: function(data){
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          $.each( data.responseJSON , function( key, value ) {
            $("#categoria_"+key+", #subcategoria_"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
          });
        }
      });
    });
    $(document).on('click', '.btn-subcategoria-create', function(e){
      e.preventDefault();
      $("#form-categoria").attr("action", "{{ route('fin.categoria.store') }}");
      $("#method").html("");
      var id = $(this).attr("rel");
      var tipo = $(this).attr("tipo");
      $("#categoria_id").val(id);
      $(".has-error").removeClass("has-error").children(".help-block").last().html('');
      $("#categoria_tipo").val(tipo);
      $(".nova-subcategoria").remove();
      $(".tools-categoria").addClass("not-active");
      var ul = $(this).parent().parent().children("ul");
      if(ul.length > 0){
        $(this).parent().parent().children("ul").addClass("in")
        .prepend('<li class="submenu-item nova-subcategoria">'+
          '<div class="col-xs-5"><input type="text" class="form-control" id="subcategoria_nome" placeholder="Nome"><span class="help-block"></span>'+
          '</div>'+
          '<div class="col-xs-4">'+
          '<input type="text" class="form-control" id="subcategoria_descricao" placeholder="Descrição">'+
          '</div>'+
          '<div class="col-xs-3 text-right">'+
          '<a href="#" class="btn btn-default btn-subcategoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a>'+
          '<a href="#" class="btn btn-success btn-subcategoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a>'+
          '</div>'+
          '</li>');
      } else {
        $(this).parent().parent().append('<ul class="submenu collapse in"><li class="submenu-item nova-subcategoria semul">'+
          '<div class="col-xs-5"><input type="text" class="form-control" id="subcategoria_nome" placeholder="Nome"><span class="help-block"></span>'+
          '</div>'+
          '<div class="col-xs-4">'+
          '<input type="text" class="form-control" id="subcategoria_descricao" placeholder="Descrição">'+
          '</div>'+
          '<div class="col-xs-3 text-right">'+
          '<a href="#" class="btn btn-default btn-subcategoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a>'+
          '<a href="#" class="btn btn-success btn-subcategoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a>'+
          '</div>'+
          '</li></ul>');
      }
      $("#subcategoria_nome").focus();
    });
    $(document).on('click', '.btn-subcategoria-edit', function(e){
      e.preventDefault();
      $("#form-categoria").attr("action", $(this).attr('route'));
      $("#method").html('<input type="hidden" name="_method" value="PUT">');
      $(".has-error").removeClass("has-error").children(".help-block").last().html('');
      $(".nova-subcategoria").remove();
      var no = $(this).parent().parent().children('a').attr('no');
      var de = $(this).parent().parent().children('a').attr('de');
      $(".tools-categoria").addClass("not-active");
      $(this).parent().prev().addClass("hidden");
      $(this).parent().before(
        '<div class="nova-subcategoria edit">'+
        '<div class="col-xs-5">'+
        '<input type="text" class="form-control" id="subcategoria_nome" placeholder="Nome" value="'+no+'">'+
        '<span class="help-block"></span>'+
        '</div>'+
        '<div class="col-xs-4">'+
        '<input type="text" class="form-control" id="subcategoria_descricao" placeholder="Descrição" value="'+de+'">'+
        '</div>'+
        '<div class="col-xs-3 text-right">'+
        '<a href="#" class="btn btn-default btn-subcategoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a> '+
        '<a href="#" class="btn btn-success btn-subcategoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a>'+
        '</div>'+
        '</div>'
        );
      $("#subcategoria_nome").focus();
    });
    $(document).on('click', '.btn-subcategoria-cancelar', function(e){
      e.preventDefault();
      $(".tools-categoria").removeClass("not-active");
      $(this).parent().parent().prev().removeClass("hidden").parent().removeClass("in");
      $(this).parent().parent().hasClass('semul') ? $(this).parent().parent().parent().remove() : $(this).parent().parent().remove();
      $('.grid-table, .categorias').show();
      $(".btn-subcategoria-create, .grid-table, .categorias, .tools-subcategoria, .tools-categoria").removeClass("hidden");
      $(".tools-categoria").removeClass("not-active");
      $("#subcategoria_nome, #subcategoria_descricao, #categoria_nome, #categoria_id, #descricao").val("");
      $("").val("");
    });
    $(document).on('click', '.btn-subcategoria-salvar', function(e){
      e.preventDefault();
      var cd = $("#subcategoria_descricao").val();
      var cn = $("#subcategoria_nome").val();
      $("#categoria_nome").val(cn);
      $("#categoria_descricao").val(cd);
      $("#form-categoria").submit();
    });
    $(document).on('keyup', '#subcategoria_nome', function() {
      var v = $('#subcategoria_nome').val();
      var rex = new RegExp($(this).val(), 'i');
      if(v.length == 0){
        $('#grid-table-body .grid-table').show();
      } else {
        $('#grid-table-body .grid-table').hide();
        $('#grid-table-body .grid-table').filter(function () {
          return rex.test($(this).text());
        }).show();
      }
    });
    $(document).on('click', '#btn-search-local', function(e){
      e.preventDefault();
      $("#input-search-local").parent().toggleClass("hidden");
      $("#input-search-local").val("").focus();
      $('.categorias').show();
      $("#btn-categoria-cancelar, .btn-subcategoria-cancelar").trigger("click");
    });
    $(document).on('keyup', '#input-search-local', function() {
      var v = $('#input-search-local').val();
      var rex = new RegExp($(this).val(), 'i');
      if(v.length == 0){
        $('.categorias').show();
      } else {
        $('.categorias').hide();
        $('.categorias').filter(function () {
          return rex.test($(this).text());
        }).show();
      }
    });
    $(document).on('click', '.btn-dre-edit', function(e) {
      e.preventDefault();
      $(".se-pre-con").fadeIn();
      var url = $(this).attr("route");
      $.ajax({
        url: url,
        type: 'GET',
        data: {"categoria_id" : $(this).attr("rel")},
        success: function(data){
          $("#dre-edit").html(data).removeClass("hidden");
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          console.log(data);
        }
      });
    });
    $(document).on("submit", "#form-dre", function(e) {
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
          $("#grid-table-body").html(data);
          fsortable();
          $(".btn-dre-cancelar").trigger("click");
          $(".has-error").removeClass("has-error").children(".help-block").last().html('');
          $(".msg-alert").css("display", "block").html('<div class="msg-alert alert alert-success" role="alert">Cadastro realizado com sucesso!!</div>').delay(1000).fadeOut(3000);
          setTimeout(function() {
            $('.msg-alert').html("");
          }, 5000);

          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          }
          $.each( data.responseJSON , function( key, value ) {
            $("#categoria_"+key+", #subcategoria_"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
          });
        }
      });
    });
    $(document).on('click', '.btn-dre-cancelar', function(e) {
      e.preventDefault();
      $("#dre-edit").addClass("hidden");
    });
    function listaGet(){
      $.ajax({
        url: "{{ route('fin.categoria.lista') }}",
        type: "GET",
        success: function(data){
          $("#grid-table-body").html(data);
          fsortable();
          $(".categorias").removeClass("hidden");
          @if(session('success'))
          @endif
          @if(session('id'))
          $(".categorias").addClass("hidden");
          var iddel = "{{ session('id') }}";
          var cat = "{{ session('cat') }}";
          var idcp = "{{ session('idcp') }}";
          $(".grid-"+idcp).removeClass("hidden");
          $(".del-"+iddel).trigger("click");
          $(".del-obs").html("Existe movimentações dependente dessa(s) categoria(s)! Deseja realmente excluir assim mesmo?").addClass(cat);
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
          $('#grid-table-body').scrollbar({ "scrollx": "none", disableBodyScroll: true });
          resizeind();
          $(".llc").parent().addClass("ullc");
          $(".se-pre-con").fadeOut();
        }
      });
    }
    function fsortable() {
      $(".menubar").sortable({
        handle: ".btn-sort",
        update: function( event, ui ) {
          var sort = [];
          $(".menubar-item").each(function (i, item){
            sort[i] = $(this).attr('rel');
          });
          sortup(sort);
        }
      });
    }
    function sortup(sort){
      $.ajax({
        url: "{{ route('fin.categoria.sortable') }}",
        type: "GET",
        data: {sort:sort},
        success: function(data){
          $(".msg-alert").css("display", "block").html('<div class="msg-alert alert alert-success" role="alert">As Categorias foram reordenas com sucesso!</div>').delay(5000).fadeOut(3000);
          setTimeout(function() {
            $('.msg-alert').html("");
          }, 5000);
          listaGet();
        }, error: function(data){
          if(data.status==404 || data.status==401) {
            location.reload();
          } else if (data.status==403){
            $("#grid-table-body").html("Você não tem acesso a essa informações!!");
          } 
        }
      });
    }
  </script>
  @endpush