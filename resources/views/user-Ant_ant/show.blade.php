@extends('layouts.admin')

@section('content')

  <div class="col-lg-12 no-padding">
    <img style="background: #fff url({{ url('img/photo1.jpg')}}) no-repeat; background-position: center; background-size: cover; height: 100px; width: 100%;">
  </div>
    
  <div style="height: 100%; border-top: 1px solid #f6f6f6">
    <div class="col-lg-3 no-padding text-center" style="background-color: #fafafa; height: 100%;">
      @if($item->img)
        <!-- <a href="#" id="btn-img-editar" style="position: absolute; margin-top: 35px; width: 100%; left: 0; font-size: 18px; color: #fff;" >Edit</a> -->
        <img src="{{ asset($item->img) }}" class="img-thumbnail img-circle img-responsive" style="height: 170px;margin-top: -85px; margin-bottom: 10px;" id="user-avatar">
      @else
        @if(isset($item->id))
          <!-- <a  href="#" id="btn-img-editar" style="position: absolute; margin-top: 35px; width: 100%; left: 0; font-size: 18px; color: #fff;">Adicionar foto</a> -->
          <img src="{{ asset('img/avatar-blank.png') }}" class="img-thumbnail img-circle img-responsive" style="height: 170px;margin-top: -85px; margin-bottom: 10px;" id="user-avatar">
        @endif
      @endif

    </div>
    <div class="col-lg-9 no-padding">
      <div class="col-lg-8 no-padding">
        <div class="col-lg-12">
          <h1 style="margin-bottom: 0px;">{{ $item->name }}</h1>
          <div style="margin-top: -2px; margin-left: 2px; margin-bottom: 5px; color: #cacaca;">{{ $item->position }}</div>
        </div>
        <div class="col-lg-12 no-padding">
          <ul class="nav list-group-horizontal" id="nav-user">
            <li class="active"><a href="#" id="btn-company"><i class="mdi mdi-content-paste mdi-30"></i>Informação</a></li>
            <li><a href="#" data-toggle="tab"><i class="mdi mdi-calendar mdi-30"></i>Agenda</a></li>
            <li><a href="#composicao" data-toggle="tab"><i class="mdi mdi-calendar mdi-30"></i>Mensagem</a></li>
            <li><a href="#agregados" data-toggle="tab"><i class="mdi mdi-image-filter-none mdi-30"></i>Resumo</a></li>
          </ul>
        </div>
        <div class="col-lg-12">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </div>
      </div>
      <div class="col-lg-4 no-padding" style="background-color: #fafafa; height: 100%; overflow-y: auto;"">
  
      </div>
    </div>
  </div>


@endsection

@push('scripts')
@endpush