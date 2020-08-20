<?php

use Illuminate\Database\Seeder;
use App\Models\Config\PDV\Plataforma;

class PontoDeVendasTableSeeder extends Seeder
{
	public function run()
	{
		Plataforma::truncate();
		Plataforma::create([
			'nome' => 'LIVRE',
			'identificador' => 0
			]);
		Plataforma::create([
			'nome' => 'TABLET',
			'identificador' => 1
			]);
		Plataforma::create([
			'nome' => 'PC',
			'identificador' => 0
			]);
	}
}
