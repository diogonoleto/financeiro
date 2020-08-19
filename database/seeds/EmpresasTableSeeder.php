<?php

use Illuminate\Database\Seeder;
use App\Models\Empresa\Empresa;
use App\Models\Config\Empresa\EmpresaEntidade;
use App\Models\Config\Empresa\EmpresaEsfera;
use App\Models\Config\Empresa\EmpresaPoder;
use App\Models\Config\Empresa\EmpresaCategoria;
use App\Models\Config\Empresa\EmpresaTipo;

class EmpresasTableSeeder extends Seeder
{
  public function run()
  { 
    EmpresaEntidade::truncate();       
    EmpresaEntidade::create([
      'nome'=> 'PÚBLICO',
      ]);
    EmpresaEntidade::create([
      'nome'=> 'PRIVADO',
      ]);

    EmpresaEsfera::truncate();
    EmpresaEsfera::create([
      'nome'=> 'ESTADUAL',
      'empresa_entidade_id' => '1',
      ]);
    EmpresaEsfera::create([
      'nome'=> 'MUNICIPAL',
      'empresa_entidade_id' => '1',
      ]);

    EmpresaPoder::truncate();
    EmpresaPoder::create([
      'nome'=> 'EXECUTIVO',
      'empresa_esfera_id' => '1',
      ]);
    EmpresaPoder::create([
      'nome'=> 'LEGISLATIVO',
      'empresa_esfera_id' => '1',
      ]);
    EmpresaPoder::create([
      'nome'=> 'JUDICIÁRIO',
      'empresa_esfera_id' => '1',
      ]);
    EmpresaPoder::create([
      'nome'=> 'EXECUTIVO',
      'empresa_esfera_id' => '2',
      ]);
    EmpresaPoder::create([
      'nome'=> 'LEGISLATIVO',
      'empresa_esfera_id' => '2',
      ]);

    EmpresaCategoria::truncate();
    EmpresaCategoria::create([
      'nome'=> 'SECRETARIA',
      'empresa_poder_id' => '1',
      ]);
    EmpresaCategoria::create([
      'nome'=> 'AUTARQUIA',
      'empresa_poder_id' => '1',
      ]);
    EmpresaCategoria::create([
      'nome'=> 'EMPRESA PÚBLICA',
      'empresa_poder_id' => '1',
      ]);
    EmpresaCategoria::create([
      'nome'=> 'FUNDAÇÃO PÚBLICA',
      'empresa_poder_id' => '1',
      ]);
    EmpresaCategoria::create([
      'nome'=> 'ECONOMIA MSTA',
      'empresa_poder_id' => '1',
      ]);

    EmpresaTipo::truncate();
    EmpresaTipo::create([
      'nome'=> 'EMPRESA',
      ]);
    EmpresaTipo::create([
      'nome'=> 'FORNECEDOR',
      ]);

    Empresa::truncate();
    factory(Empresa::class, 2)->create([
      'empresa_tipo_id' => 1,
      'account_id' => 1,
    ]);
    factory(Empresa::class, 5)->create([
      'empresa_tipo_id' => 2,
      'account_id' => 1,
    ]);

    factory(Empresa::class, 5)->create([
      'empresa_tipo_id' => 1,
      'account_id' => 2,
    ]);
    factory(Empresa::class, 2)->create([
      'empresa_tipo_id' => 2,
      'account_id' => 2,
    ]);

    factory(Empresa::class, 1)->create([
      'empresa_tipo_id' => 1,
      'account_id' => 3,
    ]);
    factory(Empresa::class, 10)->create([
      'empresa_tipo_id' => 2,
      'account_id' => 3,
    ]);
  }
}
