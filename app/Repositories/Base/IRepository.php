<?php

namespace App\Repositories\Base;


use Illuminate\Database\Eloquent\Model;

interface IRepository
{
    /**
     * @param array $order
     * @param array $columns
     * @return mixed
     */
    public function all(array $order = array(), array $columns = array('*'));


    /**
     * @param int $pageSize
     * @param array $order
     * @param array $conditions
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $pageSize = 15, array $order = array(), array $conditions = array(), array $columns = array('*'));


    /**
     * @param array $conditions
     * @param array $order
     * @param array $columns
     * @return mixed
     */
    public function where(array $conditions = array(), array $order = array(), array $columns = array('*'));

    /**
     * @param array $conditions
     * @return bool
     */
    public function exists(array $conditions = array());

    /**
     * @param array $tables
     * @param array $conditions
     * @param array $columns
     * @return mixed
     */
    public function with(array $tables = array(), array $conditions = array(), array $columns = array('*'));

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, array $columns = array('*'));

    /**
     * @param $field
     * @param $value
     * @param array $order
     * @param array $columns
     * @return mixed
     */
    public function findByProperty(string $field, string $value, array $order = array(), array $columns = array('*'));

    /**
     * @param Model $model
     * @return mixed
     */
    public function save(Model $model);

    /**
     * @param array $data
     * @param $attributeValues
     * @param string $attribute
     * @return bool|mixed
     */
    public function update(array $data, $attributeValues, string $attribute = 'id');

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): ?Model;

    /**
     * @param int|array $ids
     * @return bool
     */
    public function delete($ids): bool;

    /**
     * @param $conditions
     * @return bool
     */
    public function deleteWhere($conditions): bool;

    /**
     * @param $relation
     * @param $model
     * @param array $related
     * @return mixed
     */
    public function syncRelation($relation, $model, array $related);
}
