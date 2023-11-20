<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactStoreRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContactController extends Controller
{
    public function index(): Response
    {
        return response(ContactResource::collection(Contact::query()->paginate(10)));
    }

    public function show(Contact $contact): Response
    {
        return response(new ContactResource($contact));
    }

    public function store(ContactStoreRequest $request): Response
    {
        $data = Contact::query()->create($request->validated());

        return response(new ContactResource($data), ResponseAlias::HTTP_CREATED);
    }

    public function update(ContactStoreRequest $request, Contact $contact): Response
    {
        $contact->update($request->validated());

        return response(new ContactResource($contact), ResponseAlias::HTTP_ACCEPTED);
    }

    public function destroy(Contact $contact): Response
    {
        $contact->delete();

        return response()->noContent();
    }

    public function forceDestroy(Contact $contact): Response
    {
        $contact->forceDelete();

        return response()->noContent();
    }

    public function favorite(Contact $contact): Response
    {
        $contact->update([
            'is_favorite' => !$contact->is_favorite,
        ]);

        return response()->noContent();
    }
}
