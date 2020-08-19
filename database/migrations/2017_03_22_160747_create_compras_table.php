<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
  public function up()
  {
    Schema::create('compras', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
      $table->unsignedInteger('empresa_id');
      $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas')
            ->onDelete('cascade');
      $table->unsignedInteger('estoque_id')->nullable();
      $table->foreign('estoque_id')
            ->references('id')
            ->on('estoques')
            ->onDelete('cascade');
      $table->unsignedInteger('fornecedor_id')->nullable();
      $table->foreign('fornecedor_id')
            ->references('id')
            ->on('fornecedors')
            ->onDelete('cascade');

      $table->timestamp('data_emissao');

      $table->enum('documento_tipo', ['NOTA FISCAL', 'RECIBO'])->nullable();
      $table->string('documento_num')->nullable();

      $table->decimal('valor', 10, 2);
      $table->tinyInteger('qtde');

      $table->tinyInteger('status')->default('0');

      $table->timestamp('finalized_at')->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('compra_itens', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('compra_id');
      $table->foreign('compra_id')
            ->references('id')
            ->on('compras')
            ->onDelete('cascade');
      $table->unsignedInteger('produto_unidade_medida_id');
      $table->foreign('produto_unidade_medida_id')
            ->references('id')
            ->on('produto_unidade_medidas')
            ->onDelete('cascade');

      $table->enum('documento_tipo', ['NOTA FISCAL', 'RECIBO'])->nullable();
      $table->string('documento_num')->nullable();

      $table->decimal('valor', 10, 2);
      $table->tinyInteger('qtde');

      $table->tinyInteger('tipo');

      $table->date('data_validade')->nullable();
      $table->timestamp('data_troca')->nullable();

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('compras');
    Schema::dropIfExists('compra_itens');
  }
}
