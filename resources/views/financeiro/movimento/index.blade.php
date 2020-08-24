@extends('layouts.admin')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 id="title" class="hidden-xs">{{ $title }}</h1>
  <div class="input-group pull-right hidden-print" id="btn-tools">
    <div class="input-group-btn">
      @can('fin_movimento_create')
      <a href="#" class="btn btn-default btn-movimento-create" id="btnmc" route="{{ route('movimento.create') }}" data-toggle="tooltip" title="Adicionar movimento" data-placement="left"><i class="mdi mdi-plus mdi-20px"></i></a>
      <ul class="dropdown-menu" role="menu" aria-labelledby="btnmc">
        <li><a href="#" class="btn-drop-movimento-create" tipo="Receita" route="{{ route('movimento.create') }}" >Adicionar Receita</a></li>
        <li class="divider"></li>
        <li><a href="#" class="btn-drop-movimento-create" tipo="Despesa" route="{{ route('movimento.create') }}" >Adicionar Despesa</a></li>
        <li class="visible-xs divider"></li>
        <li class="visible-xs"><a href="#" class="btn-drop-movimento-create" tipo="Despesa" route="{{ route('movimento.create') }}" >Cancelar</a></li>
      </ul>
      @endcan
      <a href="#" class="btn btn-default" id="btn-movimento-filtro" data-toggle="tooltip" title="Pesquisar Itens" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      @can('fin_movimento_read')
      <a href="{{ route('movimento.index') }}" class="btn btn-default" data-toggle="tooltip" title="Movimentações" data-placement="bottom"><i class="mdi mdi-cash-multiple mdi-20px" style="color: green;"></i></a>
      @endcan
      @can('fin_categoria_read')
      <a href="{{ route('fin.categoria.index') }}" class="btn btn-default" data-toggle="tooltip" title="Categorias" data-placement="bottom"><i class="mdi mdi-tag-outline mdi-20px"></i></a>
      @endcan
      @can('fin_conta_read')
      <a href="{{ route('conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas Bancárias" data-placement="bottom"><i class="mdi mdi-bank mdi-20px"></i></a>
      @endcan
      @can('fin_relatorio_read')
      <a href="{{ route('fin.fdc.mensal') }}" class="btn btn-default" data-toggle="tooltip" title="Relatórios" data-placement="bottom"><i class="mdi mdi-library-books mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div class="col-sm-12 no-padding" >
    <div class="col-sm-12 hidden-print" id="movimento-header">
      <div class="pull-right" id="btn-movimento-tools">
        <ul id="nav-calendar">
          <li>
            <a href="#" class="prne" rel="-1">
              <div></div>
              <div><</div>
              <div></div>
            </a>
          </li>
          <li><a href="#" class="mes mes-1"></a></li>
          <li><a href="#" class="mes mes-2"></a></li>
          <li class="active"><a href="#" class="mes mes-3" rel="0"></a></li>
          <li><a href="#" class="mes mes-4"></a></li>
          <li><a href="#" class="mes mes-5"></a></li>
          <li>
            <a href="#" class="prne" rel="1">
              <div></div>
              <div>></div>
              <div></div>
            </a>
          </li>
          <li><a href="#" class="btn btn-default tipo active" id="Extrato" data-toggle="tooltip" title="Extrato" data-placement="bottom">M</a>
          </li>
          @can('fin_receita_read')
          <li><a href="#" class="btn btn-default tipo" id="Receita" data-toggle="tooltip" title="Contas a Receber" data-placement="bottom">R</a></li>
          @endcan
          @can('fin_despesa_read')
          <li><a href="#" class="btn btn-default tipo" id="Despesa" data-toggle="tooltip" title="Contas a Pagar" data-placement="bottom">D</a></li>
          @endcan

          @can('fin_transferencia_create')
          <li><span style="margin-right: 5px;"></span><a href="#" class="btn btn-default btn-transferencia-create" route="{{ route('movimento.create') }}" data-toggle="tooltip" title="Transferência Bancária" data-placement="bottom" id="transf">T</a><span style="margin-right: 5px;"></span></li>
          @endcan
          <li class="hidden-xs">
            <a href="#" id="export" class="btn btn-default" data-route="{{ route('financeiro.movimento.exportar') }}" data-toggle="tooltip" title="Exportar" data-placement="bottom">E</a></
          </li>
          <li class="hidden-xs">
            <a href="#" id="print" data-toggle="tooltip" title="Imprimir" data-placement="bottom" class="btn btn-default">P</a>
          </li>
          <li class="hidden-xs">
            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btncol" class="btn btn-default"><i class="mdi mdi-view-column mdi-20px"></i></a>
            <ul class="dropdown-menu" aria-labelledby="btncol" id="ulcol" style="right: 10px;">
              <li><a href="#" id="ul-a-tipo" rel="tipo"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>TIPO</a></li>
              <li><a href="#" rel="data"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>DATA</a></li>
              <li><a href="#" rel="datp"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>DATA PAGAMENTO</a></li>

              <li><a href="#" rel="datc"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>DATA CADASTRO</a></li>

              <li><a href="#" rel="focl"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>REC. DE / PAGO A</a></li>
              <li><a href="#" rel="bagc"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>BANCO/AGENCIA/CONTA</a></li>
              <li><a href="#" rel="cimg"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>ICONE DO BANCO</a></li>
              <li><a href="#" rel="valo"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>VALOR</a></li>
              <li><a href="#" rel="vapa"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>VALOR PAGO</a></li>
              <li><a href="#" id="ul-a-saldo" rel="sald"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>SALDO</a></li>
              <li><a href="#" rel="stat"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>STATUS</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-xs-12 hidden" id="movimento-filtro">
      <form id="form-pesquisa" action="{{ route('financeiro.movimento.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <div class="mf hidden" id="mf-pesquisa">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Pesquisar:</div>
          </div>
          <div style="display: inline-block; width: 200px;" id="div-pesquisa">
            <input type="text" name="pesquisa" id="pesquisa" class="form-control" style="font-size: 12px; margin-top: 3px;height: 27px;background-color: #9adc4f; color: #fff; border: 1px solid #8BC34A;">
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-lanc">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Lançamentos:</div>
          </div>
          <div style="display: inline-block; width: 105px;">
            <ul class="ul" style="width: 105px;" id="flancamento">
              <li><a href="#" class="flancamento active" rel="todos">Todos <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="flancamento hidden" rel="pagos">Pagos <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="flancamento hidden" rel="npagos">Não pagos <i class="mdi mdi-chevron-down"></i></a></li>
            </ul>
            <input type="hidden" name="lancamento" id="lancamento">
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-tipo">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Tipo:</div>
          </div>
          <div style="display: inline-block; width: 90px; ">
            <ul class="ul" style="width: 90px;" id="ftipo">
              <li><a href="#" class="ftipo active" rel="Extrato">Extrato <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="ftipo hidden" rel="Receita">Receita <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="ftipo hidden" rel="Despesa">Despesa <i class="mdi mdi-chevron-down"></i></a></li>
            </ul>
            <input type="hidden" name="tipo" id="tipo" value="Extrato">
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-regime">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Regime:</div>
          </div>
          <div style="display: inline-block; width: 120px; ">
            <ul class="ul" style="width: 120px;" id="fregime">
              <li><a href="#" class="fregime active" rel="caixa">Caixa <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fregime hidden" rel="competencia">Competência <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fregime hidden" rel="cadastro">Cadastro <i class="mdi mdi-chevron-down"></i></a></li>
            </ul>
            <input type="hidden" name="regime" id="regime">
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-conta">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Contas:</div>
          </div>
          <div style="display: inline-block; width: 250px;" id="div-conta">
            <ul class="ul ul-conta scrollbar-inner" style="max-width: 250px; min-width: 250px;" id="fconta">
              <li>
                <a href="#" class="fconta all">
                  <input type="checkbox">
                  <span class="mdi mdi-checkbox-marked-outline mdi-15px checkbox" style="margin-top: 0; margin-bottom: 0; color:#fff; font-size: 15px;"></span>
                  <span>Todas</span>
                  <i class="mdi mdi-chevron-down"></i>
                </a>
              </li>
              @foreach($contas as $i)
              <li>
                <a href="#" class="fconta">
                  <input type="checkbox" name="conta[]" value="{{ $i->id }}">
                  <span class="mdi mdi-checkbox-marked-outline mdi-15px checkbox" style="margin-top: 0; margin-bottom: 0; color:#fff; font-size: 15px"></span>
                  <span>{{ $i->descricao }}</span>
                </a>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>

        <div class="mf" id="mf-data">
          <div class="mf-title">
            <div>Periodo:</div>
          </div>
          <div style="display: inline-block; width: 155px;">
            <ul class="ul" style="width: 155px;" id="fdata">
              <li><a href="#" class="fdata hidden" rel="fdho">Hoje <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fdata hidden" rel="fdot">Ontem <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fdata hidden" rel="fdsd">Últimos 7 dias <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fdata hidden" rel="fdut">Últimos 30 dias <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fdata active" rel="fdem">Este mês <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fdata hidden" rel="fdmp">Mês passado <i class="mdi mdi-chevron-down"></i></a></li>
              <li>
                <a href="#" class="fdata hidden" rel="pees">Período específico <i class="mdi mdi-chevron-down"></i></a>
              </li>
            </ul>
            <input type="hidden" name="data" id="data" value="fdem">
          </div>
          <div id="pees" class="hidden" style="display: inline-block; margin-top: 3px; background-color: #8BC34A;">
            <div style="display: inline-block; padding: 0;">
              <input type="text" class="form-control text-center" name="data_inicio" id="data_inicio" maxlength="10" required style="font-size: 12px; width: 100px;height: 27px;background-color: #9adc4f;color: #fff;border: 1px solid #8BC34A;">
            </div>
            <div style="display: inline-block;  color: #fff">à</div>
            <div style="display: inline-block; padding: 0;">
              <input type="text" class="form-control text-center" name="data_fim" id="data_fim" maxlength="10" required style="font-size: 12px; width: 100px;height: 27px;background-color: #9adc4f;color: #fff;border: 1px solid #8BC34A;">
            </div>
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>

        <div class="mf hidden" id="mf-fornecedor">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff" id="labforn"></div>
          </div>
          <div style="display: inline-block; width: 200px;" id="div-fornecedor">
            <input type="text" name="input-fornecedor" id="input-fornecedor" class="form-control" style="font-size: 12px; margin-top: 3px;height: 27px;background-color: #9adc4f; color: #fff; border: 1px solid #8BC34A;">
            <input type="hidden" name="ffornecedor_id" id="ffornecedor_id">
            <ul class="ul ul-ffornecedor scrollbar-inner" style="max-width: 200px!important; min-width: 200px!important; max-height: 200px; margin-top: -1px;" id="ffornecedor">
              @foreach($fornecedors as $i)
              <li>
                <a href="#" class="ffornecedor" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" title="{{ $i->nome_fantasia }}" rel="{{ $i->id }}">{{ $i->nome_fantasia }}</a>
              </li>
              @endforeach
              @foreach($clientes as $i)
              <li>
                <a href="#" class="fcliente" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" title="{{ $i->nome_fantasia }}" rel="{{ $i->id }}">{{ $i->nome_fantasia }}</a>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-categoria">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Categorias:</div>
          </div>
          <div style="display: inline-block; width: 250px;" id="div-categoria">
            <input type="text" name="input-categoria" id="input-categoria" class="form-control" style="font-size: 12px; margin-top: 3px;height: 27px;background-color: #9adc4f; color: #fff; border: 1px solid #8BC34A;">
            <input type="hidden" name="fcategoria_id" id="fcategoria_id">
            <ul class="ul ul-fcategoria scrollbar-inner" style="max-width: 250px!important; min-width: 250px!important; max-height: 200px; margin-top: -1px;" id="fcategoria">
              @foreach($categorias as $i)
                @if( count($i->children) )
                  @include('financeiro.movimento.fcategoria',['categorias'=>$i->children])
                @else
                  <li><a href="#" class="fcategoria" rel="{{ $i->id }}" title="{{ $i->nome }}">{{ $i->nome }}</a></li>
                @endif
              @endforeach
            </ul>
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-centrocusto">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Centro de Custo:</div>
          </div>
          <div style="display: inline-block; width: 200px;" id="div-centrocusto">
            <input type="text" name="input-centrocusto" id="input-centrocusto" class="form-control" style="font-size: 12px; margin-top: 3px;height: 27px;background-color: #9adc4f; color: #fff; border: 1px solid #8BC34A;">
            <input type="hidden" name="fcentrocusto_id" id="fcentrocusto_id">
            <ul class="ul ul-fcentrocusto scrollbar-inner" style="max-width: 200px!important; min-width: 200px!important; max-height: 200px; margin-top: -1px;" id="fcentrocusto">
              @foreach($centrocustos as $i)
              <li>
                <a href="#" class="fcentrocusto" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;" title="{{ $i->nome }}" rel="{{ $i->id }}">{{ $i->nome }}</a>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div class="mf hidden" id="mf-pont">
          <div style="display: inline-block; margin-top: 3px;">
            <div style="padding: 4px 5px; border: 1px solid #8BC34A; background-color: #8BC34A; color: #fff">Tipo ..:</div>
          </div>
          <div style="display: inline-block; width: 105px;">
            <ul class="ul" style="width: 105px;" id="fpontual">
              <li><a href="#" class="fpontual active" rel="todas">Todos <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fpontual hidden" rel="1">Pontual <i class="mdi mdi-chevron-down"></i></a></li>
              <li><a href="#" class="fpontual hidden" rel="0">Recorrente <i class="mdi mdi-chevron-down"></i></a></li>
            </ul>
            <input type="hidden" name="input_pontual" id="input_pontual">
          </div>
          <div class="mf-remove">
            <div>
              <a href="#" id="btn-filtro-remove"><i class="mdi mdi-close-box mdi-21px"></i></a>
            </div>
          </div>
        </div>
        <div id="mf-search" style="margin-bottom: 3px; margin-top: 3px;">
          <input type="text" name="input-search" id="input-search" class="form-control" autocomplete="off" placeholder="Clique aqui para pesquisar" style="font-size: 12px; width: 300px; height: 27px; border: 0;">
          <input type="hidden" name="order" id="order">
          <input type="hidden" name="sort" id="sort">
        </div>
        <div class="search-options hidden">
          <ul>
            <li class="search-item" rel="#mf-pesquisa">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-magnify mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Pesquisa Livre</h3>
                <p>Busque por descrição, n° do documento, nome da categoria, datas e etc</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-lanc">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-briefcase mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title" >Tipo de lançamentos</h3>
                <p>Busque informações por lançamento, como movimento pagos e não pagos.</p>
                <span class="hidden">pagos, não pagos</span>
              </div>
            </li>
            <li class="search-item" rel="#mf-tipo">
              <div style="display: inline-block; font-weight: bold; padding: 0px 14px; font-size: 24px; color: #01376a;">M</div>
              <div style="display: inline-block;">
                <h3 class="title">Tipo de movimento</h3>
                <p>Busque informações por movimentos, como receita e despesa, extrato.</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-regime">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-book mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Tipo de regime</h3>
                <p>Busque informações por regime, como regime de caixa e regime de competência.</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-conta">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-bank  mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Contas</h3>
                <p>Busque informações por contas, como conta corrente, conta poupança.</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-data">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-calendar mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Período</h3>
                <p>Busque por períodos pré-estabelecidos (1 dia, 3 dias, 1 semana, 1 mês, etc.), ou por período específico</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-cliente">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-account-box mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Dados do cliente</h3>
                <p>Busque por informações do cliente, como nome do cliente, CPF, CNPJ, e-mail, etc.</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-fornecedor">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-truck mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Dados do fornecedor</h3>
                <p>Busque por informações do Fornecedor, como nome do fornecedor, CPF, CNPJ, e-mail, etc.</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-categoria">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi mdi-tag-outline mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Dados do categoria</h3>
                <p>Busque informações por categoria, .</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-centrocusto">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-truck mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Dados do centro de custo</h3>
                <p>Busque por informações do Centro de Custo.</p>
              </div>
            </li>
            <li class="search-item" rel="#mf-pont">
              <div style="display: inline-block;padding: 3px 12px; color: #01376a;">
                <i class="mdi mdi-truck mdi-24px"></i>
              </div>
              <div style="display: inline-block;">
                <h3 class="title">Tipo Receita</h3>
                <p>Busque por informações Tipo Receita. se recorrente, pontual.</p>
              </div>
            </li>
          </ul>
        </div>
      </form>
      <ul id="nav-calendar" style="display: inline-flex;">
        <li class="hidden-xs">
          <a href="#" id="export" class="btn btn-default" data-route="{{ route('financeiro.movimento.exportar') }}" data-toggle="tooltip" title="Exportar" data-placement="bottom">E</a>
        </li>
        <li class="hidden-xs">
          <a href="#" id="print" class="btn btn-default" data-toggle="tooltip" title="Imprimir" data-placement="bottom">P</a>
        </li>
        <li class="hidden-xs">
          <a href="#" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btncol"><i class="mdi mdi-view-column mdi-20px"></i></a>
          <ul class="dropdown-menu" aria-labelledby="btncol" id="ulcol" style="right: 10px;">
            <li><a href="#" id="ul-a-tipo" rel="tipo"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>TIPO</a></li>
            <li><a href="#" rel="data"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>DATA</a></li>
            <li><a href="#" rel="datp"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>DATA PAGAMENTO</a></li>

            <li><a href="#" rel="datc"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>DATA CADASTRO</a></li>

            <li><a href="#" rel="focl"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>REC. DE / PAGO A</a></li>
            <li><a href="#" rel="bagc"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>BANCO/AGENCIA/CONTA</a></li>
            <li><a href="#" rel="cimg"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>ICONE DO BANCO</a></li>
            <li><a href="#" rel="valo"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>VALOR</a></li>
            <li><a href="#" rel="vapa"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-20px checkbox" style="vertical-align: middle;"></i>VALOR PAGO</a></li>
            <li><a href="#" id="ul-a-saldo" rel="sald"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>SALDO</a></li>
            <li><a href="#" rel="stat"><input type="checkbox" checked="checked"><i class="mdi mdi-checkbox-marked-outline mdi-20px checkbox" style="vertical-align: middle;"></i>STATUS</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div id="div-list" class="col-xs-12 col-sm-12 no-padding">
      <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
        <div class="cl-tipo hb-tipo" style=""><a href="#" class="order-mov" order="tipo" sort="asc" >TIPO</a></div>
        <div class="cl-datc hb-datc hidden" style="max-width: 70px; min-width: 70px; text-align: center;"><a href="#" class="order-mov active" title="Data Vencimento" order="data_emissao" sort="asc" >D.CAD</a></div>
        <div class="cl-data hb-data" style="max-width: 70px; min-width: 70px; text-align: center;"><a href="#" class="order-mov active" title="Data Vencimento" order="data_emissao" sort="asc" >D.EMI</a></div>
        <div class="cl-datp hb-datp" style="max-width: 70px; min-width: 70px; text-align: center;"><a href="#" class="order-mov" order="data_baixa" sort="asc" >D.PAG</a></div>
        <div class="cl-desc hidden-xs col-md-auto"><a href="#" class="order-mov" order="categoria_nome" sort="asc" >CATEGORIA</a> / <a href="#" class="order-mov" order="descricao" sort="asc" >DESCRIÇÃO</a></div>
        <div class="cl-desc visible-xs col-md-auto"><a href="#" class="order-mov" order="categoria_nome" sort="asc" >CAT</a> / <a href="#" class="order-mov" order="descricao" sort="asc" >DESC</a></div>
        <div class="cl-focl hb-focl col-md-auto hidden hidden-xs"><a href="#" class="order-mov" order="nome_fantasia" sort="asc">REC. DE / PAGO A</a></div>
        <div class="cl-bagc hb-bagc col-md-auto hidden hidden-xs">BANCO/AGENCIA/CONTA</div>
        <div class="cl-cimg hb-cimg hidden-xs" style="max-width: 38px; min-width: 38px;">&nbsp;</div>
        <div class="cl-valo hb-valo text-right hidden-xs" style="max-width: 125px; min-width: 125px;">VALOR (R$)</div>
        <div class="cl-valo visible-xs text-right">VALOR(R$)</div>
        <div class="cl-vapa hb-vapa text-right hidden-xs" style="max-width: 115px; min-width: 125px;">VALOR PAGO (R$)</div>
        <div class="cl-sald text-right" style="max-width: 125px; min-width: 125px;">SALDO (R$)</div>
        <div class="cl-stat hb-stat hidden-print hidden-xs" style="max-width: 40px; min-width: 40px;">&nbsp;</div>
      </div>
      <div id="grid-table-body" class="scrollbar-inner">
      </div>
      <form id="delete-form" action="" method="POST" class="hidden">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
      </form>
      <form id="form-movimento-create" action="" class="hidden" method="GET">
        {{ csrf_field() }}
      </form>
      <form id="form-conta-create" action="" class="hidden" method="GET">
        {{ csrf_field() }}
      </form>
      <a href="#" id="btn-agenda-movimento-edit" class="hidden"><i class="mdi mdi-pencil mdi-24px"></i></a>
    </div>
    <div id="div-crud" class="col-xs-12 col-sm-4 hidden hidden-print">
    </div>
  </div>
</section>
<!-- /.content -->
@endsection
@push('link')
<link rel="stylesheet" href="{{ asset('css/movimento.css') }}"/>
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/print.css') }}"/>
@endpush
@push('scripts')
<script type="text/javascript">
  var agenda;
  @if(isset($movimento))
  agenda = { route: "{{ route('movimento.edit', $movimento->id) }}", tipo: "{{ $movimento->categoria->tipo }}"};
  @endif
</script>
<script type="text/javascript" src="{{ asset('plugins/maskMoney/jquery.maskMoney.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/valida_cpf_cnpj.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/movimento_conta.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/movimento.js') }}"></script>

<script type="text/javascript">
  $(document).bind("keyup keydown", function(e){
    if(e.ctrlKey && e.keyCode == 80){
      e.preventDefault();
      $("#grid-table-body").parent().css({ "height": "100%", "max-height": "100%" }).animate({ scrollTop: 0 }, 0);
      $("#grid-table-body").css({ "height": "100%", "max-height": "100%" }).animate({ scrollTop: 0 }, 0);
      window.print();
    }
  });
  window.onafterprint = function(e){
    $(window).off('mousemove', window.onafterprint);
    resizeind();
  };

</script>
@endpush
