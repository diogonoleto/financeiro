@extends('layouts.admin')
@push('style')
<style type="text/css">
#btn-tools-delete-all {
  padding-top: 6.5px;
}
.grid-table:nth-child(odd) {
  background-color: #fbfbfb;
}
.grid-table:hover .telefone  {
  display: none;
}
.grid-table:hover {
  background-color: rgba(187, 255, 108, 0.29)!important;
}

.grid-table > div {
  padding: 10.78px 15px;
  font-size: 16px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.form-group {
  margin-bottom: 10px;
  margin-top: 10px;
}


.slider:before {
  bottom: 2.5px;
}

.krajee-default.file-preview-frame .kv-file-content {
  width: 45px;
  height: 45px;
  float: left;
}
.krajee-default .file-other-icon {
  font-size: 3em;
}

.file-preview {
  border-radius: 0px;
}

.krajee-default .file-caption-info, .krajee-default .file-size-info {
  width: 100%;
  height: 15px;
  margin: auto;
}

.file-caption-main {
  margin-bottom: 30px;
}
.file-caption-main .btn {
  padding: 10px 12px;
}

.file-preview .fileinput-remove {
  top: 10px;
  right: 10px;
  line-height: 10px;
}

.file-caption .file-caption-name, .file-caption.icon-visible .file-caption-icon {
  padding: 5px 0;
}


.krajee-default.file-preview-frame .file-thumbnail-footer {
  height: 50px;
  float: left;
  width: calc(100% - 45px);
}
.krajee-default .file-footer-caption {
  margin-bottom: 0;
  padding-top: 0px;
}

.krajee-default.file-preview-frame {
  margin: 20px 0px 0px;
  width: 100%;
}

.krajee-default .file-drag-handle, .krajee-default .file-upload-indicator {
  margin: 0;
}

.alert {
  margin-bottom: 20px;
  margin-top: 20px;
}

</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>{{ $title }}</h1>
  <div class="input-group pull-right" id="btn-tools">
    <div class="input-group-btn">
      @can('config_importacao_create')
      <a href="#" class="btn btn-default btn-importacao-create" route="{{ route('imp.categoria.create') }}" data-toggle="tooltip" title="Adicionar Importação" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i></a>
      @endcan
      <a href="#" class="btn btn-default" id="btn-search" data-toggle="tooltip" title="Pesquisar Importação" data-placement="bottom"><i class="mdi mdi-magnify mdi-20px"></i></a>
      <div style="width: 5px;display: inline-block;"></div>
      @can('config_perfil_read')
      <a href="{{ route('configPer.perfil.index') }}" class="btn btn-default" data-toggle="tooltip" title="Perfis" data-placement="bottom"><i class="mdi mdi-account-key mdi-20px"></i></a>
      @endcan
      @can('usuario_read')
      <a href="{{ route('usuario.index') }}" class="btn btn-default" data-toggle="tooltip" title="Usuários" data-placement="bottom"><i class="mdi mdi-account-multiple mdi-20px"></i></a>
      @endcan
      @can('fin_centro_custo_read')
      <a href="{{ route('centrocusto.index') }}" class="btn btn-default" data-toggle="tooltip" title="Centro de Custos" data-placement="bottom"><i class="mdi mdi-arrange-bring-to-front mdi-20px"></i></a>
      @endcan
      @can('config_importacao_read')
      <a href="{{ route('importacao.index') }}" style="color:#009688" class="btn btn-default" data-toggle="tooltip" title="Importações" data-placement="bottom"><i class="mdi mdi-import mdi-20px"></i></a>
      @endcan
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  @if(count($errors) > 0)
  <div class="col-sm-8 col-sm-offset-2 alert alert-warning fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    @foreach( $errors->all() as $error )
    <p>{{ $error }}</p>
    @endforeach
  </div>
  @endif

  <div class="col-sm-12 col-xs-12 no-padding" id="div-list">
    <div class="col-xs-12 hidden" id="div-search">
      <form id="form-search" action="{{ route('imp.categoria.lista') }}">
        {{ method_field('GET') }}
        {{ csrf_field() }}
        <input type="text" name="input-search" id="input-search" class="form-control" value="" autocomplete="off" onkeyup="buscarNoticias(this.value)">
      </form>
    </div>
    <div class="col-sm-12 col-xs-12 no-padding" id="grid-table-header">
      <div class="col-sm-8 col-xs-6"><a href="#" class="order" order="razao_social" sort="asc">NOME</a></div>
      <div class="col-sm-3 col-xs-6 text-right"><a href="#" class="order" order="data_importacao" sort="asc">DATA IMPORTAÇÃO</a></div>
    </div>
    <div id="grid-table-body">
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 hidden" id="div-crud">
  </div>
  <form id="delete-form" action="" method="POST" class="hidden">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
  </form>
  <form id="form-importacao-create" action="" class="hidden" method="GET">
    {{ csrf_field() }}
  </form>
</section>
<!-- /.content -->
@endsection
@push('link')
<link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('plugins/fileInput/css/fileinput.min.css') }}" />
@endpush
@push('scripts')
<script type="text/javascript" src="{{ asset('js/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/maskInput/jquery.mask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('plugins/fileInput/js/fileinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/fileInput/js/locales/pt-BR.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
   @if(count($errors) > 0)
   $('.alert').delay(5000).fadeOut('3000', function(){ $(this).remove(); });
   @endif
 });
  $(document).on('click', '.btn-importacao-create', function(e){
    e.preventDefault();
    $(".se-pre-con").fadeIn();
    $(".has-error").removeClass("has-error").children(".help-block").last().html('');
    $("#input-search").val("").parent().parent().addClass("hidden");

    if( $(this).hasClass('ativo')){
      $(".tools-user").removeClass("hidden");
      $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
      $(".pagination-bottom").removeClass("pag-right");
      $("#div-crud").addClass("hidden").html("");
      $(".ativo").removeClass("ativo");
      $(".se-pre-con").fadeOut();
      return false;
    }

    var ati = 0;
    $(".ativo").each(function(i, item){
      ati = 1;
    });
    if(ati == 1){
      $(".ativo").removeClass("ativo");
    } else {
      $(".tools-user").toggleClass("hidden");
      $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
      $("#div-crud").toggleClass("hidden");
      $(".pagination-bottom").addClass("pag-right");
    }
    $(this).addClass('ativo');
    $("#form-importacao-create").attr("action", $(this).attr("route"));
    $("#form-importacao-create").submit();
  });

  $(document).on("submit", "#form-importacao-create", function(e) {
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
        $("#div-crud").html(data);

        $('.form-crud').scrollbar({
          "scrollx": "none",
          disableBodyScroll: true,
        });
        resizediv();

        $("#import_file").fileinput({
          // uploadUrl: "{{ route('imp.categoria.store') }}",
          language: 'pt-BR',
          showUpload: false,
          showRemove: false,
          uploadExtraData: { _token:"{{csrf_token()}}",  },
          allowedFileExtensions: ["xlsx", "xls"],
          fileActionSettings: {
            showZoom: false
          },
          browseOnZoneClick: true,
          maxFilePreviewSize: 10240
        });
        $(".se-pre-con").fadeOut();
      },
      error: function(data){
        console.log(data);
        $("#movimento-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
        $("#movimento-create").toggleClass("hidden");
        $(".se-pre-con").fadeOut();
      }
    });
  });

  $(document).on("submit", "#importacaoForm", function(e) {
    $(".se-pre-con").fadeIn();
  });

  $(document).on('click', '.btn-importacao-cancelar', function(e) {
    e.preventDefault();
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $("#div-crud").addClass("hidden").html("");
    $(".pagination-bottom").removeClass("pag-right");
    $(".ativo").removeClass("ativo");
  });

  var resizediv = function (){
    var h =  $("body").innerHeight();
    h -= $(".content-header").innerHeight();
    h -= $("#grid-table-header").innerHeight();
    $(".form-crud").css("max-height", h).parent().css({ "max-height": h });
    $(".se-pre-con").fadeOut();
  }

</script>
@endpush
