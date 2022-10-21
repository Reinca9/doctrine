<?php
namespace App;

use stdClass;
use Exception;
use Inter\SQLQueryBuilder;

class MySQLQueryBuilder implements SQLQueryBuilder
{

    private $query;

    private function reset(): void
    {
        $this->query = new stdClass();
    }

    public function select(array $fields, string $table): SQLQueryBuilder
    {
        $this->reset();

        $this->query->base = "SELECT ". implode(', ', $fields)." FROM ".$table;
        $this->query->type = 'select';


        return $this;
    }

    public function where(string $field, string $value, string $operator = "="): SQLQueryBuilder
    {
        if (!in_array($this->query->type, ['select', 'update', 'delete'])) {
            throw new Exception(
                "la clause WHERE ne peut être utilisé qu'avec des requêtes de type SELECT, UPDATE ou DELETE"
            );
        }

        $this->query->where[] = "$field $operator $value";
        

        return $this;
    }

    public function insert(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();

        $this->query->base = "INSERT INTO $table (".implode(', ', $fields).")";
        $this->query->type = "insert";

        return $this;
    }

    public function update(string $table): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = "UPDATE $table SET ";
        $this->query->type = 'update';

        return $this;
    }

    public function delete(string $table): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = "DELETE FROM $table ";
        $this->query->type = 'delete';

        return $this;
    }

    public function set(array $keyValue): SQLQueryBuilder
    {
        if (!in_array($this->query->type, ['update'])) {
            throw new Exception("la clause SET ne peut être utilisé qu'avec une requêtes de type UPDATE");
        }
        $this->query->setValues = [];
        foreach ($keyValue as $key => $value) {
            $this->query->setValues[] = "$key = $value";
        }
        
        return $this;
    }

    public function value(array $values): SQLQueryBuilder
    {
        if (!in_array($this->query->type, ['insert'])) {
            throw new Exception("la clause VALUES ne peut être utilisé qu'avec une requêtes de type INSERT");
        }

        $this->query->values[] = "(".implode(', ', $values).")";

        return $this;
    }

    public function group(string $field, string $order = 'ASC'): SQLQueryBuilder
    {
        
        if (!in_array($this->query->type, ['select'])) {
            throw new Exception("la clause GROUP BY ne peut être utilisé qu'avec des requêtes de type SELECT");
        }

        $this->query->group = " GROUP BY ".$field." ".$order;

        return $this;
    }

    public function getSQL(): string
    {
        $query = $this->query;
        $sql = $query->base;

        if ($query->type == 'delete' && is_null($query->where)) {
            throw new Exception('Merci de conditionné votre DELETE par la methode "where"');
        }

        if ($query->type == 'update') {
            if (is_null($query->setValues)) {
                throw new Exception('Merci de saisir des valeurs via la méthodes "set".');
            }

            $sql .= implode(', ', $query->setValues);
        }

        if (!empty($query->where)) {
            $sql .= " WHERE ".implode(' AND ', $query->where);
        }

        if ($query->type == 'insert') {
            if (is_null($query->values)) {
                throw new Exception('Merci de saisir des valeurs via la méthodes "value".');
            }

            if (sizeof($query->values) == 1) {
                $sql .= " VALUES ".$query->values[0];
            } else {
                $sql .= " VALUES (".implode(', ', $query->values).")";
            }
        }

        if (!empty($query->group)) {
            $sql .= $query->group;
        }


        $sql .= ';';

        return $sql;
    }
}
