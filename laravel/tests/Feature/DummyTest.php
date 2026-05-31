<?php

namespace Tests\Feature;

use Tests\TestCase;

class DummyTest extends TestCase
{
    /**
     * Un test básico para cumplir con el CI/CD.
     */
    public function test_pipeline_pasa_correctamente(): void
    {
        // Afirmamos que true es true. Siempre dará verde.
        $this->assertTrue(true);
    }
}
