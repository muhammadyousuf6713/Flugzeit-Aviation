<?php

namespace App\Http\Controllers;

use App\Models\AcademicProgram;
use App\Models\AcademicProgramCategory;
use Illuminate\Http\Request;

class ApiAcademicController extends Controller
{
    public function index()
    {
        $academicPrograms = AcademicProgram::with('categories')->get();

        return response()->json([
            'status' => 'success',
            'data' => $academicPrograms,
        ], 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $academicProgram = AcademicProgram::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Academic Program created successfully.',
            'data' => $academicProgram,
        ], 201);
    }


    public function edit($id)
    {
        $academicProgram = AcademicProgram::with('categories')->find($id);

        if (!$academicProgram) {
            return response()->json([
                'status' => 'error',
                'message' => 'Academic Program not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $academicProgram,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $academicProgram = AcademicProgram::find($id);

        if (!$academicProgram) {
            return response()->json([
                'status' => 'error',
                'message' => 'Academic Program not found.',
            ], 404);
        }

        $academicProgram->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Academic Program updated successfully.',
            'data' => $academicProgram,
        ], 200);
    }

    public function destroy($id)
    {
        $academicProgram = AcademicProgram::find($id);

        if (!$academicProgram) {
            return response()->json([
                'status' => 'error',
                'message' => 'Academic Program not found.',
            ], 404);
        }

        $academicProgram->delete();
        AcademicProgramCategory::where('aph_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Academic Program deleted successfully.',
        ], 200);
    }
}
