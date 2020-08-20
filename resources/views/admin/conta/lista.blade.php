@forelse($itens as $i)
  @can('config_conta_read', $i)
  @if($i->nome != 'Administrador')
  <div class="col-xs-12 no-padding grid-table">
    <div class="col-sm-3 col-xs-4 text-left" >{{ $i->nome }}</div>
    <div class="col-sm-2 col-xs-2">{{ $i->qtde_empresa }}</div>
    <div class="col-sm-2 col-xs-2">{{ $i->qtde_cliente }}</div>
    <div class="col-sm-2 col-xs-2">{{ $i->qtde_fornecedor }}</div>
    <div class="col-sm-2 col-xs-2">{{ $i->qtde_funcionario }}</div>
    <div class="col-sm-1 col-xs-1 status">
      <label class="switch" style="margin-left:0px; " for="status">
        <input type="checkbox" name="status" id="status" maxlength="1" value="{{ $i->status == 1  ? '1' : 0 }}" {{ $i->status == 1 ? 'checked' : null }}>
        <div class="slider round"></div>
      </label>
    </div>
    <span class="tools-user">
      @can('config_conta_update')
      <a href="#" route="{{ route('adminCon.conta.edit', $i->id) }}" class="btn-conta-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @endcan
      @can('config_conta_delete')
      <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('adminCon.conta.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
      @endcan
    </span>
  </div>
  @endif
  @endcan
@empty
<div class="col-xs-12 no-padding grid-table">
  <div class="col-xs-12">Conta inexistente ou não encontrado</div>
</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>