<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Contact;
use App\Models\Person;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    /** @test */
    public function it_should_access_endpoint()
    {
        $response = $this->getJson('/api/people');

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /** @test */
    public function it_should_return_list_of_people()
    {
        Person::factory()->count(3)->create();

        $response = $this->getJson('/api/people');

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
    public function it_should_return_list_of_people_with_contacts()
    {
        Person::factory()->has(Contact::factory()->count(3))->count(3)->create();

        $response = $this->getJson('/api/people');

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
    public function it_should_paginate_list_of_people()
    {
        Person::factory()->count(15)->create();

        $response = $this->getJson('/api/people');

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

        $this->call('GET', '/api/people?page=2')->assertJsonFragment([
            'current_page' => 2,
            'from'         => 11,
            'last_page'    => 2,
            'per_page'     => 10,
            'to'           => 15,
            'total'        => 15,
        ]);
    }

    /** @test */
    public function it_should_return_a_person()
    {
        $person = Person::factory()->create();

        $response = $this->getJson("/api/people/{$person->id}");

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
    public function it_should_return_a_person_with_contacts()
    {
        $person = Person::factory()->has(Contact::factory()->count(3))->create();

        $response = $this->getJson("/api/people/{$person->id}");

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
            'id'   => $person->id,
            'name' => $person->name,
            'contacts' => $person->contacts->toArray(),
        ]);
    }

}
