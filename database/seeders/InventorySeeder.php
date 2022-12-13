<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventories = file(storage_path('fertiliser_inventory.csv'), FILE_IGNORE_NEW_LINES);

        foreach ($inventories as $key => $item) {         
            if (! $key == 0) {
                $inventory = str_getcsv($item);                
                Inventory::create([
                    'type' => $inventory[1],
                    'quantity' => $inventory[2],
                    'unit_price' => $inventory[3] ? $inventory[3] : 0,
                    'created_at' => Carbon::createFromFormat('d/m/Y', $inventory[0])->toDateTimeString(),
                    'updated_at' => Carbon::createFromFormat('d/m/Y', $inventory[0])->toDateTimeString()
                ]);
            }         
        }
    }
}
