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
    
    public function test_request_quantity_is_empty()
    {
        $this->postJson('/api/inventory', ['quantity' => null])
            ->assertStatus(422);
    }

    public function test_request_quantity_is_invalid()
    {
        $this->postJson('/api/inventory', ['quantity' => 'asdasd'])
            ->assertStatus(422);
    }

    public function test_request_quantity_exceeds_available_stock()
    {
        $this->postJson('/api/inventory', ['quantity' => 6])
            ->assertStatus(422);
    }

    public function test_get_valuation()
    {
        $this->postJson('/api/inventory', ['quantity' => 2])
            ->assertJson([
                'success' => true,
                'message' => '2 unit applied at $30'
            ]);
    }    
}
