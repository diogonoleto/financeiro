<div class="col-xs-12" style="border: 1px solid #8BC34A; background-color: #fff; padding: 0" id="compra-novo">
  <div class="title">Dados da Compra</div>
  <form class="form-crud  form" method="post" id="compraForm" @if(isset($item->id)) action="{{ route('compra.update', $item->id) }}"> {{ method_field('put') }} @else action="{{ route('compra.store') }}"> @endif {{ csrf_field() }}
    <div class="col-md-12">
      <div class="form-group">
        <label for="empresa_id">Empresa</label>
        <select id="empresa_id" name="empresa_id" class="form-control">
          @foreach($empresas as $i)
          <option value="{{ $i->id }}" {{ $i->id == $item->empresa_id ? 'selected' : null }} >{{ $i->nome_fantasia }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="fornecedor_id">Fornecedor</label>
        <select id="fornecedor_id" name="fornecedor_id" class="form-control">
          @foreach($fornecedores as $i)
          <option value="{{ $i->id }}" {{ $i->id == $item->fornecedor_id ? 'selected' : null }} >{{ $i->nome_fantasia }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="estoque_id">Estoque</label>
        <select id="estoque_id" name="estoque_id" class="form-control">
          @foreach($estoques as $i)
          <option value="{{ $i->id }}" {{ $i->id == $item->estoque_id ? 'selected' : null }} >{{ $i->nome }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="data_emissao">Data da Emissão</label>
        <input type="text" id="data_emissao" name="data_emissao" class="form-control text-right" value="{{ $item->data_emissao }}">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <div class="col-sm-4" style="padding: 0 0 10px;">
          <label for="documento_tipo">Tipo</label>
          <select id="documento_tipo" name="documento_tipo" class="form-control">
            <option value="1" {{ 1 == $item->documento_tipo ? 'selected' : null }} >Nota Fiscal</option>
            <option value="2" {{ 2 == $item->documento_tipo ? 'selected' : null }} >Recibo</option>
          </select>
        </div>
        <div class="col-sm-8" style="padding: 0 0 10px;">
          <label for="documento_num">N° Documento</label>
          <input id="documento_num" name="documento_num" class="form-control" type="text" size="65" maxlength="50" required="required" value="{{ $item->documento_num }}" style="border-left: 0;">
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="qtde">Quantidade</label>
        <input type="text" id="qtde" name="qtde" class="form-control text-right" value="{{ $item->qtde }}">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="valor">Valor da Nota</label>
        <input type="text" id="valor" name="valor" class="form-control text-right" value="{{ $item->valor }}">
      </div>
    </div>

    <div class="form-footer">
      <input type="hidden" name="id" value="">
      <a href="#" class="btn pull-left btn-lg btn-default btn-compra-create">CANCELAR</a>
      <input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
    </div>
  </form>
</div>