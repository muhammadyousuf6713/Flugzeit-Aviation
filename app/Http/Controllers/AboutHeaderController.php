<?php
namespace App\Http\Controllers;

use App\Models\AboutHeader;
use Illuminate\Http\Request;

class AboutHeaderController extends Controller
{
    public function index()
    {
        // Eager load details if needed
        $headers = AboutHeader::with('details')->get();
        return view('about.header.index', compact('headers'));
    }

    public function create()
    {
        return view('about.header.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('about_images'), $imageName);
            $data['image'] = $imageName;
        }
        // dd($data);
        AboutHeader::create($data);
        return redirect()->route('header.index')->with('success', 'Header created successfully.');
    }

    public function show(AboutHeader $header)
    {
        return view('about.header.show', compact('header'));
    }

    public function edit(AboutHeader $header)
    {
        return view('about.header.edit', compact('header'));
    }

    public function update(Request $request, AboutHeader $header)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('about_images'), $imageName);
            $data['image'] = $imageName;
        }

        $header->update($data);
        return redirect()->route('header.index')->with('success', 'Header updated successfully.');
    }

    public function destroy(AboutHeader $header)
    {
        $header->delete();
        return redirect()->route('header.index')->with('success', 'Header deleted successfully.');
    }
}
