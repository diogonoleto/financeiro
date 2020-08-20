<div class="col-md-12 no-padding">
  <hr>
  <h2 class="timeline">Lançamentos do dia {{ Carbon\Carbon::parse($date)->format('d') }}</h2>
  <ul class="timeline">
    @forelse($agmovimentos as $m)
    <li title="Ver este item na tela de movimentações">
      <p>
        <a href="#" class="btn-movimento-edit" rel="{{ $m->id }}">
          <strong>{{ $m->descricao }}<span class="recorencia"> {{ $m->recorrencia != "" ? '('.$m->recorrencia.') ' : '' }}</span> – <span class="number green">R$ {{ number_format($m->valor, 2, ',', '.') }}</span>
          </strong>
        </a>
      </p>
      <em class="muted">{{ Carbon\Carbon::parse($m->data_vencimento)->format('d/m') }}, para: {{ $m->categoria->nome }}</em>
      <div class="timeline-check">
        <input class="ckPaid" expense-category="1" type="checkbox" id="226791645" tipo="E" data="11_08">
        <label class="calendarCheckLabel" for="226791645">
          <i class="icon-ok">
          </i>
        </label> marcar como pago
      </div>
    </li>
    @empty
    <li>
      <p>Você não tem lançamentos para hoje.</p>
    </li>
    @endforelse
  </ul>
</div>

@if( $date == $agora )
<div class="col-md-12 no-padding">
  <hr>
  <h2 class="timeline">Lançamentos em atraso</h2>
  <ul class="timeline">
    @forelse($atmovimentos as $m)
    <li class="{{ $m->tipo }}"  ttitle="Ver este item na tela de movimentações">
      <p>
        <a href="#" class="btn-movimento-edit" rel="{{ $m->id }}">
          <strong>{{ $m->descricao }}<span class="recorencia"> {{ $m->recorrencia != "" ? '('.$m->recorrencia.') ' : '' }}</span> – <span class="number {{ $m->tipo == 'Receita' ? 'green' : 'red' }} ">R$ {{ number_format($m->valor, 2, ',', '.') }}</span>
          </strong>
        </a>
      </p>
      <em class="muted">{{ Carbon\Carbon::parse($m->data_vencimento)->format('d/m') }}, para: {{ $m->categoria->nome }}</em>
      <div class="timeline-check">
        <input class="ckPaid" expense-category="1" type="checkbox" id="226791645" tipo="E" data="11_08">
        <label class="calendarCheckLabel" for="226791645">
          <i class="icon-ok">
          </i>
        </label><!--  marcar como pago -->
      </div>
    </li>
    @empty
    <li>
      <p>Você não tem lançamentos atraso.</p>
    </li>
    @endforelse
  </ul>
</div>
@endif