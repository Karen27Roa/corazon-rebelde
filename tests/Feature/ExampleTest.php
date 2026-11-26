<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_carga_correctamente(): void
    {
        $this->get('/')->assertStatus(200);
    }
}
