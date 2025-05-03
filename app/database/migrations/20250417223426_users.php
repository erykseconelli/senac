    <?php

    use Phinx\Migration\AbstractMigration;

    final class Users extends AbstractMigration
    {
        public function change(): void
        {   
            #Recebe a instancia da classe, e passamos o nome da tebela.
            $table = $this->table('users', ['id' => false, 'primary_key'=> ['id']]);
            $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
                ->addColumn('nome_completo', 'text', ['null' => true])
                ->addColumn('cpf', 'text', ['null' => true])
                ->addColumn('email', 'text', ['null' => true])
                ->addColumn('senha', 'text', ['null' => true])
                ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('data_alteracao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
                ->create();
        }
    }
