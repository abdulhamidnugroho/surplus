<?php

namespace Tests\Feature;

use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get All Products Test
     *
     * @return void
     */
    public function test_get_products()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }

    /**
     * Create Product Test
     *
     * @return void
     */
    public function test_create_product()
    {
        $data = [
            'name'          => 'Product Testing',
            'description'   => 'Product Testing Description',
            'category_ids'  => '[1,2]',
            'enable'        => 1,
        ];

        $response = $this->postJson('/api/products/', $data);

        $response->assertStatus(200);
    }
    
    /**
     * Get Product Detail Test
     *
     * @return void
     */
    public function test_get_product_detail()
    {
        $response = $this->get('/api/products/1');

        $response->assertStatus(200);
    }

    /**
     * Update Product Test
     *
     * @return void
     */
    public function test_update_product()
    {
        $data = [
            'name'          => 'Product Testing Updated',
            'description'   => 'Product Testing Updated Description',
            'category_ids'  => '[1,2]',
            'enable'        => 1,
        ];

        $response = $this->putJson('/api/products/1', $data);
        echo $response->getContent();

        $response->assertStatus(200);
    }

    /**
     * Delete Product Test
     *
     * @return void
     */
    public function test_delete_product()
    {
        $response = $this->delete('/api/products/1');

        $response->assertStatus(200);
    }
}
