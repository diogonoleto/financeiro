<div class="col-xs-12" id="delete-novo">
  <div class="title">Encerramento de perfil</div>
  <form class="form-crud" action="{{ route('configPer.perfil.destroy', $id) }}" method="post" id="deleteForm">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <input type="hidden" name="delete_confirmar" id="delete_confirmar" value="{{ $delete_confirmar }}" teste="10">
    <div class="col-xs-12">
      <p>PERFIL:</p>
      <p>{{ $perfil->nome }}</p>
      <p>Antes de encerrar o perfil, os usuarios tem que migrar pra outro pefil</p>
      <div class="row">
        <div class="col-lg-12">
          @foreach($perfil->user as $i)
            <div class="col-xs-12" rel="{{ $i->id }}" style="padding: 10px 5px"><div class="col-xs-3"><img src="{{ url($i->img) }}" class="img-responsive img-thumbnail"></div><div class="col-xs-9"> {{ $i->nome }}</div></div>
          @endforeach
        </div>
      </div>
    </div>
    <input type="submit" class="hidden">
  </form>
  <div class="form-footer">
    <a href="#" class="btn pull-left btn-lg btn-default btn-delete-cancelar">CANCELAR</a>
    <a href="#" class="btn pull-right btn-lg btn-success" id="btn-perfil-deletar">DELETAR</a>
  </div>
</div>