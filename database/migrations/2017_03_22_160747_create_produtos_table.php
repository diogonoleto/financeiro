<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosTable extends Migration
{
  public function up()
  {
    Schema::create('produto_ncms', function (Blueprint $table) {
      $table->increments('id');
      $table->string('ncm_id');
      $table->string('ncm_desc');
      $table->timestamps();
    });
    Schema::create('unidade_medidas', function (Blueprint $table) {
      $table->increments('id');
      $table->string('unidade');
      $table->string('descricao');
      $table->integer('conversao')->nullable();
      $table->float('multiplicador')->nullable();
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('unidade_medida_conversaos', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('unidade_medida_id');
      $table->foreign('unidade_medida_id')
            ->references('id')
            ->on('unidade_medidas')
            ->onDelete('cascade');

      $table->integer('unidade_equivalente_id')->nullable();
      $table->float('proporcao');
      $table->string('descricao');
      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('produto_categorias', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('account_id');
      $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
      $table->unsignedInteger('produto_categoria_id')->nullable();
      $table->foreign('produto_categoria_id')
            ->references('id')
            ->on('produto_categorias')
            ->onDelete('cascade');
      $table->enum('tipo', ['PRINCIPAL', 'SUBCATEGORIA']);
      $table->string('nome', 50);
      $table->tinyInteger('pdv');
      $table->tinyInteger('nivel');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
    Schema::create('produtos', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('armazenamento_id')->nullable();
      $table->foreign('armazenamento_id')
            ->references('id')
            ->on('armazenamentos')
            ->onDelete('cascade');

      $table->unsignedInteger('produto_id')->nullable();
      $table->foreign('produto_id')
            ->references('id')
            ->on('produtos')
            ->onDelete('cascade');

      $table->unsignedInteger('unidade_medida_id');
      $table->foreign('unidade_medida_id')
            ->references('id')
            ->on('unidade_medidas')
            ->onDelete('cascade');

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
      $table->unsignedInteger('fornecedor_id')->nullable();
      $table->foreign('fornecedor_id')
            ->references('id')
            ->on('fornecedors')
            ->onDelete('cascade');
      $table->unsignedInteger('produto_categoria_id');
      $table->foreign('produto_categoria_id')
            ->references('id')
            ->on('produto_categorias')
            ->onDelete('cascade');

      $table->unsignedInteger('produto_cor_id');
      $table->foreign('produto_cor_id')
            ->references('id')
            ->on('produto_cors')
            ->onDelete('cascade');
      $table->unsignedInteger('ncm_id');
      $table->foreign('ncm_id')
            ->references('id')
            ->on('produto_ncm')
            ->onDelete('cascade');
      $table->unsignedInteger('cest_id');
      $table->foreign('cest_id')
            ->references('id')
            ->on('produto_cest')
            ->onDelete('cascade');
      $table->unsignedInteger('cfop_id');
      $table->foreign('cfop_id')
            ->references('id')
            ->on('produto_cfop')
            ->onDelete('cascade');
      $table->unsignedInteger('icms_grupo_id');
      $table->foreign('icms_grupo_id')
            ->references('id')
            ->on('icms_grupos')
            ->onDelete('cascade');
      $table->unsignedInteger('pis_confis_grupo_id');
      $table->foreign('pis_confis_grupo_id')
            ->references('id')
            ->on('pis_confis_grupos')
            ->onDelete('cascade');
      $table->unsignedInteger('ipi_grupo_id');
      $table->foreign('ipi_grupo_id')
            ->references('id')
            ->on('ipi_grupos')
            ->onDelete('cascade');
      $table->enum('tipo', ['PRINCIPAL', 'SUBPRODUTO']);
      $table->string('cod_barra')->nullable();
      $table->string('nome');
      $table->string('rotulo')->nullable();
      $table->string('descricao')->nullable();
      $table->string('tamanho')->nullable();
      $table->string('peso_bruto')->nullable();
      $table->string('peso_liquido')->nullable();
      $table->string('miniatura')->nullable();
      $table->string('img')->nullable();
      $table->decimal('preco', 10, 2);


      
      $table->smallInteger('status')->nullable();



      $table->tinyInteger('tipo_insumo')->nullable();
      $table->tinyInteger('perecivel')->nullable();
      $table->tinyInteger('destinacao')->nullable();
      $table->tinyInteger('custo_valor_fixo')->nullable();


      $table->decimal('valor_custo_fixo', 10, 2)->default('0');
      $table->tinyInteger('disponivel_venda');
      $table->tinyInteger('controla_estoque')->default('0');
      $table->tinyInteger('notifica_estoque')->default('0');
      $table->float('estoque_min')->nullable();
      $table->float('estoque_max')->nullable();
      $table->string('codigo_ncm')->nullable();
      $table->bigInteger('codigo_cest')->nullable();
      $table->string('id_icms_origem_mercadoria')->nullable();
      $table->string('codigo_cfop')->nullable();


      $table->integer('codigo_gp_icms')->nullable();
      $table->integer('codigo_gp_pis_cofins')->nullable();
      $table->integer('codigo_gp_ipi')->nullable();

      $table->tinyInteger('fracionado')->default('0')->comment('0-nao;1-sim');
      $table->integer('aviso_validade')->default('0')->comment('Em dias');
      $table->tinyInteger('possui_agregado')->default('0');
      $table->tinyInteger('possui_composicao')->default('0');
      $table->tinyInteger('usado_composicao')->default('0');
      $table->tinyInteger('principal_dashboard')->default('0')->comment('0-nao;1-sim');
      $table->integer('ordem')->default('1');
      $table->tinyInteger('grade_item')->default('0')->comment('0-nao;1-sim');

      $table->timestamp('deleted_at')->nullable();
      $table->timestamps();
    });
  }
  public function down()
  {
    Schema::dropIfExists('produto_ncms');
    Schema::dropIfExists('unidade_medidas');
    Schema::dropIfExists('unidade_medida_conversaos');
    Schema::dropIfExists('produto_categorias');
    Schema::dropIfExists('produtos');
  }
}
