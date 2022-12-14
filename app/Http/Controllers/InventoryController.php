<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Services\InventoryService;
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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(InventoryRequest $request)
    {                                

        $valuation = $this->inventoryService->getValuation($request->get('quantity'));

        return response()->json([
            'success' => true,
            'message' => "{$request->get('quantity')} unit applied at $".$valuation."",
        ]);
        
    }
}
