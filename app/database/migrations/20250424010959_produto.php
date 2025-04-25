<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Produto extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('produto', ['id' => false, 'primary_key'=> ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('id_fornecedor', 'biginteger')
            ->addColumn('tipo', 'text')
            ->addColumn('nome', 'text')
            ->addColumn('descricao', 'text')
            ->addColumn('preco', 'text')
            ->addColumn('estoque', 'text')
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_alteracao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_fornecedor', 'fornecedor', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
