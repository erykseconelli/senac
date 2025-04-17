<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Fornecedor extends AbstractMigration
{   
    public function change(): void
    {
        $table = $this->table('users', ['id' => false, 'primary_ke'=> ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('empresa', 'text', ['null' => true])
            ->addColumn('cnpj', 'text', ['null' => true])
            ->addColumn('endereco', 'text', ['null' => true])
            ->addColumn('ativo', 'boolean', ['null' => true, 'default' => false])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
