<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  public function up()
  {
    Schema::create('user_tipos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');

      $table->unsignedInteger('empresa_id')->nullable();
      $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas')
            ->onDelete('cascade');

      $table->unsignedInteger('user_tipo_id');
      $table->foreign('user_tipo_id')
            ->references('id')
            ->on('user_tipos')
            ->onDelete('cascade');

      $table->string('apelido')->nullable();
      $table->string('cpf')->nullable();
      $table->string('email');
      $table->string('password');
      $table->string('img')->nullable();
      $table->string('cargo')->nullable();

      $table->string('cep')->nullable();
      $table->string('logradouro')->nullable();
      $table->string('numero')->nullable();
      $table->string('bairro')->nullable();
      $table->string('complemento')->nullable();
      $table->string('cidade')->nullable();
      $table->string('estado_uf')->nullable();
      
      $table->rememberToken();

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('user_contatos', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');
      $table->foreign('user_id')
      ->references('id')
      ->on('users')
      ->onDelete('cascade');
      $table->string('tipo');
      $table->string('descricao');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('user_tipos');
    Schema::dropIfExists('users');
    Schema::dropIfExists('user_contatos');
  }
}
