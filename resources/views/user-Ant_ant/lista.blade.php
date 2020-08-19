@forelse(array_chunk($itens->getCollection()->all(), 4) as $row)
<div>
  @foreach($row as $key => $i)

  <div class="col-xs-12 col-sm-3 grid-table grid grid-{{ $key }}">
    <div class="col-xs-3 no-padding">
      @if($i->img)
      <img src="{{ url($i->img) }}" class="img-thumbnail img-circle">
      @else
      <img src="{{ url('img/avatar-blank.png')}}" class="img-thumbnail img-circle">
      @endif
    </div>
    <div class="col-xs-9 no-padding">
      <div style="width: 40px; padding: 0px 7px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-uni"></i></div>
      <a href="{{ route('usuario.show', $i->id) }}" class="btn-show">
        <div class="col-sm-3 col-xs-4" >{{ $i->nome }}</div>
        <div class="col-sm-3 hidden-xs">{{ $i->cargo }}</div>
        <div class="col-sm-3 hidden-xs">{{ $i->email }}</div>
        <div class="col-sm-3 col-xs-4" style="width: calc(25% - 40px);">{{ isset($i->userContato->descricao) ? $i->userContato->descricao : '' }}</div>
      </a>
    </div>
    <span class="tools-user">

      <a href="{{ route('usuario.edit', $i->id) }}" class="btn-tools-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>

      <a href="#" class="btn-tools-delete" rel="del-{{ $key }}" route="{{ route('usuario.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>

    </span>
  </div>

  @endforeach
</div>
@empty
<div class="col-xs-12">Usuario não encontrao</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>