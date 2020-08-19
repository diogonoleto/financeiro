
@forelse($itens as $key => $i)
@can('produto_read', $i)
<div class="col-xs-12 no-padding grid-table">

  <div style="width: 30px; padding: 0px 8px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>
  <div class="col-sm-2 col-xs-4" style="width: calc(16.66666667% - 30px);">{{ $i->cod_barra }}</div>
  <div class="col-sm-3 col-xs-4">{{ $i->nome }}</div>
  <div class="col-sm-3 hidden-xs">{{ isset($i->fornecedor->nome_fantasia) ? $i->fornecedor->nome_fantasia : null }}</div>
  <div class="col-sm-2 col-xs-4">{{ isset($i->produtoCategoria->nome) ? $i->produtoCategoria->nome : null }}</div>
  <div class="col-sm-2 hidden-xs produto"><span style="float: left;">R$ </span><span style="float: right;">{{ number_format($i->preco, 2, ',', '.') }}</span></div>

  <span class="tools-user">
    @can('produto_update')
    <a href="#" route="{{ route('produto.edit', $i->id) }}" class="btn-produto-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('produto_delete')
    <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('produto.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
  </span>
</div>

@endcan
@empty
<div class="col-xs-12">
</div>
@endforelse