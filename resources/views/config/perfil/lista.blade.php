@forelse($itens as $i)
  @can('config_perfil_read', $i)
    <div class="col-xs-12 no-padding grid-table">
      <div class="col-sm-6">{{ substr($i->nome, $qtdsci) }}</div>
      <div class="col-sm-6">{{ $i->descricao }}</div>
      <span class="tools-user">
      @if($i->nome != Auth()->user()->sis_conta_id.'_admin_empresa' && $i->nome != '0_administrador')
        @can('config_perfil_update')
          <a href="{{ route('configPer.perfil.edit', $i->id) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
        @endcan
        @can('config_perfil_delete')
          <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('configPer.perfil.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
        @endcan
      @endif
      </span>
    </div>
  @endcan
@empty
<div class="col-xs-12 no-padding grid-table">
  <div class="col-xs-12">Perfil inexistente ou não encontrado</div>
</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>