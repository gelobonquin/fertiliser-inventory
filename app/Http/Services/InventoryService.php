<?php

namespace App\Http\Services;

use App\Models\Inventory;

class InventoryService
{
    /**
     * Process the inventories to decreased the values based on the request application quantity
     * And return the valuation
     *
     * @param int $quantity
     * @return double
     */
    public function process($quantity)
    {
        $stocks = [];
        $processedValuation = [];

        foreach ($this->inventories() as $inventory) {
            if ($inventory->type == Inventory::TYPE_PURCHASE) {
                $stocks[$inventory->id] = $inventory;
            }

            if ($inventory->type == Inventory::TYPE_APPLICATION) {

                //Convert application decrease quantity to positive
                $applicationDecreaseQuantity = abs($inventory->quantity);
                $valuation = 0;

                while ($applicationDecreaseQuantity > 0) {

                    // Retrieve the first stock purchase
                    $firstStock = reset($stocks);    
     
                    // if application quantity is greater than the stock on hand quantity, consider the stock fully consumed
                    if ($applicationDecreaseQuantity > $firstStock->quantity) {

                        $applicationDecreaseQuantity -=  $firstStock->quantity;                       

                        // Calculate the stock valuation
                        $valuation = $this->calculateValuation($valuation, $firstStock->quantity, $firstStock->unit_price);
                    
                        //Remove the consumed stock
                        unset($stocks[$firstStock->id]);

                    } else {

                        //Decrease stocks quantity
                        $stocks[$firstStock->id]->quantity -= $applicationDecreaseQuantity;
                        
                        // Calculate the stock valuation
                        $valuation = $this->calculateValuation($valuation, $applicationDecreaseQuantity, $firstStock->unit_price);
                        
                        //application decrease quantity fully used
                        $applicationDecreaseQuantity = 0;
                    }                
                }

                $processedValuation[] = $valuation;
            }
        }       
        
        return end($processedValuation);
    }


    /**
     * Retrieve inventories
     *
     * @return \App\Models\Inventory
     */
    public function inventories()
    {
        return Inventory::orderBy('created_at')->get();
    }

    /**
     * Get sum of available stock
     *
     * @return \App\Models\Inventory
     */
    public function stock()
    {
        return Inventory::sum('quantity');
    }

    /**
     * Check if stock is available
     *
     * @return boolean
     */
    public function inStock() 
    {
        return $this->stock() > 0;
    }

    /**
     * Check if request quantity is more than available stock
     *
     * @param int $quantity
     * @return boolean
     */
    public function isMoreThanAvailableStock($quantity)
    {
        return $quantity > $this->stock();
    }

    /**
     * Calculate the stocks on hand valuation
     *
     * @param int $valuation
     * @param int $quantity
     * @param double $unitPrice
     * @return 
     */
    public function calculateValuation($valuation, $quantity, $unitPrice)
    {
        return bcadd($valuation, bcmul($quantity, $unitPrice, 2), 2);
    } 
}