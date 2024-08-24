<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paper_info extends Model
{
    use HasFactory;
    protected $fillable=[
        'customer_id',

        'id_face',
        'id_back',
        'passport',
        'marriage_certificate',
        ];
}
