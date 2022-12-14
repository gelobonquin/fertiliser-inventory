<?php

namespace Tests\Feature;

use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;


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

    }
    
    public function test_request_empty_input()
    {
        $this->postJson('/api/inventory', ['quantity' => null])
            ->assertStatus(422);
    }

    public function test_request_invalid_input()
    {
        $this->postJson('/api/inventory', ['quantity' => 'asdasd'])
            ->assertStatus(422);
    }

    public function test_request_no_stock()
    {
        Inventory::factory()->create([
            'type' => Inventory::TYPE_APPLICATION,
            'quantity' => -5
        ]);

        $this->postJson('/api/inventory', ['quantity' => 1])
            ->assertStatus(400);
    }

    public function test_request_quantity_more_than_available_stock()
    {
        $this->postJson('/api/inventory', ['quantity' => 6])
            ->assertStatus(400);
    }

    public function test_request_quantity_applied()
    {
        $this->postJson('/api/inventory', ['quantity' => 2])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => '2 unit applied at $30.00.',
                'remaining_quantity' => "3"
            ]);

        $this->assertDatabaseHas('inventories', ['type' => 'Application', 'quantity' => -2]);            
    }
    
}
