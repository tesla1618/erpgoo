<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VClient extends Model
{
    // Define the table associated with the model
    protected $table = 'clients';

    // Fillable fields for mass assignment
    protected $fillable = [
        'client_name', 'passport_no', 'visa_type', 'amount_paid', 'amount_due', 'isTicket', 'status', 'attachment', 'agent_id', 'vendor_id', // Add more fields as needed
    ];

    // Guarded fields to prevent mass assignment
    // protected $guarded = [];

    // Cast attributes to appropriate types
    protected $casts = [
        'isTicket' => 'boolean', // Cast 'isTicket' attribute to boolean
    ];

    // Relationships
    // Example: a VClient belongs to an agent
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Example: a VClient belongs to a vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
