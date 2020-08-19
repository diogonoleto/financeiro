@forelse($itens as $i)
  @can('fin_centro_custo_read', $i)
  <div class="col-xs-12 no-padding grid-table">
    <div class="col-sm-3 col-xs-6">{{ $i->nome }}</div>
    @if($i->nome != 'Comum')
    <span class="tools-user">
      @can('fin_centro_custo_update')
      <a href="#" route="{{ route('centrocusto.edit', $i->id) }}" class="btn-centrocusto-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @endcan
      @can('fin_centro_custo_delete')
      <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('centrocusto.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
      @endcan
    </span>
    @endif
  </div>
  @endcan
@empty
<div class="col-xs-12 no-padding grid-table">
  <div class="col-xs-12">Centro de custo inexistente ou não encontrado</div>
</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>