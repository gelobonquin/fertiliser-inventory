<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
{

    /**
     * Get the valuation of applied purchase
     *
     * @param int $quantity
     * @return float
     */
    public function getValuation($quantity)
    {
        $availablePurchases = $this->getPurchasesWithRemainingQuantity();
        $appliedPurchases = [];

        // Check the purchases against the applied quantity
        // and store in the applied purchases array
        foreach ($availablePurchases as $purchase) {
            
            $fulfilledQuantity = $purchase->quantity;

            if ($quantity > 0) {

                if ($purchase->quantity > $quantity) {
                    $fulfilledQuantity = $quantity;
                }
    
                $quantity -= $fulfilledQuantity;
                $purchase->quantity = $fulfilledQuantity;
    
                $appliedPurchases[] = $purchase;
            }
        }

        // Calculate the valuation of applied purchases
        return $this->calculateValuation($appliedPurchases);
    } 

    /**
     * Get the purchase with remaining quantity 
     *
     * @return array
     */
    public function getPurchasesWithRemainingQuantity()
    {
        // Convert to positive value
        $totalApplication = abs($this->getTotalApplications());

        $purchasesWithRemainingQuantity = [];

        foreach ($this->getPurchases() as $purchase) {
      
            // Decrease the total application
            if ($totalApplication > 0) {
                $totalApplication -= $purchase->quantity;
                
                // If the total application is zero or  negative value, decrease the purchase quantity
                // and store in purchases with remaining quantity array
                if ($totalApplication < 0) {
                    $purchase->quantity = abs($totalApplication);
                    $purchasesWithRemainingQuantity[] =  $purchase;
                }
            } else {                
                $purchasesWithRemainingQuantity[] =  $purchase;
            }
        }

        return $purchasesWithRemainingQuantity;
    }

    /**
     * Get inventory purchases
     *
     * @return \App\Models\Inventory
     */
    public function getPurchases()
    {
        return Inventory::where('type', Inventory::TYPE_PURCHASE)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get total inventories application
     *
     * @return float
     */
    public function getTotalApplications()
    {
        return Inventory::where('type', Inventory::TYPE_APPLICATION)
            ->sum('quantity');
    }

    /**
     * Calculate the valuation
     *
     * @param array $items
     * @return float
     */
    public function calculateValuation($items)
    {
        return collect($items)->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }

}
