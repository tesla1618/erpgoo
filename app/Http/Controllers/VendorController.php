<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;

class VendorController extends Controller
{
    // Show all vendors
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendors.index', ['vendors' => $vendors]);
    }

    // Show the form to create a new vendor
    public function create()
    {
        return view('vendors.create');
    }

    // Store a newly created vendor in the database
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'company_details' => 'required|string|max:255',
            'visa_type' => 'required|string|max:255',
        ]);

        // Insert data into the database
        DB::table('vendors')->insert([
            'vendor_name' => $validatedData['vendor_name'],
            'company_details' => $validatedData['company_details'],
            'visa_type' => $validatedData['visa_type'],
        ]);

        // Redirect back to the previous page or any other page
        return redirect()->back()->with('success', 'Vendor created successfully!');
        
    }

    // Show the form to edit a vendor
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', ['vendor' => $vendor]);
    }

    // Update the specified vendor in the database
    public function update(Request $request, Vendor $vendor)
    {
        \Log::debug($request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'company_details' => 'required|string',
            'attachment' => 'nullable|file',
            'amount_paid_new' => 'nullable|numeric|min:0',
        'amount_due' => 'nullable|numeric|min:0',
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

        $validatedData['amount_paid'] = $vendor->amount_paid + $validatedData['amount_paid_new'];

        // Update the vendor
        $vendor->update($validatedData);

        // Redirect to the vendors index page with a success message
        return redirect()->back()->with('success', 'Vendor updated successfully!');
    
    }

    // Delete the specified vendor from the database
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        // Redirect to the vendors index page with a success message
        return redirect()->back()->with('success', 'Vendor deleted successfully!');

    }
}
