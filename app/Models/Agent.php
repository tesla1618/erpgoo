<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    // Define the table associated with the model
    protected $table = 'agents';

    // Fillable fields for mass assignment
    protected $fillable = [
        'agent_name', // Add more fields as needed
    ];

    // Guarded fields to prevent mass assignment
    // protected $guarded = [];

    // Relationships
    // Example: an agent may have many clients
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
