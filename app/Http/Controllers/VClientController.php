<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VClient;

class VClientController extends Controller
{
    // Show all VClients
    public function index()
    {
        $vClients = VClient::all();
        return view('vclients.index', ['vClients' => $vClients]);
    }

    // Show the form to create a new VClient
    public function create()
    {
        return view('vclients.create');
    }

    // Store a newly created VClient in the database
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'passport_no' => 'nullable|string|max:255',
            'visa_type' => 'nullable|string|max:255',
            'amount_paid' => 'numeric|min:0',
            'amount_due' => 'numeric|min:0',
            'isTicket' => 'boolean',
            'status' => 'string|max:255',
            'attachment' => 'nullable|file',
            'agent_id' => 'exists:agents,id|nullable',
            'vendor_id' => 'exists:vendors,id|nullable',
            // Add more validation rules for other fields as needed
        ]);

        // Create the VClient
        VClient::create($validatedData);

        // Redirect to the VClients index page with a success message
        return redirect()->route('vclients.index')->with('success', 'VClient created successfully.');
    }

    // Show the form to edit a VClient
    public function edit(VClient $vClient)
    {
        return view('vclients.edit', ['vClient' => $vClient]);
    }

    // Update the specified VClient in the database
    public function update(Request $request, VClient $vClient)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'passport_no' => 'nullable|string|max:255',
            'visa_type' => 'nullable|string|max:255',
            'amount_paid' => 'numeric|min:0',
            'amount_due' => 'numeric|min:0',
            'isTicket' => 'boolean',
            'status' => 'string|max:255',
            'attachment' => 'nullable|file',
            'agent_id' => 'exists:agents,id|nullable',
            'vendor_id' => 'exists:vendors,id|nullable',
            // Add more validation rules for other fields as needed
        ]);

        // Update the VClient
        $vClient->update($validatedData);

        // Redirect to the VClients index page with a success message
        return redirect()->route('vclients.index')->with('success', 'VClient updated successfully.');
    }

    // Delete the specified VClient from the database
    public function destroy(VClient $vClient)
    {
        $vClient->delete();

        // Redirect to the VClients index page with a success message
        return redirect()->route('vclients.index')->with('success', 'VClient deleted successfully.');
    }
}
