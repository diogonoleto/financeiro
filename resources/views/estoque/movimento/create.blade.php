<div class="col-xs-12 no-padding" style="border: 1px solid #8BC34A; background-color: #fff; overflow: auto;" id="estoque-movimento-novo">

  <div style="position: fixed;margin-top: -15px;margin-left: 15px;z-index: 1;font-size: 20px;font-weight: 100;background-color: #fff;color: #8bc34a;">{{ isset($item->id) ? 'Editar ' : 'Adicionar ' }}Produto</div>

  <form class="form" style="margin-top: 20px" method="post" id="estoque-movimentoForm" action="{{ route('mve.movimento.store') }}">
  {!! csrf_field() !!}


    <div class="col-md-12">
      <div class="form-group">
      <label for="produto">Produto</label>
        <input type="text" id="produto" name="produto" class="form-control"  required="">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="qtde">Quantidade</label>
        <input type="text" id="qtde" name="qtde" class="form-control"  required="">
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="form-group">
        <label for="unidade_id">Unidade</label>
        <input type="text" id="qtde" name="qtde" class="form-control"  required="">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="qtde">Data</label>
        <input type="text" id="qtde" name="qtde" class="form-control"  required="">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="unidade_id">Unidade</label>
        <input type="text" id="qtde" name="qtde" class="form-control"  required="">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="unidade_id">Data de Validade</label>
        <input type="text" id="qtde" name="qtde" class="form-control"  required="">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="unidade_id">Observação</label>
        <input type="text" id="qtde" name="qtde" class="form-control"  required="">
      </div>
    </div>

    
    <div style="position: fixed;margin-left: 15px;z-index: 1; width: 24.7%" id="form-footer">
      <input type="hidden" name="id" value="">
      <a href="#" class="btn pull-left btn-lg btn-default btn-estoque-movimento-create">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>
</div>