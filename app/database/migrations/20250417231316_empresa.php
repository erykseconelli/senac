<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Empresa extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('empresa', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('empresa_nome', 'text', ['null' => true])
            ->addColumn('cnpj', 'text', ['null' => true])
            ->addColumn('endereco', 'text', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_alteracao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
