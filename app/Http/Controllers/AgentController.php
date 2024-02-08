<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agent;


class AgentController extends Controller
{

    public function index()
    {
        $agents = Agent::all();
        return view('agents.index', ['agents' => $agents]);
    }
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

    public function edit(Agent $agent)
    {
        return view('agents.edit', ['agent' => $agent]);
    }

    public function update(Request $request, Agent $agent)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'agent_name' => 'string|max:255',
            'passport_number' => 'string|max:255',
            'visa_type' => 'nullable|string|max:255',
            'amount_paid_new' => 'nullable|numeric|min:0',
            'amount_due' => 'nullable|numeric|min:0',
            'attachment' => 'nullable|file',

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

        $validatedData['amount_paid'] = $agent->amount_paid + $validatedData['amount_paid_new'];

        // Update the specified agent in the database
        $agent->update($validatedData);


        // Redirect back to the previous page or any other page
        return redirect()->back()->with('success', 'Agent updated successfully!');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        // Redirect to the VClients index page with a success message
        return redirect()->back()->with('success', 'Agent deleted successfully!');

        // return redirect()->route('vclients.index')->with('success', 'VClient deleted successfully.');
    }
}
