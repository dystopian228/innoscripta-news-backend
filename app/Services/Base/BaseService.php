<?php

namespace App\Services\Base;


use App\Repositories\Base\IRepository;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected IRepository $primaryRepository;

    /**
     * BaseService constructor.
     * @param IRepository $primaryRepository Model's Primary Repository
     * @throws \Exception
     */
    public function __construct(IRepository $primaryRepository)
    {
        $this->primaryRepository = $primaryRepository;
    }

    /**
     * @param array $order
     * @param array $columns
     * @return mixed|null
     */
    public function all(array $order = array(), array $columns = array('*'))
    {
        return $this->primaryRepository->all($order, $columns);
    }

    /**
     * @param int $pageSize
     * @param array $order
     * @param array $conditions
     * @param array $columns
     * @return mixed|null
     */
    public function paginate(int $pageSize = 15, array $order = array(), array $conditions = array(), array $columns = array('*'))
    {
        return $this->primaryRepository->paginate($pageSize, $order, $conditions, $columns);
    }

    /**
     * @param array $conditions
     * @param array $order
     * @param array $columns
     * @return mixed
     */
    public function where(array $conditions = array(), array $order = array(), array $columns = array('*'))
    {
        return $this->primaryRepository->where($conditions, $order, $columns);
    }

    /**
     * @param array $conditions
     * @return bool
     */
    public function exists(array $conditions = array())
    {
        return $this->primaryRepository->exists($conditions);
    }

    /**
     * @param array $tables
     * @param array $conditions
     * @param array $columns
     * @return mixed|null
     */
    public function with(array $tables = array(), array $conditions = array(), array $columns = array('*'))
    {
        return $this->primaryRepository->with($tables, $conditions, $columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed|null
     */
    public function find($id, array $columns = array('*'))
    {
        return $this->primaryRepository->find($id, $columns);
    }

    /**
     * @param $field
     * @param $value
     * @param array $order
     * @param array $columns
     * @return mixed|null
     */
    public function findByProperty($field, $value, array $order = array(), array $columns = array('*'))
    {
        return $this->primaryRepository->findByProperty($field, $value, $order, $columns);
    }

    /**
     * @param $conditions
     * @param array $columns
     * @return mixed|null
     */
    public function first($conditions, array $columns = array('*'))
    {
        return $this->primaryRepository->first($conditions, $columns);
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function create(array $data): ?Model
    {
        return $this->primaryRepository->create($data);
    }

    /**
     * @param Model $model
     * @return bool|mixed
     */
    public function save(Model $model)
    {
        return $this->primaryRepository->save($model);
    }

    /**
     * @param array $data
     * @param $attributeValues
     * @param string $attribute
     * @return bool|mixed
     */
    public function update(array $data, $attributeValues, string $attribute = 'id')
    {
        return $this->primaryRepository->update($data, $attributeValues, $attribute);
    }

    /**
     * @param $ids
     * @return bool
     */
    public function delete($ids): bool
    {
        return $this->primaryRepository->delete($ids);
    }

    /**
     * @param $conditions
     * @return bool
     */
    public function deleteWhere($conditions): bool
    {
        return $this->primaryRepository->deleteWhere($conditions);
    }

    /**
     * @param $field
     * @param $value
     * @return bool
     */
    public function has($field, $value)
    {
        return $this->primaryRepository->has($field, $value);
    }

    /**
     * @param $relation
     * @param $model
     * @param array $related
     * @throws \Exception
     */
    public function syncRelation($relation, $model, array $related)
    {
        $this->primaryRepository->syncRelation($relation, $model, $related);
    }
}

