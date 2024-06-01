<?php

namespace App\Http\Controllers;

use App\Models\EditingPerson;
use App\Models\EditingPersonCategory;
use Illuminate\Http\Request;

class EditingPersonCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = EditingPersonCategory::with('persons')->get();
        $generalRole = EditingPerson::where('role', 'rector')->first();
        if ($generalRole) {
            $category = $category->sortByDesc(function ($category) use ($generalRole) {
                return $category->persons->contains('id', $generalRole->id);
            })->values();
        }
        return response()->json(['category' => $category, 'rector' => $generalRole]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'category' => 'required'
        ]);
        if ($validate) {
            $category = EditingPersonCategory::with('persons')->firstOrCreate([
                'category' => $request['category'],
            ]);
            if ($category->wasRecentlyCreated) {
                return response()->json(['category' => $category, 'query' => 'ok'], 200);
            } else {
                return response()->json(['category' => $category, 'query' => 'old'], 201);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'category' => 'required'
        ]);
        if ($validate) {
            $category = EditingPersonCategory::find($id);
            $category->category = $request['category'];
            $category->update();
            return response()->json(['category' => $category, 'query' => 'ok'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $idsDelete = [];
        $is_delete_category =  EditingPersonCategory::find($id)->delete();
        $persons = EditingPerson::where('category_id', $id)->get();
        foreach ($persons as $person) {
            array_push($idsDelete, $person->id);
        }
        $is_delete_persons = EditingPerson::whereIn('category_id', $idsDelete)->delete();
        if ($is_delete_category && $is_delete_persons) {
            return response()->json(['message' => 'category deleted'], 200);
        } else {
            return response()->json(['message' => 'category not deleted'], 201);
        }
    }
}
