@forelse($itens as $key => $i)
@can('compra_read', $i)
<div class="col-xs-12 no-padding grid-table {{ $i->status == 0 ? null : 'cl-green' }}">

  <div style="width: 41px; padding: 0px 8px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>
  
  <div class="col-sm-3 col-xs-4" style="width: calc(25% - 41px);">{{ $i->documento_num }}</div>
  <div class="col-sm-2 col-xs-4">{{ $i->fornecedor->nome_fantasia }}</div>
  <div class="col-sm-2 hidden-xs">{{ Carbon\Carbon::parse($i->data_emissao)->format('d/m/Y') }}</div>
  <div class="col-sm-1 hidden-xs text-center">{{ $i->qtde }}</div>
  <div class="col-sm-2 hidden-xs"><span style="float: left;">R$ </span><span style="float: right;">{{ number_format($i->valor, 2, ',', '.') }}</span></div>
  <div class="col-sm-2 col-xs-4{{ $i->status == 0 ? ' compra' : null }}">{{ $i->status == 0 ? 'Em Aberto' : 'Finalizada' }}</div>

  <span class="tools-user">
  @if($i->status == 0)
    @can('compra_update')
    <a href="{{ route('compra.edit', $i->id) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('compra_delete')
    <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('compra.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
  @endif
  </span>
</div>
@endcan
@empty
<div class="col-xs-12">
</div>
@endforelse

<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>