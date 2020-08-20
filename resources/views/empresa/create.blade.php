<div class="col-sm-12 div-impr scrollbar-inner no-padding {{ isset($item->id) ? null : 'hidden' }}">
	<div class="col-sm-12" style="border-bottom: 1px solid #ccc; padding: 10px 0px;">
		<div class="col-sm-3 text-center" style="padding-right: 0px;">
			@if($item->img)
			<img id="perfil-img" src="{{ url($item->img) }}" class="img-thumbnail img-circle img-responsive">
			<a href="#" id="btn-img-editar">Editar</a>
			@else
			<img id="perfil-img" src="{{ url('img/avatars/avatar-blank.png') }}" class="img-thumbnail img-circle img-responsive">
			<a href="#" id="btn-img-editar">Adicionar</a>
			@endif
		</div>
		<div class="col-sm-9">
			<form method="post" id="empresaEditForm" action="{{ route('empresa.update', $item->id) }}" class="hidden">
				{{ method_field('put') }} 
				{{ csrf_field() }}
				<div class="col-sm-12 no-padding">
					<div class="form-group">
						<label for="razao_social">Nome<span>*</span></label>
						<input type="text" id="razao_social" name="razao_social" class="form-control" size="65" maxlength="255" required placeholder="Nome" value="{{ $item->razao_social or old('razao_social') }}" autocomplete="off">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="div-cnpj col-sm-12 hidden no-padding">
					<div class="form-group">
						<label for="nome_fantasia">Nome Fantasia<span>*</span></label>
						<input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" size="65" maxlength="255" placeholder="Nome Fantasia" value="{{ $item->nome_fantasia or old('nome_fantasia') }}" autocomplete="off">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-sm-12 no-padding">
					<div class="form-group">
						<label for="cnpj">CPF / CNPJ</label>
						<input type="text" id="cnpj" name="cnpj" class="form-control text-right" size="25" maxlength="18" placeholder="Digite o CNPJ" value="{{ $item->cnpj or old('cnpj') }}">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-sm-12 no-padding" style="margin-top: 10px">
					<a href="#" class="btn pull-left btn-default" id="btn-dapri-cancelar">Cancelar</a>
					<button class="btn btn-success pull-right" type="submit">Salvar</button>
				</div>
			</form>
			<h4>{{ $item->razao_social }}</h4>
			<h6 class="h-cnpj">{{ $item->nome_fantasia }}</h6>
			<span class="cnpjs">{{ Helper::cpfcnpj($item->cnpj) }}</span>
			<a href="#" class="btn-dapri-edit"><i class="mdi mdi-pencil mdi-24px"></i></a>
		</div>
	</div>
	<div class="col-sm-12 hidden" id="div-image" style="border-bottom: 1px solid #ccc;">
		<div style="margin-bottom:10px; margin-top: 10px">
			@for($i=1; $i < 19; $i++ )
			<div class="col-xs-2" style="padding: 2px!important;" >
				<img src="{{ url('img/avatars/avatar'.$i.'.png') }}" class="img-thumbnail img-circle img-responsive" id="btn-avatar" rel="{{ 'avatar'.$i.'.png' }}">
			</div>
			@endfor
		</div>
		<form class="form-horizontal dropzone" action="{{ route('empresa.logo') }}" method="POST" enctype="multipart/form-data" >
			{!! method_field('POST') !!}
			{!! csrf_field() !!}
			<input type="hidden" name="empresa_id" id="empresa_id" value="{{ $item->id }}">
			<input type="file" class="form-control" name="img" id="img">
			<input type="hidden" name="avatar" id="avatar">
			<div class="col-sm-12">
				<div class="form-group text-right">
					<button class="btn pull-left btn-default" id="btn-image-cancelar">Cancelar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</div>
		</form>
	</div>
	@if($item->contatos)
	<div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);margin-bottom: 10px;">
		@foreach($item->contatos as $c)
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center"><i class="mdi mdi-24px {{ $c->tipo_contato == 1 ? 'mdi-email' : ($c->tipo_contato == 2 ? 'mdi-phone' : 'mdi-script') }}" style="color:#8BC34A;"></i></div>
			<div class="col-xs-9">{{ $c->descricao }}</div>
			<div class="col-xs-2 no-padding text-right">
				<a href="#" class="btn-contato-edit" route="{{ route('empresa.getContato', $c->id) }}" ><i class="mdi mdi-pencil mdi-24px"></i></a>
				<a href="#" class="item-delete" data-placement="left" data-toggle="confirmation" data-btn-cancel-label="Não" data-btn-cancel-icon="glyphicon glyphicon-remove" data-btn-cancel-class="btn-default pull-left" data-btn-ok-label="Sim" data-btn-ok-icon="glyphicon glyphicon-ok" data-btn-ok-class="btn-success pull-right" data-title="Deseja realmente excluir?" data-content="{{ $c->descricao }}" id="del-{{ $c->id }}" rel="contato" route="{{ route('empresa.destroy', $c->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
			</div>
		</div>
		@endforeach
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center">
				<a href="#" class="btn-contato-novo" title="Novo Contato" data-toggle="tooltip" data-placement="right"><i class="mdi mdi-plus-circle mdi-24px"></i></a>
			</div>
		</div>
	</div>
	@endif
	@if($item->enderecos)
	<div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);margin-bottom: 10px;">
		@foreach($item->enderecos as $e)
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center"><i class="mdi mdi-map-marker mdi-24px" style="color:#8BC34A;"></i></div>
			<div class="col-xs-9">{{ $e->logradouro }}, {{ $e->numero }} {{ $e->bairro }}  {{ $e->cidade }} {{ $e->cep }}</div>
			<div class="col-xs-2 no-padding text-right">
				<a href="#" class="btn-endereco-edit" route="{{ route('empresa.getEndereco', $e->id) }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
				<a href="#" class="item-delete" data-placement="left" data-toggle="confirmation" data-btn-cancel-label="Não" data-btn-cancel-icon="glyphicon glyphicon-remove" data-btn-cancel-class="btn-default pull-left" data-btn-ok-label="Sim" data-btn-ok-icon="glyphicon glyphicon-ok" data-btn-ok-class="btn-success pull-right" data-title="Deseja realmente excluir?" data-content="{{ $e->logradouro }}, {{ $e->numero }} {{ $e->bairro }}  {{ $e->cidade }} {{ $e->cep }}" id="del-{{ $e->id }}" rel="endereco" route="{{ route('empresa.destroy', $e->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
			</div>
		</div>
		@endforeach
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center">
				<a href="#" class="btn-endereco-novo" title="Novo Endereço" data-toggle="tooltip" data-placement="right"><i class="mdi mdi-plus-circle mdi-24px"></i></a>
			</div>
		</div>
	</div>
	@endif
	@if($item->contas)
	<div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);margin-bottom: 10px;">
		@foreach($item->contas as $co)
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center"><i class="mdi mdi-bank mdi-24px" style="color:#8BC34A;"></i></div>
			<div class="col-xs-9">{{ $co->codigo }} | {{ $co->agencia }} | {{ $co->conta }}</div>
			<div class="col-xs-2 no-padding text-right">
				<a href="#" class="btn-conta-edit" principal="{{ $co->principal }}" conta_id="{{ $co->id }}" tipo_conta="{{ $co->tipo_conta }}" banco_id="{{ $co->banco_id }}" agencia="{{ $co->agencia }}" conta="{{ $co->conta }}"><i class="mdi mdi-pencil mdi-24px"></i></a>
				<a href="#" class="item-delete" data-placement="left" data-toggle="confirmation" data-btn-cancel-label="Não" data-btn-cancel-icon="glyphicon glyphicon-remove" data-btn-cancel-class="btn-default pull-left" data-btn-ok-label="Sim" data-btn-ok-icon="glyphicon glyphicon-ok" data-btn-ok-class="btn-success pull-right" data-title="Deseja realmente excluir?" data-content="Banco:{{ $co->codigo }} Agencia:{{ $co->agencia }} Conta:{{ $co->conta }}" id="del-{{ $co->id }}" rel="daba" route="{{ route('empresa.destroy', $co->id) }}"><i class="mdi mdi-delete mdi-24px"></i></a>
			</div>
		</div>
		@endforeach
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center">
				<a href="#" class="btn-conta-nova" title="Nova Conta" data-toggle="tooltip" data-placement="right"><i class="mdi mdi-plus-circle mdi-24px"></i></a>
			</div>
		</div>
	</div>
	@endif
	@if($item->maisInfo)
	<div class="row" style="background-color:#fff; padding-left: 10px; padding-right: 10px;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);margin-bottom: 10px;">
		@if(isset($item->maisInfo->data_fundacao))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center"><i class="mdi mdi-cake-variant mdi-24px" style="color:#8BC34A;"></i></div>
			<div class="col-xs-11">{{ $item->maisInfo->data_fundacao }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->rg))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">RG</div>
			<div class="col-xs-11">{{ $item->maisInfo->rg }} {{ $item->maisInfo->oerg }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->cnh))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">CNH</div>
			<div class="col-xs-11">{{ $item->maisInfo->cnh }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->crea))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">CREA</div>
			<div class="col-xs-11">{{ $item->maisInfo->crea }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->crm))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">CRM</div>
			<div class="col-xs-11">{{ $item->maisInfo->crm }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->cro))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">CRO</div>
			<div class="col-xs-11">{{ $item->maisInfo->cro }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->oab))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">OAB</div>
			<div class="col-xs-11">{{ $item->maisInfo->oab }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->suframa))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 18px;">Sufr</div>
			<div class="col-xs-11">{{ $item->maisInfo->suframa }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->inscricao_municipal))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 18px;">IM</div>
			<div class="col-xs-11">{{ $item->maisInfo->inscricao_municipal }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->inscricao_estadual))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 18px;">IE</div>
			<div class="col-xs-11">{{ $item->maisInfo->inscricao_estadual }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->profissao))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 18px;">Prof</div>
			<div class="col-xs-11">{{ $item->maisInfo->profissao }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->nome_mae))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 18px;">Mãe</div>
			<div class="col-xs-11">{{ $item->maisInfo->nome_mae }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->nome_pai))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 18px;">Pai</div>
			<div class="col-xs-11">{{ $item->maisInfo->nome_pai }}</div>
		</div>
		@endif
		@if(isset($item->maisInfo->estado_civil))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding" style="color:#8BC34A; font-weight: 600; text-align: center;font-size: 15px; letter-spacing: -2px;">EsCi</div>
			<div class="col-xs-11">
				{{ $item->maisInfo->estado_civil == 1 ? 'Solteiro(a)' :
				$item->maisInfo->estado_civil == 2 ? 'Casado(a)' :
				$item->maisInfo->estado_civil == 3 ? 'Divorciado(a)' : 
				$item->maisInfo->estado_civil == 4 ? 'Viúvo(a)' : 
				$item->maisInfo->estado_civil == 5 ? 'Separado(a)' : null}}
			</div>
		</div>
		@endif	
		@if(isset($item->maisInfo->sexo))
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center"><i class="mdi {{ $item->maisInfo->sexo == 1 ? 'mdi-gender-male' : 'mdi-gender-female' }} mdi-24px" style="color:#8BC34A;"></i></div>
			<div class="col-xs-11">{{ $item->maisInfo->sexo == 1 ? 'Masculino' : 'Feminino' }}</div>
		</div>
		@endif
		<div class="col-xs-12 cinfo no-padding">
			<div class="col-xs-1 no-padding text-center"></div>
			<div class="col-xs-11 no-padding text-right"><a href="#" class="btn-dase-edit"><i class="mdi mdi-pencil mdi-24px"></i></a></div>
		</div>
	</div>
	@endif
	<div class="row" style="background-color:#fff; padding-left: 0; padding-right: 0">
		<div class="col-xs-12 {{ $item->maisInfo->count() ? 'hidden' : null }}">
			<div class="form-group">
				<span>DADOS SECUNDARIOS</span>
				<label class="switch" style="float: right; margin-top: 0px;" for="dase">
					<input type="radio" name="radio" id="dase" maxlength="1">
					<div class="slider round"></div>
				</label>
			</div>
		</div>
		<div class="col-xs-12 {{ $item->contatos->count() ? 'hidden' : null }}">
			<div class="form-group">
				<span>CONTATOS</span>
				<label class="switch" style="float: right; margin-top: 0px;" for="contato">
					<input type="radio" name="radio" id="contato" maxlength="1">
					<div class="slider round"></div>
				</label>
			</div>
		</div>
		<div class="col-xs-12 {{ $item->enderecos->count() ? 'hidden' : null }}">
			<div class="form-group">
				<span>ENDEREÇOS</span>
				<label class="switch" style="float: right; margin-top: 0px;" for="endereco">
					<input type="radio" name="radio" id="endereco" maxlength="1" value="{{ (isset($item->cep) or isset($item->logradouro))  ? '1' : 0 }}" {{ (isset($item->cep) or isset($item->logradouro)) ? 'checked' : null }}>
					<div class="slider round"></div>
				</label>
			</div>
		</div>
		<div class="col-xs-12  {{ $item->contas->count() ? 'hidden' : null }}">
			<div class="form-group">
				<span>DADOS BANCÁRIOS</span>
				<label class="switch" style="float: right; margin-top: 0px;" for="daba">
					<input type="radio" name="radio" id="daba" maxlength="1">
					<div class="slider round"></div>
				</label>
			</div>
		</div>
	</div>
</div>
<div class="col-xs-12 {{ isset($item->id) ? 'hidden' : null }}" id="empresa-novo">
	<div class="title">Cadastrar Empresa</div>
	<form method="post" id="empresaForm" action="{{ route('empresa.store') }}">
		{{ csrf_field() }}
		<div class="form-crud scrollbar-inner">
			<div class="col-sm-12">
				<div class="form-group">
					<div id="empresa_limite"></div>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="cnpj">CPF / CNPJ</label>
					<input type="text" id="cnpj" name="cnpj" class="form-control text-right" size="25" maxlength="18" placeholder="Digite o CNPJ" value="{{ $item->cnpj or old('cnpj') }}">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="razao_social">Nome<span>*</span></label>
					<input type="text" id="razao_social" name="razao_social" class="form-control" size="65" maxlength="255" required placeholder="Nome" value="{{ $item->razao_social or old('razao_social') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="div-cnpj col-sm-12 hidden">
				<div class="form-group">
					<label for="nome_fantasia">Nome Fantasia<span>*</span></label>
					<input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" size="65" maxlength="255" placeholder="Nome Fantasia" value="{{ $item->nome_fantasia or old('nome_fantasia') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="telefone">Telefone</label>
					<input type="text" id="telefone" name="telefone" class="form-control text-right" maxlength="15" value="{{ $item->telefone or old('telefone') }}">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" id="email" name="email" class="form-control" placeholder="Digite o e-mail!" value="{{ $item->email or old('email') }}">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="div-cpf col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<label for="tipo_identificacao">Tipo</label>
					<span class="input-group-btn">
						<select class="form-control" name="tipo_identificacao" id="tipo_identificacao">
							<option value="rg">RG</option>
							<option value="cnh">CNH</option>
							<option value="crea">CREA</option>
							<option value="crm">CRM</option>
							<option value="cro">CRO</option>
							<option value="oab">OAB</option>
						</select>
					</span>
					<label for="regra_id" class="idop oprg">N° de Identificação</label>
					<input type="text" id="rg" name="rg" class="form-control text-right idop oprg" size="20" maxlength="20" autocomplete="off">
					<label for="oerg" class="idop oprg">Orgão Exp.</label>
					<input type="text" id="oerg" name="oerg" class="form-control idop oprg" size="20" maxlength="20" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<span>Endereço</span>
						<label class="switch" style="float: right; margin-top: 0px;" for="end">
							<input type="checkbox" name="endereco" id="end" maxlength="1" value="0">
							<div class="slider round"></div>
						</label>
					</div>
				</div>
			</div>
			<div class="col-md-12 no-padding end-div hidden">
				<div class="col-md-12">
					<div class="form-group">
						<label for="cep">CEP</label>
						<input type="text" name="cep" id="cep" class="form-control text-right" maxlength="8" placeholder="CEP">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="cidade">Cidade</label>
						<input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="logradouro">Endereço</label>
						<input type="text" name="logradouro" id="logradouro" class="form-control" maxlength="100" placeholder="Endereço">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="numero">Número</label>
						<input type="text" name="numero" id="numero" class="form-control" maxlength="5" placeholder="Número">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="complemento">Complemento</label>
						<input type="text" name="complemento" id="complemento" class="form-control" maxlength="50" placeholder="">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="bairro">Bairro</label>
						<input type="text" name="bairro" id="bairro" class="form-control" maxlength="80" placeholder="Bairro">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<span>Dados Bancários</span>
						<label class="switch" style="float: right; margin-top: 0px;" for="con">
							<input type="checkbox" name="conta" id="con" maxlength="1" value="0">
							<div class="slider round"></div>
						</label>
					</div>
				</div>
			</div>
			<div class="col-md-12 no-padding con-div hidden">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="tipo_conta">Tipo de Conta</label>
						<select class="form-control" name="tipo_conta" id="tipo_conta">
							<option value="1">Corrente</option>
							<option value="2">Poupança</option>
						</select>
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group" id="banco-div">
						<label for="banco_input">Banco</label>
						<input type="text" class="form-control uppercase banco_input" autocomplete="off" value="{{ isset($item->banco->nome) ? $item->banco->nome : null }}">
						<ul class="ul-banco" style="max-height: 200px">
							@foreach($bancos as $i)
							<li style="display: none;" class="uppercase" rel="{{ $i->id }}">
								<a href="#" class="btn-banco uppercase bb-{{ $i->id }}" rel="{{ $i->id }}"><span>{{ $i->codigo }}</span> - {{ $i->nome }}</a>
							</li>
							@endforeach
						</ul>
						<input type="hidden" id="banco_id" name="banco_id" value="{{ $item->banco_id }}">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group">
						<label for="agencia">Agência</label>
						<input type="text" name="agencia" id="agencia" value="{{ $item->agencia }}" class="form-control text-right" placeholder="N° da Agência" autocomplete="off" maxlength="11">
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group">
						<label for="conta">Conta</label>
						<input type="text" name="conta" id="conta" value="{{ $item->conta }}" class="form-control text-right" placeholder="N° da Conta" autocomplete="off" maxlength="11">
					</div>
				</div>
			</div>
		</div>
		<div class="form-footer">
			<input type="hidden" id="data_fundacao" name="data_fundacao">
			<input type="hidden" name="id">
			<a href="#" class="btn pull-left btn-lg btn-default btn-empresa-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
		</div>
	</form>
</div>
<div class="div-right col-sm-6 no-padding hidden div-dase" style="height: 100%;">
	<form method="post" id="empresaMaisInfoForm" action="{{ route('empresa.maisInfo') }}">
		<div class="form-crud scrollbar-inner" style="height: calc(100% - 55px); margin-bottom: 0; margin-top: 0">
			<div class="col-sm-12">
				<div class="form-group">
					<span>DADOS SECUNDARIOS</span>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="data_fundacao">Data de Nascimento</label>
					<input type="text" id="data_fundacao" name="data_fundacao" class="form-control text-right" maxlength="10" placeholder="Data de Nascimento" value="{{ isset($item->maisInfo->data_fundacao) ? Carbon\Carbon::parse($item->maisInfo->data_fundacao)->format('d/m/Y') : null }}">
				</div>
			</div>
			<div class="div-cnpj col-sm-12 hidden">
				<div class="form-group">
					<label for="suframa">Suframa</label>
					<input type="text" id="suframa" name="suframa" class="form-control" maxlength="50" placeholder="" value="{{ $item->maisInfo->suframa or old('suframa') }}">
				</div>
			</div>
			<div class="div-cnpj col-sm-12 hidden">
				<div class="form-group">
					<label for="inscricao_municipal">Inscrição Municipal</label>
					<input type="text" name="inscricao_municipal" id="inscricao_municipal" class="form-control" autocomplete="off" maxlength="50" placeholder="" value="{{ $item->maisInfo->inscricao_municipal or old('inscricao_municipal') }}">
				</div>
			</div>
			<div class="div-cnpj col-sm-12 hidden">
				<div class="form-group">
					<span>Isenta de Inscrição Estadual?</span>
					<label class="switch" style="float: right; margin-top: 0px;" for="iie">
						<input type="checkbox" name="iie" id="iie" maxlength="1" value="0">
						<div class="slider round"></div>
					</label>
				</div>
			</div>
			<div class="div-cnpj div-iie col-sm-12 hidden">
				<div class="form-group">
					<label for="inscricao_estadual">Inscrição Estadual</label>
					<input type="text" id="inscricao_estadual" name="inscricao_estadual" class="form-control" maxlength="50" placeholder="" value="{{ $item->maisInfo->inscricao_estadual or old('inscricao_estadual') }}">
				</div>
			</div>
			@if(isset($item->maisInfo->rg))
			<div class="col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<span class="input-group-addon">RG</span>
					<label for="oerg" class="idop oprg">N° de Identificação</label>
					<input type="text" id="rg" name="rg" class="form-control text-right idop oprg" size="20" maxlength="20" value="{{ $item->maisInfo->rg or old('rg') }}" autocomplete="off">
					<label for="oerg" class="idop oprg">Orgão Exp.</label>
					<input type="text" id="oerg" name="oerg" class="form-control idop oprg" size="20" maxlength="20" value="{{ $item->maisInfo->oerg or old('oerg') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			@if(isset($item->maisInfo->crea))
			<div class="col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<span class="input-group-addon">CREA</span>
					<label for="crea" class="idop">N° de Identificação</label>
					<input type="text" id="crea" name="crea" class="form-control text-right idop" size="20" maxlength="20" value="{{ $item->maisInfo->crea or old('crea') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			@if(isset($item->maisInfo->cnh))
			<div class="col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<span class="input-group-addon">CNH</span>
					<label for="cnh" class="idop">N° de Identificação</label>
					<input type="text" id="cnh" name="cnh" class="form-control text-right idop" size="20" maxlength="20" value="{{ $item->maisInfo->cnh or old('cnh') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			@if(isset($item->maisInfo->crm))
			<div class="col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<span class="input-group-addon">CRM</span>
					<label for="crm" class="idop">N° de Identificação</label>
					<input type="text" id="crm" name="crm" class="form-control text-right idop" size="20" maxlength="20" value="{{ $item->maisInfo->crm or old('crm') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			@if(isset($item->maisInfo->cro))
			<div class="col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<span class="input-group-addon">CRO</span>
					<label for="cro" class="idop">N° de Identificação</label>
					<input type="text" id="cro" name="cro" class="form-control text-right idop" size="20" maxlength="20" value="{{ $item->maisInfo->cro or old('cro') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			@if(isset($item->maisInfo->oab))
			<div class="col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<span class="input-group-addon">OAB</span>
					<label for="oab" class="idop">N° de Identificação</label>
					<input type="text" id="oab" name="oab" class="form-control text-right idop" size="20" maxlength="20" value="{{ $item->maisInfo->oab or old('oab') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			@if(!isset($item->maisInfo->rg) && !isset($item->maisInfo->crea) && !isset($item->maisInfo->cnh) && !isset($item->maisInfo->crm) && !isset($item->maisInfo->cro) && !isset($item->maisInfo->oab) )
			<div class="div-cpf col-md-12">
				<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">
					<label for="tipo_identificacao">Tipo</label>
					<span class="input-group-btn">
						<select class="form-control" name="tipo_identificacao" id="tipo_identificacao">
							<option value="rg">RG</option>
							<option value="cnh">CNH</option>
							<option value="crea">CREA</option>
							<option value="crm">CRM</option>
							<option value="cro">CRO</option>
							<option value="oab">OAB</option>
						</select>
					</span>
					<label for="regra_id" class="idop oprg">N° de Identificação</label>
					<input type="text" id="rg" name="rg" class="form-control text-right idop oprg" size="20" maxlength="20" autocomplete="off">
					<label for="oerg" class="idop oprg">Orgão Exp.</label>
					<input type="text" id="oerg" name="oerg" class="form-control idop oprg" size="20" maxlength="20" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
			@endif
			<div class="div-cpf col-sm-12 text-right" style="margin-top: -5px; margin-bottom: 5px">
				<a href="#" id="btn-mais-identificacao">IDENTIFICAÇÃO <i class="mdi mdi-plus"></i></a>
			</div>
			<div class="div-cpf col-sm-12">
				<div class="form-group">
					<label for="profissao">Profissão</label>
					<input type="text" id="profissao" name="profissao" class="form-control" maxlength="50" placeholder="" value="{{ $item->maisInfo->profissao or old('profissao') }}">
				</div>
			</div>
			<div class="div-cpf col-sm-12">
				<div class="form-group">
					<label for="nome_mae">Nome da Mãe</label>
					<input type="text" id="nome_mae" name="nome_mae" class="form-control" maxlength="50" placeholder="" value="{{ $item->maisInfo->nome_mae or old('nome_mae') }}">
				</div>
			</div>
			<div class="div-cpf col-sm-12">
				<div class="form-group">
					<label for="nome_pai">Nome do Pai</label>
					<input type="text" id="nome_pai" name="nome_pai" class="form-control" maxlength="50" placeholder="" value="{{ $item->maisInfo->nome_pai or old('nome_pai') }}">
				</div>
			</div>
			<div class="div-cpf col-sm-12">
				<div class="form-group">
					<label for="estado_civil">Estado Civil</label>
					<select class="form-control" name="estado_civil" id="estado_civil">
						<option value=""></option>
						<option value="1">Solteiro(a)</option>
						<option value="2">Casado(a)</option>
						<option value="3">Divorciado(a)</option>
						<option value="4">Viúvo(a)</option>
						<option value="5">Separado(a)</option>
					</select>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="div-cpf col-sm-12">
				<div class="form-group">
					<label for="sexo">Sexo</label>
					<select class="form-control" name="sexo" id="sexo">
						<option value=""></option>
						<option value="1">Masculino</option>
						<option value="2">Feminino</option>
					</select>
					<span class="help-block"></span>
				</div>
			</div>
		</div>
		<div class="form-footer" style="bottom: 10px;">
			{{ csrf_field() }}
			<input type="hidden" id="empresa_id" name="empresa_id" value="{{ $item->id }}">
			<a href="#" class="btn pull-left btn-lg btn-default btn-empresa-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="SALVAR">
		</div>
	</form>
</div>
<div class="div-right col-sm-6 no-padding hidden div-ende" style="height: 100%;">
	<form method="post" id="empresaEnderecoForm" action="{{ route('empresa.endereco') }}">
		<div class="col-sm-12">
			<div class="form-group">
				<span>ENDEREÇO</span>
			</div>
		</div>
		<div class="col-sm-12 no-padding div-end-ps scrollbar-inner" style="height: calc(89vh - 100px); position: relative; overflow: hidden;">

			<div class="col-sm-12">
				<div class="form-group">
					<span>Principal</span>
					<label class="switch" style="float: right; margin-bottom: 15px" for="principal">
						<input type="checkbox" name="principal" id="principal" maxlength="1" value="1">
						<div class="slider round"></div>
					</label>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="tipo_endereco">Tipo de Endereço</label>
					<select class="form-control" name="tipo_endereco" id="tipo_endereco">
						<option value="1">Comercial</option>
						<option value="2">Entrega</option>
						<option value="3">Residencial</option>
					</select>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="cep">CEP</label>
					<input type="text" name="cep" id="cep" class="form-control text-right" maxlength="8" placeholder="CEP" value="{{ $item->cep or old('cep') }}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="cidade">Cidade</label>
					<input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade" maxlength="150" value="{{ $item->cidade or old('cidade') }}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="logradouro">Endereço</label>
					<input type="text" name="logradouro" id="logradouro" class="form-control" maxlength="150" placeholder="Endereço" value="{{ $item->logradouro or old('logradouro') }}" required>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="numero">Número</label>
					<input type="text" name="numero" id="numero" class="form-control" maxlength=6 placeholder="Número" value="{{ $item->numero or old('numero') }}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="complemento">Complemento</label>
					<input type="text" name="complemento" id="complemento" class="form-control" maxlength="150" value="{{ $item->complemento or old('complemento') }}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="bairro">Bairro</label>
					<input type="text" name="bairro" id="bairro" class="form-control" maxlength="150" placeholder="Bairro" value="{{ $item->bairro or old('bairro') }}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="referencia">Referência</label>
					<input type="text" name="referencia" id="referencia" class="form-control" maxlength="150" placeholder="Referências">
				</div>
			</div>
		</div>
		<div class="form-footer" style="bottom: 15px;">
			{{ csrf_field() }}
			<input type="hidden" id="id" name="id">
			<input type="hidden" id="empresa_id" name="empresa_id" value="{{ $item->id }}">
			<a href="#" class="btn pull-left btn-lg btn-default btn-empresa-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="SALVAR">
		</div>
	</form>
</div>
<div class="div-right col-sm-6 no-padding hidden div-contato" style="height: 100%;">
	<form method="post" id="empresaContatoForm" action="{{ route('empresa.contato') }}">
		<div class="col-sm-12">
			<div class="form-group">
				<span>CONTATO</span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<span>Principal</span>
				<label class="switch" style="float: right; margin-bottom: 15px" for="principal">
					<input type="checkbox" name="principal" id="principal" maxlength="1" value="1">
					<div class="slider round"></div>
				</label>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="telefone">Tipo de Cadastro</label>
				<select class="form-control" name="tipo_cadastro" id="tipo_cadastro">
					<option value="1">Comercial</option>
					<option value="2">Entrega</option>
					<option value="3">Residencial</option>
				</select>
				<span class="help-block"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="telefone">Tipo de Contato</label>
				<select class="form-control" name="tipo_contato" id="tipo_contato">
					<option value="1">E-Mail</option>
					<option value="2">Telefone</option>
					<option value="3">Fax</option>
				</select>
				<span class="help-block"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="descricao" id="descricao-lab">E-mail</label>
				<input type="email" id="descricao" name="descricao" class="form-control" required placeholder="Digite o e-mail!" value="{{ $contato->descricao or old('descricao') }}">
				<span class="help-block"></span>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<label for="detalhe">Detalhes</label>
				<input type="text" id="detalhe" name="detalhe" class="form-control" placeholder="Informações Adicionais" value="{{ $contato->detalhe or old('detalhe') }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-footer" style="bottom: 10px;">
			{{ csrf_field() }}
			<input type="hidden" id="id" name="id">
			<input type="hidden" id="empresa_id" name="empresa_id" value="{{ $item->id }}">
			<a href="#" class="btn pull-left btn-lg btn-default btn-empresa-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="SALVAR">
		</div>
	</form>
</div>
<div class="div-right col-sm-6 no-padding hidden div-daba"	style="height: 100%;">
	<form method="post" id="empresaContaForm" action="{{ route('empresa.conta') }}">
		<div class="col-sm-12">
			<div class="form-group">
				<span>DADOS BANCÁRIOS</span>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<span>Principal</span>
				<label class="switch" style="float: right; margin-bottom: 15px" for="principal">
					<input type="checkbox" name="principal" id="principal" maxlength="1" value="1">
					<div class="slider round"></div>
				</label>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label for="tipo_conta">Tipo de Conta</label>
				<select class="form-control" name="tipo_conta" id="tipo_conta">
					<option value="1">Corrente</option>
					<option value="2">Poupança</option>
				</select>
				<span class="help-block"></span>
			</div>
		</div>

		<div class="col-lg-12">
			<div class="form-group" id="banco-div">
				<label for="banco_input">Banco</label>
				<input type="text" class="form-control uppercase banco_input" autocomplete="off" value="{{ isset($item->banco->nome) ? $item->banco->nome : null }}">
				<ul class="ul-banco scrollbar-inner">
					@foreach($bancos as $i)
					<li style="display: none;" class="uppercase" rel="{{ $i->id }}">
						<a href="#" class="btn-banco uppercase bb-{{ $i->id }}" rel="{{ $i->id }}"><span>{{ $i->codigo }}</span> - {{ $i->nome }}</a>
					</li>
					@endforeach
				</ul>
				<input type="hidden" id="banco_id" name="banco_id" value="{{ $item->banco_id }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<label for="agencia">Agência</label>
				<input type="text" name="agencia" id="agencia" value="{{ $item->agencia }}" class="form-control text-right" placeholder="N° da Agência" autocomplete="off" maxlength="11">
			</div>
		</div>
		<div class="col-lg-12">
			<div class="form-group">
				<label for="conta">Conta</label>
				<input type="text" name="conta" id="conta" value="{{ $item->conta }}" class="form-control text-right" placeholder="N° da Conta" autocomplete="off" maxlength="11">
			</div>
		</div>

		<div class="form-footer" style="bottom: 10px;">
			{{ csrf_field() }}
			<input type="hidden" id="conta_id" name="conta_id">
			<input type="hidden" id="empresa_id" name="empresa_id" value="{{ $item->id }}">
			<a href="#" class="btn pull-left btn-lg btn-default btn-empresa-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="SALVAR">
		</div>
	</form>
</div>