<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_info extends Model
{
    use HasFactory;
    // In the App\Models\customer_info.php model
    public function reservation_data()
    {
        return $this->hasMany(reservation_data::class, 'customer_id');
    }
}
