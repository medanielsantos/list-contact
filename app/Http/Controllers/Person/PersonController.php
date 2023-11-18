<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Http\Requests\Person\PersonStoreRequest;
use App\Http\Requests\Person\PersonUpdateRequest;
use App\Http\Resources\Person\PersonResource;
use App\Models\Person;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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

    public function store(PersonStoreRequest $request): Response
    {
        $data = Person::query()->create($request->validated());

        return response(new PersonResource($data), ResponseAlias::HTTP_CREATED);
    }

    public function update(PersonUpdateRequest $request, Person $person): Response
    {
        $person->update($request->validated());

        return response(new PersonResource($person), ResponseAlias::HTTP_ACCEPTED);
    }

    public function destroy(Person $person): Response
    {
        $person->delete();

        return response()->noContent();
    }

    public function forceDestroy(Person $person): Response
    {
        $person->forceDelete();

        return response()->noContent();
    }

}
