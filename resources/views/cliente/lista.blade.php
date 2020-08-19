@forelse($itens as $i)
@can('cliente_read', $i)
<div class="col-xs-12 no-padding grid-table">
  <div class="col-sm-3 col-xs-6 mparent" rel="{{ $i->id }}" style="cursor: pointer;">{{ $i->razao_social }}</div>
  <div class="col-sm-2 col-xs-6">{{ $i->nome_fantasia }}</div>
  <div class="col-sm-2 col-xs-4">{{ Helper::cpfcnpj($i->cnpj) }}</div>
  <div class="col-sm-3 col-xs-4">
    @foreach($i->contatos as $e)
      @if($e->tipo_contato == 1)
        {{ $e->descricao }}
        @if($e->principal == 1)
          @break
        @endif
      @endif
    @endforeach
  </div>
  <div class="col-sm-2 col-xs-4 telefone">
    @foreach($i->contatos as $t)
      @if($t->tipo_contato == 2)
        {{ $t->descricao }}
        @if($e->principal == 1)
          @break
        @endif
      @endif
    @endforeach
  </div>
  @if($i->razao_social != 'Cliente Padrão')
  <span class="tools-user">
    @can('cliente_update', $i)
    <a href="#" route="{{ route('cliente.edit', $i->id) }}" class="btn-empresa-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
    @endcan
    @can('cliente_delete', $i)
    <a href="#" class="btn-tools-delete del-{{ $i->id }}" id="del-{{ $i->id }}" rel="del-{{ $i->id }}" route="{{ route('cliente.destroy', $i->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
    @endcan
  </span>
  @endif
</div>

<div class="col-xs-12 hidden mchild mchild-{{ $i->id }}" style="border-top:1px solid #8BC34A; height: calc(100% - 43px);">
  <div class="col-xs-8" >
    <div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); margin-bottom: 10px; margin-top: 10px;">
      <h5 style="margin-top: 10px;margin-bottom: 7px;padding-bottom: 8px;border-bottom: 1px solid #eee;">DADOS PRINCIPAIS</h5>
      <p>
        <span class="labe">Razao Social:</span>
        <span class="info">{{ $i->razao_social }}</span>
      </p>
      <p>
        <span class="labe">Nome Fantasia:</span>
        <span class="info">{{ $i->nome_fantasia }}</span>
      </p>
      <p>
        <span class="labe">CNPJ/CPF:</span>
        <span class="info">{{ Helper::cpfcnpj($i->cnpj) }}</span>
      </p>
    </div>
    @if(count($i->enderecos)>0)
    <div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); margin-bottom: 10px; margin-top: 10px;">
      <h5 style="margin-top: 10px;margin-bottom: 7px;padding-bottom: 8px;border-bottom: 1px solid #eee;">ENDEREÇO</h5>
      @foreach($i->enderecos as $en)
      <p>
        <span class="labe">{{ $en->tipo_endereco==1 ? 'Comercial': $en->tipo_endereco==2 ? 'Entrega': 'Residencial' }}:</span>
        <span class="info">{{ $en->logradouro }}, {{ $en->numero }} {{ $en->bairro }}  {{ $en->cidade }} {{ $en->cep }}</span>
      </p>
      @endforeach
    </div>
    @endif


    @if(count($i->maisInfo))
    <div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);margin-bottom: 10px;">
      <h5 style="margin-top: 10px;margin-bottom: 7px;padding-bottom: 8px;border-bottom: 1px solid #eee;">MAIS INFORMAÇÕES</h5>
      @if(isset($i->maisInfo->data_fundacao))
      <p>
        <span class="labe">Data Fundação</span>
        <span class="info">{{ $i->maisInfo->data_fundacao }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->rg))
      <p>
        <span class="labe">RG</span>
        <span class="info">{{ $i->maisInfo->rg }} {{ $i->maisInfo->oerg }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->cnh))
      <p>
        <span class="labe">CNH</span>
        <span class="info">{{ $i->maisInfo->cnh }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->crea))
      <p>
        <span class="labe">CREA</span>
        <span class="info">{{ $i->maisInfo->crea }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->crm))
      <p>
        <span class="labe">CRM</span>
        <span class="info">{{ $i->maisInfo->crm }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->cro))
      <p>
        <span class="labe">CRO</span>
        <span class="info">{{ $i->maisInfo->cro }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->oab))
      <p>
        <span class="labe">OAB</span>
        <span class="info">{{ $i->maisInfo->oab }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->suframa))
      <p>
        <span class="labe">Suframa</span>
        <span class="info">{{ $i->maisInfo->suframa }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->inscricao_municipal))
      <p>
        <span class="labe">Insc. Municipal</span>
        <span class="info">{{ $i->maisInfo->inscricao_municipal }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->inscricao_estadual))
      <p>
        <span class="labe">Insc. Estadual</span>
        <span class="info">{{ $i->maisInfo->inscricao_estadual }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->profissao))
      <p>
        <span class="labe">Profissão</span>
        <span class="info">{{ $i->maisInfo->profissao }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->nome_mae))
      <p>
        <span class="labe">Nome da Mãe</span>
        <span class="info">{{ $i->maisInfo->nome_mae }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->nome_pai))
      <p>
        <span class="labe">Nome do Pai</span>
        <span class="info">{{ $i->maisInfo->nome_pai }}</span>
      </p>
      @endif
      @if(isset($i->maisInfo->estado_civil))
      <p>
        <span class="labe">Estado Civil:</span>
        <span class="info">
          {{ $i->maisInfo->estado_civil == 1 ? 'Solteiro(a)' :
          $i->maisInfo->estado_civil == 2 ? 'Casado(a)' :
          $i->maisInfo->estado_civil == 3 ? 'Divorciado(a)' : 
          $i->maisInfo->estado_civil == 4 ? 'Viúvo(a)' : 
          $i->maisInfo->estado_civil == 5 ? 'Separado(a)' : null}}
        </span>
      </p>
      @endif  
      @if(isset($i->maisInfo->sexo))
      <p>
        <span class="labe">Sexo:</span>
        <span class="info">{{ $i->maisInfo->sexo == 1 ? 'Masculino' : 'Feminino' }}</span>
      </p>
      @endif
    </div>
    @endif
  </div>
  <div class="col-xs-4">

    @if(count($i->contatos)>0)
    <div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); margin-bottom: 10px; margin-top: 10px;">
      <h5 style="margin-top: 10px;margin-bottom: 7px;padding-bottom: 8px;border-bottom: 1px solid #eee;">CONTATOS</h5>
      <p>
        @foreach($i->contatos as $e)
        @if($e->tipo_contato == 1)
        <span class="labe" style="width: 65px">E-mail:</span>
        <span class="info">{{ $e->descricao }}</span>
        @endif
        @endforeach
      </p>
      <p>
        @foreach($i->contatos as $t)
        @if($t->tipo_contato == 2)
        <span class="labe" style="width: 65px">Telefone:</span>
        <span class="info">{{ $t->descricao }}</span>
        @endif
        @endforeach
      </p>
      <p>
        @foreach($i->contatos as $f)
        @if($f->tipo_contato == 3)
        <span class="labe" style="width: 65px">Fax:</span>
        <span class="info">{{ $f->descricao }}</span>
        @endif
        @endforeach
      </p>
    </div>
    @endif

    @if(count($i->contas)>0)
    <div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); margin-bottom: 10px; margin-top: 10px;">
      <h5 style="margin-top: 10px;margin-bottom: 7px;padding-bottom: 8px;border-bottom: 1px solid #eee;">CONTAS</h5>
      @foreach($i->contas as $c)
      <p>
        <span class="labe" style="width: 65px">Banco:</span>
        <span class="info"></span>
      </p>
      <p>
        <span class="labe" style="width: 65px">Agencia:</span>
        <span class="info">{{ $c->agencia }}</span>
      </p>
      <p>
        <span class="labe" style="width: 65px">Conta:</span>
        <span class="info">{{ $c->conta }}</span>
      </p>
      @endforeach
    </div>
    @endif

  </div>
</div>

@endcan
@empty
<div class="col-xs-12 no-padding grid-table">
  <div class="col-xs-12">Cliente inexistente ou não encontrado</div>
</div>
@endforelse
<div class="pagination-bottom">
  <div>
    {{ $itens->firstItem() }} - {{ $itens->lastItem() }} de {{ $itens->total() }}
  </div>
  {{ $itens->render() }}
</div>