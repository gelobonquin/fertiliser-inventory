<?php

namespace App\Http\Controllers;

use App\Actions\CreateInventoryApplication;
use App\Http\Requests\InventoryRequest;
use App\Http\Services\InventoryService;
use Exception;

class InventoryController extends Controller
{
    protected $inventoryService;
        
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;    
    }

    /**
     * Request stock available valuation
     *
     * @param \App\Http\Requests\InventoryRequest $request
     * @param \Actions\CreateInventoryApplication $createInventoryApplication
     * @return \Illuminate\Http\Response
     */
    public function __invoke(InventoryRequest $request, CreateInventoryApplication $createInventoryApplication)
    {        
        try {

            if ($this->inventoryService->isMoreThanAvailableStock($request->get('quantity'))) {
                throw new Exception('Requested quantity is more than the total available stock.', 400);
            }
            
            if (! $this->inventoryService->inStock()) {
                throw new Exception('Oops, no available stock on hand', 400);
            }            
            
            $createInventoryApplication->handle($request->get('quantity'));

            $valuation = $this->inventoryService->process($request->get('quantity'));
      
            return response()->json([
                'success' => true,
                'message' => "{$request->get('quantity')} unit applied at $".$valuation.".",
                'remaining_quantity' => $this->inventoryService->stock()
            ]);

        } catch (Exception $e) {
            $statusCode = $e->getCode() ? $e->getCode() : 500;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'remaining_quantity' => $this->inventoryService->stock(),
            ], $statusCode);            
        }        
    }
}
