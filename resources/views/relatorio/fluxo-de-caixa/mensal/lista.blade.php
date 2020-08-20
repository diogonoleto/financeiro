@foreach($novacateg as $k => $m)
  <tr>
    <td>{{ $n }}.{{ $k+1 }}.{{ $m->nome }}</td>
    <td>{{ $m->pjan }}</td>
    <td>{{ $m->rjan }}</td>
    <td>{{ $m->pfev }}</td>
    <td>{{ $m->rfev }}</td>
    <td>{{ $m->pmar }}</td>
    <td>{{ $m->rmar }}</td>
    <td>{{ $m->pabr }}</td>
    <td>{{ $m->rabr }}</td>
    <td>{{ $m->pmai }}</td>
    <td>{{ $m->rmai }}</td>
    <td>{{ $m->pjun }}</td>
    <td>{{ $m->rjun }}</td>
    <td>{{ $m->pjul }}</td>
    <td>{{ $m->rjul }}</td>
    <td>{{ $m->pago }}</td>
    <td>{{ $m->rago }}</td>
    <td>{{ $m->psetembro }}</td>
    <td>{{ $m->rsetembro }}</td>
    <td>{{ $m->poutubro }}</td>
    <td>{{ $m->routrubro }}</td>
    <td>{{ $m->pnov }}</td>
    <td>{{ $m->rnov }}</td>
    <td>{{ $m->pdez }}</td>
    <td>{{ $m->rdez }}</td>
    @if(count($m->filho))
        @include('financeiro.relatorio.fluxo-de-caixa.mensal.lista',['novacateg' => $m->filho, 'i' => $i+1, 'n' => $n.'.'.($k+1)])
    @endif
@endforeach
