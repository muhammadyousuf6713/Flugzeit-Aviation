<?php
namespace App\Http\Controllers;

use App\Models\AboutDetail;
use App\Models\AboutHeader;
use Illuminate\Http\Request;

class AboutDetailController extends Controller
{
    // Display a listing of details for a given header.
    public function index($id)
    {
        $header  = AboutHeader::with('details')->findOrFail($id);
        $details = $header->details;
        return view('about.detail.index', compact('header', 'details'));
    }

    // Show the form for creating a new detail.
    public function create($id)
    {
        $header = AboutHeader::findOrFail($id);
        return view('about.detail.create', compact('header'));
    }

    // Store a newly created detail in storage.
    public function store(Request $request, $id)
    {
        $header = AboutHeader::findOrFail($id);
        $request->validate([
            'title'     => 'required|string|max:255',
            'name'     => 'nullable|string|max:255',
            'image'     => 'nullable|image|max:2048',
            'detail'    => 'nullable|string',
            'description'    => 'nullable|string',
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('about_images'), $imageName);
            $data['image'] = $imageName;
        }

        $detail = new AboutDetail($data);
        $header->details()->save($detail);

        return redirect()->route('detail.index', $header->id)
            ->with('success', 'Detail added successfully.');
    }

    // Display the specified detail.
    public function show($id, $detail)
    {
        $header = AboutHeader::findOrFail($id);
        $detail = AboutDetail::findOrFail($detail);
        return view('about.detail.show', compact('header', 'detail'));
    }

    // Show the form for editing the specified detail.
    public function edit($id, $detail)
    {
        $header = AboutHeader::findOrFail($id);
        $detail = AboutDetail::findOrFail($detail);
        return view('about.detail.edit', compact('header', 'detail'));
    }

    // Update the specified detail in storage.
    public function update(Request $request, $id, $detail)
    {
        $header = AboutHeader::findOrFail($id);
        $detail = AboutDetail::findOrFail($detail);

        $request->validate([
            'title'     => 'required|string|max:255',
            'name'     => 'nullable|string|max:255',
            'image'     => 'nullable|image|max:2048',
            'detail'    => 'nullable|string',
            'description'    => 'nullable|string',
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('about_images'), $imageName);
            $data['image'] = $imageName;
        }

        $detail->update($data);

        return redirect()->route('detail.index', $header->id)
            ->with('success', 'Detail updated successfully.');
    }

    // Remove the specified detail from storage.
    public function destroy($id, $detail)
    {
        $header = AboutHeader::findOrFail($id);
        $detail = AboutDetail::findOrFail($detail);
        $detail->delete();

        return redirect()->route('detail.index', $header->id)
            ->with('success', 'Detail deleted successfully.');
    }
}
