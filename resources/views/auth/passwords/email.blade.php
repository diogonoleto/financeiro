@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6" id="login">
      <div class="panel panel-default">
        <div class="panel-heading"><img src="{{ url('img/LogoDiretorioDigital-m.png') }}" class="img-responsive"></div>
        <div class="panel-heading">Redefinir sua senha</div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}
            <div style="margin-top: 10px; margin-bottom: 0px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <div class="col-md-12">
                <label for="email">E-Mail</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div style="margin-top: 10px; margin-bottom: 0px;" class="form-group">
              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary">
                  Enviar link de redefinição de senha
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
