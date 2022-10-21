<?php
namespace Inter;

interface SQLQueryBuilder
{
    public function select(array $fields, string $table): SQLQueryBuilder;
    public function insert(string $table, array $fields): SQLQueryBuilder;
    public function update(string $table): SQLQueryBuilder;
    public function delete(string $table): SQLQueryBuilder;
    public function where(string $field, string $value, string $operator = "="): SQLQueryBuilder;
    public function value(array $values): SQLQueryBuilder;
    public function set(array $keyValue): SQLQueryBuilder;
    public function group(string $field, string $order = 'ASC'): SQLQueryBuilder;
    public function getSQL(): string;
}