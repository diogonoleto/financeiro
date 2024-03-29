<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/redirect', 'Auth\SocialAuthFacebookController@redirect');
// Route::get('/callback', 'Auth\SocialAuthFacebookController@callback');

Route::get('/home', 'HomeController@index');
Route::post('/password/changepassword', ['uses' => 'HomeController@changePassword', 'as' => 'changePassword']);
Route::get('/password/change', ['uses' => 'HomeController@showChangePassword', 'as' => 'password.change']);

Route::group(['namespace'=>'Panel', 'middleware'=>'auth'], function(){
	Route::get('/pagamento/lista', ['uses' => 'PagamentoController@lista', 'as' => 'pagamento.lista']);
	Route::resource('/pagamento', 'PagamentoController', ['except' => ['show', 'create', 'edit']]);
	Route::get('/pdv/lista', ['uses' => 'PDVController@lista', 'as' => 'pdv.lista']);
	Route::resource('/pdv', 'PDVController', ['except' => ['show']]);
});

Route::group(['namespace'=>'Empresa', 'middleware'=>'auth'], function(){
	Route::get('/fornecedor/lista', ['uses' => 'FornecedorController@lista', 'as' => 'fornecedor.lista']);
	Route::resource('/fornecedor', 'FornecedorController', ['except' => ['show']]);
	Route::get('/cliente/lista', ['uses' => 'ClienteController@lista', 'as' => 'cliente.lista']);
	Route::resource('/cliente', 'ClienteController');
	Route::post('/empresa/maisinfo', ['uses' => 'EmpresaController@maisinfo', 'as' => 'empresa.maisInfo']);
	Route::post('/empresa/endereco', ['uses' => 'EmpresaController@endereco', 'as' => 'empresa.endereco']);
	Route::post('/empresa/contato', ['uses' => 'EmpresaController@contato', 'as' => 'empresa.contato']);
	Route::post('/empresa/conta', ['uses' => 'EmpresaController@conta', 'as' => 'empresa.conta']);
	Route::get('/empresa/autocomplete', ['uses' => 'EmpresaController@autocomplete', 'as' => 'empresa.autocomplete']);
	Route::post('/empresa/logo', ['uses' => 'EmpresaController@logoEmpresa', 'as' => 'empresa.logo']);
	Route::get('/empresa/getcep', ['uses' => 'EmpresaController@getCEP', 'as' => 'empresa.getCEP']);
	Route::get('/empresa/ge/{id}', ['uses' => 'EmpresaController@getEndereco', 'as' => 'empresa.getEndereco']);
	Route::get('/empresa/gc/{id}', ['uses' => 'EmpresaController@getContato', 'as' => 'empresa.getContato']);
	Route::get('/empresa/lista', ['uses' => 'EmpresaController@lista', 'as' => 'empresa.lista']);
	Route::resource('/empresa', 'EmpresaController');
});


Route::group(['namespace'=>'Config', 'middleware'=>'auth'], function(){
	Route::resource('/config/importacao', 'ImportacaoController', ['except' => ['show', 'create', 'edit', 'store', 'update', 'destroy']]);
	Route::get('/config/importacao/movimento/lista', ['uses' => 'ImportacaoMovimentoController@lista', 'as' => 'imp.movimento.lista']);
	Route::resource('/config/importacao/movimento', 'ImportacaoMovimentoController', ['except' => ['show', 'edit','update'], 'as' => 'imp']);
	Route::get('/config/importacao/categoria/lista', ['uses' => 'ImportacaoCategoriaController@lista', 'as' => 'imp.categoria.lista']);
	Route::resource('/config/importacao/categoria', 'ImportacaoCategoriaController', ['except' => ['show', 'edit','update'], 'as' => 'imp']);
	Route::get('/perfil/lista', ['uses' => 'PerfilController@lista', 'as' => 'config.perfil.lista']);
	Route::resource('/perfil', 'PerfilController', ['except' => ['show'], 'as' => 'configPer']);

	Route::post('/usuario/maisinfo', ['uses' => 'UsuarioController@maisinfo', 'as' => 'usuario.maisInfo']);
	Route::post('/usuario/endereco', ['uses' => 'UsuarioController@endereco', 'as' => 'usuario.endereco']);
	Route::post('/usuario/contato', ['uses' => 'UsuarioController@contato', 'as' => 'usuario.contato']);
	Route::post('/usuario/conta', ['uses' => 'UsuarioController@conta', 'as' => 'usuario.conta']);
	Route::get('/usuario/ge/{id}', ['uses' => 'UsuarioController@getEndereco', 'as' => 'usuario.getEndereco']);
	Route::get('/usuario/gc/{id}', ['uses' => 'UsuarioController@getContato', 'as' => 'usuario.getContato']);
	Route::get('/usuario/cadastro', ['uses' => 'UsuarioController@cadastro', 'as' => 'usuario.cadastro']);
	Route::get('/usuario/apresentacao', ['uses' => 'UsuarioController@apresentacao', 'as' => 'usuario.apresentacao']);
	Route::post('/usuario/avatar', ['uses' => 'UsuarioController@avatarUser', 'as' => 'usuario.avatar']);
	Route::get('/usuario/lista', ['uses' => 'UsuarioController@lista', 'as' => 'usuario.lista']);
	Route::resource('/usuario', 'UsuarioController');

	Route::resource('/config', 'ConfigController', ['except' => ['show', 'create', 'edit', 'store', 'update', 'destroy']]);
});

Route::group(['namespace'=>'Financeiro', 'middleware'=>'auth'], function(){
	Route::get('/financeiro/relatorio/dre/anual', ['uses' => 'RelatorioController@dreAnual', 'as' => 'fin.dre.anual']);
	Route::get('/financeiro/relatorio/dre/anual/lista', ['uses' => 'RelatorioController@dreAnualLista', 'as' => 'fin.rel.dre.a.lista']);
	Route::get('/financeiro/relatorio/dre/mensal', ['uses' => 'RelatorioController@dremensal', 'as' => 'fin.dre.mensal']);
	Route::get('/financeiro/relatorio/dre/mensal/lista', ['uses' => 'RelatorioController@dremensalLista', 'as' => 'fin.rel.dre.m.lista']);

	Route::get('/financeiro/relatorio/fluxo-de-caixa/mensal', ['uses' => 'RelatorioController@mensal', 'as' => 'fin.fdc.mensal']);
	Route::get('/financeiro/relatorio/fluxo-de-caixa/mensal/lista', ['uses' => 'RelatorioController@mensalLista', 'as' => 'fin.rel.fdc.m.lista']);
	Route::get('/financeiro/relatorio/fluxo-de-caixa/diario', ['uses' => 'RelatorioController@diario', 'as' => 'fin.fdc.diario']);
	Route::get('/financeiro/relatorio/fluxo-de-caixa/diario/lista', ['uses' => 'RelatorioController@diarioLista', 'as' => 'fin.rel.fdc.d.lista']);
	Route::resource('/financeiro/relatorio', 'RelatorioController', ['except' => ['create', 'show', 'edit'], 'as' => 'finrel']);

	Route::get('/financeiro/agenda', ['uses' => 'FinanceiroController@agenda', 'as' => 'financeiro.agenda']);
	Route::get('/financeiro', ['uses' => 'FinanceiroController@index', 'as' => 'financeiro']);

	Route::match(['put', 'patch'], '/financeiro/movimento', ['uses' => 'MovimentoController@agemov', 'as' => 'financeiro.movimento.agemov']);
	Route::post('/financeiro/movimento/transferencia', ['uses' => 'MovimentoController@transferencia', 'as' => 'financeiro.movimento.transferencia']);
	Route::match(['put', 'patch'],'/financeiro/movimento/transferencia/{id}', ['uses' => 'MovimentoController@transferenciaUpdate', 'as' => 'financeiro.movimento.transferenciaUpdate']);
	Route::get('/financeiro/movimento/lista', ['uses' => 'MovimentoController@lista', 'as' => 'financeiro.movimento.lista']);

	Route::get('/financeiro/movimento/exportar', ['uses' => 'MovimentoController@exportar', 'as' => 'financeiro.movimento.exportar']);

	Route::get('/financeiro/centrocusto/lista', ['uses' => 'CentroCustoController@lista', 'as' => 'centrocusto.lista']);
	Route::resource('/financeiro/centrocusto', 'CentroCustoController', ['except' => ['show']]);

	Route::resource('/financeiro/movimento', 'MovimentoController', ['except' => ['show']]);

	Route::match(['put', 'patch'],'/financeiro/conta/lista/{id}', ['uses' => 'ContaController@padrao', 'as' => 'financeiro.conta.padrao']);

	Route::get('/financeiro/conta/banco', ['uses' => 'ContaController@banco', 'as' => 'financeiro.conta.banco']);

	Route::get('/financeiro/conta/cartaocredito', ['uses' => 'ContaController@contalista', 'as' => 'financeiro.conta.cartaocredito']);
	Route::get('/financeiro/conta/contacorrente', ['uses' => 'ContaController@contalista', 'as' => 'financeiro.conta.contacorrente']);
	Route::get('/financeiro/conta/contalista', ['uses' => 'ContaController@contalista', 'as' => 'financeiro.conta.contalista']);
	Route::get('/financeiro/conta/lista', ['uses' => 'ContaController@lista', 'as' => 'financeiro.conta.lista']);
	Route::resource('/financeiro/conta', 'ContaController');

	Route::get('/financeiro/categoria/dre', ['uses' => 'CategoriaController@dre', 'as' => 'fin.categoria.dre']);
	Route::get('/financeiro/categoria/sortable', ['uses' => 'CategoriaController@sortable', 'as' => 'fin.categoria.sortable']);
	Route::get('/financeiro/categoria/lista', ['uses' => 'CategoriaController@lista', 'as' => 'fin.categoria.lista']);
	Route::resource('/financeiro/categoria', 'CategoriaController', ['except' => ['create', 'show', 'edit'], 'as' => 'fin']);
});

Route::group(['namespace'=>'Admin', 'middleware'=>'auth'], function(){
	Route::get('/admin/usuario/lista', ['uses' => 'UsuarioController@lista', 'as' => 'adminUsu.usuario.lista']);
	Route::resource('/admin/usuario', 'UsuarioController', ['except' => ['show'], 'as' => 'adminUsu']);
	Route::get('/admin/conta/lista', ['uses' => 'ContaController@lista', 'as' => 'adminCon.conta.lista']);
	Route::resource('/admin/conta', 'ContaController', ['except' => ['show'], 'as' => 'adminCon']);
	Route::get('/admin/lista', ['uses' => 'AdminController@lista', 'as' => 'admin.lista']);
	Route::resource('/admin', 'AdminController', ['except' => ['create', 'show', 'edit', 'update', 'destroy']]);
	Route::get('/apresentacao/apresentar', ['uses' => 'ApresentacaoController@apresentar', 'as' => 'adminApr.apresentacao.apresentar']);
	Route::get('/apresentacao/lista', ['uses' => 'ApresentacaoController@lista', 'as' => 'adminApr.apresentacao.lista']);
	Route::resource('/admin/apresentacao', 'ApresentacaoController', ['except' => ['create', 'show', 'edit'], 'as' => 'adminApr' ]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
