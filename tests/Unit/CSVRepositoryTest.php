<?php

namespace Tests\Unit;

use App\BO\csv\Models\Product;
use App\BO\csv\Repositories\CSVRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CSVRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_product_by_sku()
    {
        $product = Product::create([
            'sku' => 'test-sku',
            'name' => 'Test Product',
            'description' => 'Test Description',
            'brand' => 'Test Brand'
        ]);

        $repository = new CSVRepository(new Product);
        $found = $repository->getProductBySKU('test-sku');

        $this->assertNotNull($found);
        $this->assertEquals('Test Product', $found->name);
    }
}

