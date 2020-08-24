<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'DNS ODontológica') }}</title>
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('plugins/materialdesign/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/jqueryScrollbar/jquery.scrollbar.css') }}"/>
  @stack('link')
  @stack('style')
  <style type="text/css" id="apresentacao"></style>
  <!-- Scripts -->
  <script>
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      ]) !!};
  </script>
</head>
<body>
    @include('sidebar.left')
    <div class="main">
      @yield('content')
    </div>
    <div class="pop-content hidden" id="user-profile-popover">
      <div class="img-circle-border">
        @if(Auth::user()->img)
          <img src="{{ url(Auth::user()->img) }}" class="img-circle img-responsive">
        @else
          <img src="{{ url('img/avatars/avatar-blank.png') }}" class="img-circle img-responsive" >
        @endif
      </div>
      <div class="popover-body">
        <div class="row">
          <div class="col-xs-3" style="min-height: 100%; background-color: #fafafa; margin-top: -16px; height: 230px;">
          </div>
          <div class="col-xs-9 no-padding">
            <div class="col-xs-12">
              <div style="font-size: 18px;">{{ Auth::user()->nome }}</div>
              <small>{{ Auth::user()->cargo }}</small>
            </div>
            <div class="col-xs-12 no-padding">
              <hr>
            </div>
            <div class="col-xs-12">
              <div><b>TELEFONE</b></div>
              <div>{{ Auth::user()->telefone }}</div>
            </div>
            <div class="col-xs-12">
              <div><b>E-MAIL</b></div>
              <div>{{ Auth::user()->email }}</div><br>
            </div>

            <div class="list-group-horizontal pull-right">
              <a href="{{ route('password.change') }}" id="btn-user-calendar">
                 Trocar Senha
              </a>
              <a href="{{ route('logout') }}"  id="btn-user-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout mdi-24"></i>Sair
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="popover-footer">
        <a href="{{ route('usuario.cadastro') }}">Editar Perfil</a>
      </div>
    </div>
    <div class="se-pre-con"></div>
    <div class="spotlight-lens hidden">
      <div class="spotlight-teaser">
        <span class="spotlight-header h2"></span>
        <div class="spotlight-text"></div>
        <div class="spotlight-footer hidden" >
          <div class="text-left"><input type="checkbox" name="apresentacao" id="apresentacao" value="0"><i class="mdi mdi-checkbox-blank-outline mdi-24px checkbox checkbox-spotlight"></i> Não Mostrar mais no inicio!</div>
          <a href="#" class="btn btn-default btn-lg" id="btn-apresentacao-prev">Anterior</a>
          <a href="#" class="btn btn-default btn-lg" id="btn-apresentacao-next">Proximo</a>
          <a href="#" class="btn btn-default btn-lg" id="btn-apresentacao-fina">Finalizar</a>
        </div>
      </div>
    </div>
    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/jqueryScrollbar/jquery.scrollbar.js') }}"></script>

    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
  </body>
</html>
