<?php

namespace Tests\Unit;

use App\Http\Services\InventoryService;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected InventoryService $inventoryService;

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

        $this->inventoryService = new InventoryService();
    }

    public function test_request_quantity_more_than_available_stock()
    {
        $this->assertTrue($this->inventoryService->isMoreThanAvailableStock(6));        
    }

    public function test_request_quantity_less_than_available_stock()
    {
        $this->assertFalse($this->inventoryService->isMoreThanAvailableStock(4));        
    }

    public function test_inventory_available_stock()
    {
        $this->assertEquals(5, $this->inventoryService->stock());
    }

    public function test_inventory_in_stock()
    {
        $this->postJson('/api/inventory', ['quantity' => 1])
            ->assertStatus(200);

        $this->assertTrue($this->inventoryService->inStock());
    }  
    
    public function test_can_get_inventory()
    {
        $this->assertCount(3, $this->inventoryService->inventories());
    }

    public function test_inventory_valuation_calculation()
    {
        $this->assertEquals(20.00, $this->inventoryService->calculateValuation(10, 1, 10));
    }
    

}
