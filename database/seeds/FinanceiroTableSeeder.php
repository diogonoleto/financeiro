<?php

use Illuminate\Database\Seeder;
use App\Models\Financeiro\FinCategoria;
use App\Models\Config\Financeiro\FinBanco;
use App\Models\Config\Financeiro\FinContaTipo;
use App\Models\Config\Financeiro\FinSistemaPagamento;

class FinanceiroTableSeeder extends Seeder
{
	public function run()
	{

		FinBanco::truncate();
		FinBanco::create([
			'nome' => 'Banco do Brasil'
			]);
		FinBanco::create([
			'nome' => 'Bradesco'
			]);
		FinBanco::create([
			'nome' => 'Caixa Econômica'
			]);
		FinBanco::create([
			'nome' => 'Itaú'
			]);
		FinBanco::create([
			'nome' => 'Santander'
			]);
		FinBanco::create([
			'nome' => 'Sicredi'
			]);
		FinBanco::create([
			'nome' => 'Banco de Brasília'
			]);
		FinBanco::create([
			'nome' => 'Banco do Nordeste'
			]);
		FinBanco::create([
			'nome' => 'Banco Real'
			]);
		FinBanco::create([
			'nome' => 'Banco Safra'
			]);
		FinBanco::create([
			'nome' => 'Bancoob'
			]);
		FinBanco::create([
			'nome' => 'Banestes'
			]);
		FinBanco::create([
			'nome' => 'BankBoston'
			]);
		FinBanco::create([
			'nome' => 'Banpara'
			]);
		FinBanco::create([
			'nome' => 'Banrisul'
			]);
		FinBanco::create([
			'nome' => 'BCN'
			]);
		FinBanco::create([
			'nome' => 'Citibank'
			]);
		FinBanco::create([
			'nome' => 'Credisan'
			]);
		FinBanco::create([
			'nome' => 'HSBC'
			]);
		FinBanco::create([
			'nome' => 'Mercantil do Brasil'
			]);
		FinBanco::create([
			'nome' => 'Nossa Caixa'
			]);
		FinBanco::create([
			'nome' => 'Sicoob'
			]);
		FinBanco::create([
			'nome' => 'Sudameris'
			]);
		FinBanco::create([
			'nome' => 'Unibanco'
			]);
		FinBanco::create([
			'nome' => 'Viacredi'
			]);
		FinBanco::create([
			'nome' => 'Outro Banco'
			]);

		FinCategoria::truncate();
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'nome' => 'Receitas de Venda',
			'descricao' => 'Receitas de Vendas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'categoria_id' => 1,
			'nome' => 'vendas',
			'descricao' => 'Receitas de Vendas',
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'nome' => 'Receitas Financeiras',
			'descricao' => 'Receitas e Rendimentos Financeiros'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'categoria_id' => 3,
			'nome' => 'Aplicações Financeiras',
			'descricao' => 'Receitas e Rendimentos Financeiros'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'nome' => 'Outras Receitas',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'categoria_id' => 5,
			'nome' => 'Ajuste Caixa',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'categoria_id' => 5,
			'nome' => 'Devolução de adiantamento',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'nome' => 'Despesas Administrativas e Comerciais',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'categoria_id' => 8,
			'nome' => 'Água',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'categoria_id' => 8,
			'nome' => 'Aluguel',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'categoria_id' => 8,
			'nome' => 'Assessorias e Associações',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'categoria_id' => 8,
			'nome' => 'Cartório',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'categoria_id' => 8,
			'nome' => 'Remuneração funcionários',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Receita',
			'nome' => 'Receitas de Salário',
			'descricao' => 'Receitas de Salário'
			]);
		FinCategoria::create([
			'account_id'=> 1,
			'tipo'=> 'Despesa',
			'categoria_id' => 15,
			'nome' => 'Salário Líquido',
			'descricao' => 'Receitas de Salário'
			]);

				FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'nome' => 'Receitas de Venda',
			'descricao' => 'Receitas de Vendas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'categoria_id' => 16,
			'nome' => 'vendas',
			'descricao' => 'Receitas de Vendas',
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'nome' => 'Receitas Financeiras',
			'descricao' => 'Receitas e Rendimentos Financeiros'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'categoria_id' => 18,
			'nome' => 'Aplicações Financeiras',
			'descricao' => 'Receitas e Rendimentos Financeiros'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'nome' => 'Outras Receitas',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'categoria_id' => 20,
			'nome' => 'Ajuste Caixa',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'categoria_id' => 20,
			'nome' => 'Devolução de adiantamento',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'nome' => 'Despesas Administrativas e Comerciais',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'categoria_id' => 23,
			'nome' => 'Água',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'categoria_id' => 23,
			'nome' => 'Aluguel',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'categoria_id' => 23,
			'nome' => 'Assessorias e Associações',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'categoria_id' => 23,
			'nome' => 'Cartório',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'categoria_id' => 23,
			'nome' => 'Remuneração funcionários',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Receita',
			'nome' => 'Receitas de Salário',
			'descricao' => 'Receitas de Salário'
			]);
		FinCategoria::create([
			'account_id'=> 2,
			'tipo'=> 'Despesa',
			'categoria_id' => 15,
			'nome' => 'Salário Líquido',
			'descricao' => 'Receitas de Salário'
			]);
				FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'nome' => 'Receitas de Venda',
			'descricao' => 'Receitas de Vendas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'categoria_id' => 29,
			'nome' => 'vendas',
			'descricao' => 'Receitas de Vendas',
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'nome' => 'Receitas Financeiras',
			'descricao' => 'Receitas e Rendimentos Financeiros'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'categoria_id' => 31,
			'nome' => 'Aplicações Financeiras',
			'descricao' => 'Receitas e Rendimentos Financeiros'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'nome' => 'Outras Receitas',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'categoria_id' => 33,
			'nome' => 'Ajuste Caixa',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'categoria_id' => 35,
			'nome' => 'Devolução de adiantamento',
			'descricao' => 'Outras Receitas não Operacionais'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'nome' => 'Despesas Administrativas e Comerciais',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'categoria_id' => 38,
			'nome' => 'Água',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'categoria_id' => 38,
			'nome' => 'Aluguel',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'categoria_id' => 38,
			'nome' => 'Assessorias e Associações',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'categoria_id' => 38,
			'nome' => 'Cartório',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'categoria_id' => 38,
			'nome' => 'Remuneração funcionários',
			'descricao' => 'Despesas Administrativas'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Receita',
			'nome' => 'Receitas de Salário',
			'descricao' => 'Receitas de Salário'
			]);
		FinCategoria::create([
			'account_id'=> 3,
			'tipo'=> 'Despesa',
			'categoria_id' => 44,
			'nome' => 'Salário Líquido',
			'descricao' => 'Receitas de Salário'
			]);


		FinSistemaPagamento::truncate();
		FinSistemaPagamento::create([
			'nome' =>'PayU'
			]);
		FinSistemaPagamento::create([
			'nome' =>'Iugu'
			]);
		FinSistemaPagamento::create([
			'nome' =>'MercadoPago'
			]);
		FinSistemaPagamento::create([
			'nome' =>'Moip'
			]);
		FinSistemaPagamento::create([
			'nome' =>'PagSeguro'
			]);
		FinSistemaPagamento::create([
			'nome' =>'Paypal'
			]);
		FinSistemaPagamento::create([
			'nome' =>'Outros'
			]);

		FinContaTipo::truncate();
		FinContaTipo::create([
			'nome' =>'Conta-Corrente'
			]);
		FinContaTipo::create([
			'nome' =>'Conta de poupança'
			]);
		FinContaTipo::create([
			'nome' =>'Conta-Salário'
			]);
		FinContaTipo::create([
			'nome' =>'Cartão de Crédito'
			]);
		FinContaTipo::create([
			'nome' =>'Aplicação Automatica'
			]);
		FinContaTipo::create([
			'nome' =>'Meios de Pagamentos'
			]);

		FinContaTipo::create([
			'nome' =>'Investimento'
			]);
		FinContaTipo::create([
			'nome' =>'Caixinha'
			]);
		FinContaTipo::create([
			'nome' =>'Outras'
			]);
	}
}
