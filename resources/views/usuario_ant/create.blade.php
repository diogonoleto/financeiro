<div class="col-xs-12" id="usuario-novo">
  <div class="title">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}Usuário</div>
  <form method="post" id="usuarioForm" @if(isset($item->id)) action="{{ route('usuario.update', $item->id) }}">
    {{ method_field('put') }} @else action="{{ route('usuario.store') }}"> @endif {{ csrf_field() }}
    <div class="form-crud">
      <div class="col-md-12">
        <div class="form-group">
          <label for="nome">Nome Completo</label>
          <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o Nome" value="{{ $item->nome or old('nome') }}" autocomplete="off" required>
          <span class="help-block"></span>
        </div>
      </div>
      @forelse($item->regra as $r)
      <div class="col-md-12">
        <div class="form-group">
          <label for="regra_id">Perfil do Usuario</label>
          <select class="form-control" name="regra_id" id="regra_id" required>
            <option value="">Selecione o Perfil</option>
            @foreach($regras as $i)
            <option value="{{ $i->id }}" {{ ($i->id == $r->id or old('regra_id')) ? 'selected' : null }} >{{ substr($i->nome, $qtdsci) }}</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>
      @empty
      <div class="col-md-12">
        <div class="form-group">
          <label for="regra_id">Perfil do Usuario</label>
          <select class="form-control" name="regra_id" id="regra_id" required>
            <option value="">Selecione o Perfil</option>
            @foreach($regras as $i)
            <option value="{{ $i->id }}">{{ $i->descricao }}({{ substr($i->nome, $qtdsci) }})</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>
      @endforelse
      <div class="col-md-12">
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input type="email" class="form-control" autocomplete="off" value="{{ $item->email or old('email') }}" name="email" id="email" required>
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="telefone_principal">Telefone</label>
          <input type="phone" class="form-control text-right" value="{{ $item->telefone or old('telefone_principal') }}" name="telefone_principal" id="telefone_principal" required>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="cpf">CPF</label>
          <input type="text" class="form-control text-right" value="{{ $item->cpf or old('cpf') }}" name="cpf" id="cpf" required>
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="cpf">Cargo</label>
          <input type="text" class="form-control" autocomplete="off" value="{{ $item->cargo or old('cargo') }}" name="cargo" id="cargo">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <span>Endereço</span>
          <label class="switch" style="float: right; margin-top: 0px; {{ (isset($item->cep) or isset($item->logradouro)) ? 'margin-bottom: 0px' : 'margin-bottom: 40px' }};" for="endereco">
            <input type="checkbox" name="endereco" id="endereco" maxlength="1" value="{{ (isset($item->cep) or isset($item->logradouro))  ? '1' : 0 }}" {{ (isset($item->cep) or isset($item->logradouro)) ? 'checked' : null }}>
            <div class="slider round"></div>
          </label>
        </div>
      </div>
      <div class="col-md-12 no-padding endereco-div{{ (isset($item->cep) or isset($item->logradouro)) ? null : ' hidden' }}">
        <div class="col-md-12">
          <div class="form-group">
          <label for="cep">CEP</label>
            <input type="text" name="cep" id="cep" class="form-control text-right" maxlength="8" placeholder="CEP" value="{{ $item->cep or old('cep') }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="cidade">Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade" value="{{ $item->cidade or old('cidade') }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="logradouro">Endereço</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" maxlength="100" placeholder="Endereço" value="{{ $item->logradouro or old('logradouro') }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="numero">Número</label>
            <input type="text" name="numero" id="numero" class="form-control text-right" maxlength="5" placeholder="Número" value="{{ $item->numero or old('numero') }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="complemento">Complemento</label>
            <input type="text" name="complemento" id="complemento" class="form-control" maxlength="50" placeholder="" value="{{ $item->complemento or old('complemento') }}">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="bairro">Bairro</label>
            <input type="text" name="bairro" id="bairro" class="form-control" maxlength="80" placeholder="Bairro" value="{{ $item->bairro or old('bairro') }}">
          </div>
        </div>
      </div>
    </div>
    <div class="form-footer">
      <input type="hidden" name="id" value="">
      <a href="#" class="btn pull-left btn-lg btn-default btn-usuario-cancelar">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>
</div>