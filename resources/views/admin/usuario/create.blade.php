<div class="col-xs-12 no-padding" style="border: 1px solid #8BC34A; background-color: #fff; overflow: auto;" id="usuario-novo">

  <div style="position: fixed;margin-top: -15px;margin-left: 15px;z-index: 1;font-size: 20px;font-weight: 100;background-color: #fff;color: #8bc34a;">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}Usuário</div>

  <form class="form" style="margin-top: 20px" method="post" id="usuarioForm" @if(isset($item->id)) action="{{ route('usuario.update', $item->id) }}"> {!! method_field('put') !!} @else action="{{ route('usuario.store') }}"> @endif {!! csrf_field() !!}

    <div class="col-md-12">
      <div class="form-group">
        <label for="nome">Nome Completo</label>
        <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o Nome" value="{{ $item->nome or old('nome') }}" required>
        <span class="help-block"></span>
      </div>
    </div>

    @forelse($item->regra as $r)
      <div class="col-md-12">
        <div class="form-group">
          <label for="regra_id">Perfil do Usuario</label>
          <select class="form-control" name="regra_id[]" required>
            <option value="">Selecione o Perfil</option>
            @foreach($regras as $i)
            <option value="{{ $i->id }}" {{ ($i->id == $r->id or old('regra_id')) ? 'selected' : null }} >{{ $i->nome }}</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>
    @empty
      <div class="col-md-12">
        <div class="form-group">
          <label for="regra_id">Perfil do Usuario</label>
          <select class="form-control" name="regra_id" required>
            <option value="">Selecione o Perfil</option>
            @foreach($regras as $i)
            <option value="{{ $i->id }}">{{ $i->nome }}</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>
    @endforelse

    <div class="col-md-12">
      <div class="form-group">
        <label for="email_principal">E-Mail</label>
        <input type="email" class="form-control" value="{{ $item->email or old('email_principal') }}" name="email_principal" id="email_principal" required>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="telefone_principal">Telefone</label>
        <input type="phone" class="form-control" value="{{ $item->telefone or old('telefone_principal') }}" name="telefone_principal" id="telefone_principal" required>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control" value="{{ $item->cpf or old('cpf') }}" name="cpf" id="cpf" required>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="cpf">Cargo</label>
        <input type="text" class="form-control" value="{{ $item->cargo or old('cargo') }}" name="cargo" id="cargo" required>
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
    <div class="col-md-12 no-padding endereco-div{{ (isset($item->cep) or isset($item->logradouro)) ? null : ' hidden' }}" style="{{ (isset($item->cep) or isset($item->logradouro)) ? 'margin-bottom: 0px' : 'margin-bottom: 40px' }};">
      <div class="col-md-12">
        <div class="form-group">
        <label for="cep">CEP</label>
          <input type="text" name="cep" id="cep" class="form-control" maxlength="8" placeholder="CEP" value="{{ $item->cep or old('cep') }}">
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
          <input type="text" name="numero" id="numero" class="form-control" maxlength="5" placeholder="Número" value="{{ $item->numero or old('numero') }}">
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

    

    <!-- <div class="col-md-12"> -->
      <!--       <h5 style="color: #999;">E-MAILS</h5>
      <hr style="margin-top: 0px">
      <div class="pull-right" style="margin-top: -50px;">
        <a href="#" class="btn btn-default btn-email-add" style="padding: 2px 4px; line-height: 1;"><i class="mdi mdi-plus" style="color: green;"></i></a>
      </div> -->

      <!-- <div class="form-group">
        <div class="col-sm-4" style="padding: 0 0 25px;">
          <div class="input-group">
            <span class="input-group-addon">
              <input type="radio" checked="true">
            </span>
            <label for="email_principal_tipo" style="left: 35px; z-index: 3;">Tipo</label>
            <input type="email" class="form-control" name="email_principal_tipo" value="Principal" disabled="true" required>
          </div>
        </div>
        <div class="col-sm-8" style="padding: 0 0 25px;">
          <label for="email_principal">E-Mail Principal</label>
          <input type="email" class="form-control" value="{{ $item->email or old('email_principal') }}" name="email_principal" id="email_principal" style="border-left: 0;" required>
        </div>
        <span class="help-block"></span>
      </div> -->
    <!-- </div> -->

    <!--  @-foreach($item->userContato as $k => $ema)
    <div class="form-group">
      <input type="hidden" value="{{ $ema->id or old('email_id') }}" name="email_id">
      <div class="col-sm-4" style="padding: 0 0 25px;">
        <div class="input-group">
          <span class="input-group-addon">
            <input type="radio">
          </span>
          <label for="email_tipo" style="left: 45px; z-index: 3;">Tipo</label>
          <input type="text" class="form-control" value="{{ $ema->tipo or old('email_tipo') }}" name="email_tipo" id="email_tipo" required>
        </div>
      </div>
      <div class="col-sm-8" style="padding: 0 0 25px;">
        <label for="email_email" style="left: 10px;">E-mail</label>
        <input type="email" class="form-control" value="{{ $userContato->descricao or old('email_email') }}" name="email_email" required>
      </div>
      <span class="help-block"></span>
    </div>
    @-endforeach -->


    <!-- <div class="col-md-12">
      <h5 style="color: #999;">TELEFONES</h5>
      <hr style="margin-top: 0px">
      <div class="pull-right" style="margin-top: -50px;">
        <a href="#" class="btn btn-default btn-contato-create" style="padding: 2px 4px; line-height: 1;" route="{{ route('usuario.contato') }}" data-toggle="tooltip" title="Adicionar Telefone" data-placement="bottom"><i class="mdi mdi-plus" style="color: green;"></i></a>
      </div>
      
      @-forelse($item->userContato as $k => $fone)
        if($fone->tipo == 'telefone')
          <div class="form-group">
            <input type="hidden" value="{{ $fone->id or old('telefone_id') }}" name="telefone_id">
            <div class="col-sm-4" style="padding: 0 0 25px;">
              <div class="input-group">
                <span class="input-group-addon">
                  <input type="radio" name="radio">
                </span>
                <label for="telefone_tipo" style="left: 37px; z-index: 3;">Tipo</label>
                <input type="text" class="form-control" value="{{ $fone->tipo or old('telefone_tipo') }}" name="telefone_tipo" id="telefone_tipo" required>
              </div>
            </div>
            <div class="col-sm-8" style="padding: 0 0 25px;">
              <label for="telefone_numero">Telefone</label>
              <input type="text" class="form-control" value="{{ $fone->descricao or old('telefone_numero') }}" name="telefone_numero" id="telefone_numero" style="border-left: 0;" required>
            </div>
            @if ($errors->has('telefone[$k]'))
            <span class="help-block">
              <strong>{{ $errors->first('telefone[$k]') }}</strong>
            </span>
            @endif
          </div>
        @endelse
      @-empty
        <div class="form-group">
          <input type="hidden" value="" name="telefone_id">
          <div class="col-sm-4" style="padding: 0 0 25px;">
            <div class="input-group">
              <span class="input-group-addon">
                <input type="radio" name="radio">
              </span>
              <label for="telefone_tipo" style="left: 37px; z-index: 100;">Tipo</label>
              <input type="text" class="form-control" name="telefone_tipo" id="telefone_tipo" required>
            </div>
          </div>
          <div class="col-sm-8" style="padding: 0 0 25px;">
            <label for="telefone_numero">Telefone</label>
            <input type="text" class="form-control" name="telefone_numero" id="telefone_numero" style="border-left: 0;" required>
          </div>
          <span class="help-block"></span>
        </div>
      @-endforelse
      <div class="form-group" id="div-contato">
      </div>
    </div> -->


    <div style="position: fixed;margin-left: 15px;z-index: 1; width: 24.7%" id="form-footer">
      <input type="hidden" name="id" value="">
      <a href="#" class="btn pull-left btn-lg btn-default btn-usuario-create">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>



<!--   <form id="form-contato-create" action="" class="hidden" method="GET">
    {{ csrf_field() }}
  </form> -->



</div>