<?php

namespace App\Http\Resources;

use App\Entities\BaseFields;
use App\Entities\SourceDefinition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
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
            'name' => $this[SourceDefinition::NAME],
            'description' => $this[SourceDefinition::DESCRIPTION],
            'url' => $this[SourceDefinition::URL],
            'mainCategory' => $this[SourceDefinition::MAIN_CATEGORY],
            'country' => $this[SourceDefinition::COUNTRY]
        ];
    }
}
