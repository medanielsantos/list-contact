<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Contact;
use App\Models\Person;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    public const BASE_URL = '/api/people';

    /** @test */
    public function it_should_access_endpoint(): void
    {
        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /** @test */
    public function it_should_return_list_of_people(): void
    {
        Person::factory()->count(3)->create();

        $response = $this->getJson(self::BASE_URL);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function it_should_return_list_of_people_with_contacts(): void
    {
        Person::factory()->has(Contact::factory()->count(3))->count(3)->create();

        $response = $this->getJson(self::BASE_URL);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'contacts' => [
                        '*' => [
                            'id',
                            'person_id',
                            'phone',
                            'email',
                            'whatsapp',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function it_should_paginate_list_of_people(): void
    {
        Person::factory()->count(15)->create();

        $response = $this->getJson(self::BASE_URL);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $response->assertJsonFragment([
            'current_page' => 1,
            'from'         => 1,
            'last_page'    => 2,
            'per_page'     => 10,
            'to'           => 10,
            'total'        => 15,
        ]);

        $this->assertCount(10, $response->json('data'));

        $this->call('GET', self::BASE_URL . '?page=2')->assertJsonFragment([
            'current_page' => 2,
            'from'         => 11,
            'last_page'    => 2,
            'per_page'     => 10,
            'to'           => 15,
            'total'        => 15,
        ]);
    }

    /** @test */
    public function it_should_return_a_person(): void
    {
        $person = Person::factory()->create();

        $response = $this->getJson(self::BASE_URL . "/{$person->id}");

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);

        $response->assertJsonFragment([
            'id'   => $person->id,
            'name' => $person->name,
        ]);
    }

    /** @test */
    public function it_should_return_a_person_with_contacts(): void
    {
        $person = Person::factory()->has(Contact::factory()->count(3))->create();

        $response = $this->getJson(self::BASE_URL . "/{$person->id}");

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'contacts' => [
                    '*' => [
                        'id',
                        'person_id',
                        'phone',
                        'email',
                        'whatsapp',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'id'       => $person->id,
            'name'     => $person->name,
            'contacts' => $person->contacts->toArray(),
        ]);
    }

    /** @test */
    public function it_should_create_a_person(): void
    {
        $person = Person::factory()->make();

        $response = $this->postJson(self::BASE_URL, $person->toArray());

        $response->assertStatus(ResponseAlias::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);

        $response->assertJsonFragment([
            'name' => $person->name,
        ]);
    }

    /** @test */
    public function it_should_update_a_person(): void
    {
        $person = Person::factory()->create([
            'name' => 'Old Name',
        ]);

        $response = $this->putJson(self::BASE_URL . "/{$person->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);

        $response->assertJsonFragment([
            'id'   => $person->id,
            'name' => 'New Name',
        ]);
    }

    /** @test */
    public function it_should_delete_a_person(): void
    {
        $person = Person::factory()->create();

        $response = $this->deleteJson(self::BASE_URL . "/{$person->id}");

        $response->assertStatus(ResponseAlias::HTTP_NO_CONTENT);

        $this->assertSoftDeleted('people', [
            'id' => $person->id,
        ]);
    }

    /** @test */
    public function it_should_force_delete_a_person(): void
    {
        $person = Person::factory()->create();

        $response = $this->deleteJson(self::BASE_URL . "/{$person->id}/force");

        $response->assertStatus(ResponseAlias::HTTP_NO_CONTENT);

        $this->assertDatabaseEmpty('people', [
            'id' => $person->id,
        ]);
    }

}
