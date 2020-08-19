<form class="form" method="GET" id="form-dre" action="{{ route('fin.categoria.dre') }}">
  {{ csrf_field() }}
  <div class="col-xs-12">
    <h3>Grupo do DRE Gerencial</h3>
  </div>
  <div class="col-xs-12">
    <p style="min-height: 35px; margin: 10px 0 10px;">Defina em qual grupo do relatório a categoria <b>{{ $item->nome }}</b> será exibida.</p>
    <div class="form-group">
      <select class="form-control uppercase" name="dre_id" id="dre_id" required>
        <option value="0">Não mostrar categoria no DRE Gerencial</option>
        @foreach($dres as $i)
        <option value="{{ $i->id }}" desc="{{ $i->descricao }}" {{ ( $i->id == $item->dre_id ) ? 'selected' : null }} > {{ $i->descricao }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-xs-12">
    <div class="form-group">
      <input type="hidden" name="categoria_id" value="{{ $item->id }}">
      <a href="#" class="btn pull-left btn-lg btn-default btn-dre-cancelar">CANCELAR</a>
      <button type="submit" class="btn pull-right btn-lg btn-success">SALVAR</button>
    </div>
  </div>
</form>