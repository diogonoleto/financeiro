<div class="col-xs-12" id="centrocusto-novo">
	<div class="title">{{ isset($item->id) ? 'Editar ' : 'Novo ' }}Centro de Custo</div>
	<form method="post" id="centrocustoForm" @if(isset($item->id)) action="{{ route('centrocusto.update', $item->id) }}">
		{{ method_field('put') }} @else action="{{ route('centrocusto.store') }}"> @endif {{ csrf_field() }}
		<div class="form-crud">
			<div class="col-md-12">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" id="nome" name="nome" class="form-control" size="65" maxlength="255" required placeholder="Nome" value="{{ $item->nome or old('nome') }}" autocomplete="off">
					<span class="help-block"></span>
				</div>
			</div>
		</div>
		<div class="form-footer">
			<input type="hidden" name="id" value="">
			<a href="#" class="btn pull-left btn-lg btn-default btn-centrocusto-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="{{ $item->id ? 'EDITAR' : 'SALVAR' }}">
		</div>
	</form>
</div>