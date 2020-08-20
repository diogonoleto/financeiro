<div class="col-xs-12 no-padding" style="border: 1px solid #8BC34A; background-color: #fff; overflow: auto;" id="estoque-novo">

  <div style="position: fixed;margin-top: -15px;margin-left: 15px;z-index: 1;font-size: 20px;font-weight: 100;background-color: #fff;color: #8bc34a;">{{ isset($item->id) ? 'Editar ' : 'Nova ' }}Compra</div>

  <form class="form" style="margin-top: 20px" method="post" id="estoqueForm" @if(isset($item->id)) action="{{ route('estoque.update', $item->id) }}"> {!! method_field('put') !!} @else action="{{ route('estoque.store') }}"> @endif {!! csrf_field() !!}


    <div class="col-md-12">
      <div class="form-group">
      <label for="imprime_ip">Data da Emissão</label>
        <input id="imprime_ip" name="imprime_ip" class="form-control" type="text"  maxlength="100" value="{{ $item->imprime_ip or old('imprime_ip') }}">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="imprime_ip">Quantidade de Itens</label>
        <input id="imprime_ip" name="imprime_ip" class="form-control" type="text"  maxlength="100" value="{{ $item->imprime_ip or old('imprime_ip') }}">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="imprime_ip">Valor Total</label>
        <input id="imprime_ip" name="imprime_ip" class="form-control" type="text"  maxlength="100" value="{{ $item->imprime_ip or old('imprime_ip') }}">
      </div>
    </div>
    
    <div style="position: fixed;margin-left: 15px;z-index: 1; width: 24.7%" id="form-footer">
      <input type="hidden" name="id" value="">
      <a href="#" class="btn pull-left btn-lg btn-default btn-estoque-create">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>
</div>