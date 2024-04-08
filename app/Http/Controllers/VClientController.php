<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use App\Models\VClient;

class VClientController extends Controller
{

    function generateRandomString($length = 6) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = 'CLT'; // Start with "CLT"
        $randomString .= $characters[rand(10, 35)]; 
        for ($i = 1; $i < $length; $i++) { 
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        // for ($i = 0; $i < 2; $i++) { 
        //     $randomString .= $characters[rand(10, 35)];
        // }
        return $randomString;
    }
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

        $uniqueCode = $this->generateRandomString();
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
            'visa_country_id' => 'exists:countries,id|nullable',
            // Add more validation rules for other fields as needed
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
            $validatedData['attachment'] = $filePath;
        
            // Generate the URL for the uploaded file
            $fileUrl = Storage::disk('public')->url($filePath);
        
            // Use $fileUrl as needed
        }else {
            // If no file is uploaded, remove the attachment key from the validated data
            unset($validatedData['attachment']);
        }

        


        // Create the VClient
        // VClient::create($validatedData);
        // check if passport_no already exists

        $passport_no = $validatedData['passport_no'];
        $passport_no_exists = VClient::where('passport_no', $passport_no)->first();
        if ($passport_no_exists) {
            return redirect()->back()->with('error', 'Client already exists!');
        }

        else {
            DB::table('clients')->insert([
                'client_name' => $validatedData['client_name'],
                'passport_no' => $validatedData['passport_no'],
                'visa_type' => $validatedData['visa_type'],
                'visa_country_id' => $validatedData['visa_country_id'],
                'unique_code' => $uniqueCode,
                'agent_id' => $validatedData['agent_id'],
                'vendor_id' => $validatedData['vendor_id'],
                'isTicket' => $validatedData['isTicket'],
            ]);
            return redirect()->back()->with('success', 'Client created successfully!');
        }


        // Redirect to the VClients index page with a success message

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
            'isTicket' => 'boolean|nullable',
            'status' => 'string|max:255|nullable',
            'attachment' => 'nullable|file',
            'attachment2' => 'nullable|file',
            'attachmen3' => 'nullable|file',
            'attachment4' => 'nullable|file',
            'agent_id' => 'exists:agents,id|nullable',
            'vendor_id' => 'exists:vendors,id|nullable',
            'visa_country_id' => 'exists:countries,id|nullable',
            'unit_price' => 'nullable|numeric|min:0',
            'refund' => 'nullable|numeric|min:0',
            // Add more validation rules for other fields as needed
        ]);
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
            $validatedData['attachment'] = $filePath;
        
            // Generate the URL for the uploaded file
            $fileUrl = Storage::disk('public')->url($filePath);
        
            // Use $fileUrl as needed
        }else {
            // If no file is uploaded, remove the attachment key from the validated data
            unset($validatedData['attachment2']);
        }
        if ($request->hasFile('attachment2')) {
            $file = $request->file('attachment2');
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
            $validatedData['attachment2'] = $filePath;
        
            // Generate the URL for the uploaded file
            $fileUrl = Storage::disk('public')->url($filePath);
        
            // Use $fileUrl as needed
        }else {
            // If no file is uploaded, remove the attachment key from the validated data
            unset($validatedData['attachment2']);
        }
        if ($request->hasFile('attachmen3')) {
            $file = $request->file('attachmen3');
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
            $validatedData['attachmen3'] = $filePath;
        
            // Generate the URL for the uploaded file
            $fileUrl = Storage::disk('public')->url($filePath);
        
            // Use $fileUrl as needed
        }else {
            // If no file is uploaded, remove the attachment key from the validated data
            unset($validatedData['attachmen3']);
        }
        if ($request->hasFile('attachment4')) {
            $file = $request->file('attachment4');
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
            $validatedData['attachment4'] = $filePath;
        
            // Generate the URL for the uploaded file
            $fileUrl = Storage::disk('public')->url($filePath);
        
            // Use $fileUrl as needed
        }else {
            // If no file is uploaded, remove the attachment4 key from the validated data
            unset($validatedData['attachment4']);
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
