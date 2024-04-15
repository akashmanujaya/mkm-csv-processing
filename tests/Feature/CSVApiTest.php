<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CSVApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Obtain token
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $this->token = $response->json('data.token');
    }

    public function test_csv_upload()
    {
        Storage::fake('csv_uploads');

        $file = UploadedFile::fake()->create('sample.csv', 1024, 'text/csv');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/upload-csv', [
            'csv_file' => $file
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'status_code' => 200,
                     'message' => 'Your data is being processed.',
                 ]);
    }

    public function test_get_product_by_sku()
    {
        // Assume a product is created in the database
        $product = \App\BO\csv\Models\Product::create([
            'sku' => 'unique-sku-123',
            'name' => 'Test Product',
            'description' => 'A sample product.',
            'brand' => 'Test Brand'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/product/unique-sku-123');

        $response->assertStatus(200)
             ->assertJson([
                 'status' => true,
                 'status_code' => 200,
                 'message' => 'Product found.',
                 'data' => [
                     'product' => [
                         'sku' => 'unique-sku-123',
                         'name' => 'Test Product',
                         'description' => 'A sample product.',
                         'brand' => 'Test Brand'
                     ]
                 ]
             ]);
    }
}
