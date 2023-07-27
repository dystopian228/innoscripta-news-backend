<?php

namespace App\Repositories\Article;

use App\Models\Article;
use App\Repositories\Base\BaseRepository;

class ArticleRepository extends BaseRepository implements IArticleRepository
{
    public function __construct(Article $model, array $defaultOrder = ['id' => 'desc'])
    {
        parent::__construct($model, $defaultOrder);
    }

    /**
     * New custom repository accessors/modifiers can be added here
     */
}
