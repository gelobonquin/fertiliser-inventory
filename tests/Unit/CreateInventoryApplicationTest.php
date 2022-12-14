<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Inventory;
use App\Actions\CreateInventoryApplication;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateInventoryApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected CreateInventoryApplication $createInventoryApplication;

    protected function setUp(): void
    {
        parent::setUp();   

        Inventory::factory()->create([
            'type' => 'Purchase',
            'quantity' => 1,
            'unit_price' => 10.00
        ]);

        Inventory::factory()->create([
            'type' => 'Purchase',
            'quantity' => 2,
            'unit_price' => 20.00
        ]);

        Inventory::factory()->create([
            'type' => 'Purchase',
            'quantity' => 2,
            'unit_price' => 15.00
        ]);

        $this->createInventoryApplication = new CreateInventoryApplication();
    }
    
    public function test_can_store_application()
    {
        $this->createInventoryApplication->handle(1);

        $this->assertDatabaseHas('inventories', [
            'type' => Inventory::TYPE_APPLICATION,
            'quantity' => -1
        ]);
    }
}
