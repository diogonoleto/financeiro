<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePDFsTable extends Migration
{
  public function up()
  {
    Schema::create('pdf_importacaos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('pdf_assinaturas', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('diario_oficial_id');
      $table->foreign('diario_oficial_id')
            ->references('id')
            ->on('diario_oficials')
            ->onDelete('cascade');
      
      $table->unsignedInteger('user_id');
      $table->foreign('user_id')
            ->references('id')
            ->on('user')
            ->onDelete('cascade');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    
    Schema::create('pdf_mesclagems', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('pdf_importacao_id');
      $table->foreign('pdf_importacao_id')
            ->references('id')
            ->on('pdf_importacaos')
            ->onDelete('cascade');

      $table->unsignedInteger('diario_oficial_id');
      $table->foreign('diario_oficial_id')
            ->references('id')
            ->on('diario_oficials')
            ->onDelete('cascade');

      $table->string('nome');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('pdf_importacaos');
    Schema::dropIfExists('pdf_assinaturas');
    Schema::dropIfExists('pdf_mesclagems');
  }
}
