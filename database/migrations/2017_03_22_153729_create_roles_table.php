<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
  public function up()
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome', 50);
      $table->string('descricao');
      
      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');

      $table->timestamps();
    });
    Schema::create('role_user_accounts', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');
      $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
      $table->unsignedInteger('role_id');

      $table->foreign('role_id')
            ->references('id')
            ->on('roles')
            ->onDelete('cascade');
      $table->timestamps();

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
    });
  }
  public function down()
  {
    Schema::dropIfExists('roles');
    Schema::dropIfExists('role_user_accounts');
  }
}
