<!-- sidebar menu: : style can be found in sidebar.less -->
<nav class="navbar navbar-inverse sidebar" role="navigation">
  <div class="navbar-header">
    <!-- Collapsed Hamburger -->
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
      <img src="{{ url('img/logo.png') }}" style="width: 50px">
    </button>
    <!-- Branding Image -->
    <a class="navbar-brand" href="{{ route('financeiro') }}"><img src="{{ \Auth::user()->empresa_id ? url('img/tes.png') : url('img/logo.png') }}"></a>
  </div>
  <div class="collapse navbar-collapse" id="app-navbar-collapse">
    <ul class="nav navbar-nav btn-nav-left scrollbar-inner" id="nav-menu">
      @can('financeiro_read')
      <li class="{{ (Request::is('financeiro') or Request::is('financeiro/*')) ? 'active' : '' }} menuleft7">
        <a href="{{ route('financeiro') }}" data-toggle="tooltip" data-placement="right" title="Financeiro">
          <i class="mdi mdi-cash-multiple mdi-24px"></i>
        </a>
      </li>
      @endcan
      @can('empresa_read')
      <li class="{{ (Request::is('empresa') or Request::is('empresa/*')) ? 'active' : '' }} menuleft3">
        <a href="{{ route('empresa.index') }}" data-toggle="tooltip" data-placement="right" title="Empresas">
          <i class="mdi mdi-store mdi-24px"></i>
        </a>
      </li>
      @endcan
      @can('isAdmin')
      <li class="{{ (Request::is('admin') or Request::is('admin/*')) ? 'active' : '' }}" style="border-left: 3px #673AB7 solid;">
        <a href="{{ route('admin.index') }}" data-toggle="tooltip" data-placement="right" title="Configurações">
          ADM
        </a>
      </li>
      @endcan
      @can('config_read')
      <li class="{{ (Request::is('config') or Request::is('config/*')) ? 'active' : '' }}" style="border-left: 3px #673AB7 solid;">
        <a href="{{ route('config.index') }}" data-toggle="tooltip" data-placement="right" title="Configurações">
          <i class="mdi mdi-settings mdi-24px"></i>
        </a>
      </li>
      @endcan
    </ul>
    <ul class="nav navbar-nav btn-nav-left" >
      @unless (Auth::guest())
      <li class="navbar-user active">
        <a href="#" id="user-profile" tabindex="0" role="button" data-html="true" >
          @if(Auth::user()->img)
          <img src="{{ url(Auth::user()->img)}}" class="img-circle">
          @else
          <img src="{{ url('img/avatars/avatar-blank.png')}}" class="img-circle">
          @endif
        </a>
      </li>
      @endunless
    </ul>
  </div>
</nav>
@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    var h =  $("body").innerHeight();
    h -= $(".navbar-user").outerHeight();
    h -= $(".navbar-brand").outerHeight();

    $('#nav-menu').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": h, "width": "100%"});

    $(document).on('keydown', '.form-control', function(e) {
      var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
      if(key == 13 || 40 == key) {
        e.preventDefault();
        var inputs = $(this).closest('form').find(':input:visible:enabled');
        inputs.eq( inputs.index(this)+1 ).focus();
      }
      if(38 == key) {
        e.preventDefault();
        var inputs = $(this).closest('form').find(':input:visible:enabled');
        inputs.eq( inputs.index(this)-1 ).focus();
      }
    });
    
    $(window).on('resize', function() { 
      var h =  $("body").innerHeight();
      h -= $(".navbar-user").outerHeight();
      h -= $(".navbar-brand").outerHeight();
      $("#nav-menu").parent().css({ "max-height": h });
    });
  });
</script>
@endpush