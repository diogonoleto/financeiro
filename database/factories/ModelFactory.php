<?php

use App\User;
use App\Account;
use App\Models\Empresa\Empresa;
use App\Models\Fornecedor\Fornecedor;
use App\Models\Produto\Produto;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
  static $password;
  return [
    'nome' => $faker->name,
    'account_id'=> random_int(1,4),
    'user_tipo_id' => 1,
    'email' => $faker->unique()->safeEmail,
    'password' => $password ?: $password = bcrypt('112233'),
    'cpf' => $faker->unique()->cpf,
    'img' => 'img/avatar'.random_int(1, 5).'.png',
    'remember_token' => str_random(10),
  ];
});

$factory->define(Account::class, function (Faker\Generator $faker) {
  return [
    'nome' => $faker->name,
    'subdomain' => 'demo'
  ];
});

$factory->define(Empresa::class, function (Faker\Generator $faker) {
  return [
    'empresa_entidade_id'=> 1,
    'account_id'=> random_int(1,4),
    'empresa_tipo_id' => 1,
    'cnpj'=> $faker->cnpj,
    'razao_social'=> $faker->company,
    'nome_fantasia'=> $faker->company,
    'data_fundacao'=> $faker->date,
    'img'=> 'img/empresa.png',
    'inscricao_estadual' => $faker->buildingNumber,
    'inscricao_municipal' => $faker->buildingNumber,
  ];
});

$factory->define(Produto::class, function (Faker\Generator $faker) {
  return [
    'account_id'=> random_int(1,4),
    'empresa_id'=> random_int(1,10),
    'produto_categoria_id'=> 1,
    'unidade_medida_id' => 1,
    'tipo_insumo' => 1,
    'disponivel_venda' => 1,
    'ncm_id'=> 1,
    'cest_id'=> 1,
    'cfop_id'=> 1,
    'icms_grupo_id'=> 1,
    'pis_confis_grupo_id'=> 1,
    'ipi_grupo_id'=> 1,
    'produto_cor_id'=> 1,
    'nome' => $faker->word,
    'preco' => $faker->randomDigit(2, 0, 9999),
    'descricao' => $faker->sentence(),
    'status'=> 1,
  ];
});