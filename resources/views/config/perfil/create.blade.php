<div class="col-xs-12" id="perfil-novo">
  <div class="title">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}Perfil</div>
  <form class="form-crud" method="post" id="perfilForm" @if(isset($item->id)) action="{{ route('configPer.perfil.update', $item->id) }}"> {{ method_field('put') }} @else action="{{ route('configPer.perfil.store') }}"> @endif
    {{ csrf_field() }}
    <div class="col-xs-12">
      <div class="form-group">
        <label for="nome">Perfil <span>*</span></label>
        <input type="text" class="form-control" autocomplete="off" name="nome" id="nome" value="{{ $item->nome }}" placeholder="Defina um nome para o perfil" minlength="5" maxlength="20" pattern="^[a-zA-Z_\-]+$" required>
      </div>
    </div>
    <div class="col-xs-12">
      <div class="form-group">
        <label for="descricao">Descrição <span>*</span></label>
        <input type="text" class="form-control" autocomplete="off" name="descricao" id="descricao" value="{{ $item->descricao }}" placeholder="Descrição do perfil" maxlength="200" required>
      </div>
    </div>
    <input type="submit" id="btn-perfil-form" class="hidden">
  </form>
  <div class="form-footer">
    <a href="#" class="btn pull-left btn-lg btn-default btn-perfil-cancelar">CANCELAR</a>
    <a href="#" class="btn pull-right btn-lg btn-success" id="btn-perfil-salvar">{{ $item->id ? 'EDITAR' : 'SALVAR' }}</a>
  </div>
</div>