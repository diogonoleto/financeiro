@extends('layouts.admin')
@push('link')
<!-- <link rel="stylesheet" href="{{ asset('plugins\iCheck\skins\square\green.css') }}"> -->
<style type="text/css">
  .pagination-bottom {
    bottom: -50px!important;
  }

  .list-group-horizontal li.active {
    background: #fafafa;
  }
  #grid-table-header {
    border: 1px solid #eeeeee;
    border-right: 0;
    border-bottom: 1px solid #8BC34A;
  }

  #grid-table-body > div > div > div {
    padding: 17px;
    font-size: 18px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .grid-table > div {
    border-bottom: 2px dotted #fafafa;
    padding: 13px 17px!important;
    font-size: 16px!important;
  }
  h4 {
    padding-left: 15px;
    padding-top: 20px;
    padding-bottom: 20px;
    color: #8BC34A;
    margin: 0;
    z-index: 3;
  }
  .tools-subcategoria, .tools-categoria {
    position: absolute;
    top: 0px;
    right: 15px;
    width: 130px;
    height: 45px;
  }
  .categorias a.btn-delete {
    line-height: 61px;
    height: 61px;
    width: 150px;
  }
  .grid-table a.btn-delete {
    line-height: 44px;
    height: 44px;
    width: 100px;
  }


  a.btn-categoria-create, a.btn-subcategoria-create, a.btn-categoria-delete {
    border: 1px solid #ccc;
  }
  .tools-subcategoria a {
    display: none;
    float: right;
    margin-right: 5px;
    width: 34.2px;
    text-align: center;
    height: 35px;
    margin-top: 5px;
    padding: 5px 0;
  }
  .grid-table:hover .tools-subcategoria a, .grid-table:hover .tools-categoria a{
    display: inline-block;
    z-index: 10;
  }
  .categorias div:hover + .tools-categoria a, .tools-categoria:hover a {
    display: inline-block;
    z-index: 10;
  }
  .grid-table:hover .pdv  {
    display: none;
  }
  .content{
    overflow-y: auto;
  }
  .btn-subcategoria-create, .btn-categoria-delete, .btn-categoria-create, .btn-categoria-edit {
    display: none;
    float: right;
    right: 15px;
    margin-top: 15px;
    margin-left: 5px;
    padding: 4px 4px 1px;
  }
  .form-control{
    border-top: 0px;
    border-left: 0px;
    border-right: 0px;
    box-shadow: none;
  }

  #btn-categoria-salvar, #btn-categoria-cancelar {
    padding: 16px 18px;
    margin-bottom: 10px;
  }
  .btn-subcategoria-salvar, .btn-subcategoria-cancelar {
    padding: 10px 8px;
  }
  .text-right {
    padding-right: 50px!important;
  }
  .nova-subcategoria {
    margin-top: 20px;
  }
  .form-group {
    margin-bottom: 10px;
    margin-top: 25px;
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

  .btn-categoria-edit:hover {
    color: blue!important;
    background-color: #fff;
    border: 1px solid blue!important;
  }

  .tools-categoria a, .tools-subcategoria a {
    color: #546E7A;
  }


  hr{
    width: 100%;
    margin: 0;
  }
  label{
    font-size: 19px;
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
</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1> 
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      

      <a href="#" class="btn btn-default" id="btn-categoria-create" data-toggle="tooltip" data-placement="bottom" title="Criar Categoria"><i class="mdi mdi-plus mdi-20px"></i></a>
      <a href="#" class="btn btn-default" id="btn-search-local" data-toggle="tooltip" data-placement="bottom" title="Pequisar Itens"><i class="mdi mdi-magnify mdi-20px" aria-hidden="true"></i></a>

      <a href="{{ route('produto.index') }}" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Produtos"><i class="mdi mdi-archive mdi-20px"></i></a>
      <a href="{{ route('produto.categoria.index') }}" class="btn btn-default active" data-toggle="tooltip" data-placement="bottom" title="Produto Categoria"><i class="mdi mdi-tag-outline mdi-20px" style="color: brown;"></i></a>

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
      <div class="col-xs-12 no-padding" id="grid-table-header">
        <div class="col-sm-6 col-xs-12"><a href="#" class="order" order="descricao" sort="ASC" >Nome</a></div>
        <div class="col-sm-3 hidden-xs"><a href="#" class="order" order="categoria_nome" sort="ASC">Utilizar no PDV?</a></div>
        <div class="col-sm-3 col-xs-4">Nivel</div>
      </div>
      <div class="col-xs-12 hidden" id="form-nova-categoria" >
        <form id="form-categoria" action="{{ route('produto.categoria.store') }}" method="POST">
          <div class="col-sm-12 nova-categoria no-padding">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control uppercase" name="nome" id="categoria_nome">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="categoria_pdv">Utilizar no PDV?</label>
                <select id="categoria_pdv" name="pdv" class="form-control">
                  <option value="1">SIM</option>
                  <option value="0">NÃO</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="categoria_nivel">Nível</label>
                <select id="categoria_nivel" name="nivel" class="form-control">
                  <option value="1">Nivel01</option>
                  <option value="2">Nivel02</option>
                  <option value="3">Nivel03</option>
                  <option value="4">Nivel04</option>
                </select>
              </div>
            </div>
            <div class="col-sm-12 text-right">
              {!! csrf_field() !!}
              <div id="method"></div>
              <input type="hidden" name="tipo" id="categoria_tipo">
              <input type="hidden" name="produto_categoria_id" id="categoria_produto_categoria_id">
              <a href="#" class="btn btn-default" id="btn-categoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a>
              <a href="#" class="btn btn-default" id="btn-categoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a>
            </div>
            <hr>
          </div>
        </form>
      </div>
      <div id="grid-table-body">
      </div>
      <form id="create-form" action="" method="GET" style="display: none;">
        {{ method_field('GET') }}
        {{ csrf_field() }}
      </form>
      <form id="delete-form" action="" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
      </form>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">

  listaGet();

  $(document).on('click', '.btn-delete', function(e){
    $(".se-pre-con").fadeIn();
  });

  $(document).on('click', '#btn-categoria-create', function(e){
    e.preventDefault();
    $("#categoria_tipo").val('PRINCIPAL');
    $("#form-categoria").attr("action", "{{ route('produto.categoria.store') }}");
    $("#method").html("");

    $(".nova-subcategoria").remove();
    $(".categorias, .grid-table").removeClass("hidden");
    $("#subcategoria_nome").val("");

    $("#form-nova-categoria").removeClass("hidden");
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $(".grid-table, .btn-subcategoria-create").addClass("hidden");
    $("#categoria_nome").val("").focus();
    $("#categoria_produto_categoria_id").val("");
    $("#input-search-local").parent().addClass("hidden");
    $("#input-search-local").val("");
    $('.categorias').show();
  });


  $(document).on('click', '.btn-categoria-edit', function(e){
    e.preventDefault();
    $("#categoria_tipo").val('PRINCIPAL');
    $("#form-categoria").attr("action", $(this).attr('route'));
    $("#method").append('<input type="hidden" name="_method" value="PUT">');
    $("#categoria_tipo").val($(this).attr('tipo'));
    $("#categoria_nome").val($(this).attr('nome'));

    $(".nova-subcategoria").remove();
    $(".categorias, .grid-table").removeClass("hidden");
    $("#subcategoria_nome").val("");

    $("#form-nova-categoria").removeClass("hidden");
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $(".grid-table, .btn-subcategoria-create").addClass("hidden");


    $("#categoria_produto_categoria_id").val("");
    $("#input-search-local").parent().addClass("hidden");
    $("#input-search-local").val("");
    $('.categorias').show();
    $("#categoria_nome").select();
  });

  $(document).on('keyup', '#categoria_nome', function() {
    var v = $('#categoria_nome').val();
    var rex = new RegExp($(this).val(), 'i');
    // $(".btn-pedido-clean-input").children().addClass("b-show");
    if(v.length == 0){
      $('.categorias').show();
      // $(".btn-pedido-clean-input").children().removeClass("b-show");
    } else {
      $('.categorias').hide();
      $('.categorias').filter(function () {
        return rex.test($(this).text());
      }).show();
    }
  });


  $(document).on('click', '#btn-categoria-cancelar', function(e){
    e.preventDefault();
    $("#form-nova-categoria").addClass("hidden");
    $(".grid-table, .btn-subcategoria-create").removeClass("hidden");
    $("#categoria_nome").val("");
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
        }
      },
      error: function(data){
        $.each( data.responseJSON , function( key, value ) {
          $("#categoria_"+key+", #subcategoria_"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
        });
      }
    });
  });

  $(document).on('click', '.btn-subcategoria-create', function(e){
    e.preventDefault();
    $("#form-categoria").attr("action", "{{ route('produto.categoria.store') }}");
    $(".categorias").addClass("hidden");
    var id = $(this).attr("rel");
    $("#categoria_produto_categoria_id").val(id);
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $("#categoria_tipo").val("SUBCATEGORIA");
    $(".nova-subcategoria").remove();
    $(this).addClass("hidden").parent().parent().removeClass("hidden").children("hr").last().append('<div class="col-sm-12 nova-subcategoria"><div class="col-sm-6"><div class="form-group"><label for="nome">Nome</label><input type="text" class="form-control" id="subcategoria_nome"><span class="help-block"></span></div></div><div class="col-sm-3"><div class="form-group"><label for="subcategoria_pdv">Utilizar no PDV?</label><select id="subcategoria_pdv" name="subcategoria_pdv" class="form-control"><option value="1">SIM</option><option value="0">NÃO</option></select></div></div><div class="col-sm-3"><div class="form-group"><label for="subcategoria_nivel">Nível</label><select id="subcategoria_nivel" name="subcategoria_nivel" class="form-control"><option value="1">Nivel01</option><option value="2">Nivel02</option><option value="3">Nivel03</option><option value="4">Nivel04</option></select></div></div><div class="col-sm-12 text-right"><a href="#" class="btn btn-default btn-subcategoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a> <a href="#" class="btn btn-default btn-subcategoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a></div><hr></div>');
  });
  $(document).on('click', '.btn-subcategoria-edit', function(e){
    e.preventDefault();
    $(".categorias").addClass("hidden");
    var id = $(this).attr("rel");
    $("#categoria_produto_categoria_id").val(id);
    $("#form-categoria").attr("action", $(this).attr('route'));
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $("#categoria_tipo").val("SUBCATEGORIA");
    $(".nova-subcategoria").remove();
    $(this).parent().parent().removeClass("hidden").children("hr").last().append('<div class="col-sm-12 nova-subcategoria"><div class="col-sm-6"><div class="form-group"><label for="nome">Nome</label><input type="text" class="form-control" id="subcategoria_nome"><span class="help-block"></span></div></div><div class="col-sm-3"><div class="form-group"><label for="subcategoria_pdv">Utilizar no PDV?</label><select id="subcategoria_pdv" name="subcategoria_pdv" class="form-control"><option value="1">SIM</option><option value="0">NÃO</option></select></div></div><div class="col-sm-3"><div class="form-group"><label for="subcategoria_nivel">Nível</label><select id="subcategoria_nivel" name="subcategoria_nivel" class="form-control"><option value="1">Nivel01</option><option value="2">Nivel02</option><option value="3">Nivel03</option><option value="4">Nivel04</option></select></div></div><div class="col-sm-12 text-right"><a href="#" class="btn btn-default btn-subcategoria-cancelar"><i class="mdi mdi-close"></i> Cancelar</a> <a href="#" class="btn btn-default btn-subcategoria-salvar"><i class="mdi mdi-content-save"></i> Salvar</a></div><hr></div>');
  });
  $(document).on('click', '.btn-subcategoria-cancelar', function(e){
    e.preventDefault();
    $(".nova-subcategoria").remove();
    $(".categorias, .btn-subcategoria-create, .grid-table").removeClass("hidden");
    $("#subcategoria_nome").val("");
  });
  $(document).on('click', '.btn-subcategoria-salvar', function(e){
    var cn = $("#subcategoria_nome").val().toLowerCase();
    cn = cn.substr(0,1).toUpperCase()+cn.substr(1).toLowerCase();
    $("#categoria_nome").val(cn);

    $( "select#subcategoria_nivel option:selected" ).each(function() {
      console.log("ni"+$(this).val());
      $("#categoria_nivel").val($(this).val());
    });

    $( "select#subcategoria_pdv option:selected" ).each(function() {
      console.log("pdv"+$(this).val());
      $("#categoria_pdv").val($(this).val());
    });

    $("#form-categoria").submit();
  });
  $(document).on('keyup', '#subcategoria_nome', function() {
    var v = $('#subcategoria_nome').val();
    var rex = new RegExp($(this).val(), 'i');
    // $(".btn-pedido-clean-input").children().addClass("b-show");
    if(v.length == 0){
      $('#grid-table-body .grid-table').show();
      // $(".btn-pedido-clean-input").children().removeClass("b-show");
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
    // $(".btn-pedido-clean-input").children().addClass("b-show");
    if(v.length == 0){
      $('.categorias').show();
      // $(".btn-pedido-clean-input").children().removeClass("b-show");
    } else {
      $('.categorias').hide();
      $('.categorias').filter(function () {
        return rex.test($(this).text());
      }).show();
    }
  });

  function listaGet(){
    $.ajax({
      url: "{{ route('produto.categoria.lista') }}",
      type: "GET",
      success: function(data){
        $("#grid-table-body").html(data);
      }
    });
  }

</script>
@endpush