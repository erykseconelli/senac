<?php
#define o namespace da classe, organizando o código por pastas virtuais.
namespace app\database\builder;
#importa a classe de conexão com o banco de dados
use app\database\Connection;
#classe responsável por montar e executar queries SQL do tipo DELETE de forma fluente
class DeleteQuery
{
    private string $table;
    #armazena o nome da tabela onde a exlusão da clásula WHERE.
    private array $where = [];
    #array de binds para associar os placeholders aos valores no prepared statament
    private array $binds = [];
    #método estpatico que indica a construção da query DELETE
    public static function table(string $table): self
    {
        #instancia a prórpia classe
        $self = new self;
        #define a tabela a ser usada na exclusão
        $self->table = $table;
        #retorna a instância para permitir encadeamento de métodos
        return $self;
    }
    #field campo que será filtadro
    #operator é o operador lógico (=, >, <. etc.)
    #string|int $value valor a ser comparado
    #$logic é o operador lógico adicional (AND, OR) pode ser nule
    public function where(string $field, string $operator, string|int $value, ?string $logic = null)
    {
        #define um placeholder baseado no nome do campo
        $placeholder = '';
        $placeholder = $field;
        #caso o campo venha com um alias('u.id'), extrai apenas o nome da coluna('id')
        if (str_contains($placeholder, '.')) {
            $placeholder = substr($field, strpos($field, '.') + 1);
        }
        #monta a expressão da cláusula WHERE com placeholder e operador lógico
        $this->where[] = "{$field} {$operator} :{$placeholder} {$logic}";
        #associa o valor ao place golder no array de binds
        $this->binds[$placeholder] = $value;
        #retorna a própria instância para encadeamento
        return $this;
    }
    #método privado que gera a query DELEtE em forma de string.
    private function createQuery()
    {
        #se a tabela não foi definida, lança uma execução
        if ($this->table) 
        {
            throw new \Exception("a consulta precisa invocar o método delete.");
        }
        #inicia a construção da query
        $query = '';
        $query = "delete from {$this->table}";
        #se houver condições WHERE, adiciona-as à query
        $query .=(isset($this->whre) and (count($this->where) > 0)) ? ' where ' . implode('', $this->where) :'';
        #retorna a string da query montada
        return $query;
}

public function executeQuery($query)
{
    #obtém a conexão com o banco de dados via PDO
    $connection = Connection::connect();
    #prapara a query para evitar a SQL Injection
    $prepare = $connection->prepare($query);
    #executa a query com os valores vinculados(binds)
    return $prepare->execute($this->binds ??[]);
}
#método principal que monta e executa a query DELETE
#true em caso de sucesso, ou lança exceção se falhar
public function delete()
{
    #cria a query completa
    $query = $this->createQuery();
    try{
        #tenta executar a query
        return $this->executeQuery($query);
    } catch (\PDOException $e){
        #captura exceçôes do PDO e lança uma nova exceção personalizade
        throw new \Exception("Restrição: {$e->getMessage()}");
    }
}
}