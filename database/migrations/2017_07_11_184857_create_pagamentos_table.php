<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagamentosTable extends Migration
{
  public function up()
  {
    Schema::create('cobranca_grupos', function (Blueprint $table) {
      $table->increments('id');

      $table->string('nome');
      $table->tinyInteger('tipo')->comment('1-faixa;2-valor;3-quadrante');

      $table->timestamps();
    });
    Schema::create('cobranca_items', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('cobranca_grupo_id');
      $table->foreign('cobranca_grupo_id')
            ->references('id')
            ->on('cobranca_grupos')
            ->onDelete('cascade');

      $table->decimal('valor', 10, 2);

      $table->unsignedInteger('faixa_inicial');
      $table->unsignedInteger('faixa_final');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empenhos', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');

      $table->string('numero');

      $table->decimal('valor', 10, 2);
      $table->decimal('saldo', 10, 2);

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('pagamento_titulos', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('cobranca_item_id');
      $table->foreign('cobranca_item_id')
            ->references('id')
            ->on('cobranca_items')
            ->onDelete('cascade');

      $table->unsignedInteger('user_id');
      $table->foreign('user_id')
            ->references('id')
            ->on('user')
            ->onDelete('cascade');

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');

      $table->unsignedInteger('pagamento_forma_id');
      $table->foreign('pagamento_forma_id')
            ->references('id')
            ->on('pagamento_formas')
            ->onDelete('cascade');

      $table->unsignedInteger('materia_qtd');

      $table->unsignedInteger('empenho_id')->nullable();
      $table->decimal('empenho_saldo', 10, 2)->nullable();

      $table->unsignedInteger('faixa_inicial');
      $table->unsignedInteger('faixa_final');
      $table->decimal('valor', 10, 2);

      $table->timestamp('data_inicial');
      $table->timestamp('data_final');
      $table->timestamp('data_pagamento')->nullable();

      $table->unsignedInteger('usuario_baixa_id')->nullable();

      $table->tinyInteger('status')->comment('0-nao pago;1-pago');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('pagamento_formas', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('cobranca_grupos');
    Schema::dropIfExists('cobranca_items');
    Schema::dropIfExists('empenhos');
    Schema::dropIfExists('pagamento_titulos');
    Schema::dropIfExists('pagamento_formas');
  }
}
