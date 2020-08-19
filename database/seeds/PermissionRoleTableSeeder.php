<?php
use Illuminate\Database\Seeder;
use App\Models\Config\Role;
use App\Models\Config\RoleUserAccount;
use App\Models\Config\Permission;
use App\Models\Config\PermissionRole;

class PermissionRoleTableSeeder extends Seeder
{
  public function run()
  {
 		Role::truncate();
    Role::create([
      'nome' => 'administrador',
      'descricao' => 'Administrador do Sistema',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'administrador_empresa',
      'descricao' => 'Administrador da Empresa',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'administrador',
      'descricao' => 'Administrador do Sistema',
      'account_id' => '2'
    ]);
    Role::create([
      'nome' => 'administrador_empresa',
      'descricao' => 'Administrador da Empresa',
      'account_id' => '2'
    ]);
    Role::create([
      'nome' => 'administrador',
      'descricao' => 'Administrador do Sistema',
      'account_id' => '3'
    ]);
    Role::create([
      'nome' => 'administrador_empresa',
      'descricao' => 'Administrador da Empresa',
      'account_id' => '3'
    ]);

    Role::create([
      'nome' => 'comprador',
      'descricao' => 'Comprador',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'vendedor_jr',
      'descricao' => 'Vendedor Júnior',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'vendedor_sr',
      'descricao' => 'Vendedor Sênior',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'financeiro_jr',
      'descricao' => 'Financeiro Júnior',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'financeiro_sr',
      'descricao' => 'Financeiro Sênior',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'OPDV',
      'descricao' => 'Operador de PDV',
      'account_id' => '1'
    ]);
    Role::create([
      'nome' => 'estoquista',
      'descricao' => 'Estoquista',
      'account_id' => '1'
    ]);

    Permission::truncate();
    Permission::create([
      'nome' => 'usuario_create',
      'descricao' => 'Criar Usuario',
    ]);
    Permission::create([
      'nome' => 'usuario_read',
      'descricao' => 'Visualizar Usuario',
    ]);
    Permission::create([
      'nome' => 'usuario_update',
      'descricao' => 'Editar Usuario',
    ]);
    Permission::create([
      'nome' => 'usuario_delete',
      'descricao' => 'Deletar Usuario',
    ]);
    Permission::create([
      'nome' => 'empresa_create',
      'descricao' => 'Criar Empresa',
    ]);
    Permission::create([
      'nome' => 'empresa_read',
      'descricao' => 'Visualizar Empresa',
    ]);
    Permission::create([
      'nome' => 'empresa_update',
      'descricao' => 'Editar Empresa',
    ]);
    Permission::create([
      'nome' => 'empresa_delete',
      'descricao' => 'Deletar Empresa',
    ]);
    Permission::create([
      'nome' => 'receita_create',
      'descricao' => 'Criar Receita',
    ]);
    Permission::create([
      'nome' => 'receita_read',
      'descricao' => 'Visualizar Receita',
    ]);
    Permission::create([
      'nome' => 'receita_update',
      'descricao' => 'Editar Receita',
    ]);
    Permission::create([
      'nome' => 'receita_delete',
      'descricao' => 'Deletar Receita',
    ]);
    Permission::create([
      'nome' => 'despesa_create',
      'descricao' => 'Criar Despesa',
    ]);
    Permission::create([
      'nome' => 'despesa_read',
      'descricao' => 'Visualizar Despesa',
    ]);
    Permission::create([
      'nome' => 'despesa_update',
      'descricao' => 'Editar Despesa',
    ]);
    Permission::create([
      'nome' => 'despesa_delete',
      'descricao' => 'Deletar Despesa',
    ]);
    Permission::create([
      'nome' => 'produto_create',
      'descricao' => 'Criar Produto',
    ]);
    Permission::create([
      'nome' => 'produto_read',
      'descricao' => 'Visualizar Produto',
    ]);
    Permission::create([
      'nome' => 'produto_update',
      'descricao' => 'Editar Produto',
    ]);
    Permission::create([
      'nome' => 'produto_delete',
      'descricao' => 'Deletar Produto',
    ]);
    Permission::create([
      'nome' => 'calendario_create',
      'descricao' => 'Criar Calendário',
    ]);
    Permission::create([
      'nome' => 'calendario_read',
      'descricao' => 'Visualizar Calendário',
    ]);
    Permission::create([
      'nome' => 'calendario_update',
      'descricao' => 'Editar Calendário',
    ]);
    Permission::create([
      'nome' => 'calendario_delete',
      'descricao' => 'Deletar Calendário',
    ]);
    Permission::create([
      'nome' => 'fornecedor_create',
      'descricao' => 'Criar Fornecedor',
    ]);
    Permission::create([
      'nome' => 'fornecedor_read',
      'descricao' => 'Visualizar Fornecedor',
    ]);
    Permission::create([
      'nome' => 'fornecedor_update',
      'descricao' => 'Editar Fornecedor',
    ]);
    Permission::create([
      'nome' => 'fornecedor_delete',
      'descricao' => 'Deletar Fornecedor',
    ]);
    Permission::create([
      'nome' => 'financeiro_create',
      'descricao' => 'Criar Financeiro',
    ]);
    Permission::create([
      'nome' => 'financeiro_read',
      'descricao' => 'Visualizar Financeiro',
    ]);
    Permission::create([
      'nome' => 'financeiro_update',
      'descricao' => 'Editar Financeiro',
    ]);
    Permission::create([
      'nome' => 'financeiro_delete',
      'descricao' => 'Deletar Financeiro',
    ]);
    Permission::create([
      'nome' => 'fin_movimento_create',
      'descricao' => 'Financeiro Criar Financeiro',
    ]);
    Permission::create([
      'nome' => 'fin_movimento_read',
      'descricao' => 'Financeiro Visualizar Financeiro',
    ]);
    Permission::create([
      'nome' => 'fin_movimento_update',
      'descricao' => 'Financeiro Editar Financeiro',
    ]);
    Permission::create([
      'nome' => 'fin_movimento_delete',
      'descricao' => 'Financeiro Deletar Financeiro',
    ]);
    Permission::create([
      'nome' => 'produto_categoria_create',
      'descricao' => 'Criar Categoria Produto'
    ]);
    Permission::create([
      'nome' => 'produto_categoria_read',
      'descricao' => 'Visualizar Categoria Produto'
    ]);
    Permission::create([
      'nome' => 'produto_categoria_update',
      'descricao' => 'Editar Categoria Produto'
    ]);
    Permission::create([
      'nome' => 'produto_categoria_delete',
      'descricao' => 'Deletar Categoria Produto'
    ]);
    Permission::create([
      'nome' => 'config_create',
      'descricao' => 'Criar Configuração Produto'
    ]);
    Permission::create([
      'nome' => 'config_read',
      'descricao' => 'Visualizar Configuração Produto'
    ]);
    Permission::create([
      'nome' => 'config_update',
      'descricao' => 'Editar Configuração Produto'
    ]);
    Permission::create([
      'nome' => 'config_delete',
      'descricao' => 'Deletar Configuração Produto'
    ]);
    Permission::create([
      'nome' => 'pdv_create',
      'descricao' => 'Criar pdv'
    ]);
    Permission::create([
      'nome' => 'pdv_read',
      'descricao' => 'Visualizar pdv'
    ]);
    Permission::create([
      'nome' => 'pdv_update',
      'descricao' => 'Editar pdv'
    ]);
    Permission::create([
      'nome' => 'pdv_delete',
      'descricao' => 'Deletar pdv'
    ]);
    Permission::create([
      'nome' => 'compra_create',
      'descricao' => 'Criar Compra'
    ]);
    Permission::create([
      'nome' => 'compra_read',
      'descricao' => 'Visualizar Compra'
    ]);
    Permission::create([
      'nome' => 'compra_update',
      'descricao' => 'Editar Compra'
    ]);
    Permission::create([
      'nome' => 'compra_delete',
      'descricao' => 'Deletar Compra'
    ]);
    Permission::create([
      'nome' => 'estoque_create',
      'descricao' => 'Criar Estoque'
    ]);
    Permission::create([
      'nome' => 'estoque_read',
      'descricao' => 'Visualizar Estoque'
    ]);
    Permission::create([
      'nome' => 'estoque_update',
      'descricao' => 'Editar Estoque'
    ]);
    Permission::create([
      'nome' => 'estoque_delete',
      'descricao' => 'Deletar Estoque'
    ]);

    RoleUserAccount::create([
        'role_id' => 1,
        'user_id' => 1,
        'account_id' => 1,
    ]);
    RoleUserAccount::create([
        'role_id' => 2,
        'user_id' => 1,
        'account_id' => 1,
    ]);

    RoleUserAccount::create([
        'role_id' => 2,
        'user_id' => 2,
        'account_id' => 2,
    ]);
    RoleUserAccount::create([
        'role_id' => 2,
        'user_id' => 3,
        'account_id' => 3,
    ]);

    PermissionRole::truncate();
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '1',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '2',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '3',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '4',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '5',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '6',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '7',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '8',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '9',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '10',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '11',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '12',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '13',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '14',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '15',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '16',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '17',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '18',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '19',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '20',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '21',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '22',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '23',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '24',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '25',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '26',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '27',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '28',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '29',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '30',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '31',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '32',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '33',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '34',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '35',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '36',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '37',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '38',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '39',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '40',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '41',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '42',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '43',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '44',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '45',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '46',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '47',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '48',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '49',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '50',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '51',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '52',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '53',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '54',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '55',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '56',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '57',
    ]);
    PermissionRole::create([
        'role_id' => '2',
        'permission_id' => '58',
    ]);
  }
}
