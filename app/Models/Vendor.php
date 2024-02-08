<?php

// namespace App;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // Define the table associated with the model
    protected $table = 'vendors';

    // Fillable fields for mass assignment
    protected $fillable = ['vendor_name', 'company_details', 'attachment', 'amount_paid', 'amount_due'];


    // Guarded fields to prevent mass assignment
    // protected $guarded = [];

    // Cast attributes to appropriate types
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'amount_due' => 'decimal:2',
    ];

    // Relationships
    // Example: a vendor may have many clients
    public function clients()
    {
        return $this->hasMany(VClient::class);
    }
}
