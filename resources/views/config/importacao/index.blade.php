@extends('layouts.admin')
@push('style')
<style type="text/css">
.help-icon {
  margin: 5px;
}

.panel {
  margin-top: 10px;
}
.panel-body {
  padding: 0px 15px;
  line-height: 1.2;
}
.panel h2 {
  font-weight: 300;
  font-size: 20px;
  margin-top: 10px;
  margin-bottom: 0;
}

.panel span.detail {
  margin: 0;
  padding: 0 15px 5px;
  display: block;
  font-size: 1em;
  font-style: italic;
  font-weight: normal;
  color: #8d9096;
}

.content{
  overflow-y: auto;
  position: relative;
}

h4 {
  font-family: 'Roboto', sans-serif;
  font-weight: 100;
  font-size: 20px;
  margin-top: 5px;
  margin-bottom: 5px;
}
hr {
  margin-top: 0px;
  margin-bottom: 10px;
}
h3 {
  font-weight: 300;
  margin-right: 10px;
  margin-top: -10px;
  margin-bottom: 0px;
  padding-bottom: 0px;
}

.panel-footer {
  padding: 5px 15px 20px;
}


.grid-table > div {
  padding: 10.78px 10px;
  font-size: 16px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  border-left: 1px solid #fff;
  border-bottom: 1px solid #ddd;
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
      <a href="{{ route('importacao.index') }}" class="btn btn-default" style="color:#009688" data-toggle="tooltip" title="Importações" data-placement="bottom"><i class="mdi mdi-import mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">


  <div class="col-sm-12">
    <div class="col-sm-6">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo." data-original-title="Importação de categorias"></i>
        <!-- <div class="panel-body" style="min-height: 176.06px"> -->
        <div class="panel-body">
          <h2>Categorias</h2>
          <span class="detail account-name-detail">Importação de categorias</span>
          <div class="row hidden">
            <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
              <div class="col-sm-8 col-xs-6">NOME</a></div>
              <div class="col-sm-4 col-xs-6 text-center">DATA IMPORTAÇÃO</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">130 - Categoria de ReceitaDespesa</div>
              <div class="col-sm-4 col-xs-6 text-center">01/12/19</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">129 - Categoria de Receita</div>
              <div class="col-sm-4 col-xs-6 text-center">12/12/17</div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <a href="{{ route('imp.categoria.index') }}" class="btn pull-right btn-default btn-importacao-download">Importar</a>
          <a href="{{ url('doc/Planilha_Modelo_Categorias_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download" style="margin-right: 5px;margin-bottom: 10px;">Planilha Padrao</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 hidden">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo." data-original-title="Importação de contas"></i>
        <div class="panel-body" style="min-height: 176.06px">
          <h2>Contas</h2>
          <span class="detail account-name-detail">Importação de contas</span>

          <div class="row">
            <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
              <div class="col-sm-8 col-xs-6">NOME</div>
              <div class="col-sm-4 col-xs-6 text-center">DATA IMPORTAÇÃO</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">130 - Contas</div>
              <div class="col-sm-4 col-xs-6 text-center">01/12/19</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">129 - Poupanca</div>
              <div class="col-sm-4 col-xs-6 text-center">12/12/17</div>
            </div>
          </div>

        </div>
        <div class="panel-footer">
          <a href="{{ route('imp.movimento.index') }}" class="btn pull-right btn-default btn-importacao-download">Importar</a>
          <a href="{{ url('doc/Planilha_Modelo_Movimentacoes_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download" style="margin-right: 5px;margin-bottom: 10px;">Planilha Padrao</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo." data-original-title="Importação de movimentações"></i>
        <!-- <div class="panel-body" style="min-height: 176.06px"> -->
        <div class="panel-body">
          <h2>Movimentações</h2>
          <span class="detail account-name-detail">Importação de movimentações</span>

          <div class="row hidden">
            <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
              <div class="col-sm-8 col-xs-6">NOME</div>
              <div class="col-sm-4 col-xs-6 text-center">DATA IMPORTAÇÃO</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">130 - Movimentações de Receita e Despesa</div>
              <div class="col-sm-4 col-xs-6 text-center">01/12/19</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">129 - Movimentações de Receita</div>
              <div class="col-sm-4 col-xs-6 text-center">12/12/17</div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <a href="{{ route('imp.movimento.index') }}" class="btn pull-right btn-default btn-importacao-download">Importar</a>
          <a href="{{ url('doc/Planilha_Modelo_Movimentacoes_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download" style="margin-right: 5px;margin-bottom: 10px;">Planilha Padrao</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 hidden">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo." data-original-title="Importação de cientes e fornecedores"></i>
        <div class="panel-body" style="min-height: 176.06px">
          <h2>Clientes / fornecedores</h2>
          <span class="detail account-name-detail">Importação de cientes e fornecedores</span>

          <div class="row">
            <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
              <div class="col-sm-8 col-xs-6">NOME</div>
              <div class="col-sm-4 col-xs-6 text-center">DATA IMPORTAÇÃO</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">130 - Clientes</div>
              <div class="col-sm-4 col-xs-6 text-center">01/12/19</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">129 - Fornecedores</div>
              <div class="col-sm-4 col-xs-6 text-center">11/10/17</div>
            </div>
          </div>

        </div>
        <div class="panel-footer">
          <a href="{{ route('imp.movimento.index') }}" class="btn pull-right btn-default btn-importacao-download">Importar</a>
          <a href="{{ url('doc/Planilha_Modelo_Movimentacoes_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download" style="margin-right: 5px;margin-bottom: 10px;">Planilha Padrao</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 hidden">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo." data-original-title="Importação de cientes"></i>
        <div class="panel-body" style="min-height: 176.06px">
          <h2>Produtos</h2>
          <span class="detail account-name-detail">Importação de produtos</span>

          <div class="row">
            <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
              <div class="col-sm-8 col-xs-6">NOME</div>
              <div class="col-sm-4 col-xs-6 text-center">DATA IMPORTAÇÃO</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">130 - Produtos</div>
              <div class="col-sm-4 col-xs-6 text-center">15/12/19</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">129 - Produtos</div>
              <div class="col-sm-4 col-xs-6 text-center">12/12/17</div>
            </div>
          </div>

        </div>
        <div class="panel-footer">
          <a href="{{ route('imp.movimento.index') }}" class="btn pull-right btn-default btn-importacao-download">Importar</a>
          <a href="{{ url('doc/Planilha_Modelo_Movimentacoes_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download" style="margin-right: 5px;margin-bottom: 10px;">Planilha Padrao</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 hidden">
      <div class="panel panel-default">
        <i class="mdi mdi-help-circle help-icon" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo." data-original-title="Importação de cientes"></i>
        <div class="panel-body" style="min-height: 176.06px">
          <h2>Usuários</h2>
          <span class="detail account-name-detail">Importação de usuários</span>

          <div class="row">
            <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
              <div class="col-sm-8 col-xs-6">NOME</div>
              <div class="col-sm-4 col-xs-6 text-center">DATA IMPORTAÇÃO</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">130 - Usuários</div>
              <div class="col-sm-4 col-xs-6 text-center">01/12/19</div>
            </div>
            <div class="col-xs-12 no-padding grid-table">
              <div class="col-sm-8 col-xs-6">129 - Usuários</div>
              <div class="col-sm-4 col-xs-6 text-center">12/03/17</div>
            </div>
          </div>

        </div>
        <div class="panel-footer">
          <a href="{{ route('imp.movimento.index') }}" class="btn pull-right btn-default btn-importacao-download">Importar</a>
          <a href="{{ url('doc/Planilha_Modelo_Movimentacoes_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download" style="margin-right: 5px;margin-bottom: 10px;">Planilha Padrao</a>
        </div>
      </div>
    </div>
  </div>
  

</section>
<!-- /.content -->

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('plugins/jquery-knob/jquery.knob.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/chart/dist/Chart.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/chart/dist/utils.js') }}"></script>
<script type="text/javascript">


</script>
@endpush


