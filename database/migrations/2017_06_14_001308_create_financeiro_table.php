<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceiroTable extends Migration
{
  public function up()
  {
    Schema::create('fin_bancos', function (Blueprint $table) {
      $table->increments('id'); 
      $table->string('codigo')->nullable();
      $table->string('nome');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('fin_conta_tipos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });
    Schema::create('fin_sistema_pagamentos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });
    Schema::create('fin_contas', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
      $table->unsignedInteger('sistema_pagamento_id')->nullable();
      $table->foreign('sistema_pagamento_id')
            ->references('id')
            ->on('sistema_pagamentos')
            ->onDelete('cascade');
      $table->unsignedInteger('conta_tipo_id');
      $table->foreign('conta_tipo_id')
            ->references('id')
            ->on('conta_tipos')
            ->onDelete('cascade');
      $table->unsignedInteger('conta_id')->nullable();
      $table->foreign('conta_id')
            ->references('id')
            ->on('contas')
            ->onDelete('cascade');
      $table->unsignedInteger('banco_id');
      $table->foreign('banco_id')
            ->references('id')
            ->on('bancos')
            ->onDelete('cascade');
      $table->string('padrao')->default(0);
      $table->string('descricao');
      $table->string('agencia')->nullable();
      $table->string('conta')->nullable();
      $table->string('bandeira')->nullable();
      $table->string('dia_fechamento')->nullable();
      $table->string('dia_vencimento')->nullable();
      $table->decimal('saldo', 10, 2)->default(0);
      $table->timestamp('saldo_data')->nullable();
      $table->enum('tipo_pessoa', ['fisica', 'juridica'])->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('fin_movimentos', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
      $table->unsignedInteger('categoria_id');
      $table->foreign('categoria_id')
            ->references('id')
            ->on('categorias')
            ->onDelete('cascade');
      $table->unsignedInteger('empresa_id')->nullable();
      $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas')
            ->onDelete('cascade');
      $table->unsignedInteger('conta_id');
      $table->foreign('conta_id')
            ->references('id')
            ->on('contas')
            ->onDelete('cascade');
      $table->unsignedInteger('user_id');
      $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
      $table->unsignedInteger('cento_custo_id')->nullable();
      $table->foreign('cento_custo_id')
            ->references('id')
            ->on('cento_custos')
            ->onDelete('cascade');
      $table->unsignedInteger('movimento_id')->nullable();
      $table->foreign('movimento_id')
            ->references('id')
            ->on('movimentos')
            ->onDelete('cascade');
      $table->string('recorrencia')->nullable();
      $table->string('descricao');
      $table->string('observacao')->nullable();
      $table->string('anexo')->nullable();
      $table->date('data_emissao');
      $table->timestamp('data_baixa')->nullable();
      $table->dateTime('data_vencimento');
      $table->string('desconto')->default(0)->nullable();
      $table->string('juro')->default(0);
      $table->decimal('valor', 10, 2);
      $table->decimal('valor_recebido', 10, 2)->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('fin_categorias', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
      $table->unsignedInteger('categoria_id')->nullable();
      $table->foreign('categoria_id')
            ->references('id')
            ->on('categorias')
            ->onDelete('cascade');

      $table->enum('tipo', ['Receita', 'Despesa']);
      $table->string('nome');
      $table->string('descricao')->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('fin_bancos');
    Schema::dropIfExists('fin_conta_tipos');
    Schema::dropIfExists('fin_sistema_pagamentos');
    Schema::dropIfExists('fin_contas');
    Schema::dropIfExists('fin_movimentos');
    Schema::dropIfExists('fin_categorias');
  }
}
