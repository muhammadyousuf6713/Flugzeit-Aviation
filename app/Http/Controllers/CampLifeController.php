<?php
namespace App\Http\Controllers;

use App\Models\CampLife;
use App\Models\CampLifeDetail;
use Illuminate\Http\Request;

class CampLifeController extends Controller
{
    public function index()
    {
        $campus_life = CampLife::all();

        return view('camp_life.index', compact('campus_life'));
    }

    public function create()
    {
        return view('camp_life.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            // Validate image file (optional)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $data = $request->all();

        // Check and store the uploaded image
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('campus_life_images'), $imageName);
            $data['image'] = $imageName;
        }

        CampLife::create($data);

        return redirect()->route('campus-life.list')
            ->with('success', 'Campus Life created successfully.');
    }

    public function edit($id)
    {
        $CampLife = CampLife::find($id);
        // dd($academicProgramCategory);
        return view('camp_life.edit', compact('CampLife'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $CampLife = CampLife::find($id);
        if ($request->hasFile('image')) {
            if ($CampLife->image && file_exists(public_path('campus_life_images/' . $CampLife->image))) {
                unlink(public_path('campus_life_images/' . $CampLife->image));
            }
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('campus_life_images'), $imageName);
            $data['image'] = $imageName;
        } else {
            $data['image'] = $CampLife->image;
        }
        $CampLife->update($request->only(['name', 'title', 'description']));

        return redirect()->route('campus-life.list')
            ->with('info', 'Campus Life updated successfully.');
    }

    public function destroy($id)
    {
        CampLife::find($id)->delete();

        return redirect()->route('campus-life.list')
            ->with('danger', 'Campus Life deleted successfully.');
    }

    public function index_detail()
    {
        $details = CampLifeDetail::all();
        return view('campus_life_detail.index', compact('details'));
    }

    // Show the form to create a new detail record
    public function create_detail()
    {
        $campusLives = CampLife::all();
        return view('campus_life_detail.create', compact('campusLives'));
    }

    // Store a new detail record
    public function store_detail(Request $request)
    {
        $request->validate([
            'campus_life_id' => 'required|exists:campus_life,id',
            'name'           => 'required',
            'title'          => 'required',
            'description'    => 'required',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        // Check and store the uploaded image
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('campus_life_images'), $imageName);
            $data['image'] = $imageName;
        }

        CampLifeDetail::create($data);

        return redirect()->route('campus-life-detail.list')
            ->with('success', 'Campus Life Detail created successfully.');
    }

    // Show the form to edit an existing record
    public function edit_detail($id)
    {
        $detail      = CampLifeDetail::findOrFail($id);
        $campusLives = CampLife::all();
        return view('campus_life_detail.edit', compact('detail', 'campusLives'));
    }

    // Update an existing detail record
    public function update_detail(Request $request, $id)
    {
        $request->validate([
            'campus_life_id' => 'required|exists:campus_life,id',
            'name'           => 'required',
            'title'          => 'required',
            'description'    => 'required',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $detail = CampLifeDetail::findOrFail($id);
        $data   = $request->all();

        // If a new image is uploaded, delete the old one and store the new file
        if ($request->hasFile('image')) {
            if ($detail->image && file_exists(public_path('campus_life_images/' . $detail->image))) {
                unlink(public_path('campus_life_images/' . $detail->image));
            }
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('campus_life_images'), $imageName);
            $data['image'] = $imageName;
        } else {
            $data['image'] = $detail->image;
        }

        $detail->update($data);

        return redirect()->route('campus-life-detail.list')
            ->with('success', 'Campus Life Detail updated successfully.');
    }

    // Delete a detail record
    public function destroy_detail($id)
    {
        $detail = CampLifeDetail::findOrFail($id);
        if ($detail->image && file_exists(public_path('campus_life_images/' . $detail->image))) {
            unlink(public_path('campus_life_images/' . $detail->image));
        }
        $detail->delete();
        return redirect()->route('campus-life-detail.list')
            ->with('success', 'Campus Life Detail deleted successfully.');
    }
}
