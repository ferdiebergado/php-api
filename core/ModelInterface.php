<?php

namespace Core;

interface ModelInterface {
    // public function all();
    public function find($id);
    // public function findByField($field, $operator = '=', $value);
    // public function findWhere($where);
    // public function latest($date);
    // public function orderBy($field, $dir = 'ASC');
    // public function create(array $attrs);
    public function update($id, array $fields);
    // public function delete($id);
}
