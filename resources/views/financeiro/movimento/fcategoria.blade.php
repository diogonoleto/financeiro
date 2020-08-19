@foreach($categorias as $c)
	@if(count($c->children))
	  @include('financeiro.movimento.fcategoria',['categorias' => $c->children])
	@else
		<li><a href="#" class="fcategoria" rel="{{ $c->id }}" title="{{ $c->nome }}">{{ $c->nome }}</a></li>
	@endif
@endforeach