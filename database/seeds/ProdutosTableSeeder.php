<?php

use Illuminate\Database\Seeder;
use App\Models\Produto\Produto;
use App\Models\Produto\UnidadeMedida;
use App\Models\Produto\UnidadeMedidaConversao;
class ProdutosTableSeeder extends Seeder
{
  public function run()
  {
    Produto::truncate();
    factory(Produto::class, 10)->create();

    UnidadeMedida::truncate();       
    UnidadeMedida::create([
      'unidade' => 'KG',
      'descricao' => 'QUILOGRAMA'
    ]);
    UnidadeMedida::create([
      'unidade' => 'G',
      'descricao' => 'GRAMAS'
    ]);
    UnidadeMedida::create([
      'unidade' => 'L',
      'descricao' => 'LITRO'
    ]);
    UnidadeMedida::create([
      'unidade' => 'ML',
      'descricao' => 'MILILITRO'
    ]);
    UnidadeMedida::create([
      'unidade' => 'UNID',
      'descricao' => 'UNIDADE'
    ]);
    UnidadeMedida::create([
      'unidade' => 'AMPOLA',
      'descricao' => 'AMPOLA'
    ]);
    UnidadeMedida::create([
      'unidade' => 'BALDE',
      'descricao' => 'BALDE'
    ]);
    UnidadeMedida::create([
      'unidade' => 'BANDEJ',
      'descricao' => 'BANDEJA'
    ]);
    UnidadeMedida::create([
      'unidade' => 'BARRA',
      'descricao' => 'BARRA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'BISNAG',
      'descricao' => 'BISNAGA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'BLOCO',
      'descricao' => 'BLOCO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'BOBINA',
      'descricao' => 'BOBINA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'BOMB',
      'descricao' => 'BOMBONA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CAPS',
      'descricao' => 'CAPSULA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CART',
      'descricao' => 'CARTELA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CENTO',
      'descricao' => 'CENTO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CJ',
      'descricao' => 'CONJUNTO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CM',
      'descricao' => 'CENTIMETRO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CM2',
      'descricao' => 'CENTIMETRO QUADRADO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX',
      'descricao' => 'CAIXA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX2',
      'descricao' => 'CAIXA COM 2 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX3',
      'descricao' => 'CAIXA COM 3 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX5',
      'descricao' => 'CAIXA COM 5 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX10',
      'descricao' => 'CAIXA COM 10 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX15',
      'descricao' => 'CAIXA COM 15 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX20',
      'descricao' => 'CAIXA COM 20 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX25',
      'descricao' => 'CAIXA COM 25 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX50',
      'descricao' => 'CAIXA COM 50 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'CX100',
      'descricao' => 'CAIXA COM 100 UNIDADES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'DISP',
      'descricao' => 'DISPLAY'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'DUZIA',
      'descricao' => 'DUZIA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'EMBAL',
      'descricao' => 'EMBALAGEM'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'FARDO',
      'descricao' => 'FARDO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'FOLHA',
      'descricao' => 'FOLHA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'FRASCO',
      'descricao' => 'FRASCO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'GALAO',
      'descricao' => 'GALÃO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'GF',
      'descricao' => 'GARRAFA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'JOGO',
      'descricao' => 'JOGO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'KIT',
      'descricao' => 'KIT'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'LATA',
      'descricao' => 'LATA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'M',
      'descricao' => 'METRO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'M2',
      'descricao' => 'METRO QUADRADO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'M3',
      'descricao' => 'METRO CÚBICO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'MILHEI',
      'descricao' => 'MILHEIRO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'MWH',
      'descricao' => 'MEGAWATT HORA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'PACOTE',
      'descricao' => 'PACOTE'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'PALETE',
      'descricao' => 'PALETE'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'PARES',
      'descricao' => 'PARES'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'PC',
      'descricao' => 'PEÇA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'POTE',
      'descricao' => 'POTE'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'K',
      'descricao' => 'QUILATE'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'RESMA',
      'descricao' => 'RESMA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'ROLO',
      'descricao' => 'ROLO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'SACO',
      'descricao' => 'SACO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'SACOLA',
      'descricao' => 'SACOLA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'TAMBOR',
      'descricao' => 'TAMBOR'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'TANQUE',
      'descricao' => 'TANQUE'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'TON',
      'descricao' => 'TONELADA'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'TUBO',
      'descricao' => 'TUBO'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'VASIL',
      'descricao' => 'VASILHAME'
    ]);
    UnidadeMedida::create([
      'unidade' =>  'VIDRO',
      'descricao' => 'VIDRO'
    ]);

    UnidadeMedidaConversao::truncate();  
    UnidadeMedidaConversao::create([
      'unidade_medida_id' => 1,
      'unidade_equivalente_id' => 2,
      'proporcao' => 1000,
      'descricao' => 'KG -> G'
    ]);

    UnidadeMedidaConversao::create([
      'unidade_medida_id' => 2,
      'unidade_equivalente_id' => 1,
      'proporcao' => 0.001,
      'descricao' => 'G -> KG'
    ]);

    UnidadeMedidaConversao::create([
      'unidade_medida_id' => 3,
      'unidade_equivalente_id' => 4,
      'proporcao' => 1,
      'descricao' => 'L -> ML'
    ]);

    UnidadeMedidaConversao::create([
      'unidade_medida_id' => 4,
      'unidade_equivalente_id' => 3,
      'proporcao' => 0.001,
      'descricao' => 'ML -> L'
    ]);
  }
}
