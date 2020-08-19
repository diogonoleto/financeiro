<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoeamTable extends Migration
{
  public function up()
  {
    Schema::create('doeam_diario_oficials', function (Blueprint $table) {
        $table->increments('id');

        $table->integer('numero');
        $table->timestamp('data_circulacao');
        $table->string('ano');
        $table->string('path_pdf');

        $table->timestamps();
    });
    Schema::create('doeam_cadernos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });

    // Schema::create('doeam_caderno_poders', function (Blueprint $table) {
    //   $table->increments('id');

    //   $table->unsignedInteger('caderno_id');
    //   $table->foreign('caderno_id')
    //       ->references('id')
    //       ->on('cadernos')
    //       ->onDelete('cascade');

    //   $table->unsignedInteger('poder_id');
    //   $table->foreign('poder_id')
    //       ->references('id')
    //       ->on('poders')
    //       ->onDelete('cascade');
    //   $table->timestamps();
    // });
    
    Schema::create('doeam_materia_tipos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('doeam_materia_layouts', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->string('larg');
      $table->string('unid');
      $table->timestamps();
    });
    Schema::create('doeam_materia_status', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->string('cor');
      $table->timestamps();
    });
    Schema::create('doeam_materias', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('diario_oficial_id');
      $table->foreign('diario_oficial_id')
            ->references('id')
            ->on('diario_oficials')
            ->onDelete('cascade');

      $table->unsignedInteger('caderno_id');
      $table->foreign('caderno_id')
            ->references('id')
            ->on('cadernos')
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

      $table->unsignedInteger('materia_tipo_id');
      $table->foreign('materia_tipo_id')
            ->references('id')
            ->on('materia_tipo')
            ->onDelete('cascade');

      $table->unsignedInteger('materia_layout_id');
      $table->foreign('materia_layout_id')
            ->references('id')
            ->on('materia_layouts')
            ->onDelete('cascade');

      $table->unsignedInteger('materia_status_id');
      $table->foreign('materia_status_id')
            ->references('id')
            ->on('materia_status')
            ->onDelete('cascade');

      $table->timestamp('data_circulacao');

      $table->string('titulo');
      $table->text('texto');

      $table->string('user_autorizador')->nullable();

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('doeam_diario_oficials');
    Schema::dropIfExists('doeam_cadernos');
    Schema::dropIfExists('doeam_materia_tipos');
    Schema::dropIfExists('doeam_materia_layouts');
    Schema::dropIfExists('doeam_materia_status');
    Schema::dropIfExists('doeam_materias');
  }
}
