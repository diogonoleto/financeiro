<ul class="submenu collapse" id="{{ $id }}">
  @foreach($categorias as $k => $categoria)
  <li class="submenu-item">
    @if(count($categoria->children))
    <a class="menu-dropdown collapsed" role="button" data-toggle="collapse" href="#cid-{{ $categoria->id }}" aria-expanded="false" aria-controls="{{ $categoria->cod }}" no="{{ $categoria->nome }}" de="{{ $categoria->desc }}"><e></e>{{ $categoria->cod }}{{ $categoria->nome }}<div class="desc"> {{ $categoria->desc }}</div></a>
    <span class="tools-categoria">
      <a href="#" class="btn btn-default btn-dre-edit" rel="{{ $categoria->id }}" route="{{ route('fin.categoria.dre') }}" style="padding: 4px 6px 1px;font-size: 18px;">DRE</a>
      @can('fin_categoria_delete')
      <a href="#" class="btn btn-default btn-categoria-delete del-{{ $categoria->id }}" id="del-{{ $categoria->id }}" route="{{ route('fin.categoria.destroy', $categoria->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
      @endcan
      @can('fin_categoria_update')
      <a href="#" class="btn btn-default btn-subcategoria-edit" route="{{ route('fin.categoria.update', $categoria->id) }}" nome="{{ $categoria->nome }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @endcan
      @can('fin_categoria_create')
      <a href="#" class="btn btn-default btn-subcategoria-create" rel="{{ $categoria->id }}" tipo="{{ $categoria->tipo }}"><i class="mdi mdi-plus mdi-24px"></i></a>
      @endcan
    </span>
    @include('financeiro.categoria.sublista',['categorias' => $categoria->children, 'id' => 'cid-'.$categoria->id ])
    @else
    <a href="#" class="submenu-target waves-effect waves-theme {{ count($categorias) == $k+1 ? 'llc' : '' }}" no="{{ $categoria->nome }}" de="{{ $categoria->desc }}">{{ $categoria->cod }}{{ $categoria->nome }}<div class="desc"> {{ $categoria->desc }}</div></a>
<!--     <a href="#" class="">{{ $categoria->desc }}</a> -->
    <span class="tools-categoria">
      <a href="#" class="btn btn-default btn-dre-edit" rel="{{ $categoria->id }}" route="{{ route('fin.categoria.dre') }}" style="padding: 4px 6px 1px;font-size: 18px;">DRE</a>
      @can('fin_categoria_delete')
      <a href="#" class="btn btn-default btn-categoria-delete del-{{ $categoria->id }}" id="del-{{ $categoria->id }}" route="{{ route('fin.categoria.destroy', $categoria->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
      @endcan
      @can('fin_categoria_update')
      <a href="#" class="btn btn-default btn-subcategoria-edit" route="{{ route('fin.categoria.update', $categoria->id) }}" nome="{{ $categoria->nome }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
      @endcan
      @can('fin_categoria_create')
      <a href="#" class="btn btn-default btn-subcategoria-create" rel="{{ $categoria->id }}" tipo="{{ $categoria->tipo }}"><i class="mdi mdi-plus mdi-24px"></i></a>
      @endcan
    </span>
    @endif
  </li>
  @endforeach
</ul>