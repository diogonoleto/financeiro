<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePontoDeVendasTable extends Migration
{
  public function up()
  {
    Schema::create('ponto_de_vendas', function (Blueprint $table) {
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

      $table->unsignedInteger('plataforma_id');
      $table->foreign('plataforma_id')
            ->references('id')
            ->on('plataformas')
            ->onDelete('cascade');
      $table->string('nome'); 
      $table->string('responsavel'); 
      $table->string('local'); 
      $table->string('uuid'); 
      $table->decimal('desc_valor_max', 10, 2);
      $table->string('desc_perc_max');
      $table->tinyInteger('mesa_qtd');
      $table->tinyInteger('imprime'); 
      $table->string('imprime_ip')->nullable();
      $table->tinyInteger('nfce')->nullable(); 
      $table->string('nfce_ip')->nullable();
      $table->integer('nfce_num_serie')->nullable();
      $table->integer('nfce_num_nota')->nullable();

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('plataformas', function (Blueprint $table) {
      $table->increments('id');
      
      $table->string('nome'); 
      $table->string('identificador'); 

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('ponto_de_vendas');
    Schema::dropIfExists('plataformas');
  }
}

