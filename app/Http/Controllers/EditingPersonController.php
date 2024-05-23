<?php

namespace App\Http\Controllers;

use App\Models\EditingPerson;
use Illuminate\Http\Request;

class EditingPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'categoryId' => 'required',
            'fullName' => 'required',
            'description' => 'required',
            'role' => 'required',
        ]);
        if ($validate) {
            $attributes = [
                'category_id' => $request['categoryId'],
                'full_name' => $request['fullName'],
                'description' => $request['description'],
                'role' => $request['role'],
            ];
            $values = [];
            if ($request->hasFile('image')) {
                $currentId = EditingPerson::withTrashed()->count() + 1;
                $imgName = $this->saveStorageData($request->file('image'), 'image', 'editing_persone/', '_editing_person', $currentId);
                $values['image'] = $imgName;
            }

            $editing_person = EditingPerson::firstOrCreate($attributes, $values);
            if ($editing_person->wasRecentlyCreated) {
                return response()->json(['persone' => $editing_person, 'query' => 'ok'], 200);
            } else {
                return response()->json(['persone' => $editing_person, 'query' => 'old'], 201);
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
    public function categoryPersonShow(string $id)
    {
        return response()->json(['persone' => EditingPerson::where('category_id', $id)->get(), 'query' => 'ok'], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'categoryId' => 'required',
            'fullName' => 'required',
            'description' => 'required',
            'role' => 'required',
        ]);
        if ($validate) {
            $person = EditingPerson::find($id);
            $person->category_id = $request['categoryId'];
            $person->full_name = $request['fullName'];
            $person->description = $request['description'];
            $person->role = $request['role'];

            if ($request->hasFile('image')) {
                $imgName = $this->saveStorageData($request->file('image'), 'image', 'editing_persone/', '_editing_person', $id, $person);
                $person->image = $imgName;
            } else {
                $person->image = $request['image'];
            }
            $person->update();
            return response()->json(['persone' => $person, 'query' => 'ok'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $is_delete =  EditingPerson::find($id)->delete();
        if ($is_delete) {
            return response()->json(['message' => 'person deleted'], 200);
        } else {
            return response()->json(['message' => 'person not deleted'], 201);
        }
    }
    public function getImage(string $id)
    {
        $person = EditingPerson::find($id);
        if ($person) {
            return response()->file(storage_path('app/public/editing_persone/' . $person->image));
        } else {
            return response()->json(['message' => 'person not found'], 404);
        }
    }
}
