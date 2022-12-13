<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    const TYPE_APPLICATION = 'Application'; 
    
    const TYPE_PURCHASE = 'Purchase';

    protected $fillable = ['type', 'quantity', 'unit_price'];
    
}
