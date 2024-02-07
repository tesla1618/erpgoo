<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Vendor;

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
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company_details' => 'required|string',
            'attachment' => 'nullable|file',
            'paid' => 'boolean',
            'due' => 'nullable|date',
            // Add more validation rules for other fields as needed
        ]);

        // Update the vendor
        $vendor->update($validatedData);

        // Redirect to the vendors index page with a success message
        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    // Delete the specified vendor from the database
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        // Redirect to the vendors index page with a success message
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
