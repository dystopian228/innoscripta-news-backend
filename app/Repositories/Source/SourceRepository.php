<?php

namespace App\Repositories\Source;

use App\Models\Source;

class SourceRepository extends \App\Repositories\Base\BaseRepository implements ISourceRepository
{
    protected function __construct(Source $model, array $defaultOrder = ['id' => 'desc'])
    {
        parent::__construct($model, $defaultOrder);
    }

    /**
     * New custom repository accessors/modifiers can be added here
     */
}
