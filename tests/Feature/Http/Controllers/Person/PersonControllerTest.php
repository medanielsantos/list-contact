<?php

namespace Tests\Feature\Http\Controllers\Person;

use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    /** @test */
    public function it_should_return_a_list_of_people(): void
    {
        $response = $this->getJson('/api/people');

        $response->assertStatus(200);
    }
}
