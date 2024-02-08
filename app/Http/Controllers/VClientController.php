<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VClient;

class VClientController extends Controller
{
    // Show all VClients
    public function index()
    {
        $vclients = VClient::all();
        return view('vclients.index', ['vClients' => $vclients]);
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
        return redirect()->back()->with('success', 'Client created successfully!');

        // return redirect()->route('vclients.index')->with('success', 'VClient created successfully.');
    }

    // Show the form to edit a VClient
    public function edit(VClient $vclient)
    {
        return view('vclients.edit', ['vclient' => $vclient]);
    }

    // Update the specified VClient in the database
    public function update(Request $request, VClient $vclient)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'passport_no' => 'nullable|string|max:255',
            'visa_type' => 'nullable|string|max:255',
            'amount_paid_new' => 'nullable|numeric|min:0',
            'amount_due' => 'nullable|numeric|min:0',
            'isTicket' => 'boolean',
            'status' => 'string|max:255',
            'attachment' => 'nullable|file',
            'agent_id' => 'exists:agents,id|nullable',
            'vendor_id' => 'exists:vendors,id|nullable',
            // Add more validation rules for other fields as needed
        ]);
        if ($request->hasFile('attachment')) {
            // Get the uploaded file
            $file = $request->file('attachment');
            
            // Store the file data in the database
            $fileData = file_get_contents($file->getRealPath()); // Get the binary data of the file
            $validatedData['attachment'] = $fileData;
        } else {
            // If no file is uploaded, remove the attachment key from the validated data
            unset($validatedData['attachment']);
        }

        $validatedData['amount_paid'] = $vclient->amount_paid + $validatedData['amount_paid_new'];


        // Update the VClient
        $vclient->update($validatedData);

        // Redirect to the VClients index page with a success message
        
        return redirect()->back()->with('success', 'Client updated successfully!');

        // return redirect()->route('vclients.index')->with('success', 'VClient updated successfully.');
    }

    // Delete the specified VClient from the database
    public function destroy(VClient $vclient)
    {
        $vclient->delete();

        // Redirect to the VClients index page with a success message
        return redirect()->back()->with('success', 'Client deleted successfully!');

        // return redirect()->route('vclients.index')->with('success', 'VClient deleted successfully.');
    }
}
