<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\owner;
class apartness extends Model
{
    use HasFactory;

    public function owner()
    {
        return $this->belongsTo(owner::class);
    }

   
}
