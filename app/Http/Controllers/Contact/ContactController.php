<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactStoreRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContactController extends Controller
{
    public function store(ContactStoreRequest $request)
    {
        $data = Contact::query()->create($request->validated());

        return response(new ContactResource($data), ResponseAlias::HTTP_CREATED);
    }

    public function update(ContactStoreRequest $request, Contact $contact)
    {
        $contact->update($request->validated());

        return response(new ContactResource($contact), ResponseAlias::HTTP_ACCEPTED);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->noContent();
    }

    public function forceDestroy(Contact $contact)
    {
        $contact->forceDelete();

        return response()->noContent();
    }
}
