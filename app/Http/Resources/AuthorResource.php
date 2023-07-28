<?php

namespace App\Http\Resources;

use App\Entities\AuthorDefinition;
use App\Entities\BaseFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this[BaseFields::ID],
            'name' => $this[AuthorDefinition::NAME],
            'organization' => $this[AuthorDefinition::ORGANIZATION],
            'title' => $this[AuthorDefinition::TITLE]
        ];
    }
}
