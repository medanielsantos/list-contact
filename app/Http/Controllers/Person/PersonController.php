<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Http\Requests\Person\PersonStoreRequest;
use App\Http\Requests\Person\PersonUpdateRequest;
use App\Http\Resources\Person\PersonResource;
use App\Models\Person;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PersonResource::collection(Person::query()->paginate(10));
    }

    public function show(Person $person): PersonResource
    {
        return new PersonResource($person);
    }

    public function store(PersonStoreRequest $request): PersonResource
    {
        $data = Person::query()->create($request->validated());

        return new PersonResource($data);
    }

    public function update(PersonUpdateRequest $request, Person $person): PersonResource
    {
        $person->update($request->validated());

        return new PersonResource($person);
    }

    public function destroy(Person $person): Response
    {
        $person->delete();

        return response()->noContent();
    }

}
