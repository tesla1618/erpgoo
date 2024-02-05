<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    // Define the table associated with the model
    protected $table = 'vendors';

    // Fillable fields for mass assignment
    protected $fillable = [
        'name', 'company_details', 'attachment', 'paid', 'due', // Add more fields as needed
    ];

    // Guarded fields to prevent mass assignment
    // protected $guarded = [];

    // Cast attributes to appropriate types
    protected $casts = [
        'paid' => 'boolean', // Cast 'paid' attribute to boolean
    ];

    // Relationships
    // Example: a vendor may have many clients
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
