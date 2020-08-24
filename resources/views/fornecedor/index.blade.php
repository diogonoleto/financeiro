@extends('layouts.admin')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="hidden-xs">{{ $title }}</h1>
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Fornecedor" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      @can('fornecedor_create')
      <a href="#" class="btn btn-default btn-empresa-create{{ (isset($qtde_empresa) && $qtde_empresa <= 0) ? ' hidden': null }}" id="btn-empresa-create" qtde="{{ (isset($qtde_empresa) && $qtde_empresa <= 0) ? $qtde_empresa: null }}" route="{{ route('fornecedor.create') }}" data-toggle="tooltip" title="Adicionar Fornecedor" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>{{ $qtde_empresa }}</a>
      @endcan
      <div style="width: 5px;display: inline-block;"></div>
      @can('empresa_read')
      <a href="{{ route('empresa.index') }}" class="btn btn-default" data-toggle="tooltip" title="Empresas" data-placement="bottom"><i class="mdi mdi-store mdi-20px"></i></a>
      @endcan
      @can('cliente_read')
      <a href="{{ route('cliente.index') }}" class="btn btn-default" data-toggle="tooltip" title="Clientes" data-placement="bottom"><i class="mdi mdi-account-box mdi-20px"></i></a>
      @endcan
      @can('fornecedor_read')
      <a href="{{ route('fornecedor.index') }}" style="color:#2196f3" class="btn btn-default" data-toggle="tooltip" title="Fornecedores" data-placement="bottom"><i class="mdi mdi-truck mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('fornecedor.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
      <div class="col-sm-3 col-xs-6"><a href="#" class="order" order="razao_social" sort="asc">RAZÃO SOCIAL</a></div>
      <div class="col-sm-2 col-xs-6"><a href="#" class="order" order="nome_fantasia" sort="asc">NOME FANTASIA</a></div>
      <div class="col-sm-2 col-xs-4"><a href="#" class="order" order="cnpj" sort="asc">CNPJ/CPF</a></div>
      <div class="col-sm-3 col-xs-4"><a href="#" class="order" order="email" sort="asc">E-MAIL</a></div>
      <div class="col-sm-2 col-xs-4">TELEFONE</div>
    </div>
    <div id="grid-table-body">
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" id="div-crud">
  </div>
  <form id="delete-form" action="" method="POST" class="hidden">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <input type="hidden" id="acao" name="acao" value="">
  </form>
  <form id="form-empresa-create" action="" class="hidden" method="GET">
    {{ csrf_field() }}
  </form>
</section>
@endsection
@push('link')
<link rel="stylesheet" href="{{ asset('css/empresa.css') }}"/>
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
@endpush
@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/confirmation/bootstrap-confirmation.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/valida_cpf_cnpj.js') }}"></script>
<script type="text/javascript">
  var urlCEP = "{{ route('empresa.getCEP') }}";
</script>
<script type="text/javascript" src="{{ asset('js/empresa.js') }}"></script>
@endpush
