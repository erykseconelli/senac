<?php
#NAMESPACE permite que a minha classe seja acessada em qualquer lugar do projeto.
namespace app\database\builder;
# Faz a importação da classe Connection.
use app\database\Connection;

# Cria a classe SelectQuery.
class SelectQuery
{
   # Receber o nome tabela para alteração dos dados.
   private ?string $table = null;
   # Recebe os nomes dos campos e valores que serão atualizados.
   private ?string $fields = null;
   # Cria uma propriedade para Order (Order By)
   private string $order;
   # Cria uma propriedade para Group (Group By)
   private string $group;
   # Define o limite de registros para ser registrado (10 por padrão)
   private int $limit = 10;
   # é o deslocamento do limite de registros (Inicio por padrão)
   private int $offset = 0;
   # Recebe os campos do filtro para WHERE
   private array $where = [];
   # Faz o join ( a junção ) entre as tabelas
   private array $join = [];
   # Recebe os valores e campos do filtro para WHERE
   private array $binds = [];
   # Cria a propriedade para o limite de registros
   private string $limits;

   # É uma função statica que cria os campos que será selecionado na consulta. ( O padrão é " * ")
   public static function select(string $fields = '*'): ?self
   {
      $self = new self;
      $self->fields = $fields;
      return $self;
   }

   # Define a tabela que vai ser usada na tabela.
   public function table(string $table): ?self
   {
      $this->table = '';
      $this->table = $table;
      return $this;
   }

   # Adiciona a condição WHERE e o Operador para fazer a comparação.
   # O operador de comparação pode ser: '=', 'LIKE', 'IN', 'BETWEEN'.
   public function where(
      string $field,
      string $operator,
      string|int|bool|float $value,
      ?string $logic = null
   ): ?self {
      # Cria uma placeHolder de acordo com o nome do campo.
      $placeHolder = ''; # Os dois cria o placeHolder com o nome do campo.
      $placeHolder = $field;
      # Retira o nome do campo, nesse caso, removera o  (.) do nome do campo.
      if (str_contains($placeHolder, '.')) {
         $placeHolder = substr($field, strpos($field, '.') + 1);
      }
      # Garante que o nome do campo não seja repetido.
      $i = 1;
      while (array_key_exists($placeHolder, $this->binds)) {
         $placeHolder = $placeHolder; # Cria o placeHolder com o nome do campo.
         $i++;
      }
      #Campo formatado com UPPER e cast para texto
      $formattedField = "UPPER({$field}::TEXT)";
      $paramValue = strtoupper((string)$value);
      # Caso o operador seja LIKE, ele coloca o % no valor do campo.
      if (strtoupper($operator) === 'LIKE') { # Verifica se é LIKE.
         $paramValue = "%{$paramValue}%"; # Adiciona a % no campo.
      }
      # Monta a propriedade WHERE junto com o operador e o valor do campo.
      $condition = "{$formattedField} {$operator} :{$placeHolder}"; # Adiciona o operador.
      $logicSQL = $logic ? strtoupper($logic) : ''; # Verifica se o operador é LIKE, caso seja, ele coloca o % no valor do campo.
      $this->where[] = "{$condition} {$logicSQL}"; # Adiciona o WHERE na propriedade Where.
      $this->binds[$placeHolder] = $paramValue; # Adiciona o valor do campo na propriedade Binds.
      return $this;
   }
   # Cria a função order (Order By) para arrumar os campos.
   public function order(string $field, string $value): ?self
   {
      $this->order = " order by {$field} {$value}";
      return $this;
   }
   # O método createQuery() verifica se os campos e valores foram informados, e se não foram, lança um erro.
   public function createQuery()
   {
      # Verifica se os campos fora informados, se não tiver, ele envia o erro.
      if (!$this->fields) {
         throw new \Exception("A query precisa chamar o metodo select");
      }
      if (!$this->table) {
         throw new \Exception("A query precisa chamar o metodo from");
      }
      # Começa a criar a Query
      $query = '';
      $query = 'select '; # Aqui ele começa a adicionar os campos na Query.
      $query .= $this->fields . ' from '; # Adiciona o FROM na Query.
      $query .= $this->table; # Adiciona o nome da tabela na Query.

      # Aqui ele começa a adicionar os JOIN, WHERE, GROUP BY, ORDER BY e LIMIT na Query.
      $query .= (isset($this->join) and (count($this->join) > 0)) ? implode(
         ' ',
         $this->join
      ) : '';
      $query .= (isset($this->where) and (count($this->where) > 0)) ? ' where ' .
         implode(' ', $this->where) : '';
      $query .= $this->group ?? '';
      $query .= $this->order ?? '';
      $query .= $this->limits ?? '';
      return $query;
   }
   # Cria a função JOIN para juntar as tabelas.
   public function join(string $foreingTable, string $logic, string $type = 'inner'): ?self
   {
      $this->join[] = " {$type} join {$foreingTable} on {$logic}";
      return $this;
   }
   # Cria a função GROUP BY para agrupar os campos.
   public function group(string $field): ?self
   {
      $this->group = " group by {$field}";
      return $this;
   }
   # Cria a função LIMIT que limita os registros.
   public function limit(int $limit, int $offset): ?self
   {
      $this->limit = $limit; # Limite de registros
      $this->offset = $offset;
      $this->limits = " limit {$this->limit} offset {$this->offset}";
      return $this;
   }
   # Cria a função BETWEEN que limita os registros entre dois valores.
   public function between(string $field, string|int $value1, string|int $value2): ?self
   {
      $placeHolder1 = $field . '_1';  # Cria o placeHolder1
      $placeHolder2 = $field . '_2'; #  Cria o placeHolder2
      $this->where[] = "{$field} between :{$placeHolder1} and :{$placeHolder2}"; # Usa a condição WHERE entre os dois valores.
      $this->binds[$placeHolder2] = $value2;
      return $this;
   }
   # Cria a função FETCH que vai executar a Query e retornar apenas um registro.
   public function fetch()
   {
      $query = '';
      $query = $this->createQuery();
      try {
         $connection = Connection::connect();
         $prepare = $connection->prepare($query);
         $prepare->execute($this->binds ?? []);
         return $prepare->fetchObject();
      } catch (\PDOException $e) {
         throw new \Exception("Restrição: {$e->getMessage()}");
      }
   }
   # Cria a função FETCHALL que vai executar toda a Query e vai retornar em forma de array.
   public function fetchAll()
   {
      $query = $this->createQuery();
      try {
         $connection = Connection::connect();
         $prepare = $connection->prepare($query);
         $prepare->execute($this->binds ?? []);
         $data = $prepare->fetchAll();
         return $data;
      } catch (\PDOException $e) {
         throw new \Exception("Restrição: {$e->getMessage()}");
      }
   }
}
