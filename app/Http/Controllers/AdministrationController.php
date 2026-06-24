<?php

namespace App\Http\Controllers;

use App\Models\AdministrationHeader;
use App\Models\AdministrationDetail;
use App\Models\AdministrationContact;
use App\Models\AdministrationAuthor;
use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    public function index()
    {
        // Fetch all the headers with details, contacts, and authors
        $headers = AdministrationHeader::with('details.contacts', 'authors')->get();
        // dd($headers);
        return view('administration.index', compact('headers'));
    }

    // Separate create views
    public function createHeader()
    {
        return view('administration.create');
    }

    public function createDetail()
    {

        return view('administration.create-detail');
    }

    public function createContact()
    {

        $id = request()->query('id');

        if ($id) {
            $detail = AdministrationDetail::where('id', $id)->first();
        } else {
            $detail = AdministrationDetail::orderBy('id', 'desc')->get(); // Use get() for a collection when no $id
        }

        // dd($header);

        return view('administration.create-contact', compact('id', 'detail'));
    }

    public function createAuthor()
    {
        $id = request()->query('id');

        if ($id) {
            $header = AdministrationHeader::where('id', $id)->first();
        } else {
            $header = AdministrationHeader::orderBy('id', 'desc')->get(); // Use get() for a collection when no $id
        }
        // dd($header);
        return view('administration.create-author', compact('id', 'header'));
    }
    public function storeHeader(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        AdministrationHeader::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('administration.create-detail');
    }

    public function storeDetail(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
        ]);

        $ah_id = AdministrationHeader::orderBy('id', 'desc')->pluck('id')->first();
        AdministrationDetail::create([
            'ah_id' => $ah_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('administration.create-contact');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'nullable',
            'email' => 'nullable',
        ]);
        // dd($request->all());
        AdministrationContact::create([
            'ad_id' =>  $request->ad_id,
            'name' => $request->name,
            'number' => $request->number,
            'email' => $request->email,
        ]);

        return redirect()->route('administration.create-author');
    }

    public function storeAuthor(Request $request)
    {
        // Validate the request data
        $request->validate([
            'ah_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'depart_name' => 'nullable|string|max:255',
        ]);
        // dd($request->all());


        // Handle file upload for the image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate a short random code for the file name
            $randomCode = substr(bin2hex(random_bytes(8)), 0, 10);

            // Build the new file name
            $fileName = 'author_image_' . $randomCode . '.' . $file->getClientOriginalExtension();

            // Save the file to the public/author_image directory
            $file->move(public_path('author_image'), $fileName);
            // Store the path in the database (relative path)
            $imagePath =  $fileName;
        } else {
            $imagePath = null; // Handle the case where no file is uploaded
        }

        // Create a new author record
        AdministrationAuthor::create([
            'ah_id' => $request->ah_id,
            'image' => $imagePath,
            'name' => $request->name,
            'about' => $request->about,
            'number' => $request->number,
            'email' => $request->email,
            'address' => $request->address,
            'description' => $request->description,
            'depart_name' => $request->depart_name,
        ]);

        return redirect()->route('administration.index')->with('success', 'Author added successfully!');
    }



    // Edit the full record in a single page
    public function edit($id)
    {
        $header = AdministrationHeader::with(['details.contacts', 'authors'])->findOrFail($id);
        // dd( $header);
        return view('administration.edit', compact('header'));
    }
    public function edit_author($id)
    {
        $author = AdministrationAuthor::findOrFail($id);

        return view('administration.edit-author', compact('author'));
    }
    public function edit_detail($id)
    {
        $detail = AdministrationDetail::findOrFail($id);

        return view('administration.edit-detail', compact('detail'));
    }
    public function edit_contact($id)
    {
        $contact = AdministrationContact::findOrFail($id);

        return view('administration.edit-contact', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'contact_name' => 'required',
            'contact_number' => 'nullable',
            'contact_email' => 'nullable',
            'author_name' => 'required',
            'author_email' => 'nullable',
        ]);

        // Update AdministrationHeader
        $header = AdministrationHeader::findOrFail($id);
        $header->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Update AdministrationDetail
        $detail = $header->details->first();
        $detail->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Update AdministrationContact
        $contact = $detail->contacts->first();
        $contact->update([
            'name' => $request->contact_name,
            'number' => $request->contact_number,
            'email' => $request->contact_email,
        ]);

        // Update AdministrationAuthor
        $author = $contact->authors->first();
        $author->update([
            'name' => $request->author_name,
            'about' => $request->about,
            'email' => $request->author_email,
        ]);

        return redirect()->route('administration.index');
    }

    public function update_author(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'about' => 'nullable|string',
            'number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'depart_name' => 'nullable|string|max:255',
        ]);

        // Update AdministrationHeader
        $author = AdministrationAuthor::findOrFail($id);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate a short random code for the file name
            $randomCode = substr(bin2hex(random_bytes(8)), 0, 10);

            // Build the new file name
            $fileName = 'author_image_' . $randomCode . '.' . $file->getClientOriginalExtension();

            // Save the file to the public/author_image directory
            $file->move(public_path('author_image'), $fileName);
            // Store the path in the database (relative path)
            $imagePath =  $fileName;
        } else {
            $imagePath = null; // Handle the case where no file is uploaded
        }

        $author->first();
        $author->update([
            'image' => $imagePath,
            'name' => $request->name,
            'about' => $request->about,
            'number' => $request->number,
            'email' => $request->email,
            'address' => $request->address,
            'description' => $request->description,
            'depart_name' => $request->depart_name,
        ]);

        return redirect()->route('administration.index');
    }

    public function update_detail(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
        ]);
        $detail = AdministrationDetail::findOrFail($id);
        $detail->first();
        $detail->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return redirect()->route('administration.index');
    }
    public function update_contact(Request $request, $id)
    {


        $request->validate([
            'name' => 'required',
            'number' => 'nullable',
            'email' => 'nullable',
        ]);
        $contact = AdministrationContact::findOrFail($id);
        $contact->first();
        $contact->update([
            'name' => $request->name,
            'number' => $request->number,
            'email' => $request->email,
        ]);
        return redirect()->route('administration.index');
    }

    public function destroy($id)
    {
        $header = AdministrationHeader::findOrFail($id);
        $header->details->each(function ($detail) {
            $detail->contacts->each(function ($contact) {
                $contact->authors->each(function ($author) {
                    $author->delete();
                });
                $contact->delete();
            });
            $detail->delete();
        });
        $header->delete();

        return redirect()->route('administration.index');
    }
    public function destroy_author($id)
    {
        $author = AdministrationAuthor::findOrFail($id); // Find the author by ID
        $author->delete(); // Delete the author

        return redirect()->route('administration.index')->with('success', 'Author deleted successfully!');
    }
    public function destroy_detail($id)
    {
        $detail = AdministrationDetail::findOrFail($id); // Find the author by ID
        $detail->delete(); // Delete the detail

        return redirect()->route('administration.index')->with('success', 'Administration Detail deleted successfully!');
    }
    public function destroy_contact($id)
    {
        $detail = AdministrationContact::findOrFail($id); // Find the author by ID
        $detail->delete(); // Delete the detail

        return redirect()->route('administration.index')->with('success', 'Contact deleted successfully!');
    }
}
