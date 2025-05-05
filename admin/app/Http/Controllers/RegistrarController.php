<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrarModel; // Import the model
use App\Models\DocumentRequest; // Import DocumentRequest model if you're using it for requests
use App\Models\PickUpRequest; // Import PickUpRequest model if you're using it for requests

class RegistrarController extends Controller
{


// Fetch the total documents for dashboard
public function dashboard()
{
    // Fetch total number of documents in the documents table
    $total_documents = RegistrarModel::count() ?: 0;

    // Fetch total number of pickup requests
    $total_requests = PickUpRequest::count() ?: 0;

    // Log counts (optional)
    \Log::info("Total Documents: " . $total_documents);
    \Log::info("Total Pickup Requests: " . $total_requests);

    // Return the view with both counts
    return view('registrar.dashboard', compact('total_documents', 'total_requests'));
}



    public function index()
    {
        // Fetch all documents
        $documents = RegistrarModel::paginate(10);
        return view('registrar.documents', compact('documents'));
    }

    public function create()
    {
        $documents = RegistrarModel::all();
        return view('registrar.addDocument', compact('documents'));
    }

    // Store new document
    public function store(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'document_name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
        ]);

        // Create the new document record
        RegistrarModel::create($request->all());

        // Redirect back to the documents list with success message
        return redirect()->route('documents')->with('success', 'Document created successfully.');
    }

    // Edit document
    public function edit($id)
    {
        $document = RegistrarModel::findOrFail($id);
        return view('registrar.editDocument', compact('document'));
    }

    // Update document
    public function update(Request $request, $id)
    {
        // Validate the input fields
        $request->validate([
            'document_name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
        ]);

        // Find and update the document record
        $document = RegistrarModel::findOrFail($id);
        $document->update($request->all());

        // Redirect back to the documents list with success message
        return redirect()->route('documents')->with('success', 'Document updated successfully.');
    }

    // Delete document
    public function destroy($id)
    {
        // Find the document and delete it
        $document = RegistrarModel::findOrFail($id);
        $document->delete();

        // Redirect back to the documents list with success message
        return redirect()->route('documents')->with('success', 'Document deleted successfully.');
    }
}
