@forelse(array_chunk($itens->getCollection()->all(), 4) as $row)
<div>
  @foreach($row as $key => $i)
  @can('usuario_read', $i)
  <div class="col-xs-12 col-sm-3 grid-table grid grid-{{ $key }}">
    <div class="col-xs-3 no-padding">
      @if($i->img)
      <img src="{{ url($i->img) }}" class="img-thumbnail img-circle">
      @else
      <img src="{{ url('img/avatar-blank.png')}}" class="img-thumbnail img-circle">
      @endif
    </div>
    <div class="col-xs-9 no-padding">
      <div class="col-sm-3 col-xs-3">{{ $i->nome }}</div>
      <div class="col-sm-3 col-xs-3">
        @foreach($i->regra as $r)
          <span>{{ substr($r->nome, $qtdsci) }}</span>
        @endforeach
      </div>
      <div class="col-sm-3 col-xs-3">{{ $i->email }}</div>
      <div class="col-sm-3 col-xs-3">{{ $i->telefone }}</div>
    </div>
    <span class="tools-user">
      @can('usuario_update')
      <a href="#" route="{{ route('usuario.edit', $i->id) }}" class="btn-usuario-edit usuario-{{ $i->id }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @endcan
      @if($i->id != Auth()->user()->id)
        @can('usuario_delete')
        <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('usuario.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
        @endcan
      @endif
    </span>
  </div>
  @endcan
  @endforeach
</div>
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