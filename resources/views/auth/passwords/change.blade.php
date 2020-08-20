@extends('layouts.admin')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6" id="login">
      <div class="panel panel-default">
        <div class="panel-heading">Trocar a Senha</div>
        <div class="panel-body">
          @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
          @endif
          @if (session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif
          <form class="form-horizontal" method="POST" action="{{ route('changePassword') }}">
            {{ csrf_field() }}
            <div style="margin-top: 10px; margin-bottom: 10px;" class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
              <div class="col-md-12">
                <label for="current-password">Senha Atual</label>
                <input id="current-password" type="password" class="form-control" name="current-password" required>
                @if ($errors->has('current-password'))
                <span class="help-block">
                  <strong>{{ $errors->first('current-password') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div style="margin-top: 10px; margin-bottom: 10px;" class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
              <div class="col-md-12">
                <label for="new-password">Nova Senha</label>
                <input id="new-password" type="password" class="form-control" name="new-password" required>
                @if ($errors->has('new-password'))
                <span class="help-block">
                  <strong>{{ $errors->first('new-password') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div style="margin-top: 10px; margin-bottom: 10px;" class="form-group{{ $errors->has('new-password_confirmation') ? ' has-error' : '' }}">
              <div class="col-md-12">
                <label for="new-password-confirm">Confirmar Senha</label>
                <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
              </div>
             @if ($errors->has('new-password_confirmation'))
              <span class="help-block">
                <strong>{{ $errors->first('new-password_confirmation') }}</strong>
              </span>
              @endif
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