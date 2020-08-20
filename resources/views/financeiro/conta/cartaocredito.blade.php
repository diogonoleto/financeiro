@forelse($itens as $key => $i)
@can('fin_movimento_read', $i)
<div class="col-xs-12 no-padding grid-table">
  <div style="max-width: 110px; min-width: 110px; text-align: center;">
    <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 300; color: #41545e; line-height: 5px;">
      {{ Carbon\Carbon::parse($i->data_emissao)->formatLocalized("%b") }}
    </span>
    <span style="display: block; font-weight: bold; color: #41545e;">{{ Carbon\Carbon::parse($i->data_emissao)->format('d') }}</span>
  </div>
  <div class="hb-desc col-md-auto">
    <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 300; color: #41545e; line-height: 5px;">
      {{ $i->categoria->nome }}
    </span>
    <span style="display: block; font-weight: bold; color: #41545e;">
      {{ $i->descricao }}<span class="recorencia"> {{ $i->recorrencia != "" ? '('.$i->recorrencia.') ' : '' }}</span>
    </span>
  </div>
  <div class="{{ isset($i->data_baixa) ? ' recebida' : null }}" style="max-width: 115px; min-width: 115px;"><span style="float: left;">R$ </span><span style="float: right;">{{ number_format($i->valor, 2, ',', '.') }}</span></div>
  <span class="tools-user hidden-print">
    @if($i->categoria->nome == "Saldo Inicial")
    @can('fin_movimento_update')
    <a href="#" class="btn-conta-edit" route="{{ route('conta.edit', $i->conta_id) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @elseif($i->categoria->nome == 'Transferência de Saída' || $i->categoria->nome == 'Transferência de Entrada')
    @php($pago = 1)
    @can('fin_movimento_update')
    <a href="#" route="{{ route('movimento.edit', $i->id) }}" rel="{{ $fatura->id }}" class="btn-transferencia-create"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @else
    @can('fin_movimento_update')
    <a href="#" route="{{ route('movimento.edit', $i->id) }}" tipo="{{ $i->categoria->tipo }}" class="btn-movimento-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('fin_movimento_delete')
    <a href="#" route="{{ route('movimento.destroy', $i->id) }}" rel="del-{{ $key }}" class="btn-tools-delete"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
    @endif
  </span>
</div>
@if($i->categoria->nome == 'Transferência de Entrada')
@php($fat = 1)
@endif
@endcan
@empty
<div class="col-xs-12">
</div>
@endforelse
<div id="movimento-footer">
  @if(isset($fatura))
  @if($fatura->status == 3 && count($itens) > 0 && !isset($pago) )
  <div class="col-xs-12 col-lg-2 col-sm-3 display-total" style="max-width: 255px; width: 255px;">
    <a href="#" class="btn btn-success btn-transferencia-create" route="{{ route('movimento.create') }}" rel="{{ $fatura->id }}" data-toggle="tooltip" title="Transferência Bancária" data-placement="bottom" style="padding: 13px 8px;font-size: 17px;">Informar pagamento</a>
  </div>
  @endif
  <div class="col-xs-12 col-lg-2 col-sm-2 display-total">
    <div>
      <span>Valor da fatura</span>
      <span>R$</span>
      <span>{{ isset($total->valor) ? number_format($total->valor, 2, ',', '.') : number_format(0, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-12 col-lg-2 col-sm-3 display-total">
    <div>
      <span>Vencimento</span>
      <span></span>
      <span>{{ Carbon\Carbon::parse($fatura->data_vencimento)->format('d/m/y') }}</span>
    </div>
  </div>
  <div class="col-xs-12 col-lg-2 col-sm-3 display-total">
    <div>
      <span>Fechamento</span>
      <span></span>
      <span>{{ Carbon\Carbon::parse($fatura->data_fechamento)->format('d/m/y') }}</span>
    </div>
  </div>
  <div class="col-xs-12 col-lg-2 col-sm-3 display-total">
    <div>
      <span>Inicio</span>
      <span></span>
      <span>{{ Carbon\Carbon::parse($fatura->data_inicial)->format('d/m/y') }}</span>
    </div>
  </div>
  <div class="col-xs-12 col-lg-2 col-sm-2 display-total">
    <div>
      <span>Saldo anterior</span>
      <span>R$</span>
      <span>{{ isset($anterior->valor) ? number_format($anterior->valor, 2, ',', '.') : number_format(0, 2, ',', '.') }}</span>
    </div>
  </div>
  @else
  <div class="col-xs-12 text-right">
    <div style="font-size: 20px; padding: 11px;font-weight: 300;font-family: 'Roboto', sans-serif;">
      Não Possui Fatura
    </div>
  </div>
  @endif
</div>