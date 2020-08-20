<?php

use Illuminate\Database\Seeder;
use App\Models\Config\Pagamento\PagamentoForma;

class PagamentosTableSeeder extends Seeder
{

	public function run()
	{
		PagamentoForma::truncate();
		PagamentoForma::create([
			'nome' => 'Diversos',
			]);
		PagamentoForma::create([
			'nome' => 'Dinheiro',
			]);
		PagamentoForma::create([
			'nome' => 'Cartão de Crédito',
			]);
		PagamentoForma::create([
			'nome' => 'Cartão de Débito',
			]);

	}
}
