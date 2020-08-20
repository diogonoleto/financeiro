<?php
use Illuminate\Database\Seeder;
use App\Models\Config\Doeam\MateriaTipo;
use App\Models\Config\Doeam\MateriaLayout;
use App\Models\Config\Doeam\Caderno;

class DoeamTableSeeder extends Seeder
{
	public function run()
	{

		Caderno::truncate();
		Caderno::create([
			'nome' => 'PODER EXECUTIVO'
			]);
		Caderno::create([
			'nome' => 'PODER LEGISLATIVO'
			]);
		Caderno::create([
			'nome' => 'PODER JUDICIÁRIO'
			]);
		Caderno::create([
			'nome' => 'MUNICIPALIDADE'
			]);
		Caderno::create([
			'nome' => 'PUBLICAÇÕES DIVERSOS'
			]);
		Caderno::create([
			'nome' => 'ANEXOS DIVERSOS'
			]);

		MateriaTipo::truncate();
		MateriaTipo::create([
			'nome' => 'Lei'
			]);
		MateriaTipo::create([
			'nome' => 'Lei Complementar'
			]);
		MateriaTipo::create([
			'nome' => 'Lei Delegada'
			]);
		MateriaTipo::create([
			'nome' => 'Lei Orçamentária'
			]);
		MateriaTipo::create([
			'nome' => 'Decreto'
			]);
		MateriaTipo::create([
			'nome' => 'Edital'
			]);
		MateriaTipo::create([
			'nome' => 'Extrato'
			]);
		MateriaTipo::create([
			'nome' => 'Instrução Normativa'
			]);
		MateriaTipo::create([
			'nome' => 'Portária'
			]);

		MateriaLayout::truncate();
		MateriaLayout::create([
			'nome' => '6cm',
			'larg' => '6',
			'unid' => 'cm'
			]);
		MateriaLayout::create([
			'nome' => '12cm',
			'larg' => '12',
			'unid' => 'cm'
			]);
		MateriaLayout::create([
			'nome' => '18cm',
			'larg' => '18',
			'unid' => 'cm'
			]);
		MateriaLayout::create([
			'nome' => '26cm',
			'larg' => '26',
			'unid' => 'cm'
			]);
	}
}
