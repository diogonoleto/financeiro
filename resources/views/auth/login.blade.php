@extends('layouts.app')
@section('content')
<div class="col-md-6" id="login">
  <div class="panel panel-default">
    <div class="panel-heading text-center">
      <img src="{{ url('img/logo_c.png') }}" style="width:250px">
    </div>
    <div class="panel-body">
      <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div  style="margin-top: 10px; margin-bottom: 0px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <div class="col-md-12">
            <label for="email">E-Mail  / CPF</label>
            <input id="email" type="text" class="form-control" name="login" value="{{ old('login') }}" autofocus>
            @if ($errors->has('login'))
            <span class="help-block">
              <strong>{{ $errors->first('login') }}</strong>
            </span>
            @endif
          </div>
        </div>
        <div  style="margin-top: 10px; margin-bottom: 0px;" class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          <div class="col-md-12">
            <label for="password">Senha</label>
            <input id="password" type="password" class="form-control" name="password" required>
            @if ($errors->has('password'))
            <span class="help-block">
              <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>
        </div>
        <div  style="margin-top: 10px; margin-bottom: 0px;" class="form-group">
          <div class="col-md-12">
            <div class="checkbox">
              <i class="mdi {{ old('remember') ? 'mdi-checkbox-marked-outline' : 'mdi-checkbox-blank-outline' }} mdi-24px "></i> Lembrar-me
              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 
            </div>
          </div>
        </div>
        <div  style="margin-top: 10px; margin-bottom: 0px;" class="form-group">
          <div class="col-md-6 col-md-offset-3" id="dsubm">
            <button type="submit" class="btn btn-primary btn-block">
              Acessar
            </button>
          </div>
<!--           <div class="col-md-12 text-center">OR</div>
          <div class="col-md-6 col-md-offset-3">
            <a href="{{url('/redirect')}}" class="btn btn-primary btn-block">Login with Facebook</a>
          </div> -->
        </div>
        <a class="btn btn-link" href="{{ route('password.request') }}">
          Esqueceu seu login ou senha?
        </a>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
  $(document).on('click', '.checkbox', function(e){
    e.preventDefault();
    if($(this).children('input').is(':checked')){
      $(this).children('input').removeAttr('checked');
      $(this).children("i").removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
    } else {
      $(this).children('input').attr("checked", "checked");
      $(this).children("i").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
    }
  });
</script>
@endpush