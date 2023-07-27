<?php

namespace App\Repositories\Author;

use App\Models\Author;
use App\Repositories\Base\BaseRepository;

class AuthorRepository extends BaseRepository implements IAuthorRepository
{
    public function __construct(Author $model, array $defaultOrder = ['id' => 'desc'])
    {
        parent::__construct($model, $defaultOrder);
    }

    /**
     * New custom repository accessors/modifiers can be added here
     */
}
