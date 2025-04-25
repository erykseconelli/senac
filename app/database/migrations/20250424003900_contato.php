<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Contato extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('contato', ['id' => false, 'primary_key'=> ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_empresa', 'biginteger')
            ->addColumn('tipo', 'text')
            ->addColumn('contato', 'text')
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_alteracao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_empresa', 'empresa', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
