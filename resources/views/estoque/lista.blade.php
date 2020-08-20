@forelse($itens as $key => $i)
@can('estoque_read', $i)
<div class="col-xs-12 no-padding grid-table">

  <div style="width: 41px; padding: 0px 8px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>
  
  <div class="col-sm-12" style="width: calc(100% - 41px);">{{ $i->nome }}</div>
  <span class="tools-user">
    @can('estoque_entrada')
    <a href="{{ route('estq.movimento.index', $i->id) }}" class="btn-estoque-entrada"><i class="mdi mdi-package-up mdi-24px"></i></a>
    @endcan
    @can('estoque_saida')
    <a href="#" route="{{ route('estq.movimento.index', $i->id) }}" class="btn-estoque-saida"><i class="mdi mdi-package-down mdi-24px"></i></a>
    @endcan

    @can('estoque_update')
    <a href="#" route="{{ route('estoque.edit', $i->id) }}" class="btn-estoque-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('estoque_delete')
    <a href="#" route="{{ route('estoque.destroy', $i->id) }}" class="btn-tools-delete" rel="del-{{ $key }}"><i class="mdi mdi-delete mdi-24px"></i></a>
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