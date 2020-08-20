<div class="col-xs-12" id="delete-novo">
  <div class="title">Encerramento de Conta</div>
  <form class="form-crud" action="{{ route('conta.destroy', $id) }}" method="post" id="deleteForm">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <input type="hidden" name="conta_transferencia_id" id="conta_transferencia_id">
    <input type="hidden" name="delete_confirmar" id="delete_confirmar" value="1">
    <div class="col-xs-12">
      <p>CONTA:</p>
      <p>{{ $conta->descricao }}</p>
      <p>SALDO:</p>
      <p>{{ $conta->valor }}</p>
      <p>Antes de encerrar, selecione uma conta para transferência dos lançamentos.</p>
      <div class="row">
        <div class="col-lg-12">
          @foreach($contas as $i)
            <div class="col-lg-2 btn-conta-transferencia" rel="{{ $i->id }}" style="padding: 10px 5px"><img title="{{ $i->descricao }}" src="{{ url($i->img) }}" class="img-responsive img-thumbnail"></div>
          @endforeach
        </div>
      </div>
      <div class="bg-warning" role="alert">
        <strong>Atenção:</strong> Todos os lançamentos em aberto e o saldo remanescente serão transferidos para a conta selecionada. A Diretorio Digital manterá o histórico da conta encerrada para sua consulta futura.
      </div>
    </div>
  </form>
  <div class="form-footer">
    <a href="#" class="btn pull-left btn-lg btn-default btn-delete-cancelar">CANCELAR</a>
    <a href="#" class="btn pull-right btn-lg btn-success" id="btn-conta-deletar">DELETAR</a>
  </div>
</div>