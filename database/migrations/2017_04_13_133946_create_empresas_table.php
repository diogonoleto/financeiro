<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
  public function up()
  {
    Schema::create('empresa_entidades', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empresa_esferas', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('empresa_entidade_id');
      $table->foreign('empresa_entidade_id')
            ->references('id')
            ->on('empresa_entidades')
            ->onDelete('cascade');

      $table->string('nome');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empresa_poders', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('empresa_esfera_id');
      $table->foreign('empresa_esfera_id')
            ->references('id')
            ->on('empresa_esferas')
            ->onDelete('cascade');

      $table->string('nome');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empresa_categorias', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('empresa_poder_id');
      $table->foreign('empresa_poder_id')
            ->references('id')
            ->on('empresa_poders')
            ->onDelete('cascade');

      $table->string('nome');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });

    Schema::create('empresa_tipos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });
    Schema::create('empresas', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');

      $table->unsignedInteger('empresa_tipo_id');
      $table->foreign('empresa_tipo_id')
            ->references('id')
            ->on('empresa_tipos')
            ->onDelete('cascade');

      $table->unsignedInteger('empresa_entidade_id');
      $table->foreign('empresa_entidade_id')
            ->references('id')
            ->on('empresa_entidades')
            ->onDelete('cascade');

      $table->unsignedInteger('empresa_esfera_id')->nullable();
      $table->foreign('empresa_esfera_id')
            ->references('id')
            ->on('empresa_esferas')
            ->onDelete('cascade');

      $table->unsignedInteger('empresa_poder_id')->nullable();
      $table->foreign('empresa_poder_id')
            ->references('id')
            ->on('empresa_poders')
            ->onDelete('cascade');

      $table->unsignedInteger('empresa_categoria_id')->nullable();
      $table->foreign('empresa_categoria_id')
            ->references('id')
            ->on('empresa_categorias')
            ->onDelete('cascade');

      $table->string('cnpj')->nullable();
      $table->string('razao_social');
      $table->string('nome_fantasia');
      $table->date('data_fundacao')->nullable();
      $table->string('inscricao_estadual')->nullable();
      $table->string('inscricao_municipal')->nullable();

      $table->string('cep')->nullable();
      $table->string('logradouro')->nullable();
      $table->string('numero')->nullable();
      $table->string('bairro')->nullable();
      $table->string('complemento')->nullable();
      $table->string('cidade')->nullable();
      $table->string('estado_uf')->nullable();

      $table->string('img')->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empresa_contatos', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('empresa_id');
      $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas')
            ->onDelete('cascade');
      $table->string('tipo');
      $table->string('descricao');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empresa_cnaes', function (Blueprint $table) {
      $table->increments('id');
      $table->string('cnae_id');
      $table->string('cnae_desc');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('empresa_cfops', function (Blueprint $table) {
      $table->increments('id');
      $table->string('cfop_id');
      $table->string('cfop_desc');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('empresa_entidades');
    Schema::dropIfExists('empresa_esferas');
    Schema::dropIfExists('empresa_poders');
    Schema::dropIfExists('empresa_categorias');
    Schema::dropIfExists('empresa_tipos');
    Schema::dropIfExists('empresas');
    Schema::dropIfExists('empresa_contatos');
    Schema::dropIfExists('empresa_cnaes');
    Schema::dropIfExists('empresa_cfops');
  }
}
