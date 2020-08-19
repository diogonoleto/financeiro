@extends('layouts.admin')
@push('style')
<style type="text/css">
  #btn-tools-delete-all {
    padding-top: 6.5px;
  }
</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1> 
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      <a href="#" class="btn-tipo btn btn-default" tipo="2" data-toggle="tooltip" title="Clientes" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
      <a href="#" class="btn-tipo btn btn-default" tipo="1" data-toggle="tooltip" title="Funcionarios" data-placement="bottom"><i class="mdi mdi-worker mdi-20px"></i></a>
      <a href="#" class="btn-tipo btn btn-default active" tipo="" data-toggle="tooltip" title="Todos" data-placement="bottom"><i class="mdi mdi-view-headline mdi-20px"></i></a>

      <div id="del-group"></div>
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Contato" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      <a href="{{ route('usuario.create') }}" class="btn btn-default btn-plus" ><i class="mdi mdi-account-plus mdi-20px" style="color: green;"></i></a>
      <a href="#" class="btn btn-default" id="btn-grid-table"><i class="mdi mdi-view-module mdi-20px"></i></a>
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-xs-12 hidden">
    <form id="form-search" action="{{ route('usuario.lista') }}">
      {!! method_field('GET') !!}
      {!! csrf_field() !!}
      <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
    </form>
  </div>
  <div class="col-xs-12 no-padding" id="grid-table-header">
    <div style="width: 40px;padding: 0px  5px 0px;float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox" id="checkbox-all"></i></div>
    <div class="col-sm-3 col-xs-4"><a href="#" class="order" order="nome" sort="ASC" >NOME</a></div>
    <div class="col-sm-3 hidden-xs"><a href="#" class="order" order="cargo" sort="ASC">CARGO</a></div>
    <div class="col-sm-3 hidden-xs"><a href="#" class="order" order="email" sort="ASC">E-mail</a></div>
    <div class="col-sm-3 col-xs-4" style="width: calc(25% - 40px);">TELEFONE</div>
  </div>
  <div id="grid-table-body">
  </div>
  <form id="delete-form" action="" method="POST" style="display: none;">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
  </form>
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">
  $(document).on('click', '.btn-tools-edit, .btn-plus, .btn-show', function(e){
    $(".se-pre-con").fadeIn();
  });
  $(document).on('click', '#checkbox-all', function(e){
  });
  $(document).on('click', '.btn-tipo', function(e){
    e.preventDefault();
    $('.btn-tipo').removeClass("active");
    $('.tooltip').remove();
    var url = $("#form-search").attr("action");
    var get = "GET";
    var tipo = $(this).attr("tipo");
    $.ajax({
      url: url+"?tipo="+tipo,
      type: get,
      success: function(data){
        $("#grid-table-body").html(data);
      },
    });
  });
</script>
@endpush