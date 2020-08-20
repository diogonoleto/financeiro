<?php

use Illuminate\Database\Seeder;
use App\Account;
class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::truncate();
        factory(Account::class,1)->create([
            'nome' => 'Bandeira & Barbirato',
            'subdomain' => 'bmb'
        ]);
        factory(Account::class,1)->create([
            'nome' => 'Diretório Digital',
            'subdomain' => 'diretorio'
        ]);
        factory(Account::class,1)->create([
            'nome' => 'Morokye Açaí',
            'subdomain' => 'morokye'
        ]);
        factory(Account::class,1)->create([
            'nome' => 'Grafica Brasil',
            'subdomain' => 'graficabra'
        ]);
    }
}
