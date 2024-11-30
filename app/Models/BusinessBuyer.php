<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessBuyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'company_name',
        'location',
        'contact_details',
    ];

    // Define the relationship with the Business model
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
