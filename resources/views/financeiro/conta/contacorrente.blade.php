@php($saldoant = $anterior->valor )
@forelse($itens as $key => $i)
@can('fin_movimento_read', $i)
<div class="col-xs-12 no-padding grid-table">
  <div style="max-width: 110px; min-width: 110px; text-align: center;">
    <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 300; color: #41545e; line-height: 5px;">
      {{ Carbon\Carbon::parse($i->data_baixa)->formatLocalized("%b") }}
    </span>
    <span style="display: block; font-weight: bold; color: #41545e;">{{ Carbon\Carbon::parse($i->data_baixa)->format('d') }}</span>
  </div>
  <div class="hb-desc col-md-auto">
    <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 300; color: #41545e; line-height: 5px;">
      {{ $i->categoria->nome }}
    </span>
    <span style="display: block; font-weight: bold; color: #41545e;">
      {{ $i->descricao }}<span class="recorencia"> {{ $i->recorrencia != "" ? '('.$i->recorrencia.') ' : '' }}</span>
    </span>
  </div>
    @if($i->tipo == 'Receita')
      @php($anterior->valor += $i->valor_recebido )
      <div class="text-right" style="max-width: 135px; min-width: 135px; color: #269800;">{{ number_format($i->valor, 2, ',', '.') }}</div>
      <div style="max-width: 135px; min-width: 135px;"></div>
    @else
      @php($anterior->valor -= $i->valor_recebido )
      <div style="max-width: 135px; min-width: 135px;"></div>
      <div class="despesa text-right" style="max-width: 135px; min-width: 135px; color: #e59d27;">-{{ number_format($i->valor, 2, ',', '.') }}</div>
    @endif
    <div class="hb-sald text-right" style="max-width: 135px; min-width: 135px;">{{ number_format($anterior->valor, 2, ',', '.') }}</div>
</div>
@endcan
@empty
<div class="col-xs-12">
</div>
@endforelse
<div id="movimento-footer">
  <div class="col-xs-12 col-lg-2 col-sm-2 display-total" style="border: 0!important;">
    <div>
      <span>Saldo</span>
      <span>R$</span><span>{{ number_format($anterior->valor, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-12 col-lg-2 col-sm-2 display-total" style="border: 0!important;text-align: left!important; float: left!important;" id="saldoAnt">
    <div>
      <span>Saldo Anterior</span>
      <span>R$</span><span>{{ number_format($saldoant, 2, ',', '.') }}</span>
    </div>
  </div>

</div>