<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Inventory;
use Mockery\MockInterface;
use App\Services\InventoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

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

        Inventory::factory()->create([
            'type' => 'Application',
            'quantity' => -2,
            'unit_price' => 0
        ]);
        
        $this->inventoryService = Mockery::mock(InventoryService::class)->makePartial();
    }

    public function test_get_purchases()
    {
        $this->assertCount(3, $this->inventoryService->getPurchases());
        $this->assertInstanceOf(Collection::class, $this->inventoryService->getPurchases());
    }

    public function test_get_total_applications()
    {
        $this->assertEquals(-2, $this->inventoryService->getTotalApplications());
    }

    public function test_get_calculate_valuation()
    {
        $inventories = $this->inventoryService->getPurchases();
        $this->assertEquals(80, $this->inventoryService->calculateValuation($inventories));        
    }

    public function test_get_purchases_with_remaining_quantity()
    {
        $purchases = $this->inventoryService->getPurchasesWithRemainingQuantity();

        collect($purchases)->each(function($purchase) {
            $this->assertNotEquals(0, $purchase->quantity);
        });                
    }

    public function test_get_valuation()
    {   
        $this->assertEquals(35.0, $this->inventoryService->getValuation(2));
    }
}
