<?php

namespace App\Repositories\Base;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements IRepository
{
    /**
     * @var array order for queries
     */
    protected array $defaultOrder;

    /**
     * @var Model $presistantModelClass
     */
    protected Model $persistentModelClass;

    /**
     * Repository constructor.
     * @param Model $model
     * @param array $defaultOrder
     */
    protected function __construct(Model $model, array $defaultOrder = ['id' => 'desc'])
    {
        $this->persistentModelClass = $model;
        $this->defaultOrder = $defaultOrder;
    }

    /**
     * @param array $order
     * @param array $columns
     * @return mixed|null
     * @throws \Exception
     */
    public function all(array $order = array(), array $columns = array('*'))
    {
        $order = empty($order) ? $this->defaultOrder : $order;
        $data = null;
        try {
            $querySet = $this->persistentModelClass::query();
            $querySet = $this->buildNestedOrder($querySet, $order);

            $data = $querySet->get($columns);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param int $pageSize
     * @param array $order
     * @param array $conditions
     * @param array $columns
     * @return mixed|null
     * @throws \Exception
     */
    public function paginate(int $pageSize = 15, array $order = array(), array $conditions = array(), array $columns = array('*')): mixed
    {
        $order = empty($order) ? $this->defaultOrder : $order;
        $data = null;
        try {
                $querySet = $this->persistentModelClass::where($conditions);

            $querySet = $this->buildNestedOrder($querySet, $order);
            $data = $querySet->paginate($pageSize, $columns);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param array $conditions
     * @param array $order
     * @param array $columns
     * @return mixed
     * @throws \Exception
     */
    public function where(array $conditions = array(), array $order = array(), array $columns = array('*')): mixed
    {
        $order = empty($order) ? $this->defaultOrder : $order;
        $data = null;
        try {
            $data = $this->whereBase($conditions, $order, $columns)->get();
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param array $conditions
     * @return bool
     * @throws \Exception
     */
    public function exists(array $conditions = array()): bool
    {
        try {
            return $this->whereBase($conditions)->exists();
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param array $conditions
     * @param array $order
     * @param array $columns
     * @throws \Exception
     */
    private function whereBase(array $conditions = array(), array $order = array(), array $columns = array('*'))
    {
        $order = empty($order) ? $this->defaultOrder : $order;
        $data = null;
        try {
            $querySet = $this->persistentModelClass::where($conditions);

            $this->buildNestedOrder($querySet, $order);

            return $querySet;
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }


    /**
     * @param array $tables
     * @param array $conditions
     * @param array $columns
     * @return mixed|null
     * @throws \Exception
     */
    public function with(array $tables = array(), array $conditions = array(), array $columns = array('*')): mixed
    {
        $data = null;
        try {
            $query = $this->persistentModelClass::where($conditions);

            foreach ($tables as $tableName => $tableConditions) {
                $query->with([$tableName => function ($query) use (&$tableConditions) {
                    $query->where($tableConditions);
                }]);
            }
            $data = $query->get();
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param int $id
     * @param array $columns
     * @return mixed|null
     * @throws \Exception
     */
    public function find(int $id, array $columns = array('*'))
    {
        $data = null;
        try {
            $data = $this->persistentModelClass::find($id, $columns);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param string $field
     * @param string $value
     * @param array $order
     * @param array $columns
     * @return mixed|null
     * @throws \Exception
     */
    public function findByProperty(string $field, string $value, array $order = array(), array $columns = array('*'))
    {
        $order = empty($order) ? $this->defaultOrder : $order;
        $data = null;
        try {
            $querySet = $this->persistentModelClass::where($field, $value);

            $this->buildNestedOrder($querySet, $order);

            $data = $querySet->get($columns);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param $conditions
     * @param array $columns
     * @return mixed|null
     * @throws \Exception
     */
    public function first($conditions, $columns = array('*'))
    {
        $data = null;
        try {
            $data = $this->persistentModelClass::where($conditions)->first($columns);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
        return $data;
    }

    /**
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function create(array $data): ?Model
    {
        try {
            return $this->persistentModelClass::create($data);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param Model $model
     * @return bool|mixed
     * @throws \Exception
     */
    public function save(Model $model)
    {
        try {
            return $model->save();
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param $attributeValues
     * @param string $attribute
     * @return bool|mixed
     * @throws \Exception
     */
    public function update(array $data, $attributeValues, string $attribute = 'id')
    {
        try {
            return $this->persistentModelClass::whereIn($attribute, $attributeValues)->update($data);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param array|int $ids
     * @return bool
     * @throws \Exception
     */
    public function delete($ids): bool
    {
        try {
            $this->persistentModelClass::whereIn('id', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param $conditions
     * @return bool
     * @throws \Exception
     */
    public function deleteWhere($conditions): bool
    {
        try {
            $this->persistentModelClass::where($conditions)->delete();
            return true;
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param $relation
     * @param $model
     * @param array $related
     * @throws \Exception
     */
    public function syncRelation($relation, $model, array $related): void
    {
        try {
            $model->$relation()->sync($related);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param Builder $querySet
     * @param array $order
     * @return Builder
     */
    protected function buildNestedOrder(Builder &$querySet, array $order = array()): Builder
    {
        if (count($order) == 0)
            return $querySet;

        foreach ($order as $orderBy => $orderDir) {
            //Check if the order field is a table.column composite
            if (strpos(strval($orderBy), '.') !== false) {
                //separate tablename for relation and column name as a target field
                $orderBySegments = explode('.', $orderBy);
                $targetField = array_pop($orderBySegments);
                $targetRelation = implode('.', $orderBySegments);

                //Use both in with query
                $querySet->with([$targetRelation => function ($query) use ($targetField, $orderDir) {
                    $query->orderBy($targetField, $orderDir);
                }]);
            } else {
                $querySet->orderBy($orderBy, $orderDir);
            }
        }

        return $querySet;
    }
}
