<?php
namespace app\database\builder;
use app\database\Connection;
class InsertQuery
{
    #Atributo tabela recebe o nome da tabela
    private string $table;
    #Atributo fieldsAndValues recebe os campos e valores a serem inseridos
    private array $fieldsAndValues = [];
    #Metodo table recebe o nome da tabela e retorna uma instancia da classe
    public static function table(string $table): ?self
    {
        try {
            $self = new self;
            $self->table = $table;
            return $self;
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
    #Metodos createQuery cria a a instrução de insert
    private function createQuery(): ?string
    {
        if (!$this->table) {
            throw new \Exception("A consulta precisa invocar o método insert.");
        }
        if (!$this->fieldsAndValues) {
            throw new \Exception("A consulta precisa dos dados para realizar a inserção.");
        }
        $query = '';
        $query = "insert into {$this->table} (";
        $query .= implode(',', array_keys($this->fieldsAndValues)) . ') values (';
        $query .= ':' . implode(',:', array_keys($this->fieldsAndValues)) . ');';
        return $query;
    }
    #Metodo executeQuery executa a query de insert
    public function executeQuery($query): ?bool
    {
        $connection = Connection::connect();
        $prepare = $connection->prepare($query);
        return $prepare->execute($this->fieldsAndValues);
    }
    #Metodo save recebe os campos e valores a serem inseridos
    #e chama o metodo createQuery para criar a query de insert
    public function save(array $fieldsAndValues): ?bool
    {
        $this->fieldsAndValues = $fieldsAndValues;
        $query = $this->createQuery();
        try {
            $IsSave = $this->executeQuery($query);
            return $IsSave;
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }
}