@extends('layouts.admin')

@push('style')
<style type="text/css">

	#btn-pdv-tools {
		margin-top: -7px!important;
		right: 10px;
		width: 76px;
		height: 50px;
		float: right!important;
	}

	#content-central, #content-right {
		height: calc(100% - 59px);
		overflow: hidden!important;
	}

	.grid-table:nth-child(odd) {
		background-color: #fbfbfb;
	}

	.grid-table:hover .pdv  {
		display: none;
	}
	.grid-table:hover {
		background-color: rgba(187, 255, 108, 0.29)!important;
	}

	.pagination-bottom {
		bottom: -50px!important;
	}

	#grid-table-header {
		border: 1px solid #eeeeee;
		border-right: 0;
		border-bottom: 1px solid #ddbd6f;
	}

	#btn-tools {
		text-align: right;
		margin-right: 20px;
	}

	#btn-search {
		margin-right: 5px;
	}

	.btn-pdv-create{
		color: green;
	}

	.form-group {
		margin-bottom: 10px;
		margin-top: 10px;
	}

	.grid-table > div {
		padding: 10.78px 15px;
		font-size: 16px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		border-left: 1px solid #fafafa;
	}

	.grid-table > div:first-child {
		padding: 10.78px 15px;
		font-size: 16px;
		overflow: hidden;
		white-space: initial;
		text-overflow: initial;
	}

	#grid-table-header div {
		margin-right: 0px;
	}

</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>{{ $title }}</h1>
	<div class="input-group pull-right" id="btn-tools">
		 <div class="input-group-btn">
      <a href="{{ route('adminCon.conta.index') }}" class="btn btn-default" data-toggle="tooltip" title="Contas" data-placement="bottom"><i class="mdi mdi-folder-account mdi-20px"></i></a>

      <a href="{{ route('usuario.index') }}" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>

<!--       <a href="{{ route('empresa.index') }}" class="btn btn-default" data-toggle="tooltip" title="Empresas" data-placement="bottom"><i class="mdi mdi-store mdi-20px"></i></a>

      <a href="{{ route('fornecedor.index') }}" class="btn btn-default" data-toggle="tooltip" title="Fornecedores" data-placement="bottom"><i class="mdi mdi-truck mdi-20px"></i></a> -->
    </div>
	</div>
</section>
<!-- Main content -->
<section class="content">
	<div class="col-sm-6 col-xs-12" id="pdv-list">
		<div class="col-xs-12 hidden">
			<form id="form-search" action="{{ route('pdv.lista') }}">
				{{ method_field('GET') }}
				{{ csrf_field() }}
				<input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
			</form>
		</div>
		<!-- <div class="col-xs-12 no-padding" id="grid-table-header">
			<div style="width: 40px; padding: 0px 5px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox" id="checkbox-all"></i></div>
			<div class="col-sm-3 col-xs-4" style="width: calc(25% - 40px);"><a href="#" class="order" order="nome" sort="asc" >NOME</a></div>
			<div class="col-sm-2 col-xs-4"><a href="#" class="order" order="responsavel" sort="asc" >RESPONSÁVEL</a></div>
			<div class="col-sm-2 hidden-xs"><a href="#" class="order" order="plataforma" sort="asc">PLATAFORMA</a></div>
			<div class="col-sm-2 hidden-xs"><a href="#" class="order" order="uuid" sort="asc">ID DISP.</a></div>
			<div class="col-sm-1 hidden-xs">MESA</div>
			<div class="col-sm-1 hidden-xs">DESC.(R$)</div>
			<div class="col-sm-1 hidden-xs">DESC.(%)</div>
		</div> -->
		<div id="grid-table-body">
		</div>
		<form id="delete-form" action="" method="POST" class="hidden">
			{{ method_field('DELETE') }}
			{{ csrf_field() }}
		</form>
		<form id="form-pdv-create" action="" class="hidden" method="GET">
			{{ csrf_field() }}
		</form>
	</div>
	<div class="col-sm-4 col-xs-4 hidden" style="height: 100%; padding: 30px; border-left: 1px solid #ddbd6f;" id="pdv-create" >
	</div>
</section>
<!-- /.content -->
@endsection

@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('plugins/maskMoney/jquery.maskMoney.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">
  // $(document).on('click', '.btn-tools-edit, .btn-plus, .btn-show', function(e){
  //   $(".se-pre-con").fadeIn();
  // });

  $(document).on('click', '.btn-pdv-create, .btn-pdv-edit', function(e){
  	e.preventDefault();
  	$(".has-error").removeClass("has-error").children(".help-block").last().html('');

  	$(".se-pre-con").fadeIn();
  	$("#pdv-create").html("");

  	$("#grid-table-header > div:nth-child(7), #grid-table-header > div:nth-child(8), .grid-table > div:nth-child(7), .grid-table > div:nth-child(8)").toggleClass("hidden");

  	$("#grid-table-header > div:nth-child(3), .grid-table > div:nth-child(3)").toggleClass("col-sm-2").toggleClass("col-xs-3");
  	$("#grid-table-header > div:nth-child(6), .grid-table > div:nth-child(6)").toggleClass("col-sm-1").toggleClass("col-xs-2");

  	if( $(this).hasClass('btn-pdv-create')){
  		$("#pdv-list").toggleClass("col-sm-8").toggleClass("col-xs-8");
  		$("#pdv-create").toggleClass("hidden");
  		$("#form-pdv-create").attr("action", $(this).attr("route"));
  	} else {
  		$("#pdv-list").addClass("col-sm-8").addClass("col-xs-8");
  		$("#pdv-create").removeClass("hidden");
  		$("#form-pdv-create").attr("action", $(this).attr("route"));
  	}
  	$("#form-pdv-create").submit();
  });

  $(document).on("submit", "#pdvForm", function(e) {
  	e.preventDefault();
  	$(".se-pre-con").fadeIn();
  	var url = $(this).attr("action");
  	var get = $(this).attr("method");
  	var data = $(this).serializeArray();
  	$.ajax({
  		url: url,
  		type: get,
  		data: data,
  		success: function(data){
  			if(data.error){
  				$.each(data.error , function( key, value ) {
  					$("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
  				});
  			} else {
  				$("#form-search").submit();
  				$(".btn-pdv-create").trigger("click");
  				$("#pdv-create").html("");
  			}
  			$(".se-pre-con").fadeOut();
  		},
  		error: function(data){
  			$.each( data.responseJSON , function( key, value ) {
  				$("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
  			});
  			$(".se-pre-con").fadeOut();
  		}
  	});
  });

  $(document).on("submit", "#form-pdv-create", function(e) {
  	e.preventDefault();
  	var url = $(this).attr("action");
  	var get = $(this).attr("method");
  	var data = $(this).serializeArray();
  	$.ajax({
  		url: url,
  		type: get,
  		data: data,
  		success: function(data){
  			$("#pdv-create").html(data);

  			$(".se-pre-con").fadeOut();
  			$("#pdv-novo").draggable();
  			$('#desc_valor_max').mask("#.##0,00", {reverse: true});

  			$("#mesa_qtd").mask('##9');

  			$('#imprime_ip, #nfce_ip').mask('099.099.099.099');
  			$('#desc_perc_max').mask('##0,00%', {reverse: true});

  			$("#nome").focus();

  			var h =  $("body").innerHeight();
      			h -= $(".content-header").innerHeight();
      			h -= 70;
  			$("#pdv-novo").css("height", h);
  			$("#form-footer").css("margin-top", h - 53);
  			if( $("#imprime").val() == 1 ){
  				$(".imprime-div").removeClass("hidden");
  			} else if( $("#imprime").val() > 1 ){
  				$(".imprime-div, .nfce-div").removeClass("hidden");
  			}
  		}
  	});
  });

  $(document).on("change", "select[name='imprime']", function(e) {
  	e.preventDefault();
  	$(".imprime-div, .nfce-div").addClass("hidden");

  	if( $(this).val() == 1 ){
  		$(".imprime-div").removeClass("hidden");
  	} else if( $(this).val() > 1 ){
  		$(".imprime-div, .nfce-div").removeClass("hidden");
  	}
  	$("#imprime_ip").focus();
  });

  $(document).on('change', '#mesa', function(e){
  	if( !$('#mesa').is(':checked') ){
  		$(".mesa-div").addClass("hidden");
  		$(this).val(0);
  	} else {
  		$(".mesa-div").removeClass("hidden");
  		$("#mesa_qtd").focus();
  		$(this).val(1);
  	}
  });

  $(document).on('change', '#desconto', function(e){
  	if( !$('#desconto').is(':checked') ){
  		$(".desconto-div").addClass("hidden");
  		$("#desconto").parent().css("margin-bottom", "40px");
  		$(this).val(0);
  	} else {
  		$(".desconto-div").removeClass("hidden");
  		$("#desconto").parent().css("margin-bottom", "0");
  		$("#desc_valor_max").focus();
  		$(this).val(1);
  	}
  });

</script>
@endpush
