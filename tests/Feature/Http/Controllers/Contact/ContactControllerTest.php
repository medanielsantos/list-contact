<?php

namespace Tests\Feature\Http\Controllers\Contact;

use App\Models\Contact;
use App\Models\Person;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    /** @test */
    public function it_should_create_a_contact(): void
    {
        $person = Person::factory()->createOne();

        $response = $this->postJson('/api/contacts', [
            'person_id' => $person->id,
            'type'      => 'email',
            'value'     => 'mail@aol.com',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);

        $response->assertJsonStructure([
            'id',
            'person_id',
            'type',
            'value',
        ]);

        $this->assertDatabaseHas('contacts', [
            'person_id' => $person->id,
            'type'      => 'email',
            'value'     => 'mail@aol.com',
        ]);
    }

    /** @test */
    public function it_should_update_a_contact(): void
    {
        $person = Person::factory()->has(Contact::factory([
            'type'  => 'email',
            'value' => 'old@mail.com',
        ])->count(1))->createOne();

        $response = $this->putJson('/api/contacts/' . $person->contacts->first()->id, [
            'person_id' => $person->id,
            'type'      => 'email',
            'value'     => 'new@mail.com',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_ACCEPTED);

        $response->assertJsonStructure([
            'id',
            'person_id',
            'type',
            'value',
        ]);

        $this->assertDatabaseMissing('contacts', [
            'person_id' => $person->id,
            'type'      => 'email',
            'value'     => 'old@mail.com',
        ]);

        $this->assertDatabaseHas('contacts', [
            'person_id' => $person->id,
            'type'      => 'email',
            'value'     => 'new@mail.com',
        ]);
    }

    /** @test */
    public function it_should_delete_a_contact(): void
    {
        $person = Person::factory()->has(Contact::factory()->count(1))->createOne();

        $response = $this->deleteJson('/api/contacts/' . $person->contacts->first()->id);

        $response->assertStatus(ResponseAlias::HTTP_NO_CONTENT);

        $this->assertSoftDeleted('contacts', [
            'id' => $person->contacts->first()->id,
        ]);

        $this->assertDatabaseHas('people', [
            'id' => $person->id,
        ]);

    }

    /** @test */
    public function it_should_force_delete_a_contact(): void
    {
        $person = Person::factory()->has(Contact::factory()->count(1))->createOne();

        $response = $this->deleteJson('/api/contacts/' . $person->contacts->first()->id . '/force');

        $response->assertStatus(ResponseAlias::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('contacts', [
            'id' => $person->contacts->first()->id,
        ]);

        $this->assertDatabaseHas('people', [
            'id' => $person->id,
        ]);
    }
}
