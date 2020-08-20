<div class="col-xs-12 no-padding" id="conta-novo">
  <div class="title">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}Usuário</div>
  <form class="form" method="post" id="contaForm" @if(isset($item->id)) action="{{ route('adminCon.conta.update', $item->id) }}"> {{ method_field('put') }} @else action="{{ route('adminCon.conta.store') }}"> @endif {{ csrf_field() }}
    <div class="form-crud">
      <div class="col-md-12">
        <h4>Informações da Conta</h4>
        <hr style="margin-top: 10px; margin-bottom: 10px;">
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="nome">Empresa Grupo</label>
          <input type="text" name="nomeeg" id="nomeeg" class="form-control" placeholder="Digite o Nome" value="{{ $item->nome or old('nome') }}" required>
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="qtde_empresa">Qtde Máx de Empresa</label>
          <input type="text" class="form-control text-right" value="{{ $item->qtde_empresa or old('qtde_empresa') }}" name="qtde_empresa" id="qtde_empresa">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="qtde_funcionario">Qtde Máx de Usuário do Sistema</label>
          <input type="text" class="form-control text-right" value="{{ $item->qtde_funcionario or old('qtde_funcionario') }}" name="qtde_funcionario" id="qtde_funcionario">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="qtde_cliente">Qtde Máx de Cliente</label>
          <input type="text" class="form-control text-right" value="{{ $item->qtde_cliente or old('qtde_cliente') }}" name="qtde_cliente" id="qtde_cliente">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="qtde_fornecedor">Qtde Máx de Fornecedor</label>
          <input type="text" class="form-control text-right" value="{{ $item->qtde_fornecedor or old('qtde_fornecedor') }}" name="qtde_fornecedor" id="qtde_fornecedor">
        </div>
      </div>
      <div class="col-md-12">
        <h4 style="margin-top: 20px;">Informações do Usuário Principal</h4>
        <hr style="margin-top: 10px; margin-bottom: 10px;">
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="nome">Nome Completo</label>
          <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o Nome" value="{{ $item->user->nome or old('nome') }}" required>
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input type="email" class="form-control" value="{{ $item->user->email or old('email') }}" placeholder="Digite o E-Mail" name="email" id="email" required>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="telefone">Telefone</label>
          <input type="text" class="form-control text-right" value="{{ $item->user->telefone or old('telefone') }}" name="telefone" id="telefone" required>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="cpf">CPF</label>
          <input type="text" class="form-control text-right" value="{{ $item->user->cpf or old('cpf') }}" name="cpf" id="cpf" required>
        </div>
      </div>

      
      <div class="col-md-12">
        <h4 style="margin-top: 20px;">Informações de Endereço</h4>
        <hr style="margin-top: 10px; margin-bottom: 10px;">
      </div>
      <div class="col-md-12">
        <div class="form-group">
        <label for="cep">CEP</label>
          <input type="text" name="cep" id="cep" class="form-control text-right" maxlength="8" placeholder="CEP" value="{{ $item->user->cep or old('cep') }}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="cidade">Cidade</label>
          <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade" value="{{ $item->user->cidade or old('cidade') }}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="logradouro">Endereço</label>
          <input type="text" name="logradouro" id="logradouro" class="form-control" maxlength="100" placeholder="Endereço" value="{{ $item->user->logradouro or old('logradouro') }}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="numero">Número</label>
          <input type="text" name="numero" id="numero" class="form-control text-right" maxlength="5" placeholder="Número" value="{{ $item->user->numero or old('numero') }}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="complemento">Complemento</label>
          <input type="text" name="complemento" id="complemento" class="form-control" maxlength="50" placeholder="Complemento" value="{{ $item->user->complemento or old('complemento') }}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="bairro">Bairro</label>
          <input type="text" name="bairro" id="bairro" class="form-control" maxlength="80" placeholder="Bairro" value="{{ $item->user->bairro or old('bairro') }}">
        </div>
      </div>
      <div class="col-md-12">
        <h4>Modulos</h4>
        <hr style="margin-top: 10px; margin-bottom: 10px;">
      </div>
      @foreach($modulos as $m)
        @php($check = '')
        @if( count($modulo) > 0 )
          @foreach($modulo as $im)
            @if($m->id == $im->getOriginal('pivot_sis_modulo_id'))
              @php($check = 'checked=checked')
            @endif
          @endforeach
        @endif
        <div class="col-md-12">
          <div class="form-group">
            <span>{{ $m->nome }}</span>
            <label class="switch" style="float: right; margin-top: 0px;" for="modulos{{ $m->id }}">
              <input type="checkbox" name="modulos[]" id="modulos{{ $m->id }}" class="modulos" value="{{ $m->id }}" {{ $check }}>
              <div class="slider round"></div>
            </label>
          </div>
        </div>
      @endforeach
      <div class="col-md-12{{ !isset($item->id) ? ' hidden' : null }}">
        <h4 style="margin-top: 20px;">Gerenciar conta</h4>
        <hr style="margin-top: 10px; margin-bottom: 10px;">
      </div>
      <div class="col-md-12{{ !isset($item->id) ? ' hidden' : null }}">
        <div class="form-group">
          <span>{{ isset($item->status) &&  $item->status == 1 ? 'Desativar' : 'Ativar' }} conta</span>
          <label class="switch" style="float: right; margin-top: 0px;" for="ativa">
            <input type="checkbox" name="ativa" id="ativa" value="{{ $item->status }}">
            <div class="slider round"></div>
          </label>
        </div>
      </div>
    </div>
    <div class="form-footer">
      <a href="#" class="btn pull-left btn-lg btn-default btn-conta-create">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>
</div>