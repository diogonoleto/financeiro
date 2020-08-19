<div class="col-xs-12 hidden" id="movimento-novo">
  <div class="title">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}{{ $tipo == "Receita"? 'Recebimento' : 'Pagamento'}}</div>
  <form class="form-crud scrollbar-inner" method="post" id="movimentoForm" @if(isset($item->id)) action="{{ route('movimento.update', $item->id) }}"> {{ method_field('put') }} @else action="{{ route('movimento.store') }}"> @endif
    {{ csrf_field() }}

    <div class="col-xs-12">
      <div class="form-group">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control" name="descricao" class="col-xs-12" id="descricao" value="{{ $item->descricao }}" placeholder="Defina um nome para seu pagamento" maxlength="200" autocomplete="off" required>
      </div>
    </div>
    <div class="col-xs-12">
      <div class="form-group" id="categoria-div">
        <label for="categoria_input">Categoria <span>*</span></label>
        <input type="text" class="form-control" id="categoria_input" autocomplete="off" required value="{{ isset($item->categoria->nome) ? $item->categoria->nome : null }}">
        <ul class="ul-categoria scrollbar-inner">
          @foreach($categorias as $k => $i)
          <li style="display: none;" rel="{{ $i->id }}">
            <span style="width: calc(100% - 36px);"><a href="#" class="{{ count($i->children)>0 ? null : 'btn-categoria '  }}" rel="{{ $i->id }}">{{ $i->cod }}{{ $i->nome }}</a></span>
            @can('fin_categoria_create', $i)
            <span style="color: green; width: 30px; padding: 0;">
              <div class="btn-categoria-create" rel="{{ $i->id }}" desc="{{ $i->descricao }}">
                <i class="mdi mdi-plus mdi-14px" style="border: 1px solid;"></i>
              </div>
            </span>
            @endcan
            @if(count($i->children))
            @include('financeiro.movimento.categoria',['categorias' => $i->children, 'n' => $i->cod ])
            @endif
          </li>
          @endforeach
        </ul>
        <input type="hidden" id="categoria_id" name="categoria_id" required value="{{ $item->categoria_id }}">
        <span class="help-block"></span>
      </div>
    </div>
    @if(isset($conta))
    <input type="hidden" name="conta_id" value="{{ $conta->id}}">
    @else
    <div class="col-xs-12">
      <div class="form-group">
        <label for="conta_id">Conta <span>*</span></label>
        <select class="form-control" name="conta_id" required>
          <option value="">Selecione a conta</option>
          @foreach($contas as $i)
          <option value="{{ $i->id }}" {{ ( ( $i->id == $item->conta_id ) or ( $i->padrao == '1' and !isset($item->conta_id) ) )  ? 'selected' : null }} >{{ $i->descricao }}</option>
          @endforeach
        </select>
        <span class="help-block"></span>
      </div>
    </div>
    @endif
    <div class="col-xs-12">
      <div class="form-group">
        <label class="finance-label-small" for="data_emissao">{{ isset($conta) ? 'Data compra' : 'Data competência' }} <i title="Data em que a {{ $tipo=='Receita' ? 'receita' : 'despesa'}} ocorreu" class="mdi mdi-information-outline mdi-14px" style="position: absolute; right: -18px; margin-top: -8px; background-color: #fff;"></i></label>
        <input type="text" class="form-control text-right" id="data_emissao" required placeholder="Data da {{ $tipo=='Receita' ? 'recebimento' : 'compra'}}" autocomplete="off" name="data_emissao" maxlength="10" value="{{ isset($item->data_emissao) ? Carbon\Carbon::parse($item->data_emissao)->format('d/m/Y') : null }}">
        <span class="help-block"></span>
      </div>
    </div>
    @if(!isset($conta))
    <div class="col-xs-12">
      <div class="form-group">
        <label for="data_vencimento">Data vencimento</label>
        <input type="text" class="form-control text-right" autocomplete="off" value="{{ isset($item->data_vencimento) ? Carbon\Carbon::parse($item->data_vencimento)->format('d/m/Y') : null }}" name="data_vencimento" id="data_vencimento" maxlength="10" required>
      </div>
    </div>
    @endif
    <div class="col-xs-12">
      <div class="form-group">
        <label for="valor">Valor</label>
        <input type="text" class="form-control text-right" name="valor" maxlength="15" value="{{ isset($item->valor) ? number_format($item->valor, 2, ',', '.') : null }}" id="valor" required autocomplete="off" placeholder="0,00">
      </div>
    </div>
    @if(!isset($item->id))
    <div class="row" style="margin: 0">
      <div class="col-md-12" id="divr">
        <div class="form-group">
          <span>Repetir?</span>
          <label class="switch" style="float: right; margin-top: 0px;" for="repetir">
            <input type="checkbox" name="repetir" id="repetir" maxlength="1" value="{{ (isset($item->ciclo) and $item->ciclo != 0) ? '1' : 0 }}" {{ (isset($item->ciclo) and $item->ciclo != 0) ? 'checked' : null }}>
            <div class="slider round"></div>
          </label>
        </div>
      </div>
    </div>
    <div class="row" style="margin: 0">
      <div class="col-xs-12 repetir-div{{ (isset($item->ciclo) and $item->ciclo != 0) ? null : ' hidden' }}">
        <div class="form-group" style="margin-top: 0px;">
          <label>Ciclo</label>
          <select name="ciclo" id="ciclo" class="form-control">
            <option value="days">Diariamente</option>
            <option value="weeks">Semanalmente</option>
            <option value="months" selected="">Mensalmente</option>
            <option value="2months">Bimestralmente</option>
            <option value="3months">Trimestralmente</option>
            <option value="6months">Semestralmente</option>
            <option value="years">Anualmente</option>
          </select>
        </div> 
      </div>
    </div>
    <div class="row" style="margin: 0">
      <div class="col-xs-12 repetir-div{{ (isset($item->ciclo) and $item->ciclo != 0) ? null : ' hidden' }}">
        <div class="form-group" style="margin-top: 0px;">
          <label for="repeticoes">Ocorrências</label>
          <input type="text" class="form-control" name="repeticoes" id="repeticoes" maxlength="3" style="text-align: right;">
          <label>vezes</label>
        </div>
      </div>
    </div>
    @endif
    @if(!isset($conta))
    <div class="row" style="margin: 0">
      <div class="col-md-12">
        <div class="form-group">
          <span>{{ $tipo == "Receita"? 'Recebido' : 'Pago'}}?</span>
          <label class="switch" style="float: right; margin-top: 0px;" for="pago">
            <input type="checkbox" name="pago" id="pago" maxlength="1" value="{{ (isset($item->data_baixa) and $item->data_baixa != 0) ? '1' : 0 }}" {{ (isset($item->data_baixa) and $item->data_baixa != 0) ? 'checked' : null }}>
            <div class="slider round"></div>
          </label>
        </div>
      </div>
    </div>
    <div class="row" style="margin: 0">
      <div class="col-xs-12 pago-div{{ (isset($item->data_baixa) and $item->data_baixa != 0) ? null : ' hidden' }}">
        <div class="form-group">
          <label for="data_baixa">{{ $tipo == "Receita"? 'Data Recebimento' : 'Data pagamento'}}</label>
          <input type="text" class="form-control text-right" id="data_baixa" name="data_baixa" autocomplete="off" maxlength="10" value="{{ isset($item->data_baixa) ? Carbon\Carbon::parse($item->data_baixa)->format('d/m/Y') : null }}">
        </div>
      </div>
    </div>
    <div class="row" style="margin: 0">
      <div class="col-xs-12 pago-div{{ (isset($item->data_baixa) and $item->data_baixa != 0) ? null : ' hidden' }}">
        <div class="form-group">
          <label for="desconto">Descontos</label>
          <input type="text" class="form-control text-right" name="desconto" maxlength="15" id="desconto" value="{{ number_format($item->desconto, 2, ',', '.') }}">  
        </div>
      </div>
    </div>
    <div class="row" style="margin: 0">
      <div class="col-xs-12 pago-div{{ (isset($item->data_baixa) and $item->data_baixa != 0) ? null : ' hidden' }}">
        <div class="form-group">
          <label for="juro">Juros / Multa</label>
          <input type="text" class="form-control text-right" name="juro" maxlength="15" id="juro" value="{{ number_format($item->juro, 2, ',', '.') }}">  
        </div>
      </div>
    </div>
    <div class="row" style="margin: 0">
      <div class="col-xs-12 pago-div{{ (isset($item->data_baixa) and $item->data_baixa != 0) ? null : ' hidden' }}">
        <div class="form-group">
          <label for="valor_recebido">{{ $tipo == "Receita"? 'Valor recebido' : 'Valor pago'}} </label>
          <input type="text" class="form-control text-right" name="valor_recebido" maxlength="15" id="valor_recebido" value="{{ isset ($item->valor_recebido) ? number_format($item->valor_recebido, 2, ',', '.') : null }}" placeholder="0,00">  
        </div>  
      </div>
    </div>
    @endif
    <div class="row" style="margin: 0">
      <div class="col-md-12">
        <div class="form-group">
          <span>Mais Opções</span>
          <label class="switch" style="float: right; margin-top: 0px;" for="outro">
            <input type="checkbox" name="outro" id="outro" maxlength="1" value="{{ ((isset($item->fornecedor->nome_fantasia) && ($item->fornecedor->nome_fantasia!='Fornecedor Padrão' && $item->fornecedor->nome_fantasia!='Cliente Padrão')) or (isset($item->centroCusto->nome) && $item->centroCusto->nome != 'Comum' ) or (isset($item->observacao) && $item->observacao != '') or (isset($item->num_doc) && $item->num_doc != '') ) ? '1' : 0 }}" {{ ((isset($item->fornecedor->nome_fantasia) && ($item->fornecedor->nome_fantasia!='Fornecedor Padrão' && $item->fornecedor->nome_fantasia!='Cliente Padrão')) or (isset($item->centroCusto->nome) && $item->centroCusto->nome != 'Comum' ) or (isset($item->observacao) && $item->observacao != '') or (isset($item->num_doc) && $item->num_doc != '') ) ? 'checked' : null }}>
            <div class="slider round"></div>
          </label>
        </div>
      </div>

      <div class="col-md-12 no-padding outro-div{{ ((isset($item->fornecedor->nome_fantasia) && ($item->fornecedor->nome_fantasia!='Fornecedor Padrão' && $item->fornecedor->nome_fantasia!='Cliente Padrão')) or (isset($item->centroCusto->nome) && $item->centroCusto->nome != 'Comum' ) or (isset($item->observacao) && $item->observacao != '') or (isset($item->num_doc) && $item->num_doc != '') ) ? null : ' hidden' }}">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="colx-xs-12" for="num_doc">N° do Documento</label>
            <input type="text" class="form-control" id="num_doc" name="num_doc" value="{{ $item->num_doc }}" placeholder="Adicione o N° do Documento">
          </div>
        </div>
        @if($tipo == "Receita")
        <div class="col-xs-12">
          <div class="form-group" id="cliente-div">
            <label for="cliente_input">Cliente</label>
            <input type="text" class="form-control" id="cliente_input" autocomplete="off" value="{{ isset($item->fornecedor->nome_fantasia) ? $item->fornecedor->nome_fantasia : null }}">
            <div class="hidden" id="btn-cliente-novo">
              @can('cliente_create', $item)
              <span class="pull-right" style="color: green; margin-top: -30px; position: absolute; right: 25px;">
                <div class="btn-cliente-create">
                  <i class="mdi mdi-plus mdi-14px" style="border: 1px solid;"></i>
                </div>
              </span>
              @endcan
            </div>
            <ul class="ul-cliente scrollbar-inner">
              @foreach($fornecedores as $i)
              <li style="display: none;" rel="{{ $i->id }}">
                <a href="#" class="btn-cliente" rel="{{ $i->id }}">{{ (isset($i->cnpj) && $i->cnpj != '' && $i->cnpj != '00000000000000' && $i->cnpj != '00000000000') ? Helper::cpfcnpj($i->cnpj).' - ' : null }}{{ $i->nome_fantasia }}</a>
              </li>
              @endforeach
            </ul>
            <input type="hidden" id="cliente_id" name="cliente_id" value="{{ $item->fornecedor_id }}">
            <span class="help-block"></span>
          </div>
        </div>
        @else  
        <div class="col-xs-12">
          <div class="form-group" id="fornecedor-div">
            <label for="fornecedor_input">Fornecedor</label>
            <input type="text" class="form-control" id="fornecedor_input" autocomplete="off" value="{{ isset($item->fornecedor->nome_fantasia) ? $item->fornecedor->nome_fantasia : null }}">
            <div class="hidden" id="btn-fornecedor-novo">
              @can('fornecedor_create', $item)
              <span class="pull-right" style="color: green; margin-top: -30px; position: absolute; right: 25px;">
                <div class="btn-fornecedor-create">
                  <i class="mdi mdi-plus mdi-14px" style="border: 1px solid;"></i>
                </div>
              </span>
              @endcan
            </div>
            <ul class="ul-fornecedor scrollbar-inner">
              @foreach($fornecedores as $i)
              <li style="display: none;" rel="{{ $i->id }}">
                <a href="#" class="btn-fornecedor" rel="{{ $i->id }}">{{ (isset($i->cnpj) && $i->cnpj != '' && $i->cnpj != '00000000000000' && $i->cnpj != '00000000000') ? Helper::cpfcnpj($i->cnpj).' - ' : null }}{{ $i->nome_fantasia }}</a>
              </li>
              @endforeach
            </ul>
            <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{ $item->fornecedor_id }}">
            <span class="help-block"></span>
          </div>
        </div>
        @endif
        <div class="col-xs-12">
          <div class="form-group" id="centro-custo-div">
            <label for="centro_custo_input">Centro de Custo</label>
            <input type="text" class="form-control" id="centro_custo_input" autocomplete="off" value="{{ isset($item->centroCusto->nome) ? $item->centroCusto->nome : null }}">
            <div class="hidden" id="btn-centro-custo-novo">
              @can('fin_centro_custo_create', $item)
              <span class="pull-right" style="color: green; margin-top: -30px; position: absolute; right: 25px;">
                <div class="btn-centro-custo-create">
                  <i class="mdi mdi-plus mdi-14px" style="border: 1px solid;"></i>
                </div>
              </span>
              @endcan
            </div>
            <ul class="ul-centro-custo scrollbar-inner">
              @foreach($centrocustos as $i)
              <li style="display: none;" rel="{{ $i->id }}">
                <a href="#" class="btn-centro-custo" rel="{{ $i->id }}">{{ $i->nome }}</a>
              </li>
              @endforeach
            </ul>
            <input type="hidden" id="centro_custo_id" name="centro_custo_id" value="{{ $item->centro_custo_id }}">
            <span class="help-block"></span>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="form-group">
            <label class="colx-xs-12" for="observacao">Observações</label>
            <textarea rows="3" class="form-control" id="observacao" name="observacao" placeholder="Adicione comentários sobre esta movimentação" maxlength="4000">{{ $item->observacao }}</textarea>
          </div>
        </div>
          <!--       <div class="col-xs-12">
            <div class="form-group">
              <label class="colx-xs-12" for="statementAtach">Anexos:</label>
              <input type="file" id="uploadedImageStatementId" name="uploadedImageStatementId" value="">
            </div>
            <button class="btn btn-primary">Anexar foto no pagamento</button>
            <button class="btn btn-danger">Descartar foto</button>
          </div> -->
      </div>
    </div>

    @if($tipo == "Receita")
    <div class="row" style="margin: 0">
      <div class="col-md-12">
        <div class="form-group">
          <span>Pontual</span>
          <label class="switch" style="float: right; margin-top: 0px;" for="flag_pontual">
            <input type="checkbox" name="flag_pontual" id="flag_pontual" maxlength="1" value="{{ isset($item->flag_pontual) ? '1' : 0 }}" {{ isset($item->flag_pontual) ? 'checked' : null }}>
            <div class="slider round"></div>
          </label>
        </div>
      </div>
    </div>
    @endif

    <input type="submit" id="btn-movimento-form" class="hidden">
    <input type="hidden" name="conta_tipo_id" value="1">
    <input type="hidden" name="tipo" value="{{ $tipo }}">
  </form>
    <div class="form-footer">
      <a href="#" class="btn pull-left btn-lg btn-default btn-movimento-cancelar">CANCELAR</a>
      <a href="#" class="btn pull-right btn-lg btn-success" id="btn-movimento-salvar">{{ $item->id ? 'EDITAR' : 'SALVAR' }}</a>
    </div>
  </div>
  @if( Gate::check('fin_transferencia_create') || Gate::check('fin_transferencia_update') )
  <div class="col-xs-12 hidden" id="transferencia-nova">
    @if(isset($fatura))
    <div class="title">Pagamento da fatura n° {{ $fatura->id }}</div>
    @else
    <div class="title">Adicionar transferência entre contas</div>
    @endif
    <form class="form-crud scrollbar-inner" method="post" id="transferenciaForm" @if( isset($item->id)  && isset($item2->id) ) action="{{ route('financeiro.movimento.transferenciaUpdate', ( isset($item->id) && $item->categoria->nome == "Transferência de Saída") ? $item->id : $item2->id) }}"> {{ method_field('put') }} @else action="{{ route('financeiro.movimento.transferencia') }}"> @endif
      {{ csrf_field() }}
      <div class="col-xs-12">
        <div class="form-group">
          <label for="conta_saida_id">Conta de Origem<span>*</span></label>
          <select class="form-control" name="conta_saida_id" id="conta_saida_id" required>
            <option value="">Selecione a conta</option>
            @foreach($contas as $i)
            <option value="{{ $i->id }}" {{ ( isset($item->id) && $item->categoria->nome == "Transferência de Saída" && $i->id == $item->conta_id ) || ( isset($item2->id) && $item2->categoria->nome == "Transferência de Saída" && $i->id == $item2->conta_id ) ? 'selected' : null }} >{{ $i->descricao }} (R$ {{ number_format($i->valor, 2, ',', '.') }})</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>
      @if(isset($fatura))
      <div class="col-xs-12">
        <div class="form-group">
          <label for="conta_entrada_id">Conta de Destino<span>*</span></label>
          <input type="text" value="{{ $conta->descricao }}" disabled="" class="form-control">
          <input type="hidden" name="conta_entrada_id" value="{{ $fatura->conta_id}}">
          <input type="hidden" name="conta_fatura_id" value="{{ $fatura->id}}">
        </div>
      </div>
      @else
      <div class="col-xs-12">
        <div class="form-group">
          <label for="conta_entrada_id">Conta de Destino<span>*</span></label>
          <select class="form-control" name="conta_entrada_id" id="conta_entrada_id" required>
            <option value="">Selecione a conta</option>
            @foreach($contas as $i)
            <option value="{{ $i->id }}" {{ ( isset($item->id) && $item->categoria->nome == "Transferência de Entrada" && $i->id == $item->conta_id ) || ( isset($item2->id) && $item2->categoria->nome == "Transferência de Entrada" && $i->id == $item2->conta_id ) ? 'selected' : null }}>{{ $i->descricao }}</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>
      @endif
      <div class="col-xs-12">
        <div class="form-group">
          <label for="descricao">Descrição</label>
          @php($dc = '')
          @if( isset($fatura->descricao) && isset($item->descricao) )
          @php( $dc = $item->descricao )
          @elseif( isset($fatura->descricao) && !isset($item->descricao) )
          @php( $dc = 'Fatura '.$fatura->descricao.'('.Carbon\Carbon::parse($fatura->data_vencimento)->format('d/m/Y').')' )
          @endif
          <input type="text" class="form-control" name="descricao" class="col-xs-12" id="descricao" value="{{ $dc }}" placeholder="Defina um nome para seu pagamento" maxlength="200" required>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label class="finance-label-small" for="data_transferencia">Data da transferência<span>*</span></label>
          <input type="text" class="form-control text-right" id="data_transferencia" value="{{ Carbon\Carbon::parse($item->data_emissao)->format('d/m/Y') }}" required name="data_transferencia" maxlength="10">
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="valor_transferencia">Valor</label>
          @php($vt = '')
          @if( isset($total->valor) && isset($item->valor) )
          @php( $vt = number_format($item->valor, 2, ',', '.') )
          @elseif( isset($total->valor) && !isset($item->valor) )
          @php( $vt = number_format($total->valor, 2, ',', '.') )
          @endif
          <input type="text" class="form-control text-right" name="valor_transferencia" value="{{ $vt }}" maxlength="15" id="valor_transferencia" required placeholder="0,00">
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label class="colx-xs-12" for="observacao">Observações</label>
          <textarea rows="3" class="form-control" id="observacao" name="observacao" placeholder="Adicione comentários sobre esta transferência" maxlength="4000">{{ $item->observacao }}</textarea>
        </div>
      </div>
      <input type="submit" id="btn-transferencia-form" class="hidden">
    </form>
    <div class="form-footer">
      <input type="hidden" name="conta_tipo_id" value="1">
      <a href="#" class="btn pull-left btn-lg btn-default btn-movimento-cancelar">CANCELAR</a>
      <a href="#" class="btn pull-right btn-lg btn-success" id="btn-transferencia-salvar">SALVAR</a>
    </div>
  </div>
  @endif
  @can('fin_categoria_read')
  <div class="hidden" id="categoria-nova">
    <form class="form" method="post" id="form-categoria" action="{{ route('fin.categoria.store') }}">
      {{ csrf_field() }}
      <div class="col-xs-12">
        <h3>Nova Categoria</h3>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <select class="form-control" name="categoria_id" id="categoria_id" required>
            @foreach($categorias as $i)
            <option value="{{ $i->id }}" desc="{{ $i->descricao }}">{{ $i->nome }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" name="nome" id="nome" autocomplete="off">
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="descricao">Descrição</label>
          <input type="text" class="form-control" name="descricao" id="descricao" autocomplete="off">
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <input type="hidden" name="tipo" value="{{ $tipo }}">
          <a href="#" class="btn pull-left btn-lg btn-default btn-categoria-cancelar">CANCELAR</a>
          <button type="submit" class="btn pull-right btn-lg btn-success">SALVAR</button>
        </div>
      </div>
    </form>
  </div>
  @endcan
  @can('cliente_read')
  <div class="hidden" id="cliente-nova" >
    <form class="form" method="post" id="form-cliente" action="{{ route('cliente.store') }}"> 
      {{ csrf_field() }}
      <div class="col-xs-12">
        <h3>Novo Cliente</h3>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="nome">CNPJ / CPF</label>
          <input type="text" class="form-control text-right" name="cnpj" id="cnpj">
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" name="razao_social" id="razao_social">
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <input type="hidden" name="movimento" value="1">
          <a href="#" class="btn pull-left btn-lg btn-default btn-cliente-cancelar">CANCELAR</a>
          <button type="submit" class="btn pull-right btn-lg btn-success">SALVAR</button>
        </div>
      </div>
    </form>
  </div>
  @endcan
  @can('fornecedor_read')
  <div class="hidden" id="fornecedor-nova" >
    <form class="form" method="post" id="form-fornecedor" action="{{ route('fornecedor.store') }}"> 
      {{ csrf_field() }}
      <div class="col-xs-12">
        <h3>Novo Fornecedor</h3>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="nome">CNPJ / CPF</label>
          <input type="text" class="form-control text-right" name="cnpj" id="cnpj">
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" name="razao_social" id="razao_social">
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <input type="hidden" name="movimento" value="1">
          <a href="#" class="btn pull-left btn-lg btn-default btn-fornecedor-cancelar">CANCELAR</a>
          <button type="submit" class="btn pull-right btn-lg btn-success">SALVAR</button>
        </div>
      </div>
    </form>
  </div>
  @endcan
  @can('fin_centro_custo_create')
  <div class="hidden" id="centro-custo-nova" >
    <form class="form" method="post" id="form-centro-custo" action="{{ route('centrocusto.store') }}">
      {{ csrf_field() }}
      <div class="col-xs-12">
        <h3>Novo Centro de Custo</h3>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" name="nome" id="nome">
          <span class="help-block"></span>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="form-group">
          <a href="#" class="btn pull-left btn-lg btn-default btn-centro-custo-cancelar">CANCELAR</a>
          <button type="submit" class="btn pull-right btn-lg btn-success">SALVAR</button>
        </div>
      </div>
    </form>
  </div>
  @endcan
