<?php

use Illuminate\Database\Seeder;
use App\Models\Config\User\UserTipo;
use App\User;
class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    UserTipo::truncate();
    UserTipo::create([
      'nome' => 'Cliente',
      ]);
    UserTipo::create([
      'nome' => 'Funcionario',
      ]);

    User::truncate();
    User::create([
      'nome' => 'Diogo Noleto',
      'empresa_id'=> 1,
      'account_id' => 1,
      'user_tipo_id' => 1,
      'email' => 'diogonoletodasilva@gmail.com',
      'cargo' => 'Desenvolvedor WEB',
      'cpf' => '74036807234',
      'password' => bcrypt('112233'),
      'img' => 'img/diogo.jpg',
      'remember_token' => str_random(10),
    ]);
    User::create([
      'nome' => 'Marcos Paulo',
      'account_id' => 2,
      'empresa_id'=> 2,
      'user_tipo_id' => 1,
      'email' => 'marcosp.castro@gmail.com',
      'cargo' => 'Desenvolvedor WEB',
      'cpf' => '74317016249',
      'password' => bcrypt('112233'),
      'img' => 'img/marcos.jpg',
      'remember_token' => str_random(10),
    ]);
    User::create([
      'nome' => 'Renan Arce',
      'account_id' => 3,
      'empresa_id'=> 3,
      'user_tipo_id' => 1,
      'email' => 'renan.arce@gmail.com',
      'cpf' => '74590162253',
      'cargo' => 'Desenvolvedor WEB',
      'password' => bcrypt('112233'),
      'img' => 'img/renan.jpg',
      'remember_token' => str_random(10),
    ]);

    factory(User::class,20)->create([
        'account_id' => 1,
        'empresa_id'=> 1,
        'user_tipo_id' => 2
    ]);

    factory(User::class,50)->create([
        'account_id' => 2,
        'empresa_id'=> 2,
        'user_tipo_id' => 2
    ]);

    factory(User::class,5)->create([
        'account_id' => 3,
        'empresa_id'=> 1,
        'user_tipo_id' => 2
    ]);


    factory(User::class,1)->create([
        'account_id' => 1,
        'user_tipo_id' => 1
    ]);

    factory(User::class,30)->create([
        'account_id' => 2,
        'user_tipo_id' => 1
    ]);

    factory(User::class,10)->create([
        'account_id' => 3,
        'user_tipo_id' => 1
    ]);

    }
  }
