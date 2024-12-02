<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessBuyer extends Model
{
    use HasFactory;
    use SoftDeletes;

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
