<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Requirement::get()->first());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'content' => 'required',
        ]);
        if ($validate) {
            $requirement = new Requirement();
            $requirement->html_content = $request['content'];
            $requirement->save();
        }
        return response()->json();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requirement = Requirement::find($id);
        if ($requirement) {
            $validate = $request->validate([
                "content" => 'required'
            ]);
            if ($validate) {
                $requirement->html_content = $request['content'];
                $requirement->update();
                return response()->json($requirement);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
