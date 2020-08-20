<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstoquesTable extends Migration
{
  public function up()
  {

    Schema::create('estoques', function (Blueprint $table) {
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

      $table->unsignedInteger('ponto_de_venda_id');
      $table->foreign('ponto_de_venda_id')
            ->references('id')
            ->on('ponto_de_vendas')
            ->onDelete('cascade');

      $table->unsignedInteger('estoque_tipo_id');
      $table->foreign('estoque_tipo_id')
            ->references('id')
            ->on('estoque_tipos')
            ->onDelete('cascade');

      $table->unsignedInteger('estoque_tipo_arm_id')->nullable();
      $table->foreign('estoque_tipo_arm_id')
            ->references('id')
            ->on('estoque_tipo_arms')
            ->onDelete('cascade');

      $table->unsignedInteger('estoque_id')->nullable();
      $table->foreign('estoque_id')
            ->references('id')
            ->on('estoques')
            ->onDelete('cascade');
      
      $table->string('nome');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('estoque_reposicao', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('estoque_id');
      $table->foreign('estoque_id')
            ->references('id')
            ->on('estoques')
            ->onDelete('cascade');

      $table->unsignedInteger('ponto_de_venda_id');
      $table->foreign('ponto_de_venda_id')
            ->references('id')
            ->on('ponto_de_vendas')
            ->onDelete('cascade');

      $table->integer('numero')->default('0');
      $table->integer('qtde')->default('0');
      $table->string('entregador');
      $table->text('observacao')->nullable();

      $table->timestamp('data_recebimento')->nullable();
      $table->timestamp('data_finalizacao')->nullable();
      $table->timestamp('data_envio');
      $table->timestamp('data_estorno')->nullable();

      $table->integer('usu_recebeu')->nullable();
      $table->integer('usu_finalizou')->nullable();
      $table->integer('usu_enviou')->nullable();
      $table->integer('usu_estornou')->nullable();
      
      $table->tinyInteger('status')->default('0')->comment('0-cadastrada;1-a receber;2-recebida;3-receb. parcial;4-Estornada');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('estoque_reposicao_items', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('produto_id')->nullable();
      $table->foreign('produto_id')
            ->references('id')
            ->on('produtos')
            ->onDelete('cascade');

      $table->unsignedInteger('estoque_reposicao_id')->nullable();
      $table->foreign('estoque_reposicao_id')
            ->references('id')
            ->on('estoque_reposicaos')
            ->onDelete('cascade');

      $table->unsignedInteger('unidade_medida_id');
      $table->foreign('unidade_medida_id')
            ->references('id')
            ->on('unidade_medidas')
            ->onDelete('cascade');

      $table->float('qtde');
      $table->float('qtde_recebida');
      $table->float('estorno')->default('0');
      $table->float('perda')->default('0');
      $table->decimal('custo', 10, 2)->default('0.00');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('estoques');
    Schema::dropIfExists('estoque_reposicao');
    Schema::dropIfExists('estoque_reposicao_items');
  }
}
