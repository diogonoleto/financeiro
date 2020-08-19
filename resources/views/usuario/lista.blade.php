@forelse($itens as $i)
@can('usuario_read', $i)
<div class="col-xs-12 no-padding grid-table">
  <div class="col-sm-2 col-xs-3">{{ $i->nome }}</div>
  <div class="col-sm-2 col-xs-2">{{ Helper::cpfcnpj($i->cpf) }}</div>
  <div class="col-sm-3 col-xs-2">
    @foreach($i->regra as $r)
    <span>{{ substr($r->nome, $qtdsci) }}</span>
    @endforeach
  </div>
  <div class="col-sm-3 col-xs-3">
    @foreach($i->contatos as $e)
    @if($e->tipo_contato == 1 && $e->principal == 1)
    {{ $e->descricao }}
    @endif
    @endforeach
  </div>
  <div class="col-sm-2 col-xs-2 telefone">
    @foreach($i->contatos as $t)
    @if($t->tipo_contato == 2 && $t->principal == 1)
    {{ $t->descricao }}
    @endif
    @endforeach
  </div>
  @if($i->razao_social != 'Usuários Padrão')
  <span class="tools-user">
    @can('usuario_update', $i)
    <a href="#" route="{{ route('usuario.edit', $i->id) }}" class="btn-usuario-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @if($i->id != Auth()->user()->id)
    @can('usuario_delete')
    <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('usuario.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
    @endif
  </span>
  @endif
</div>
@endcan
@empty
<div class="col-xs-12 no-padding grid-table">
  <div class="col-xs-12">Usuários inexistente ou não encontrado</div>
</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>