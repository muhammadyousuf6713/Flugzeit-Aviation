<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionCategory;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $Admission = Admission::with('categories')->get();

        return view('admission.index', compact('Admission'));
    }

    public function create()
    {
        return view('admission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        Admission::create($request->all());

        return redirect()->route('admission.list')
            ->with('success', 'Admission created successfully.');
    }

    public function edit($id)
    {
        $academicProgram = Admission::find($id);
        $academicProgramCategory = AdmissionCategory::where('ah_id', $academicProgram->id)->get();
        // dd($academicProgramCategory);
        return view('admission.edit', compact('academicProgram', 'academicProgramCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $academicProgram = Admission::find($id);
        $academicProgram->update($request->only(['name', 'title', 'description']));

        if ($request->has('categories')) {
            foreach ($request->input('categories') as $categoryId => $categoryData) {
                $category = AdmissionCategory::find($categoryId);
                if ($category) {
                    $category->update([
                        'name' => $categoryData['name'],
                        'description' => $categoryData['description'],
                    ]);
                }
            }
        }

        return redirect()->route('admission.list')
            ->with('info', 'Admission updated successfully.');
    }




    public function cat_create()
    {
        $academicPrograms = Admission::all();
        return view('admission_categories.create', compact('academicPrograms'));
    }


    public function cat_store(Request $request)
    {
        $request->validate([
            'ah_id' => 'required|exists:ap_header,id',
            'name' => 'required',
            'description' => 'required',
        ]);

        AdmissionCategory::create([
            'ah_id' => $request->ah_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admission.list')
            ->with('success', 'Admission Category created successfully.');
    }

    public function destroy($id)
    {
        Admission::find($id)->delete();
        AdmissionCategory::where('ah_id', $id)->delete();

        return redirect()->route('admission.list')
            ->with('danger', 'Admission deleted successfully.');
    }
}
