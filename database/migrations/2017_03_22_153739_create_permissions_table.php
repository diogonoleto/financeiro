<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
  public function up()
  {
    Schema::create('permissions', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome', 50);
      $table->string('descricao');
      $table->timestamps();
    });
    Schema::create('permission_roles', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('permission_id');
      $table->foreign('permission_id')
            ->references('id')
            ->on('permissions')
            ->onDelete('cascade');
      $table->unsignedInteger('role_id');
      $table->foreign('role_id')
            ->references('id')
            ->on('roles')
            ->onDelete('cascade');
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('permission_roles');
  }
}
