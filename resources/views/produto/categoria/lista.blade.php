@forelse($itens as $i)
@can('produto_categoria_read', $i)
<div class="col-xs-12 no-padding categorias" >
<hr>
  <div style="color: #8BC34A; border: 0">
    <div class="col-xs-6 uppercase">{{ $i->nome }}</div>
    <div class="col-xs-3">{{ $i->pdv == 1 ? 'sim' : 'não' }}</div>
    <div class="col-xs-3 pdv">{{ $i->nivel }}</div>
  </div>
  <span class="tools-categoria">
    @can('produto_categoria_edit')
    <a href="#" class="btn btn-default btn-categoria-edit" route="{{ route('produto.categoria.update', $i->id) }}" nome="{{ $i->nome }}" tipo="{{ $i->tipo }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('produto_categoria_create')
    <a href="#" class="btn btn-default btn-subcategoria-create" rel="{{ $i->id }}" tipo="{{ $i->tipo }}"><i class="mdi mdi-plus mdi-24px"></i></a>
    @endcan
    @can('produto_categoria_delete')
    <a href="#" class="btn btn-default btn-categoria-delete" route="{{ route('produto.categoria.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
  </span>
<hr>
  @foreach($i->children as $s)
  <div class="col-xs-12 no-padding grid-table">
    <div class="col-xs-6 capitalize" style="padding-left: 30px!important;">{{ $s->nome }}</div>
    <div class="col-xs-3">{{ $s->pdv == 1 ? 'sim' : 'não' }}</div>
    <div class="col-xs-3 pdv">{{ $s->nivel }}</div>
    <span class="tools-subcategoria">
      @can('produto_categoria_edit')
      <a href="#" class="btn btn-default btn-subcategoria-edit" route="{{ route('produto.categoria.update', $s->id) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @endcan
      @can('produto_categoria_delete')
      <a href="#" class="btn btn-default btn-categoria-delete" route="{{ route('produto.categoria.destroy', $s->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
      @endcan
    </span>
  </div>
  @endforeach
</div>
@endcan
@empty
<div class="col-xs-12">
</div>
@endforelse