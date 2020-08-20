<div class="col-xs-12" id="importacao-novo">
	<div class="title">Importar Movimentos</div>
	<form method="post" id="importacaoForm" action="{{ route('imp.movimento.store') }}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="form-crud scrollbar-inner">
			<div class="col-xs-12">
				<div style="border: 1px solid #eee; padding: 0px 15px 45px 15px; margin-bottom: 10px;">
					<h3>Formate sua planilha</h3>
					<p style="text-align: justify;">Para que a Diretorio Digital possa importar com precisão suas movimentações, é necessário que você formate sua planilha para o nosso modelo. Faça o download da planilha de modelo no botão abaixo:</p>
					<a href="{{ url('doc/Planilha_Modelo_Movimentacoes_Diretorio_Digital.xlsx') }}" class="btn pull-right btn-default btn-importacao-download">Fazer download</a>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group">
					<label for="conta_id">Conta Padrão<span>*</span></label>
					<select class="form-control" name="conta_id" required>
						<option value="">Selecione a conta</option>
						@foreach($contas as $i)
						<option value="{{ $i->id }}">{{ $i->descricao }}</option>
						@endforeach
					</select>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group">
					<label for="tipo">Tipo <span>*</span></label>
					<select class="form-control" name="tipo" required>
						<option value="">Selecione o tipo</option>
						<option value="Despesa">Despesa</option>
						<option value="Receita">Receita</option>
						<option value="ReceitaDespesa">Receita e Despesa</option>
					</select>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="file-loading">
					<input type="file" id="import_file" name="import_file" required>
				</div>
			</div>
		</div>
		<div class="form-footer">
			<a href="#" class="btn pull-left btn-lg btn-default btn-importacao-cancelar">CANCELAR</a>
			<input type="submit" class="btn pull-right btn-lg btn-success" value="SALVAR">
		</div>
	</form>
</div>