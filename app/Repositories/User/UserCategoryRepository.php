<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserCategory;
use App\Repositories\Base\BaseRepository;

class UserCategoryRepository extends BaseRepository implements IUserCategoryRepository
{
    public function __construct(UserCategory $model, array $defaultOrder = ['id' => 'desc'])
    {
        parent::__construct($model, $defaultOrder);
    }

    /**
     * New custom repository accessors/modifiers can be added here
     */
}
