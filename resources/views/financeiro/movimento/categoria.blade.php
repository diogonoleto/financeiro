@foreach($categorias as $k => $c)
<a href="#" class="{{ count($c->children)>0 ? null : 'btn-categoria '  }}" rel="{{ $c->id }}" style="font-weight: 400;">{{ $c->cod }}{{ $c->nome }}</a>
@if(count($c->children))
@include('financeiro.movimento.categoria',['categorias' => $c->children, 'n' => $c->cod ])
@endif
@endforeach