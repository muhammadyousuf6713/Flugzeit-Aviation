<?php
namespace App\Http\Controllers;

use App\Models\AcademicProgram;
use App\Models\AcademicProgramCategory;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    public function index()
    {
        $academicPrograms = AcademicProgram::with('categories')->get();

        return view('academic_programs.index', compact('academicPrograms'));
    }

    public function create()
    {
        return view('academic_programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'title'       => 'required',
            'description' => 'required',
        ]);

        AcademicProgram::create($request->all());

        return redirect()->route('academic-program.list')
            ->with('success', 'Academic Program created successfully.');
    }

    public function edit($id)
    {
        $academicProgram = AcademicProgram::find($id);
        $academicProgramCategory = AcademicProgramCategory::where('aph_id', $academicProgram->id)->get();
        return view('academic_programs.edit', compact('academicProgram', 'academicProgramCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required',
            'title'       => 'required',
            'description' => 'required',
        ]);

        $academicProgram = AcademicProgram::find($id);
        $academicProgram->update($request->only(['name', 'title', 'description']));

        if ($request->has('categories')) {
            foreach ($request->input('categories') as $categoryId => $categoryData) {
                $category = AcademicProgramCategory::find($categoryId);
                if ($category) {
                    $updateData = [
                        'name'                 => $categoryData['name'] ?? $category->name,
                        'description'          => $categoryData['description'] ?? $category->description,
                        'approach'             => $categoryData['approach'] ?? $category->approach,
                        'enriching_experience' => $categoryData['enriching_experience'] ?? $category->enriching_experience,
                        'ultimate_goal'        => $categoryData['ultimate_goal'] ?? $category->ultimate_goal,
                        'eligibility'          => $categoryData['eligibility'] ?? $category->eligibility,
                        'career'               => $categoryData['career'] ?? $category->career,
                        'cta'                  => $categoryData['cta'] ?? $category->cta,
                    ];
                    $category->update($updateData);
                }
            }
        }

        return redirect()->route('academic-program.list')
            ->with('info', 'Academic Program updated successfully.');
    }
    public function cat_create()
    {
        $academicPrograms = AcademicProgram::all();
        return view('academic_program_categories.create', compact('academicPrograms'));
    }

    public function cat_store(Request $request)
    {
        $request->validate([
            'aph_id'               => 'required|exists:ap_header,id',
            'name'                 => 'required',
            'description'          => 'required',
            'approach'             => 'nullable',
            'enriching_experience' => 'nullable',
            'ultimate_goal'        => 'nullable',
            'career'               => 'nullable',
            'cta.startText'        => 'nullable',
            'cta.enrollText'       => 'nullable',
            // No explicit validation for eligibility since it's coming as an array of inputs.
        ]);

        // Get the eligibility array; if none provided, it will default to null.
        $eligibility = $request->input('eligibility', null);
        // dd($request);
        AcademicProgramCategory::create([
            'aph_id'               => $request->aph_id,
            'name'                 => $request->name,
            'description'          => $request->description,
            'approach'             => $request->approach,
            'enriching_experience' => $request->enriching_experience,
            'ultimate_goal'        => $request->ultimate_goal,
            'eligibility'          => $eligibility, // Stored as JSON
            'career'               => $request->career,
            'cta'                  => $request->cta, // Ensure this is sent as an array from the form.
        ]);

        return redirect()->route('academic-program.list')
            ->with('success', 'Academic Program Category created successfully.');
    }

    public function destroy($id)
    {
        AcademicProgram::find($id)->delete();
        AcademicProgramCategory::where('aph_id', $id)->delete();

        return redirect()->route('academic-program.list')
            ->with('danger', 'Academic Program deleted successfully.');
    }
}
