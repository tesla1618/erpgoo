<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'agent_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:255',
            'visa_type' => 'required|string|max:255',
        ]);

        // Insert data into the database
        DB::table('agents')->insert([
            'agent_name' => $validatedData['agent_name'],
            'passport_number' => $validatedData['passport_number'],
            'visa_type' => $validatedData['visa_type'],
        ]);

        // Redirect back to the previous page or any other page
        return redirect()->back()->with('success', 'Agent created successfully!');
    }
}
