@extends('layouts.admin')

@push('style')
<style type="text/css">

  .grid-table:nth-child(odd) {
    background-color: #fbfbfb;
  }

  .grid-table:hover .perfil  {
    display: none;
  }
  .grid-table:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }

  #btn-perfil-tools {
    right: 10px;
    width: 76px;
    height: 50px;
    float: right!important;
  }

  #content-central, #content-right {
    height: calc(100% - 59px);
    overflow: hidden!important;
  }

  .pagination-bottom {
    bottom: -50px!important;
  }

  .list-group-horizontal li.active {
    background: #fafafa;
  }
  .list-group-horizontal {
    margin-bottom: 0px;
  }

  #grid-table-header {
    border: 1px solid #eeeeee;
    border-right: 0;
    border-bottom: 1px solid #8BC34A;
  }

  #btn-tools {
    text-align: right;
    margin-right: 20px;
  }

  #btn-search {
    margin-right: 5px;
  }

  .btn-perfil-create{
    color: green;
  }

  .input-group-addon {
    padding: 9px 0px;
    color: #555;
    background-color: #fff;
    border: 1px solid #fff;
    border-radius: 0;
  }

  .grid-table > div {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .grid-table > div:first-child {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: initial;
    text-overflow: initial;
  }
  .btn-success{
    margin-bottom: 15px!important;
  }
  #btn-perfil-tools .active{
    color: #8BC34A;
  }
  .form-group {
    margin-bottom: 15px;
    margin-top: 5px;
  }

  .btn-perfil-create {
    margin-right: 5px!important;
  }
</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 class="hidden-xs">{{ $title }}</h1> 
  <div class="input-group pull-right hidden-print " id="btn-tools">
    <div class="input-group-btn">
      @can('config_perfil_read')
      <a href="{{ route('configPer.perfil.index') }}" class="btn btn-default" data-toggle="tooltip" title="Perfis" data-placement="bottom"><i class="mdi mdi-account-key mdi-20px"></i></a>
      @endcan
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
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
  

</section>
<!-- /.content -->
@endsection

@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/print.css') }}"/>
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
@endpush