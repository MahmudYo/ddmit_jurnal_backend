<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Jurnal::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valudate = $request->validate([
            'year' => 'required|integer',
            'title' => 'required',
            'jurnal' => 'required',
            'image' => 'required',
        ]);
        if ($valudate) {
            if ($request->hasFile('image') && $request->hasFile('jurnal')) {
                $currentId = Jurnal::withTrashed()->count() + 1;
                $imgName = $this->saveStorageData($request->file('image'), 'image', 'jurnals/images/', '_wrapper_jurnal', $currentId, null, 175, 260);
                $jurnalName = $this->saveStorageData($request->file('jurnal'), 'document', 'jurnals/documents/', '_jurnal', $currentId);
                $jurnal = Jurnal::firstOrCreate([
                    'year' => $request['year'],
                    'title' => $request['title'],
                    'jurnal' =>  $jurnalName,
                    'image' => $imgName,
                ]);
                if ($jurnal->wasRecentlyCreated) {
                    return response()->json(['jurnal' => $jurnal, 'query' => 'ok'], 200);
                } else {
                    return response()->json(['jurnal' => $jurnal, 'query' => 'old'], 201);
                }
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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $valudate = $request->validate([
            'year' => 'required|integer',
            'title' => 'required',
            'jurnal' => 'required',
            'image' => 'required',
        ]);
        if ($valudate) {
            if ($request->hasFile('image') && $request->hasFile('jurnal')) {
                $currentId = Jurnal::withTrashed()->count() + 1;
                $jurnal = Jurnal::find($id);
                $imgName = $this->saveStorageData($request->file('image'), 'image', 'jurnals/images/', '_wrapper_jurnal', $currentId, $jurnal, 175, 260);
                $jurnalName = $this->saveStorageData($request->file('jurnal'), 'document', 'jurnals/documents/', '_jurnal', $currentId, $jurnal);
                $jurnal->title = $request->title;
                $jurnal->year = $request->year;
                $jurnal->image = $imgName;
                $jurnal->jurnal = $jurnalName;
                $jurnal->update();
                return response()->json(['jurnal' => $jurnal, 'query' => 'ok'], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $is_delete =  Jurnal::find($id)->delete();
        if ($is_delete) {
            return response()->json(['message' => 'jurnal deleted'], 200);
        } else {
            return response()->json(['message' => 'jurnal not found'], 404);
        }
    }
    public function  document(string $id)
    {
        $jurnal = Jurnal::find($id);
        if ($jurnal) {
            return response()->file(storage_path('app/public/jurnals/documents/' . $jurnal->jurnal));
        } else {
            return response()->json(['message' => 'jurnal not found'], 404);
        }
    }
    public function  getImage(string $id)
    {
        $jurnal = Jurnal::find($id);
        if ($jurnal) {
            return response()->file(storage_path('app/public/jurnals/images/' . $jurnal->image));
        } else {
            return response()->json(['message' => 'image jurnal not found'], 404);
        }
    }
    public function documentDownload(string $id, string $document_name)
    {
        $jurnal = Jurnal::find($id);
        if ($jurnal) {
            return response()->download(storage_path('app/public/jurnals/documents/' . $document_name));
        } else {
            return response()->json(['message' => 'jurnal not found'], 404);
        }
    }
}
