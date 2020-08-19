@extends('layouts.admin')
@push('style')
<style type="text/css">

  #btn-compra-tools {
    margin-top: -7px!important;
    right: 10px;
    width: 76px;
    height: 50px;
    float: right!important;
  }

  #content-central, #content-right {
    height: calc(100% - 59px);
    overflow: hidden!important;
  }

  .grid-table:nth-child(odd) {
    background-color: #fbfbfb;
  }

  .grid-table:hover .compra  {
    display: none;
  }
  .grid-table:hover {
    background-color: rgba(187, 255, 108, 0.29)!important;
  }

  .pagination-bottom {
    bottom: -50px!important;
  }

  #grid-table-header {
    border: 1px solid #eeeeee;
    border-right: 0;
    border-bottom: 1px solid #8BC34A;
  }

  #grid-table-footer {
    border: 1px solid #eeeeee;
    border-right: 0;
  }

  #grid-table-footer > div {
    padding: 16px;
    font-size: 16px;
    font-weight: 100;
    font-family: 'Roboto', sans-serif;
    white-space: nowrap;
    text-overflow: ellipsis;
    border-left: 1px solid #fafafa;
  }

  #btn-tools {
    text-align: right;
    margin-right: 20px;
  }

  #btn-search {
    margin-right: 5px;
  }

  .btn-compra-create{
    color: green;
  }

  .form-group {
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .grid-table > div {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    border-left: 1px solid #fafafa;
  }

  .grid-table > div:first-child {
    padding: 10.78px 15px;
    font-size: 16px;
    overflow: hidden;
    white-space: initial;
    text-overflow: initial;
  }

  #grid-table-header div {
    margin-right: 0px;
  }

  .alert-danger {
    color: #bb0400;
    background-color: #fff7f7;
    border-color: #ebccd1;
    margin: 20px;
  }

</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="col-sm-3">
    <h3>{{ $compra->documento_tipo }}: {{ $compra->documento_num }}</h3>
  </div>
  <div class="col-sm-2">
    <h3>QTDE: {{ $compra->qtde }}</h3>
  </div>
  <div class="col-sm-3">
    <h3>VALOR NOTA: {{ $compra->valor }}</h3>
  </div>
  <div class="col-sm-3">
    <h3>DATA EMISSÃO: {{ $compra->data_emissao }}</h3>
  </div>
  <div class="col-sm-1 text-right">
    <a href="#" class="btn btn-default btn-editar-venda" route="{{ route('compra.create') }}" data-toggle="tooltip" title="Editar compra" data-placement="left" style="margin-top: 12px; padding: 6px;"><i class="mdi mdi-pencil mdi-20px" aria-hidden="true"></i></a>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <div id="editar-venda" class="hidden" style="position: fixed; background-color: rgba(255, 255, 255, 0.90); left: 0; right: 0; top: 60px; bottom: 0; z-index: 2;">
    <form class="form" method="post" action="{{ route('compra.update', $compra->id) }}" style="margin-left: 80px; margin-top: 15px;">
      {{ method_field('put') }}
      {{ csrf_field() }}
      <div class="col-md-3">
        <div class="form-group">
          <div class="col-sm-5" style="padding: 0 0 10px;">
            <label for="documento_tipo">Tipo</label>
            <select id="documento_tipo" name="documento_tipo" class="form-control">
              <option value="1" {{ 1 == $compra->documento_tipo ? 'selected' : null }} >Nota Fiscal</option>
              <option value="2" {{ 2 == $compra->documento_tipo ? 'selected' : null }} >Recibo</option>
            </select>
          </div>
          <div class="col-sm-7" style="padding: 0 0 10px;">
            <label for="documento_num">N° Documento</label>
            <input id="documento_num" name="documento_num" class="form-control text-right" type="text" size="65" maxlength="50" required="required" value="{{ $compra->documento_num }}" style="border-left: 0;">
          </div>
        </div>
      </div>
      <div class="col-md-2 no-padding">
        <div class="form-group">
          <label for="qtde">Quantidade</label>
          <input type="text" id="qtde" name="qtde" class="form-control text-right" value="{{ $compra->qtde }}">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="valor">Valor da Nota</label>
          <input type="text" id="valor" name="valor" class="form-control text-right" value="{{ $compra->valor }}">
        </div>
      </div>
      <div class="col-md-3 no-padding">
        <div class="form-group">
          <label for="data_emissao">Data da Emissão</label>
          <input type="text" id="data_emissao" name="data_emissao" class="form-control text-right" maxlength="10" data-date-format="DD/MM/YYYY" value="{{ Carbon\Carbon::parse($item->data_emissao)->format('d/m/Y') }}">
        </div>
      </div>
      <div class="col-md-1">
        <input type="submit" class="btn btn-success btn-block" style="margin-top: 10px; padding: 9.5px;" value="{{ $compra->id ? 'EDITAR' : 'SALVAR' }}">
      </div>
    </form>
  </div>
  <div class="col-sm-12 col-xs-12 no-padding" id="compra-list">
    <div class="col-sm-12 col-xs-12">
      @if(count($errors) > 0)
      <div class="alert alert-danger alert-dismissible fade in active" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        @foreach( $errors->all() as $error )
        <p>{{ $error }}</p>
        @endforeach
      </div>
      @endif
    </div>
    <div class="col-xs-12 no-padding" id="grid-table-header">
      <div style="width: 40px; padding: 0px 5px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox" id="checkbox-all"></i></div>
      <div class="col-sm-5 col-xs-4" style="width: calc(41.66666667% - 40px);">PRODUTO</div>
      <div class="col-sm-1 col-xs-4 text-center">QTDE</div>
      <div class="col-sm-2 hidden-xs">VALOR UNITÁRIO</div>
      <div class="col-sm-2 hidden-xs">VALOR TOTAL</div>
      <div class="col-sm-2 hidden-xs">DT. VALIDADE</div>
    </div>
    <div id="grid-table-body">
      @forelse($itens as $key => $i)
      @can('compra_read', $i)

      <div class="col-xs-12 no-padding grid-table">
        <div style="width: 41px; padding: 0px 8px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>

        <div class="col-sm-5 col-xs-4" style="width: calc(41.66666667% - 41px);">{{ $i->produto->nome }}</div>
        <div class="col-sm-1 col-xs-4 text-center"><span>{{ $i->qtde }}<span>{{ isset($i->unidadeMedida->unidade) ? $i->unidadeMedida->unidade : null }}</div>
        <div class="col-sm-2 hidden-xs valoruni"><span style="float: left;">R$ </span><span style="float: right;">{{ number_format($i->valor, 2, ',', '.') }}</span></div>
        <div class="col-sm-2 hidden-xs valor"><span style="float: left;">R$ </span><span style="float: right;">{{ number_format($i->qtde*$i->valor, 2, ',', '.') }}</span></div>
        <div class="col-sm-2 hidden-xs compra">{{ Carbon\Carbon::parse($item->data_validade)->format('d/m/Y') }}</div>

        <span class="tools-user">
          @can('compra_update')
          <a href="{{ route('compra.edit', [$i->compra_id, $i->id] ) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
          @endcan
          @can('compra_delete')
          <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('compra.destroy', [$i->compra_id, $i->id]) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
          @endcan
        </span>
      </div>
      @endcan
      @empty
      <div class="col-xs-12">
      </div>
      @endforelse
    </div>
    <form id="delete-form" action="" method="POST" class="hidden">
      {{ method_field('DELETE') }}
      {{ csrf_field() }}
    </form>
  </div>
  <div class="col-sm-4 col-xs-4 hidden" style="height: calc(100% - 77px); padding: 30px; border-left: 1px solid #8bc34a; z-index: 1;" id="compra-create" >
    <div class="col-xs-12 no-padding" style="border: 1px solid #8BC34A; background-color: #fff; overflow: auto;" id="compra-item">
      <div style="position: fixed;margin-top: -15px;margin-left: 15px;z-index: 1;font-size: 20px;font-weight: 100;background-color: #fff;color: #8bc34a;">Adicionar Item da Compra</div>
      <form class="form" style="margin-top: 20px" method="post" @if(isset($item->id)) action="{{ route('compra.update', [$item->compra_id, $item->id]) }}"> {{ method_field('put') }} @else action="{{ route('compra.store') }}"> @endif {{ csrf_field() }}
        <div class="col-xs-12">
          <div class="form-group">
            <label for="produto_id">Produto <span>*</span></label>
            <select class="form-control" name="produto_id" required>
              <option value="">Selecione o produto</option>
              @foreach($produtos as $i)
              <option value="{{ $i->id }}" {{ ( (!isset($item->produto_id) ) or ($i->id == $item->produto_id) )  ? 'selected' : null }} >{{ $i->nome }}</option>
              @endforeach
            </select>
            <span class="help-block"></span>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="form-group">
            <label for="produto_id">Unidade de Medida <span>*</span></label>
            <select class="form-control" name="unidade_medida_id" required>
              <option value="">Selecione a unidade</option>
              @foreach($unidades as $i)
              <option value="{{ $i->id }}" {{ ( (isset($item->unidade_medida_id) ) or ($i->id == $item->unidade_medida_id) )  ? 'selected' : null }} >{{ $i->descricao }}</option>
              @endforeach
            </select>
            <span class="help-block"></span>
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <div class="col-sm-4" style="padding: 0 0 10px;">
              <label for="plataforma_id">Quantidade</label>
              <input type="text" id="qtde" name="qtde" autocomplete="off" class="form-control text-center" maxlength="10" style="border-right: 0;" value="{{ $item->qtde }}">
            </div>
            <div class="col-sm-8" style="padding: 0 0 10px;">
              <label for="valor">Valor Unitário</label>
              <input type="text" id="valor" name="valor" autocomplete="off" class="form-control text-right" maxlength="10" value="{{ $item->valor }}">
            </div>
          </div>
        </div>
        
        <div class="col-md-12">
          <div class="form-group">
            <label for="vtotal">Valor Total</label>
            <input type="text" id="vtotal" name="vtotal" class="form-control text-right" maxlength="10" value="">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="data_validade">Data de Validade</label>
            <input type="text" id="data_validade" name="data_validade" class="form-control text-right" maxlength="10" data-date-format="DD/MM/YYYY" value="{{ Carbon\Carbon::parse($item->data_validade)->format('d/m/Y') }}">
          </div>
        </div>
        <div style="position: fixed;margin-left: 15px;z-index: 1; width: 24.7%" class="form-footer">
          <input type="hidden" name="compra_id" value="{{ $compra->id }}">
          <a href="{{ route('compra.edit', $compra->id) }}" class="btn pull-left btn-lg btn-default">CANCELAR</a>
          <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Content footer (Page footer) -->
<section class="content-footer" style="border-bottom: 1px solid #8BC34A;border-top: 1px solid #8BC34A;position: fixed;bottom: 0;padding: 0px;left: 80px;right: 0;background-color: #fff;">
  <div class="col-sm-12 col-xs-12 no-padding">
    <div id="grid-table-footer">
      <div style="width: 41px; padding: 0px 8px; float: left;">&nbsp;</div>
      <div class="col-sm-5 col-xs-4" style="width: calc(41.66666667% - 41px);">&nbsp;</div>
      <div class="col-sm-1 col-xs-4 text-center">&nbsp;</div>
      <div class="col-sm-4"><span style="float: right; font-size: 31px">{{ number_format($itens->sum('vtotal'), 2, ',', '.') }}</span><span style="float: right;">R$ </span><span style="float: right; font-size: 31px">Total:</span></div>
      <div class="col-sm-2 text-right" style="font-size: 31px">Qtde Itens: <span>{{ count($itens) }}</span></div>
    </div>
  </div>
  <div class="hidden">

    <form class="form" method="post" id="finalizarForm" action="{{ route('compra.update', $compra->id) }}">
      {{ method_field('put') }}
      {{ csrf_field() }}
      <button type="submit" class="btn btn-success btn-lg" style="padding: 15px; position: absolute; top: 11px; right: 15px;">FINALIZAR COMPRA</button>
    </form>

    
  </div>
</section>
@endsection

@push('scripts')

<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- <script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>numberFormat.js -->

<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">
  // $(document).on('click', '.btn-tools-edit, .btn-plus, .btn-show', function(e){
  //   $(".se-pre-con").fadeIn();
  // });
  $(document).ready(function(){
    var timeNow = moment().format("YYYY-MM-DD HH:mm:ss");
    $('#data_validade').datetimepicker({
      locale: 'pt-BR',
      format: 'DD-MM-YYYY',
      defaultDate: timeNow,
      widgetPositioning: {
        horizontal: 'right',
        vertical: 'top'
      }
    });

    var q = $("#compra-create #qtde").val();
    var v = $("#compra-create #valor").val();
    var t = q*v;

    $("#vtotal").val(t.toFixed(2)).mask("#.###.##0,00", {reverse: true});
    $('#valor').mask("#.###.##0,00", {reverse: true});
    $("#qtde").mask('######9');

    var h =  $("body").innerHeight();
    h -= $(".content-header").innerHeight();
    h -= $(".content-footer").innerHeight();
    h -= 280;
    $("#compra-novo, #compra-item").css({ "height": h, "top": "55px" });

    $(".form-footer").css("margin-top", h - 53);
    $("#compra-item").draggable({
      containment: ".content"
    });
    var item = null;
    @if($item->id)
    item = {{ $item->id }};
    @endif
    if({{ count($itens) }} == {{ $compra->qtde }} && item == null && {{ $itens->sum('vtotal') }} == {{ $compra->valor }} ){
      $(".content-footer > div:last-child").toggleClass("hidden");
    } else if({{ count($itens) }} > {{ $compra->qtde }}){
      alert("Quantidade de itens da Adicionado maior que a Quantidade informada na nota");
    } else {
      if(item != null || {{ count($itens) }} < {{ $compra->qtde }}  ){
        $("#compra-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
        $("#compra-create").toggleClass("hidden");
        // $("#form-compra-create").attr("action", $(this).attr("route")); 
      }
    }
  });

  $(document).on('click', '.btn-editar-venda', function(e){
    e.preventDefault();

    // $('.close').trigger('click');

    $(this).children().toggleClass('mdi-pencil').toggleClass('mdi-close');
    $("#editar-venda").toggleClass('hidden');
  });

  $(document).on('keyup', '#valor, #qtde', function(e){
    e.preventDefault();

    var q = $("#compra-create #qtde").val();
    var v = $("#compra-create #valor").val();
    v = v.replace('.','');
    v = v.replace(',','.');
    var t = q*v;

    $("#vtotal").val(t.toFixed(2)).mask("#.###.##0,00", {reverse: true});
  });

</script>
@endpush