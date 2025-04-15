<?php

#NAMESPACE permite que a minha classe seja acessada em qualquer lugar do projeto
namespace app\database\builder;

use app\database\Connection;
use Exception;

class UpdateQuery 
{
    # Receber o nome tabela para alteração dos dados.
    private string $table;
    # Recebe os campos do filtro para WHERE
    private array $where = [];
    # Recebe os valores e campos do filtro para WHERE
    private array $binds = [];
    # Recebe os nomes dos campos e valores que serão atualizados.
    private array $fieldsAndValues = [];
    public static function table(string $table): self 
    {
        # Cria a instancia da classe
        $self = new self();
        # Recebe o valor do parametro $table, e guarda no atributos classe da table
        $self->table = $table;
        # Retorna a instancia da propria classe
        return $self;
    }
    public function set(array $fieldsAndValues): self 
    {
        # Adicionar na propriedade fieldsAndValues os valores que serão atualizados
        # A propriedade da classe $fieldAndValues
        $this->fieldsAndValues = $fieldsAndValues;
        # Retorna a instancia da propria classe
        return $this;
    }
    public function where(string $field, string $operator, string | int $value, $logic = null):self 
    {
        $placeHolder = '';
        $placeHolder = $field;
        if (str_contains($placeHolder, '.')) {
            $placeHolder = substr($field, strpos ($field, '.') + 1);
        }
        $this->where[] = "{$field} {$operator} :{$placeHolder} {$logic}";
        $this->binds[$placeHolder] = $value;
        return $this;
    }
    public function createQuery()
    {
        # Verificamos se o nome de uma tablea foi informado
        if (!$this->table) {
            # Caso não seja informado o valor de uma tabela, lançamos um ERRO
            throw new \Exception("Tabela não informada");
        }
        # Verificamos se o os campos e valores foram informados
        if (!$this->fieldsAndValues) {
            # Caso não seja informado o campo e valor de uma tabela, lançamos um ERRO.
            throw new \Exception("Nenhum campo e valor informado para atualização");
        }
        $query = '';
        # Inicia a criação query com os placeHplderpara execução do UPDATE
        $query = "UPDATE {$this->table} SET";
        # Loop para percorrer os campos e valores que são atualizados
        foreach ($this->fieldsAndValues as $fields => $value) {
            $query .= "{$fields} = :{$fields},";
            $this->binds[$fields] = $value;
        }
        # Remove a ultima vírgula da minha query
        $query = rtrim ($query,",");
        $query .= (isset($this->where) and (count($this->where) > 0)) ?
        ' where ' . implode (' ', $this->where) :
        '';
        return $query;
    }
    public function executeQuery($query)
    {
        # Recebe a conexão com o banco de dados
        $connection = Connection::connect();
        # Prepara a query para execução
        $prepare = $connection->prepare($query);
        # Faz a substituição dos placeholders pelos valores
        return $prepare->execute($this->binds ?? []);
    }
    public function update(){
        $query = $this->createQuery();
        try {
            # Faz a substituição segura dos parametros da query, pelo valor do array Binds, e executar no banco
            return $this->executeQuery($query);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 1);
        }
    }
}