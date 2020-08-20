@php($saldoant = $saldo->valor )
@forelse($itens as $key => $i)
@can('fin_movimento_read', $i)
<div class="col-xs-12 no-padding grid-table{{ (!isset($i->data_baixa) && $i->data_vencimento < $agora) ? ' vencido' : null }}">
  <div class="cl-tipo hb-tipo">
    @if($i->tipo == 'Despesa')
    <span style="font-weight: bold; color: #e59d27; border: 1px solid #e59d27; padding: 1px 3px;">D</span>
    @else
    <span style="font-weight: bold; color: #269800; border: 1px solid #269800; padding: 1px 3px;">R</span>
    @endif
  </div>
  <div class="cl-datc hb-datc hidden" style="max-width: 70px; min-width: 70px; text-align: center;">
    <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 300; color: #41545e; line-height: 5px;">
      {{ isset($i->created_at) ? Carbon\Carbon::parse($i->created_at)->formatLocalized("%b") : null }}
    </span>
    <span style="display: block; font-weight: bold; color: #41545e;">
      {{ isset($i->created_at) ? Carbon\Carbon::parse($i->created_at)->format('d') : null  }}
    </span>
    <span>
      {{ isset($i->created_at) ? Carbon\Carbon::parse($i->created_at)->format('y') : null  }}
    </span>
  </div>
  <div class="cl-data hb-data" style="max-width: 70px; min-width: 70px; text-align: center;">
    <span>{{ Carbon\Carbon::parse($i->data_vencimento)->formatLocalized("%b") }}</span>
    <span>{{ Carbon\Carbon::parse($i->data_vencimento)->format('d') }}</span>
    <span>
      {{ Carbon\Carbon::parse($i->data_vencimento)->format('y') }}
    </span>
  </div>
  <div class="cl-datp hb-datp" style="max-width: 70px; min-width: 70px; text-align: center;">
    <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 300; color: #41545e; line-height: 5px;">
      {{ isset($i->data_baixa) ? Carbon\Carbon::parse($i->data_baixa)->formatLocalized("%b") : null }}
    </span>
    <span style="display: block; font-weight: bold; color: #41545e;">
      {{ isset($i->data_baixa) ? Carbon\Carbon::parse($i->data_baixa)->format('d') : null  }}
    </span>
    <span>
      {{ isset($i->data_baixa) ? Carbon\Carbon::parse($i->data_baixa)->format('y') : null  }}
    </span>
  </div>
  <div class="cl-desc hb-desc col-md-auto mparent" rel="{{ $i->id }}" style="cursor: pointer;">
    <span>
      {{ $i->categoria->nome }}
    </span>
    <span>
      {{ $i->descricao }}<span class="recorencia"> {{ $i->recorrencia != "" ? '('.$i->recorrencia.') ' : '' }}</span>
    </span>
  </div>
  <div class="cl-focl hb-focl col-md-auto hidden hidden-xs">{{ isset($i->cnpj) ? $i->cnpj.' - ': null  }}{{ isset($i->nome_fantasia) ? $i->nome_fantasia : null }}</div>
  <div class="cl-bagc hb-bagc col-md-auto hidden hidden-xs"  style="width: calc(25% - 131px);">{{ isset($i->contas->banco_id) }} {{ isset($i->contas->agencia) }} {{ isset($i->contas->conta) }}</div>
  <div class="cl-cimg hb-cimg hidden-xs" style="max-width: 38px; min-width: 38px; padding: 0px;"><img class="img-responsive" src="{{ url($i->img) }}"></div>
  <div class="cl-valo hb-valo hidden-xs{{ isset($i->data_baixa) ? ' recebida' : ' nrecebida' }}" style="max-width: 125px; min-width: 125px; text-align: right;">{{ number_format($i->valor, 2, ',', '.') }}</div>
  <div class="cl-valo hb-valo visible-xs{{ isset($i->data_baixa) ? ' recebida' : ' nrecebida' }}" style="text-align: right;">{{ number_format($i->valor, 2, ',', '.') }}</div>
  <div class="cl-vapa hb-vapa hidden-xs{{ isset($i->data_baixa) ? ' recebida' : ' nrecebida' }}" style="max-width: 125px; min-width: 125px; text-align: right;">{{ number_format($i->valor_recebido, 2, ',', '.') }}</div>
  @if($i->categoria->tipo == 'Receita')
  @php($saldo->valor += $i->valor_recebido )
  @else
  @php($saldo->valor -= $i->valor_recebido )
  @endif
  <div class="cl-sald hb-sald" style="max-width: 125px; min-width: 125px; text-align: right;">{{ $tipo == 'Extrato' ? number_format($saldo->valor, 2, ',', '.') : null }}</div>
  <div class="cl-stat hb-stat hidden-xs hidden-print" style="max-width: 40px; min-width: 40px;"><i class="mdi{{ isset($i->data_baixa) ? ' mdi-check mdi-18px recebida ' : null }}hidden-print"></i></div>
  <span class="tools-user hidden-print">
    @if($i->categoria->nome == "Saldo Inicial")
    @can('fin_conta_update')
    <a href="#" class="btn-conta-edit" route="{{ route('conta.edit', $i->conta_id) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @elseif($i->categoria->nome == 'Transferência de Saída' || $i->categoria->nome == 'Transferência de Entrada')
    @can('fin_transferencia_update')
    <a href="#" route="{{ route('movimento.edit', $i->id) }}" class="btn-transferencia-create"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('fin_transferencia_delete')
    <a href="#" route="{{ route('movimento.destroy', $i->id) }}" rel="del-{{ $key }}" class="btn-tools-delete"><i class="mdi mdi-delete mdi-24px"></i></a>
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
<div class="col-xs-12 hidden mchild mchild-{{ $i->id }}" style="border-top:1px solid #8BC34A; height: calc(100% - 43px);">
  <div class="col-xs-6" >
    <h5 style="margin-top: 40px;margin-bottom: 20px;">INFORMAÇÃO DO PAGAMENTO</h5>
    <p>
      <span class="labe">Descrição:</span>
      <span class="info">{{ $i->descricao }}<span class="recorencia"> {{ $i->recorrencia != "" ? '('.$i->recorrencia.') ' : '' }}</span>
    </p>
    <p>
      <span class="labe">Categoria:</span>
      <span class="info">{{ $i->categoria->nome }}</span>
    </p>
    <p>
      <span class="labe">N° do Documento:</span>
      <span class="info">{{ $i->num_doc }}</span>
    </p>
    <p>
      <span class="labe">Tipo:</span>
      <span class="info">{{ $i->tipo }}</span>
    </p>
    <p>
      <span class="labe">Data Emissão:</span>
      <span class="info">{{ Carbon\Carbon::parse($i->data_emissao)->format('d/m/Y') }}</span>
    </p>
    <p>
      <span class="labe">Data Vencimento:</span>
      <span class="info">{{ Carbon\Carbon::parse($i->data_vencimento)->format('d/m/Y') }}</span>
    </p>
  </div>
  <div class="col-xs-6">
    <h5 style="margin-top: 40px;margin-bottom: 20px;">DADOS DO PAGAMENTO</h5>
    <p>
      <span class="labe">Data Baixa:</span>
      <span class="info">{{ isset($i->data_baixa) ? Carbon\Carbon::parse($i->data_baixa)->format('d/m/Y') : null  }}</span>
    </p>
    <p>
      <span class="labe">Valor Recebido:</span>
      <span class="info">{{ number_format($i->valor_recebido, 2, ',', '.') }}</span>
    </p>
    <p>
      <span class="labe">CNPJ: </span>
      <span class="info">{{ isset($i->cnpj) ? $i->cnpj.' - ': null  }}</span>
    </p>
    <p>
      <span class="labe">Nome: </span>
      <span class="info">{{ isset($i->nome_fantasia) ? $i->nome_fantasia : null }}</span>
    </p>
    <p>
      <span class="labe">Conta: </span>
      <span class="info">{{ isset($i->contas->banco_id) }} {{ isset($i->contas->agencia) }} {{ isset($i->contas->conta) }}</span>
    </p>
  </div>
</div>
@endcan
@empty
<div class="col-xs-12 text-center" style="margin-bottom:5px;"><div class="col-xs-12">Não existe ou não foi encontrada movimentação</div></div>
@endforelse

<div id="movimento-footer">
  <div class="col-xs-4 hidden fdate hidden-print">{{ $fdate }}</div>

  <div class="col-xs-6 col-lg-2 col-sm-2 display-total {{ $tipo == 'Extrato' ? null : 'hidden' }}" style="border: 0!important; text-align: left;" id="saldoAnt">
    <div>
      <span>Saldo Anterior</span>
      <span>R$</span><span>{{ number_format($saldoant, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total recebida col-lg-offset-2 {{ $tipo == 'Receita' ? null : 'hidden' }}" id="frs">
    <div class="boleft">
      <span>Receitas Recebidas</span>
      <span>R$</span><span>{{ number_format($recebidas, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total cvencido hidden" id="frv">
    <div>
      <span>Receitas Vencidas</span><span>R$</span><span>{{ number_format($rvencidas, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-2 display-total areceber hidden" id="fap">
    <div>
      <span>Receita A Receber</span><span>R$</span><span>{{ number_format($areceber, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total paga col-lg-offset-2 {{ $tipo == 'Despesa' ? null : 'hidden' }}" id="fgs">
    <div class="boleft">
      <span>Dispesas Pagas</span>
      <span>R$</span><span>{{ number_format($pagas, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total cvencido hidden" id="fdv">
    <div>
      <span>Despesas Vencidas</span><span>R$</span><span>{{ number_format($dvencidas, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-2 display-total areceber hidden" id="far">
    <div>
      <span>Despesas A Pagar</span><span>R$</span><span>{{ number_format($apagar, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total cvencido {{ $tipo == 'Extrato' ? 'hidden' : null }}" id="fvdr">
    <div>
      <span>Vencidas</span><span>R$</span><span>{{ number_format($rvencidas+$dvencidas, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-2 display-total areceber {{ $tipo == 'Extrato' ? 'hidden' : null }}" id="farp">
    <div>
      <span>{{ $tipo == "Receita"? 'A Receber' : 'A Pagar'}}</span><span>R$</span><span>{{ number_format($areceber+$apagar, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total col-lg-offset-1 {{ $tipo == 'Extrato' ? null : 'hidden' }}" id="ftr">
    <div class="boleft">
      <span>Total de Recebimentos</span>
      <span>R$</span><span>{{ number_format($recebidas, 2, ',', '.') }}</span>
    </div>
  </div>
  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-3 display-total {{ $tipo == 'Extrato' ? 'cvencido' : 'hidden' }}" id="ftp">
    <div>
      <span>Total de Pagamentos</span>
      <span>R$</span><span>{{ number_format($pagas, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-4 hidden-xs col-lg-2 col-sm-2 display-total" id="fres">
    <div>
      <span>Resultado</span>
      <span>R$</span><span>{{ number_format($total, 2, ',', '.') }}</span>
    </div>
  </div>

  <div class="col-xs-6 col-lg-2 col-sm-2 display-total col-lg-offset-1 {{ $tipo == 'Extrato' ? null : 'hidden'}}" id="saldo" style="border: 0!important;">
    <div>
      <span>Saldo</span>
      <span>R$</span><span>{{ number_format($saldo->valor, 2, ',', '.') }}</span>
    </div>
  </div>
</div>