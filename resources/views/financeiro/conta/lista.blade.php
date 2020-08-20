@forelse($itens as $i)
@can('fin_conta_read', $i)
<div class="col-xs-12 col-sm-3 grid-table grid grid-{{ $i->id }}">
  <div class="col-xs-3 no-padding">
    @if($i->img)
      <img src="{{ url($i->img) }}" class="img-thumbnail img-circle">
    @else
      <img src="{{ url('img/default.png')}}" class="img-thumbnail img-circle">
    @endif
  </div>
  <div class="col-xs-9 no-padding">
    @if($i->conta_tipo_id == 1 or $i->conta_tipo_id == 8 )
    <div>
      <a href="#" class="radio-padrao" route="{{ route('financeiro.conta.padrao', $i->id) }}">
        <input type="radio">
        <i class="mdi {{ ($i->padrao == '1') ? 'mdi-checkbox-marked-circle-outline' : 'mdi-checkbox-blank-circle-outline' }} mdi-24px" id="checkbox"></i>
      </a>
    </div>
    @else
    <div></div>
    @endif
    <div class="col-xs-1 col-sm-1 text-center hidden-xs">{{ $i->id }}</div>
    <div class="col-xs-12 col-sm-5" style="padding: 11px 10px 3px;">
      <a href="{{ route('conta.show', $i->id) }}">
        <span style="display: block; text-transform: uppercase; font-size: 10px; font-weight: 200; color: #41545e; line-height: 5px;">
          {{ $i->tnome }}
        </span>
        <span class="cl-desc hb-desc col-md-auto" style="display: block; font-weight: 500; color: #41545e;">
          {{ $i->descricao }}<span class="recorencia"> {{ $i->recorrencia != "" ? '('.$i->recorrencia.') ' : '' }}</span>
        </span>
      </a>
    </div>
    <div class="col-xs-6 col-sm-3">{{ ucwords(strtolower($i->bnome)) }}</div>
    <div class="col-xs-1 col-sm-1 no-padding cl-cimg hb-cimg hidden-xs" style="max-width: 42px; min-width: 42px;" ><img src="{{ url($i->img) }}" class="img-responsive"></div>
    <div class="col-xs-2 col-sm-2 conta hidden-xs">
      @if($i->conta_tipo_id != 4 )
      <span style="float: left;">R$ </span><span style="float: right;">{{ number_format($i->saldo, 2, ',', '.') }}</span>
      @endif
    </div>
  </div>
  <span class="tools-user">
    @can('fin_conta_update')
    <a href="#" class="btn-conta-edit" route="{{ route('conta.edit', $i->id) }}" tipo="{{ $i->conta_tipo_id }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('fin_conta_delete')
    <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('conta.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
  </span>
</div>
@endcan
@empty
<div class="col-xs-12">
  <h3></h3>
</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>