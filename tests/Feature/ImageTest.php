<?php

namespace Tests\Feature;

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get All Images Test
     *
     * @return void
     */
    public function test_get_images()
    {
        $response = $this->get('/api/images');

        $response->assertStatus(200);
    }

    /**
     * Create Image Test
     *
     * @return void
     */
    public function test_create_image()
    {
        // Storage::fake('fake');
        $file = UploadedFile::fake()->image('fake.jpg');

        $data = [
            'name'      => 'Image Testing',
            'file'      => $file,
            'enable'    => 1,
        ];

        $response = $this->postJson('/api/images/', $data);

        // Storage::disk('fake')->assertExists('product_images/' . $file->hashName());
        $response->assertStatus(200);
    }
    
    /**
     * Get Image Detail Test
     *
     * @return void
     */
    public function test_get_image_detail()
    {
        $response = $this->get('/api/images/1');

        $response->assertStatus(200);
    }

    /**
     * Update Image Test
     *
     * @return void
     */
    public function test_update_image()
    {
        // Storage::fake('avatars');
        $file = UploadedFile::fake()->image('fake-1.jpg');

        $data = [
            'name'      => 'Image Testing Updated',
            'file'      => $file,
            'enable'    => 1,
        ];

        $response = $this->postJson('/api/images/update/1', $data);

        $response->assertStatus(200);
    }

    /**
     * Delete Image Test
     *
     * @return void
     */
    public function test_delete_image()
    {
        $response = $this->delete('/api/images/1');

        $response->assertStatus(200);
    }
}
