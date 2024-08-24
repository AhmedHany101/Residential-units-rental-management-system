<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservation_data extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(customer_info::class);
    }
 
}
