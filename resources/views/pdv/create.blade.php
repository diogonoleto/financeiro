<div class="col-xs-12 no-padding" style="border: 1px solid #8BC34A; background-color: #fff; overflow: auto;" id="pdv-novo">

  <div style="position: fixed;margin-top: -15px;margin-left: 15px;z-index: 1;font-size: 20px;font-weight: 100;background-color: #fff;color: #8bc34a;">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}PDV</div>

  <form class="form" style="margin-top: 20px" method="post" id="pdvForm" @if(isset($item->id)) action="{{ route('pdv.update', $item->id) }}"> {!! method_field('put') !!} @else action="{{ route('pdv.store') }}"> @endif {!! csrf_field() !!}

    <div class="col-md-12">
      <div class="form-group">
        <label for="nome">Nome {{ $item->nome }}</label>
        <input id="nome" name="nome" class="form-control" type="text" size="65" maxlength="255" required="required" value="{{ $item->nome or old('nome') }}">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="responsavel">Responsável</label>
        <input id="responsavel" name="responsavel" class="form-control" type="text" size="65" maxlength="255" value="{{ $item->responsavel or old('responsavel') }}">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="local">Local</label>
        <input id="local" name="local" class="form-control" type="text" size="65" maxlength="255" value="{{ $item->local or old('local') }}">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <div class="col-sm-6" style="padding: 0 0 25px;">
          <label for="plataforma_id">Plataforma</label>
          <select id="plataforma_id" name="plataforma_id" class="form-control">
            @foreach($plataformas as $i)
            <option value="{{ $i->id }}" data-id="{{ $i->identificador }}" {{ $i->id == $item->plataforma_id ? 'selected' : null }} >{{ $i->nome }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-6" style="padding: 0 0 25px;">
          <label for="uuid">ID Dispositivo</label>
          <input id="uuid" name="uuid" class="form-control" type="text" size="65" maxlength="50" required="required" value="{{ $item->uuid or old('uuid') }}">
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="imprime">Modo de Impressão</label>
        <select id="imprime" name="imprime" class="form-control">
          <option value="0" {{ $item->imprime == 0 ? 'selected' : null }} >Finaliza</option>
          <option value="1" {{ $item->imprime == 1 ? 'selected' : null }} >Finaliza+Cupom</option>
          <option value="2" {{ $item->imprime == 2 ? 'selected' : null }} >Finaliza+Cupom+NFCe</option>
          <option value="3" {{ $item->imprime == 3 ? 'selected' : null }} >NFCe</option>
        </select>
      </div>
    </div>
    <div class="col-md-6 imprime-div hidden no-padding-right">
      <div class="form-group">
        <label for="imprime_ip">IP Impressora</label>
        <input id="imprime_ip" name="imprime_ip" class="form-control" type="text"  maxlength="100" value="{{ $item->imprime_ip or old('imprime_ip') }}">
      </div>
    </div>
    <div class="col-md-6 nfce-div hidden no-padding-left">
      <div class="form-group">
        <label for="nfce_ip">IP Dispositivo</label>
        <input id="nfce_ip" name="nfce_ip" class="form-control" type="text"  maxlength="100" value="{{ $item->nfce_ip or old('nfce_ip') }}">
      </div>
    </div>
    <div class="col-md-6 nfce-div hidden no-padding-right">
      <div class="form-group">
        <label for="nfce_num_serie">N° de Série da NFCE</label>
        <input id="nfce_num_serie" name="nfce_num_serie" class="form-control text-right" type="text" size="999" maxlength="3" value="{{ $item->nfce_num_serie or old('nfce_num_serie') }}">
      </div>
    </div>
    <div class="col-md-6 nfce-div hidden no-padding-left">
      <div class="form-group">
        <label for="nfce_num_nota">N° da Nota da NFCE</label>
        <input id="nfce_num_nota" name="nfce_num_nota" class="form-control text-right" type="text" size="9999999" maxlength="10" value="{{ $item->nfce_num_nota or old('nfce_num_nota') }}">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>PDV Ultiliza Mesa?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="mesa">
          <input type="checkbox" name="mesa" id="mesa" value="{{ (isset($item->mesa_qtd) and $item->mesa_qtd != 0) ? '1' : 0 }}" {{ (isset($item->mesa_qtd) and $item->mesa_qtd != 0) ? 'checked' : null }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-6 col-md-offset-6 mesa-div{{ (isset($item->mesa_qtd) and $item->mesa_qtd != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="mesa_qtd">Qtde de Mesa</label>
        <input type="text" id="mesa_qtd" name="mesa_qtd" class="form-control text-right" size="999" maxlength="3" value="{{ $item->mesa_qtd or old('mesa_qtd') }}">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <span>PDV Dar Desconto?</span>
        <label class="switch" style="float: right; margin-top: 0px;  {{ (isset($item->desc_valor_max) and $item->desc_valor_max != 0) ? 'margin-bottom: 0px' : 'margin-bottom: 40px' }}" for="desconto">
          <input type="checkbox" name="desconto" id="desconto"  value="{{ (isset($item->desc_valor_max) and $item->desc_valor_max != 0) ? '1' : 0 }}" {{ (isset($item->desc_valor_max) and $item->desc_valor_max != 0) ? 'checked' : null }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-6 desconto-div red-padding-right{{ (isset($item->desc_valor_max) and $item->desc_valor_max != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="desc_valor_max">Valor Max.</label>
        <input id="desc_valor_max" name="desc_valor_max" class="form-control text-right" type="text" maxlength="10" value="{{ $item->desc_valor_max or old('desc_valor_max') }}">
      </div>
    </div>
    <div class="col-md-6 desconto-div red-padding-left{{ (isset($item->desc_valor_max) and $item->desc_valor_max != 0) ? null : ' hidden' }}" style=" {{ (isset($item->desc_valor_max) and $item->desc_valor_max != 0) ? 'margin-bottom: 40px' : 'margin-bottom: 0px' }}">
      <div class="form-group">
        <label for="desc_perc_max">Perc. Max.</label>
        <input id="desc_perc_max" name="desc_perc_max" class="form-control text-right" type="text"  maxlength="5" value="{{  $item->desc_perc_max or old('desc_perc_max') }}">
      </div>
    </div>

    <div style="position: fixed;margin-left: 15px;z-index: 1; width: 24.7%" id="form-footer">
      <input type="hidden" name="id" value="">
      <a href="#" class="btn pull-left btn-lg btn-default btn-pdv-create">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>
</div>