<?php

namespace App\Http\Resources\Person;

use App\Http\Resources\Contact\ContactResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array $contacts
 */
class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $personResource = parent::toArray($request);

        $personResource['contacts'] = ContactResource::make($this->contacts);

        return $personResource;
    }
}
