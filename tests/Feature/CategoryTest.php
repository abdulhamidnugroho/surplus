<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get All Categories Test
     *
     * @return void
     */
    public function test_get_categories()
    {
        $response = $this->get('/api/categories');

        $response->assertStatus(200);
    }

    /**
     * Create Category Test
     *
     * @return void
     */
    public function test_create_category()
    {
        $data = [
            'name'          => 'Category Testing',
            'enable'        => 1,
        ];

        $response = $this->postJson('/api/categories/', $data);

        $response->assertStatus(200);
    }
    
    /**
     * Get Category Detail Test
     *
     * @return void
     */
    public function test_get_category_detail()
    {
        $response = $this->get('/api/categories/1');

        $response->assertStatus(200);
    }

    /**
     * Update Category Test
     *
     * @return void
     */
    public function test_update_category()
    {
        $data = [
            'name'          => 'Category Testing Updated',
            'enable'        => 1,
        ];

        $response = $this->putJson('/api/categories/1', $data);

        $response->assertStatus(200);
    }

    /**
     * Delete Category Test
     *
     * @return void
     */
    public function test_delete_category()
    {
        $response = $this->delete('/api/categories/1');

        $response->assertStatus(200);
    }
}
