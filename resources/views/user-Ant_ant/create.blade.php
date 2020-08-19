@extends('layouts.admin')
@section('content')
<section class="content-header">
  <img style="background: #fff url({{ url('img/photo1.jpg')}}) no-repeat; background-position: center; background-size: cover; height: 60px; width: 100%;">
</section>
<section class="content">
  <div class="col-sm-3 hidden-xs no-padding text-center" id="content-left">
    @if($item->img)
    <img id="perfil-img" src="{{ asset($item->img) }}" class="img-thumbnail img-responsive img-circle">
    <a href="#" id="btn-img-editar">Editar</a>
    @else
    @if(isset($item->id))
    <img id="perfil-img" src="{{ asset('img/avatar-blank.png') }}" class="img-thumbnail img-circle img-responsive">
    <a href="#" id="btn-img-editar">Adicionar</a>
    @endif
    @endif

    <div class="col-sm-12 hidden" id="div-image">
      @for($i=1; $i < 7; $i++ )
      <div class="col-sm-4">
        <img src="{{ url('img/avatar'.$i.'.png') }}" class="img-circle img-responsive" id="btn-avatar" rel="{{ url('img/avatar'.$i.'.png') }}">
        <br>
      </div>
      @endfor

      @if($item)
      <form class="form-horizontal dropzone" action="{{ route('usuario.avatar') }}" method="POST" enctype="multipart/form-data" >
        {!! method_field('POST') !!}
        {!! csrf_field() !!}
        <input type="hidden" name="id" id="id" value="{{ $item->id }}">
        <input type="file" class="form-control" name="img" id="img">
        <br>
        <div class="pull-right">
          <button class="btn btn-primary" id="btn-image-cancelar">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
      <div id="preview-template"></div>
      @endif
    </div>
  </div>
  <div class="col-sm-6 col-xs-12 no-padding" id="content-central">
    <div class="col-sm-12">
      @if(isset($item->id))
      <h1 style="margin-bottom: 0px; margin-top: 5px;">{{ $item->nome }}</h1>
      <div style="margin-top: -2px; margin-left: 2px; margin-bottom: 5px; color: #cacaca;">Edição de usuarios.</div>
      @else
      <h1 style="margin-bottom: 0px; margin-top: 5px;">Cadastrar</h1>
      <div style="margin-top: -2px; margin-left: 2px; margin-bottom: 5px; color: #cacaca;">cadastro de usuarios.</div>
      @endif
    </div>
    <div class="col-sm-12 no-padding" style="display: none;">
      @if(isset($item->id))
      <ul class="nav list-group-horizontal" id="nav-user">
        <li class="active"><a href="#user" data-toggle="tab"><i class="mdi mdi-content-paste mdi-30"></i>Informação</a></li>
        <li><a href="#appointment" data-toggle="tab"><i class="mdi mdi-calendar mdi-30"></i>Agenda</a></li>
        <li><a href="#composicao" data-toggle="tab"><i class="mdi mdi-calendar mdi-30"></i>Mensagem</a></li>
        <li><a href="#agregados" data-toggle="tab"><i class="mdi mdi-image-filter-none mdi-30"></i>Resumo</a></li>
      </ul>
      @endif
    </div>
    <div class="col-sm-12">
      <div class="tab-content">
        <div class="tab-pane fade in active" id="user">
          <form class="form" method="post"
          @if( isset($item->id) )
          action="{{ route('usuario.update', $item->id) }}">
          {!! method_field('put') !!}
          @else
          action="{{ route('usuario.store') }}">
          @endif
          {!! csrf_field() !!}


          @can('isAdmin')
          <div class="form-group{{ $errors->has('empresa_id') ? ' has-error' : '' }}">
            <label for="empresa_id" class="{{ $errors->has('empresa_id') ? ' has-error' : '' }}">Empresa</label>
            <select class="form-control" name="empresa_id" required>
              <option value="">Selecione a Empresa</option>
              @foreach($empresas as $i)
              @can('gCompany', $i)
              <option value="{{ $i->id }}" {{ ($i->id == $item->empresa_id or old('empresa_id')) ? 'selected' : null }} >{{ $i->nome_fantasia }}</option>
              @endcan
              @endforeach
            </select>
            @if ($errors->has('empresa_id'))
            <span class="help-block">
              <strong>{{ $errors->first('empresa_id') }}</strong>
            </span>
            @endif
          </div>
          @endcan


          <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
            <label for="nome">Nome Completo</label>
            <input type="text" name="nome" class="form-control" placeholder="Digite o Nome" value="{{ $item->nome or old('nome') }}" required>
            <span class="help-block">
            </span>
          </div>

          <h5 style="color: #999;">PERFIL</h5>
          <hr style="margin-top: 0px">
          <div class="pull-right" style="margin-top: -54px;">
            <a href="#" class="btn btn-default" style="padding: 2px 4px; line-height: 1;"><i class="mdi mdi-plus mdi-20px" style="color: green;"></i></a>
          </div>




          <h5 style="color: #999;">TELEFONES</h5>
          <hr style="margin-top: 0px">
          <div class="pull-right" style="margin-top: -54px;">
            <a href="#" class="btn btn-default btn-contato-create" style="padding: 2px 4px; line-height: 1;" route="{{ route('usuario.contato') }}" data-toggle="tooltip" title="Adicionar Telefone" data-placement="bottom"><i class="mdi mdi-plus mdi-20px" style="color: green;"></i></a>
          </div>

          <form id="form-contato-create" action="" class="hidden" method="GET">
            {{ csrf_field() }}
          </form>
          <div class="form-group" id="div-contato">
            
          </div>


          
          @foreach($item->userContato as $k => $fone)
          if($fone->tipo_id == '1')
          <div class="form-group{{ $errors->has('telefone_numero') ? ' has-error' : '' }}" >
            <input type="hidden" value="{{ $fone->id or old('telefone_id') }}" name="telefone_id">
            <div class="col-sm-4" style="padding: 0 0 25px;">
              <div class="input-group">
                <span class="input-group-addon">
                  <input type="radio" name="radio">
                </span>
                <label for="telefone_tipo" style="left: 37px; z-index: 3;">Tipo</label>
                <input type="text" class="form-control" value="{{ $fone->tipo or old('telefone_tipo') }}" name="telefone_tipo" required>
              </div>
            </div>
            <div class="col-sm-8" style="padding: 0 0 25px;">
              <label for="telefone_numero">Telefone</label>
              <input type="text" class="form-control" value="{{ $fone->descricao or old('telefone_numero') }}" name="telefone_numero" id="telefone_numero" style="border-left: 0;" required>
            </div>
            @if ($errors->has('telefone[$k]'))
            <span class="help-block">
              <strong>{{ $errors->first('telefone[$k]') }}</strong>
            </span>
            @endif
          </div>
          @endelse
          @endforeach

          <h5 style="color: #999;">E-MAILS</h5>
          <hr style="margin-top: 0px">
          <div class="pull-right" style="margin-top: -54px;">
            <a href="#" class="btn btn-default btn-email-add" style="padding: 2px 4px; line-height: 1;"><i class="mdi mdi-plus mdi-20px" style="color: green;"></i></a>
          </div>
          <div class="form-group">
            <div class="col-sm-4" style="padding: 0 0 25px;">
              <div class="input-group">
                <span class="input-group-addon">
                  <input type="radio" checked="true">
                </span>
                <label for="email_principal_tipo" style="left: 35px; z-index: 3;">Tipo</label>
                <input type="email" class="form-control" name="email_principal_tipo" value="Principal" disabled="true" required>
              </div>
            </div>
            <div class="col-sm-8" style="padding: 0 0 25px;">
              <label for="email_principal">E-Mail Principal</label>
              <input type="text" class="form-control" value="{{ $item->email or old('email_principal') }}" name="email_principal" style="border-left: 0;" required>
            </div>
            <span class="help-block"></span>
          </div>

          @foreach($item->userContato as $k => $ema)
            @if($fone->tipo_id == '2')
            <div class="form-group">
              <input type="hidden" value="{{ $ema->id or old('email_id') }}" name="email_id">
              <div class="col-sm-4" style="padding: 0 0 25px;">
                <div class="input-group">
                  <span class="input-group-addon">
                    <input type="radio">
                  </span>
                  <label for="email_tipo" style="left: 45px; z-index: 3;">Tipo</label>
                  <input type="text" class="form-control" value="{{ $ema->tipo or old('email_tipo') }}" name="email_tipo" required>
                </div>
              </div>
              <div class="col-sm-8" style="padding: 0 0 25px;">
                <label for="email_email" style="left: 10px;">E-mail</label>
                <input type="email" class="form-control" value="{{ $userContato->descricao or old('email_email') }}" name="email_email" required>
              </div>
              <span class="help-block"></span>
            </div>
             @endif
          @endforeach

          <div class="form-group">
           <button type="submit" class="form-control">Salvar</button>
         </div>
       </form>
     </div>
     <div class="tab-pane fade" id="appointment">
     </div>
   </div>
 </div>
</div>
<div class="col-sm-3 hidden-xs no-padding" id="content-right">
  @forelse($users as $i)
  @can('user', $i)
  <div class="col-sm-12" style="margin-bottom: 5px; margin-top: 5px;">
    <a href="{{ route('usuario.edit', $i->id) }}" style="color:#000;" class="btn-user">
      <div class="col-sm-3 no-padding" style="text-align: right; padding-right: 15px">
        @if($i->img)
        <img src="{{ url($i->img)}}" class="img-thumbnail img-circle " style="max-height: 63px;">
        @else
        <img src="{{ url('img/avatar-blank.png')}}" class="img-thumbnail img-circle " style="max-height: 63px;">
        @endif
      </div>
      <div class="col-sm-9 no-padding">
        <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><b>{{ $i->nome }}</b></div>
        <div style="color: #999;"><small>{{ $i->cargo }}</small></div>
        <div style="color: #31708f;"><small>telefone </small></div>
      </div>
    </a>
  </div>
  @endcan
  @empty
  <div class="col-sm-3" style="margin-bottom: 5px; margin-top: 5px;">Not exist Users</div>
  @endforelse
</div>
</section>

@if(count($errors) > 0)
<div class="alert alert-danger">
  @foreach( $errors->all() as $error )
  <p>{{ $error }}</p>
  @endforeach
</div>
@endif

@endsection

@push('scripts')
<!-- <script src="{{ asset('plugins/inputmask/js/jquery.mask.min.js') }}"></script> -->
<script type="text/javascript">
  $(document).on('submit', 'form', function(){
    $(".se-pre-con").fadeIn();
  });

  $(document).on('click', '.btn-user', function(e){
    $(".se-pre-con").fadeIn();
  });
    // $(document).ready(function(){

    // });
    $(document).on("click", "#btn-img-editar, #btn-image-cancelar", function(e){
      e.preventDefault();
      //$('#img').trigger('click');
      $("#div-image").toggleClass("hidden");
      $("#btn-img-editar").toggleClass("hidden");
    });

    $(document).on("change", "#img", function(e){
      e.preventDefault();
      var tmppath = URL.createObjectURL(e.target.files[0]);
      $("#perfil-img").fadeIn("fast").attr('src',tmppath);
    });



    $(document).on('click', '.btn-contato-create, .btn-contato-edit', function(e){
      e.preventDefault();
      $("#form-contato-create").attr("action", $(this).attr("route"));
      $("#div-contato").append('<input type="hidden" name="telefone_id"><div class="col-sm-4" style="padding: 0 0 25px;"><div class="input-group"><span class="input-group-addon"><input type="radio" name="radio"></span><label for="telefone_tipo" style="left: 37px; z-index: 3;">Tipo</label><input type="text" class="form-control" name="telefone_tipo" required></div></div><div class="col-sm-8" style="padding: 0 0 25px;"><label for="telefone_numero">Telefone</label><input type="text" class="form-control" name="telefone_numero" id="telefone_numero" style="border-left: 0;" required></div><span class="help-block"></span>')
      // $("#form-contato-create").submit();
    });

    $(document).on("submit", "#form-contato-create", function(e) {
      e.preventDefault();
      $(".se-pre-con").fadeIn();
      var url = $(this).attr("action");
      var get = $(this).attr("method");

      var timeNow = moment().format("YYYY-MM-DD HH:mm:ss");
      var now = moment().format("YYYY-MM-DD");
      var setDate = $(".mes-3").attr("date")+"-01";
      if(now > setDate)
        setDate = now;

      var tipo = $("a.tipo.active").attr("id");
      $.ajax({
        url: url+"?tipo="+tipo,
        type: get,
        success: function(data){
          $("#contato-create").html(data);
          $(".tab-pane.fade.in.active").each(function(){
            $(this).find("input[type='text']").focus().select();
          });

          $('#data_vencimento').datetimepicker({
            locale: 'pt-BR',
            defaultDate: setDate,
          });

          $('#data_baixa').datetimepicker({
            locale: 'pt-BR',
            format: 'DD-MM-YYYY HH:mm:ss',
            defaultDate: timeNow,
          });

          $('#data_emissao').datetimepicker({
            locale: 'pt-BR',
            format: 'DD-MM-YYYY',
            defaultDate: timeNow,
          });

          var h = ($(".content").innerHeight() - 380) / 2;
          $("a[href='#contatoCorrente']").css("margin-top", h);
          $('[data-toggle="popover"]').popover();
          $(".se-pre-con").fadeOut();
          $("#categoria-nova, #contato-novo").draggable();
          $("#valor, #desconto, #juro, #valor_recebido").maskMoney({symbol:'R$ ', showSymbol:true, thousands:'.', decimal:',', symbolStay: true});
          $("#valor").focus();
          $("#desconto").focus();
          $("#juro").focus();
          $("#valor_recebido").focus();
          $("#descricao").focus();
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          console.log(data);
          $(".se-pre-con").fadeOut();
        }
      });
    });



  </script>
  @endpush