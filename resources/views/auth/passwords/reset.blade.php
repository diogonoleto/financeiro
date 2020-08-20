@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6" id="login">
      <div class="panel panel-default">
        <div class="panel-heading"><img src="{{ url('img/LogoDiretorioDigital-m.png') }}" class="img-responsive"></div>
        <div class="panel-heading">Trocar A Senha</div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">
            <div style="margin-top: 10px; margin-bottom: 10px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <div class="col-md-12">
                <label for="email">E-Mail</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div style="margin-top: 10px; margin-bottom: 10px;" class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
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
            <div style="margin-top: 10px; margin-bottom: 10px;" class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}"> 
              <div class="col-md-12">
                <label for="password-confirm">Confirmar Senha</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                @if ($errors->has('password_confirmation'))
                <span class="help-block">
                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div style="margin-top: 10px; margin-bottom: 0px;" class="form-group">
              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary">
                  Trocar Senha
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
