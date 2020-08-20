@forelse($itens as $i)
@can('config_importacao_read', $i)
<div class="col-xs-12 no-padding grid-table">
  <div class="col-sm-8 col-xs-6 {{ isset($i->deleted_at) ? 'text-through' : null }}">{{ $i->id }} - {{ $i->nome }}</div>
  <div class="col-sm-3 col-xs-6 text-right">{{ $i->created_at }}</div>
  <span class="tools-user">
    @if($i->deleted_at == null)
    @can('config_importacao_delete')
    <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('imp.categoria.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
    @endif
  </span>
</div>
@endcan
@empty
<div class="col-xs-12 no-padding grid-table">
  <div class="col-xs-12">Importação inexistente ou não encontrada</div>
</div>
@endforelse

<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>