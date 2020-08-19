<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        
        $this->call(AccountsTableSeeder::class);
        $this->call(FinanceiroTableSeeder::class);
        // $this->call(DoeamTableSeeder::class);
        $this->call(EmpresasTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(ProdutosTableSeeder::class);
        $this->call(PontoDeVendasTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
