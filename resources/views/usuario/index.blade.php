@extends('layouts.admin')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="hidden-xs">{{ $title }}</h1>
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Usuário" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      @can('usuario_create')
      <a href="#" class="btn btn-default btn-usuario-create{{ (isset($qtde_usuario) && $qtde_usuario <= 0) ? ' hidden': null }}" id="btn-usuario-create" qtde="{{ (isset($qtde_usuario) && $qtde_usuario <= 0) ? $qtde_usuario: null }}" route="{{ route('usuario.create') }}" data-toggle="tooltip" title="Adicionar Usuário" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>{{ $qtde_usuario }}</a>
      @endcan
      <div style="width: 5px;display: inline-block;"></div>
      @can('config_perfil_read')
      <a href="{{ route('configPer.perfil.index') }}" class="btn btn-default" data-toggle="tooltip" title="Perfis" data-placement="bottom"><i class="mdi mdi-account-key mdi-20px"></i></a>
      @endcan
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" style="color:#2196f3" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
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
  <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('usuario.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="nome" sort="asc" >NOME</a></div>
      <div class="col-sm-2 col-xs-2"><a href="#" class="order" order="cpf" sort="asc" >CPF</a></div>
      <div class="col-sm-2 col-xs-2"><a href="#" class="order" order="perfil" sort="asc" >PERFIL</a></div>
      <div class="col-sm-3 col-xs-3"><a href="#" class="order" order="email" sort="asc">E-MAIL</a></div>
      <div class="col-sm-2 col-xs-2">TELEFONE</div>
    </div>
    <div id="grid-table-body" class="scrollbar-inner">
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" id="div-crud">
  </div>
  <form id="delete-form" action="" method="POST" class="hidden">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <input type="hidden" id="acao" name="acao" value="">
  </form>
  <form id="form-usuario-create" action="" class="hidden" method="GET">
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
<script type="text/javascript" src="{{ asset('js/usuario.js') }}"></script>
@endpush
