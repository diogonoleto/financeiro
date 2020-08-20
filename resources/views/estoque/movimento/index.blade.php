@extends('layouts.admin')

@push('style')
<style type="text/css">

	#btn-estoque-movimento-tools {
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

	.grid-table:hover .estoque-movimento  {
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

	.btn-estoque-movimento-create{
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

  .tools-user {
    width: 150px;
  }

  .grid-table:hover .tools-user a:nth-child(1) {
    color: #03A9F4!important;
    z-index: 10;
  }

  .grid-table:hover .tools-user a:nth-child(2) {
    color: #03A9F4!important;
    z-index: 10;
  }

  .grid-table:hover .tools-user a:nth-child(3) {
    color: #23527c!important;
    z-index: 10;
  }
  .grid-table:hover .tools-user a:nth-child(4) {
    color: #F44336!important;
    z-index: 10;
  }


</style>
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>{{ $title }}</h1> 
	<div class="input-group pull-right" id="btn-tools">
		<div class="input-group-btn">
			<a href="#" class="btn btn-default btn-estoque-movimento-create" route="{{ route('estq.movimento.create') }}" data-toggle="tooltip" title="Adicionar Movimento de Estoque" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>
			<a href="#" class="btn btn-default" id="btn-search"><i class="mdi mdi-magnify mdi-20px" aria-hidden="true"></i></a>
		</div>
	</div>
</section>
<!-- Main content -->
<section class="content">
	<div class="col-sm-12 col-xs-12 no-padding" id="estoque-movimento-list">
		<div class="col-xs-12 hidden">
			<form id="form-search" action="{{ route('estoque.movimento.lista') }}">
				{!! method_field('GET') !!}
				{!! csrf_field() !!}
				<input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
			</form>
		</div>
		<div class="col-xs-12 no-padding" id="grid-table-header">
			<div style="width: 40px; padding: 0px 5px; float: left;"><input type="checkbox"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox" id="checkbox-all"></i></div>
			<div class="col-sm-2"><a href="#" class="order" order="nome" sort="ASC">PRODUTO</a></div>

      <div class="col-sm-2"><a href="#" class="order" order="nome" sort="ASC">DATA</a></div>
      <div class="col-sm-1"><a href="#" class="order" order="nome" sort="ASC">QTDE</a></div>
      <div class="col-sm-1"><a href="#" class="order" order="nome" sort="ASC">CUSTO</a></div>
      <div class="col-sm-2"><a href="#" class="order" order="nome" sort="ASC">VALIDADE</a></div>
      <div class="col-sm-2"><a href="#" class="order" order="nome" sort="ASC">OBSERVAÇÃO</a></div>
      <div class="col-sm-2" style="width: calc(16.66666667% - 40px);"><a href="#" class="order" order="nome" sort="ASC">TIPO</a></div>

		</div>
		<div id="grid-table-body">
		</div>
		<form id="delete-form" action="" method="POST" style="display: none;">
			{{ method_field('DELETE') }}
			{{ csrf_field() }}
		</form>
		<form id="delete-form" action="" method="POST" class="hidden">
			{{ method_field('DELETE') }}
			{{ csrf_field() }}
		</form>
		<form id="form-estoque-movimento-create" action="" class="hidden" method="GET">
			{{ csrf_field() }}
		</form>
	</div>
	<div class="col-sm-4 col-xs-4 hidden" style="height: 100%; padding: 30px; border-left: 1px solid #8bc34a;" id="estoque-movimento-create" >
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

  $(document).on('click', '.btn-estoque-movimento-create, .btn-estoque-movimento-edit', function(e){
  	e.preventDefault();
  	$(".has-error").removeClass("has-error").children(".help-block").last().html('');

  	$(".se-pre-con").fadeIn();
  	$("#estoque-movimento-create").html("");

  	$("#grid-table-header > div:nth-child(7), #grid-table-header > div:nth-child(8), .grid-table > div:nth-child(7), .grid-table > div:nth-child(8)").toggleClass("hidden");

  	$("#grid-table-header > div:nth-child(3), .grid-table > div:nth-child(3)").toggleClass("col-sm-2").toggleClass("col-xs-3");
  	$("#grid-table-header > div:nth-child(6), .grid-table > div:nth-child(6)").toggleClass("col-sm-1").toggleClass("col-xs-2");

  	if( $(this).hasClass('btn-estoque-movimento-create')){
  		$("#estoque-movimento-list").toggleClass("col-sm-8").toggleClass("col-xs-8");
  		$("#estoque-movimento-create").toggleClass("hidden");
  		$("#form-estoque-movimento-create").attr("action", $(this).attr("route")); 
  	} else {
  		$("#estoque-movimento-list").addClass("col-sm-8").addClass("col-xs-8");
  		$("#estoque-movimento-create").removeClass("hidden");
  		$("#form-estoque-movimento-create").attr("action", $(this).attr("route")); 
  	}
  	$("#form-estoque-movimento-create").submit();
  });

  $(document).on("submit", "#estoque-movimentoForm", function(e) {
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
  				$(".btn-estoque-movimento-create").trigger("click");
  				$("#estoque-movimento-create").html("");
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

  $(document).on("submit", "#form-estoque-movimento-create", function(e) {
  	e.preventDefault();
  	var url = $(this).attr("action");
  	var get = $(this).attr("method");
  	var data = $(this).serializeArray();
  	$.ajax({
  		url: url,
  		type: get,
  		data: data,
  		success: function(data){
  			$("#estoque-movimento-create").html(data);
  			
  			$(".se-pre-con").fadeOut();
  			$("#estoque-movimento-novo").draggable();
  			$('#desc_valor_max').mask("#.##0,00", {reverse: true});

  			$("#mesa_qtd").mask('##9');

  			$('#imprime_ip, #nfce_ip').mask('099.099.099.099');
  			$('#desc_perc_max').mask('##0,00%', {reverse: true});


  			$("#nome").focus();

  			var h =  $("body").innerHeight();
  			h -= $(".content-header").innerHeight();
  			h -= 70;
  			$("#estoque-movimento-novo").css("height", h);

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