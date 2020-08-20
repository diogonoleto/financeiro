@forelse($itens as $key => $i)
  @can('config_usuario_read', $i)
  <div class="col-xs-12 col-sm-3 grid-table grid grid-{{ $key }}">
    <div class="col-xs-3 no-padding">
      @if($i->img)
      <img src="{{ url($i->img) }}" class="img-thumbnail img-circle">
      @else
      <img src="{{ url('img/avatars/avatar-blank.png')}}" class="img-thumbnail img-circle">
      @endif
    </div>
    <div class="col-xs-9 no-padding">
      <div style="width: 40px; padding: 0px 7px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>
      <div class="col-sm-3 col-xs-4" >{{ $i->nome }}</div>
      <div class="col-sm-3 col-xs-4">{{ $i->cnome }}</div>
      <div class="col-sm-3 col-xs-4">{{ $i->email }}</div>
      <div class="col-sm-3 col-xs-4" style="width: calc(25% - 40px);">{{ $i->telefone }}</div>
    </div>
      <!-- <span class="tools-user">
      @-can('config_usuario_update')
      <a href="#" route="{{ route('usuario.edit', $i->id) }}" class="btn-usuario-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @-endcan
      @-can('config_usuario_delete')
      <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('usuario.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
      @-endcan 
    </span>-->
  </div>
  @endcan
@empty
<div class="col-xs-12 col-sm-3 grid-table grid">
  <div class="col-xs-3 no-padding"></div>
  <div class="col-xs-9 no-padding"><div class="col-xs-12">Usuário inexistente ou não encontrado</div></div>
</div>
@endforelse

<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>