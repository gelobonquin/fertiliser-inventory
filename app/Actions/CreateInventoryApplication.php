<?php

namespace App\Actions;

use App\Models\Inventory;

class CreateInventoryApplication
{
     /**
     * Handle creation of requested quantity
     *
     * @param int $quantity
     * @return void
     */
    public function handle($quantity)
    {
        Inventory::create([
            'type' => Inventory::TYPE_APPLICATION,
            'quantity' => abs($quantity) * -1
        ]);
    }
}