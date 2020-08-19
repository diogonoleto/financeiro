@forelse($itens as $key => $i)
@can('pdv_read', $i)
<div class="col-xs-12 no-padding grid-table">

  <div style="width: 41px; padding: 0px 8px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>

  <div class="col-sm-3 col-xs-4" style="width: calc(25% - 41px);">{{ $i->nome }}</div>
  <div class="col-sm-2 col-xs-4">{{ $i->responsavel }}</div>
  <div class="col-sm-2 hidden-xs">{{ $i->plataforma->nome }}</div>
  <div class="col-sm-2 hidden-xs">{{ $i->uuid }}</div>
  <div class="col-sm-1 hidden-xs">{{ $i->mesa_qtd }}</div>
  <div class="col-sm-1 hidden-xs"">R$ <span style="float: right;">{{ number_format($i->desc_valor_max, 2, ',', '.') }}</span></div>
  <div class="col-sm-1 col-xs-4 pdv">{{ $i->desc_perc_max }}</div>

  <span class="tools-user">
    @can('pdv_update')
    <a href="#" route="{{ route('pdv.edit', $i->id) }}" class="btn-pdv-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('pdv_delete')
    <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('pdv.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
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