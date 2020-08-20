<div class="tab-content col-xs-12">
  <div class="tab-pane{{ $item->id ? null : ' fade in active' }}" id="NovaConta">
    <div class="title">Adicione uma nova conta</div>
    <div style="padding-top: 20px;">
      <a href="#ContaCorrente" class="ca-slimbox" data-toggle="tab" rel="1">
        <img alt="Conta Corrente" src="{{ url('img/icon/banco.svg') }}" >
        <div class="stronger">Conta Corrente</div>
      </a>
      <a href="#ContaCorrente" class="ca-slimbox" data-toggle="tab" rel="4">
        <img alt="Cartão de Crédito" src="{{ url('img/icon/cartao-credito.svg') }}">
        <div class="stronger">Cartão de Crédito</div>
      </a>
      <a href="#ContaCorrente" class="ca-slimbox" data-toggle="tab" rel="2">
        <img alt="Poupança" src="{{ url('img/icon/poupanca.svg') }}">
        <div class="stronger">Poupança</div>
      </a>
      <a href="#ContaCorrente" class="ca-slimbox" data-toggle="tab" rel="8">
        <img alt="Caixinha" src="{{ url('img/icon/caixinha.svg') }}">
        <div class="stronger">Caixinha</div>
      </a>
    </div>
  </div>
  <div class="tab-pane{{ $item->id ? ' fade in active' : null }}" id="ContaCorrente">
    <div class="title"></div>
    <form class="ContaForm" method="POST" enctype="multipart/form-data" @if(isset($item->id)) action="{{ route('conta.update', $item->id) }}">
      {{ method_field('put') }}
      @else
      action="{{ route('conta.store') }}">
      @endif
      {{ csrf_field() }}
      <div class="form-crud scrollbar-inner">
        <div class="col-lg-12 div-ccorrente div-poupanca">
          <div class="form-group" id="banco-div">
            <label class="div-ccorrente" for="banco_input">Banco</label>
            <label class="div-poupanca" for="banco_input">Banco Emissor</label>
            <input type="text" class="form-control uppercase" id="banco_input" autocomplete="off" value="{{ isset($item->banco->nome) ? $item->banco->nome : null }}">
            <input type="hidden" class="form-control" id="banco_id" name="banco_id" value="{{ $item->banco_id }}">
            <span class="help-block"></span>
          </div>
          <ul class="ul-banco scrollbar-inner" >
            @foreach($bancos as $i)
            <li style="display: none;" class="uppercase" rel="{{ $i->id }}">
              <a href="#" class="btn-banco uppercase" rel="{{ $i->id }}" img="{{ $i->img }}"><span>{{ $i->codigo }}</span> - {{ $i->nome }}</a>
            </li>
            @endforeach
          </ul>
        </div>
        <div class="col-sm-12 div-ccorrente div-poupanca div-caixinha div-ccredito hidden" id="div-image">
          <div class="col-sm-12 no-padding" style="margin-top: 10px; margin-bottom: 10px">
            @for($i=1; $i < 7; $i++)
            <div class="col-xs-2" style="padding: 2px!important;" >
              <img src="{{ url('img/bancos/default'.$i.'.png') }}" class="img-thumbnail img-circle img-responsive" id="btn-icone" rel="{{ 'default'.$i.'.png' }}">
            </div>
            @endfor
          </div>
          <div class="form-group">
            <input type="file" class="form-control" name="img" id="img">
            <input type="hidden" name="icone" id="icone">
          </div>
        </div>
        <div class="col-lg-12 div-ccorrente div-poupanca div-caixinha div-ccredito">
          <div class="input-group">
            <label for="descricao" style="margin-left: 50px;">Descrição <span>*</span></label>
            <span class="input-group-addon no-padding" id="sizing-addon" style="background-color: #fff">
              @if($item->img)
              <a href="#" id="btn-banco-img-editar"><img id="icone-img" src="{{ url($item->img) }}" style="width: 38.02px;"></a>
              @else
              <a href="#" id="btn-banco-img-editar"><img id="icone-img" src="{{ url('img/bancos/default1.png') }}" style="width: 38.02px;"></a>
              @endif
            </span>
            <input type="text" id="descricao" value="{{ $item->descricao }}" aria-describedby="sizing-addon" class="form-control" name="descricao" maxlength="100" minlength="3" placeholder="Ex: Itaú - Conta Corrente" autocomplete="off" required>
            <span class="help-block"></span>
          </div>
        </div>
        <div class="col-lg-12 div-ccorrente">
          <fieldset class="padrao">
            <legend>Conta Padrão</legend>
            <a href="#" class="checkbox-padrao">
              <input type="checkbox" id="padrao" name="padrao" value="1">
              <i class="mdi  {{ $item->padrao == 1 ? 'mdi-checkbox-marked-outline' : 'mdi-checkbox-blank-outline' }} mdi-24px "></i>
            </a>
          </fieldset>
        </div>
        <div class="col-lg-12 div-ccorrente">
          <fieldset>
            <legend>Tipo de Pessoa <span style="color:red">*</span></legend>
            <a href="#" style="margin-right: 30px;" class="fisica-juridica">
              <input type="radio" name="tipo_pessoa" value="fisica" required {{ ($item->tipo_pessoa == 'fisica' or !$item->id ) ? 'checked' : null }} >
              <i style="margin-top: 10px; margin-bottom: 4px;margin-left: 10px;" class="mdi {{ ( $item->tipo_pessoa == 'fisica' or !$item->id ) ? 'mdi-checkbox-marked-circle-outline' : 'mdi-checkbox-blank-circle-outline' }} mdi-24px radio"></i>
              Pessoa Física
            </a>
            <a href="#" class="fisica-juridica">
              <input type="radio" name="tipo_pessoa" value="juridica" {{ $item->tipo_pessoa == 'juridica' ? 'checked' : null }}>
              <i style="margin-top: 10px; margin-bottom: 4px;" class="mdi mdi-checkbox-blank-circle-outline {{ $item->tipo_pessoa == 'juridica' ? 'mdi-checkbox-marked-circle-outline' : 'mdi-checkbox-blank-circle-outline' }} mdi-24px radio"></i>
              Pessoa Jurídica
            </a>
          </fieldset>
        </div>
        <div class="col-lg-12 div-ccorrente div-poupanca div-caixinha">
          <div class="form-group">
            <label for="saldo">Saldo Inicial <span>*</span></label>
            <input type="text" name="saldo" id="saldo" value="{{ number_format($item->saldo, 2, ',', '.') }}" class="form-control text-right" placeholder="0,00" maxlength="20" autocomplete="off">
          </div>
        </div>
        <div class="col-lg-12 div-ccorrente div-poupanca div-caixinha">
          <div class="form-group">
            <label for="saldo_data">Data do saldo</label>
            <input type="text" name="saldo_data" id="saldo_data" value="{{ Carbon\Carbon::parse($item->saldo_data)->format('d/m/Y') }}" class="form-control text-right" placeholder="DD/MM/AAAA" required maxlength="10" autocomplete="off">
          </div>
        </div>
        <div class="col-lg-12 div-ccorrente div-poupanca">
          <div class="form-group">
            <label for="agencia">Agência</label>
            <input type="text" name="agencia" id="agencia" value="{{ $item->agencia }}" class="form-control text-right" autocomplete="off">
          </div>
        </div>
        <div class="col-lg-12 div-ccorrente div-poupanca div-ccredito">
          <div class="form-group">
            <label class="div-ccorrente div-poupanca" for="conta">Conta</label>
            <label class="div-ccredito" for="conta">Últimos 04 números 
              <i class="pull-right mdi mdi-information-outline" data-toggle="popover" data-trigger="hover" data-container="body" data-placement="left" title="" data-content="Digite apenas os 04 últimos números do seu cartão de crédito para fins de identificação. Ex: xxxx-xxxx-xxxx-1234" style="margin-top: -6px;margin-left: 5px;"></i>
            </label>
            <input type="text" name="conta" id="conta" value="{{ $item->conta }}" class="form-control text-right" maxlength="11" autocomplete="off">
          </div>
        </div>
        <div class="col-lg-12 div-ccorrente div-ccredito">
          <div class="form-group">
            <label for="saldo">Limite</label>
            <input type="text" name="limite" id="limite" value="{{ number_format($item->limite, 2, ',', '.') }}" class="form-control text-right" placeholder="0,00" maxlength="20" autocomplete="off">
          </div>
        </div>
        <div class="col-lg-12 div-ccredito text-center" id="bandeira">
          <div class="col-lg-2">
            <a href="#" class="bandeira{{ ($item->bandeira == 'VISA' or !$item->id) ? ' active' : null }}" tooltip="Visa">
              <img src="{{ url('img/credit/visa.svg') }}" alt="Visa" src="{{ url('img/credit/visa.svg') }}">
              <input type="radio" name="bandeira" value="VISA" required {{ ( $item->bandeira == 'VISA' or !$item->id ) ? 'checked' : null }} >
            </a>
          </div>
          <div class="col-xs-2">
            <a href="#" class="bandeira{{ $item->bandeira == 'MASTERCARD' ? ' active' : null }}" tooltip="Mastercard">
              <img src="{{ url('img/credit/mastercard.svg') }}" alt="Mastercard" src="{{ url('img/credit/mastercard.svg') }}">
              <input type="radio" name="bandeira" value="MASTERCARD" {{ $item->bandeira == 'MASTERCARD' ? 'checked' : null }}>
            </a>
          </div>
          <div class="col-xs-2">
            <a href="#" class="bandeira{{ $item->bandeira == 'DINERS' ? ' active' : null }}" tooltip="Diners">
              <img src="{{ url('img/credit/diners.svg') }}" alt="Diners" src="{{ url('img/credit/diners.svg') }}">
              <input type="radio" name="bandeira" value="DINERS" {{ $item->bandeira == 'DINERS' ? 'checked' : null }}>
            </a>
          </div>
          <div class="col-xs-2">
            <a href="#" class="bandeira{{ $item->bandeira == 'AMERICANEXPRESS' ? ' active' : null }}" tooltip="American Express">
              <img src="{{ url('img/credit/americanexpress.svg') }}" alt="American Express" src="{{ url('img/credit/americanexpress.svg') }}">
              <input type="radio" name="bandeira" value="AMERICANEXPRESS" {{ $item->bandeira == 'AMERICANEXPRESS' ? 'checked' : null }}>
            </a>
          </div>
          <div class="col-xs-2">
            <a href="#" class="bandeira{{ $item->bandeira == 'ELO' ? ' active' : null }}" tooltip="Elo">
              <img src="{{ url('img/credit/elo.svg') }}" alt="Elo" src="{{ url('img/credit/elo.svg') }}">
              <input type="radio" name="bandeira" value="ELO" {{ $item->bandeira == 'ELO' ? 'checked' : null }}>
            </a>
          </div>
          <div class="col-xs-2">
            <a href="#" class="bandeira{{ $item->bandeira == 'OUTRO' ? ' active' : null }}" tooltip="Outro">
              <img src="{{ url('img/credit/outro.svg') }}" alt="Outro" src="{{ url('img/credit/outro.svg') }}">
              <input type="radio" name="bandeira" value="OUTRO" {{ $item->bandeira == 'OUTRO' ? 'checked' : null }}>
            </a>
          </div>
        </div>
        <div class="col-lg-12 div-poupanca div-ccredito">
          <div class="form-group">
            <label for="conta_id">Conta Corrente Vinculada</label>
            <select class="form-control" name="conta_id">
              <option value="">Não possui conta padrão</option>
              @foreach($contas as $i)
              <option value="{{ $i->id }}" {{ $i->id == $item->conta_id ? 'selected' : null }} >{{ $i->descricao }}</option>
              @endforeach
            </select>
            <span class="help-block"></span>
          </div>
        </div>
        <div class="col-lg-12 div-ccredito">
          <div class="form-group">
            <label for="dia_vencimento">Dia do vencimento <span>*</span></label>
            <input type="number" autocomplete="off" name="dia_vencimento" id="dia_vencimento" min="1" max="31" maxlength="2" value="{{ $item->dia_vencimento }}" class="form-control text-right" placeholder="Informe o dia">
          </div>
        </div>
      </div>
      <div class="form-footer">
        <input type="hidden" name="conta_tipo_id" id="conta_tipo_id">
        @if(!$item->id)
        <a href="#NovaConta" class="btn pull-left btn-lg btn-default" data-toggle="tab">VOLTAR</a>
        @else
        <a href="#" class="btn pull-left btn-lg btn-default btn-conta-cancelar">CANCELAR</a>
        @endif
        <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
      </div>
    </form>
  </div>
</div>