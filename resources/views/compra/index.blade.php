@extends('layouts.admin')

@push('style')
<style type="text/css">

	#btn-compra-tools {
		margin-top: -7px!important;
		right: 10px;
		width: 76px;
		height: 50px;
		float: right!important;
	}


	.grid-table:nth-child(odd) {
		background-color: #fbfbfb;
	}

	.grid-table:hover .compra  {
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
		border-bottom: 1px solid #8BC34A;
	}

	#btn-tools {
		text-align: right;
		margin-right: 20px;
	}

	#btn-search {
		margin-right: 5px;
	}

	.btn-compra-create{
		color: green;
	}

	.cl-green {
		color: green;
	}

	.form-group {
		margin-bottom: 15px;
		margin-top: 15px;
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


</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>{{ $title }}</h1> 
	<div class="input-group pull-right" id="btn-tools">
		<div class="input-group-btn">
			<a href="#" class="btn btn-default btn-compra-create" data-toggle="tooltip" title="Adicionar compra" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>
			<a href="#" class="btn btn-default" id="btn-search"><i class="mdi mdi-magnify mdi-20px" aria-hidden="true"></i></a>
		</div>
	</div>
</section>
<!-- Main content -->
<section class="content">
	<div class="col-sm-12 col-xs-12 no-padding" id="div-list">
		<div class="col-xs-12 hidden">
			<form id="form-search" action="{{ route('compra.lista') }}">
				{{ method_field('GET') }}
				{{ csrf_field() }}
				<input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
			</form>
		</div>
		<div class="col-xs-12 no-padding" id="grid-table-header">
			<div class="col-sm-3 col-xs-4" style="width: calc(25% - 40px);"><a href="#" class="order" order="documento_num" sort="ASC">NOTA FISCAL</a></div>
			<div class="col-sm-2 col-xs-4"><a href="#" class="order" order="fornecedor" sort="ASC">FORNECEDOR</a></div>
			<div class="col-sm-2 hidden-xs"><a href="#" class="order" order="data_emissao" sort="ASC">DATA EMISSÃO</a></div>
			<div class="col-sm-1 hidden-xs text-center"><a href="#" class="order" order="qtde" sort="ASC">QTD. ITENS</a></div>
			<div class="col-sm-2 hidden-xs">VL. TOTAL</div>
			<div class="col-sm-2 hidden-xs"><a href="#" class="order" order="status" sort="ASC">STATUS</a></div>
		</div>
		<div id="grid-table-body">
		</div>
		<form id="delete-form" action="" method="POST" class="hidden">
			{{ method_field('DELETE') }}
			{{ csrf_field() }}
		</form>
		<form id="form-compra-create" action="{{ route('compra.create') }}" class="hidden" method="GET">
			{{ csrf_field() }}
		</form>
	</div>
	<div class="col-sm-4 col-xs-4 hidden" style="height: 100%; padding: 30px; border-left: 1px solid #8bc34a;" id="div-crud" >
	</div>
</section>
<!-- /.content -->
@endsection

@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
@endpush

@push('scripts')

<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript">
  $(document).on('click', '.btn-compra-create', function(e){
  	e.preventDefault();
  	$(".has-error").removeClass("has-error").children(".help-block").last().html('');
  	$(".se-pre-con").fadeIn();
  	$("#div-crud").html("");
  	$("#grid-table-header > div:nth-child(7), #grid-table-header > div:nth-child(8), .grid-table > div:nth-child(7), .grid-table > div:nth-child(8)").toggleClass("hidden");
  	$("#grid-table-header > div:nth-child(3), .grid-table > div:nth-child(3)").toggleClass("col-sm-2").toggleClass("col-xs-3");
  	$("#grid-table-header > div:nth-child(6), .grid-table > div:nth-child(6)").toggleClass("col-sm-1").toggleClass("col-xs-2");
  	$("#compra-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
  	$("#div-crud").toggleClass("hidden");
  	$("#form-compra-create").submit();
  });

  $(document).on("submit", "#form-compra-create", function(e) {
  	e.preventDefault();
  	var url = $(this).attr("action");
  	var get = $(this).attr("method");
  	var data = $(this).serializeArray();
  	$.ajax({
  		url: url,
  		type: get,
  		data: data,
  		success: function(data){
  			$("#div-crud").html(data);
  			$(".se-pre-con").fadeOut();
  			$("#compra-novo").draggable({
  				containment: ".content"
  			});
  			$("#fornecedor_id").focus();
  			var timeNow = moment().format("YYYY-MM-DD HH:mm:ss");
  			$('#data_emissao').datetimepicker({
  				locale: 'pt-BR',
  				format: 'DD-MM-YYYY',
  				defaultDate: timeNow,
  			});
  			$('#valor').mask("#.##0,00", {reverse: true});
  			$("#qtde").mask('######9');
  			var h =  $("body").innerHeight();
		  			h -= $(".content-header").innerHeight();
		  			h -= 180;
  			$("#compra-novo, #compra-item").css({ "height": h, "top": "55px" });
  			$(".form-footer").css("margin-top", h - 53);
  		}
  	});
  });
</script>
@endpush