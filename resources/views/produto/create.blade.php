<div class="col-xs-12 no-padding" style="border: 1px solid #8BC34A; background-color: #fff; overflow: auto;" id="produto-novo">

  <div style="position: fixed;margin-top: -15px;margin-left: 15px;z-index: 1;font-size: 20px;font-weight: 100;background-color: #fff;color: #8bc34a; ">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}Produto</div>

  <form class="form" style="margin-top: 20px" method="post" id="produtoForm" @if(isset($item->id)) action="{{ route('produto.update', $item->id) }}"> {!! method_field('put') !!} @else action="{{ route('produto.store') }}"> @endif {!! csrf_field() !!}


    <div class="col-xs-12">
      <div class="form-group" id="categoria-div">
        <label for="categoria_input">Categorias <span>*</span></label>
        <input type="text" class="form-control uppercase" id="categoria_input" autocomplete="off" required value="{{ isset($item->categoria->nome) ? $item->categoria->nome : null }}">
        <ul class="ul-categoria">
          @foreach($categorias as $i)
          <li style="display: none;" class="uppercase" rel="{{ $i->id }}"><div style="width: 85%; cursor: pointer;" class="btn-categoria uppercase" rel="{{ $i->id }}">{{ $i->nome }}</div><span class="pull-right" style="color: green; padding: 5px;"><div class="btn-categoria-create" rel="{{ $i->id }}" desc="{{ $i->descricao }}"><i class="mdi mdi-plus mdi-14px" style="border: 1px solid; padding-top: 2px;"></i></div></span>
            @foreach($i->children as $c)
            <a href="#" class="btn-categoria uppercase" rel="{{ $c->id }}">{{ $c->nome }}</a>
            @endforeach
          </li>
          @endforeach
        </ul>
        <input type="hidden" class="form-control" id="produto_categoria_id" name="produto_categoria_id" required value="{{ $item->produto_categoria_id }}">
        <span class="help-block"></span>
      </div>
    </div>

    <div class="col-xs-12">
      <div class="form-group">
        <label for="descricao">Nome</label>
        <input type="text" class="form-control" name="nome" id="descricao" value="{{ $item->nome }}" placeholder="Defina o nome do produto!" maxlength="200" required>
        <span class="help-block"></span>
      </div>
    </div>

    <div class="col-xs-12">
      <div class="form-group">
        <label for="descricao">Rótulo</label>
        <input type="text" class="form-control" name="rotulo" id="rotulo" value="{{ $item->rotulo }}" placeholder="Defina um rotulo para o produto!" maxlength="200" required>
        <span class="help-block"></span>
      </div>
    </div>
    
    <!--     <div class="col-md-12"><hr></div> -->
    
    <div class="col-md-12">
      <hr>
      <h4>Informações de Venda</h4>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="ean">Cód. Barra</label>
        <input id="ean" name="ean" class="form-control" type="text" maxlength="255" required="required" value="">
      </div>
    </div>
    <div class="col-xs-12">
      <div class="form-group">
        <label for="unidade_medida_id">Unidade de Medida</label>
        <select class="form-control" name="unidade_medida_id">
          <option value="">Selecione a unidade</option>
          @foreach($unidades as $i)
          <option value="{{ $i->id }}" {{ ( isset($item->unidade_medida_id ) or ($i->id == $item->unidade_medida_id) )  ? 'selected' : null }} >{{ $i->descricao }}</option>
          @endforeach
        </select>
        <span class="help-block"></span>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="capacidade">Capacidade</label>
        <input id="capacidade" name="capacidade" class="form-control" type="text" maxlength="12" required="required" value="">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="preco">Preço de Venda</label>
        <input id="preco" name="preco" class="form-control" type="text" maxlength="12" required="required" value="">
      </div>
    </div>
    <div class="col-md-12">
      <hr>
      <h4>Informações adicionais</h4>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <label for="destinacao">Destinação</label>
        <select id="destinacao" name="destinacao" class="form-control">
          <option value="1">Venda</option>
          <option value="2">Material de Uso/Consumo/Expediente</option>
        </select>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="peso_liquido">Peso Líquido (Kg)</label>
        <input id="peso_liquido" name="peso_liquido" class="form-control" placeholder="0.000" value="">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="peso_bruto">Peso Bruto (Kg)</label>
        <input id="peso_bruto" name="peso_bruto" class="form-control" placeholder="0.000" value="">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label for="ordem">Ordem</label>
        <select id="ordem" name="ordem" class="form-control">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>Informar Fornecedor?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="fornecedor">
          <input type="checkbox" name="fornecedor" id="fornecedor" value="{{ (isset($item->fornecedor_id) and $item->fornecedor_id > 0) ? '1' : 0 }}" {{ (isset($item->fornecedor_id) and $item->fornecedor_id > 0) ? 'checked' : null }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-12 fornecedor-div{{ (isset($item->fornecedor_id) and $item->fornecedor_id != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="fornecedor_id">Fornecedor</label>
        <select id="fornecedor_id" name="fornecedor_id" class="form-control">
          <option value="">Selecione o Fornecedor</option>
          @foreach($fornecedores as $i)
          <option value="{{ $i->id }}" {{ $i->id == $item->fornecedor_id ? 'selected' : null }} >{{ $i->nome_fantasia }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>Controlar Estoque?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="controla_estoque">
          <input type="checkbox" name="controla_estoque" id="controla_estoque" value="1" {{ (isset($item->aviso_validade) and $item->aviso_validade != 0) ? 'checked' : null }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-8 col-md-offset-4 controla_estoque-div{{ (isset($item->aviso_validade) and $item->aviso_validade != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="aviso_validade">Aviso de Validade (Em dias)</label>
        <input type="text" id="aviso_validade" name="aviso_validade" class="form-control text-right" size="999" maxlength="3" value="{{ $item->aviso_validade }}">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>Disponível para venda Produto</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="disponivel_venda">
          <input type="checkbox" name="disponivel_venda" id="disponivel_venda" value="1" {{ $item->disponivel_venda == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <span>Produto é Perecivel</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="perecivel">
          <input type="checkbox" name="perecivel" id="perecivel" value="1" {{ $item->perecivel == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <span>Produto é Fracionado</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="fracionado">
          <input type="checkbox" name="fracionado" id="fracionado" value="1" {{ $item->fracionado == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>Tipo Insumo</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="tipo_insumo">
          <input type="checkbox" name="tipo_insumo" id="tipo_insumo" value="1" {{ $item->tipo_insumo == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <span>Usado em Composição?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="usado_composicao">
          <input type="checkbox" name="usado_composicao" id="usado_composicao" value="1" {{ $item->usado_composicao == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <span>Principal?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="principal_dashboard">
          <input type="checkbox" name="principal_dashboard" id="principal_dashboard" value="1" {{ $item->principal_dashboard == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <span>Grade?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="grade_item">
          <input type="checkbox" name="grade_item" id="grade_item" value="1" {{ $item->grade_item == 0 ? null : 'checked' }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>Custo com Valor Fixo?</span>
        <label class="switch" style="float: right; margin-top: 0px;" for="custo_fixo">
          <input type="checkbox" name="custo_fixo" id="custo_fixo" value="{{ (isset($item->valor_custo_fixo) and $item->valor_custo_fixo != 0) ? '1' : 0 }}" {{ (isset($item->valor_custo_fixo) and $item->valor_custo_fixo != 0) ? 'checked' : null }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>
    <div class="col-md-8 col-md-offset-4 custo_fixo-div{{ (isset($item->valor_custo_fixo) and $item->valor_custo_fixo != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="valor_custo_fixo">Valor Fixo</label>
        <input type="text" id="valor_custo_fixo" name="valor_custo_fixo" class="form-control text-right" size="999" maxlength="3" value="{{ $item->valor_custo_fixo or old('valor_custo_fixo') }}">
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <span>Informar Tributo?</span>
        <label class="switch" style="float: right; margin-top: 0px;  {{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? 'margin-bottom: 0px' : 'margin-bottom: 40px' }};" for="fiscal">
          <input type="checkbox" name="fiscal" id="fiscal" value="{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? '1' : 0 }}" {{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? 'checked' : null }}>
          <div class="slider round"></div>
        </label>
      </div>
    </div>

    <div class="col-md-12 fiscal-div{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <input id="codigo_ncm" name="codigo_ncm" type="hidden" value="">
        <label for="descricao_ncm">NCM</label>
        <input id="descricao_ncm" nome="descricao_ncm" type="text" class="form-control ul-autocomplete-input" value="" autocomplete="off">
      </div>
    </div>

    <div class="col-md-12 fiscal-div{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="id_icms_origem_mercadoria">Origem</label>
        <select id="id_icms_origem_mercadoria" name="id_icms_origem_mercadoria" class="form-control select2">
          <option value="">Selecione...</option>
          <option value="0">0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8</option>
          <option value="1">1 - Estrangeira - Importação direta, exceto a indicada no código 6</option>
          <option value="2">2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7</option>
          <option value="3">3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%</option>
          <option value="4">4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes</option>
          <option value="5">5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%</option>
          <option value="6">6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural</option>
          <option value="7">7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural</option>
          <option value="8">8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%</option>
        </select>
      </div>
    </div>
    <div class="col-md-12 fiscal-div{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <input id="codigo_cfop" name="codigo_cfop" type="hidden" value="">
        <label for="descricao_cfop">CFOP Padrão (utlizado apenas na emissão de NFC-E)</label>
        <input id="descricao_cfop" nome="descricao_cfop" type="text" class="form-control ul-autocomplete-input" value="" autocomplete="off">
      </div>
    </div>

    <div class="col-md-12 fiscal-div{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="icms_id">Grupo de ICMS</label>
        <select id="icms_id" name="icms_id" class="form-control select2">
          <option value="0">Selecione...</option>
          <option value="1">102 - SIMPLES NACIONAL</option>
        </select>
      </div>
    </div>
    <div class="col-md-12 fiscal-div{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? null : ' hidden' }}">
      <div class="form-group">
        <label for="pis_cofins_id">Grupo de PIS/COFINS</label>
        <select id="pis_cofins_id" name="pis_cofins_id" class="form-control select2">
          <option value="0">Selecione...</option>
          <option value="1">99 - SIMPLES</option>
        </select>
      </div>
    </div>
    <div class="col-md-12 fiscal-div{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? null : ' hidden' }}" style="{{ (isset($item->descricao_ncm) and $item->descricao_ncm != 0) ? 'margin-bottom: 0px' : 'margin-bottom: 40px' }};">
      <div class="form-group">
        <label for="ipi_id">Grupo de IPI</label>
        <select id="ipi_id" name="ipi_id" class="form-control select2">
          <option value="0">Selecione...</option>
          <option value="1">SIMPLES IPI</option>
        </select>
      </div>
    </div>

    <div style="position: fixed;margin-left: 15px;z-index: 1; width: 24.7%" id="form-footer">
      <a href="#" class="btn pull-left btn-lg btn-default btn-categoria-cancelar">CANCELAR</a>
      <button type="submit" class="btn pull-right btn-lg btn-success">{{ $item->id ? 'EDITAR' : 'SALVAR' }}</button>
    </div>
  </form>
</div>

<div class="hidden" id="categoria-nova" >
  <form class="form" method="post" id="form-categoria" action="{{ route('produto.categoria.store') }}">
    {!! csrf_field() !!}
    <div class="col-xs-12">
    <h3>Nova Subcategoria</h3>
    </div>
    <div class="col-xs-12">
      <div class="form-group">
        <select class="form-control uppercase" name="categoria_id" id="categoria_id" required>
          @foreach($categorias as $i)
          <option value="{{ $i->id }}" desc="{{ $i->descricao }}">{{ $i->nome }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-xs-12">
      <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome">
      </div>
    </div>
    <div class="col-xs-12">
      <div class="form-group">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control" name="descricao" id="descricao">
      </div>
    </div>
    <div class="col-xs-12">
      <div class="form-group">
        <!--         <input type="hidden" name="tipo" value=""> -->
        <a href="#" class="btn pull-left btn-lg btn-default btn-categoria-cancelar">CANCELAR</a>
        <button type="submit" class="btn pull-right btn-lg btn-success">{{ $item->id ? 'EDITAR' : 'SALVAR' }}</button>
      </div>
    </div>
  </form>
</div>