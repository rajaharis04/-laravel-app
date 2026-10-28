<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test that the home page redirects to the students page.
     */
    public function test_the_application_redirects_to_students_page(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('students.index'));
    }
}
