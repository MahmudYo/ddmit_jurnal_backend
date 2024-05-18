<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class JurnalController extends Controller
{
    private function saveImage($requestImageFile)
    {
        $currentId = count(Jurnal::withoutTrashed()->get()) + 1;
        $imgFile = $requestImageFile;
        $imgFilename = $currentId . '_wrapper_jurnal' .  '.' . $imgFile->getClientOriginalExtension();
        $img = Image::read($imgFile);
        $img->resize(175, 260);

        $path = public_path('storage/jurnals/images/' . $imgFilename);
        $img->save($path, 100);
        return $imgFilename;
    }
    private function saveDocument($requestDcoumentFile)
    {
        $currentId = count(Jurnal::withoutTrashed()->get()) + 1;
        $file = $requestDcoumentFile;
        $filename = $currentId . '_jurnal' . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('jurnals/documents/', $filename, 'public');
        return $filename;
    }
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
                $imgName = $this->saveImage($request->file('image'));
                $jurnalName = $this->saveDocument($request->file('jurnal'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function  document(string $id, string $document_name)
    {
        $jurnal = Jurnal::find($id);
        if ($jurnal) {
            return response()->file(storage_path('app/public/jurnals/documents/' . $document_name));
        } else {
            return response()->json(['message' => 'jurnal not found'], 404);
        }
    }
    public function  getImage(string $id, string $image_name)
    {
        $jurnal = Jurnal::find($id);
        if ($jurnal) {
            return response()->file(storage_path('app/public/jurnals/images/' . $image_name));
        } else {
            return response()->json(['message' => 'jurnal not found'], 404);
        }
    }
    public function documentDownload(string $id, string $document_name) {
        $jurnal = Jurnal::find($id);
        if ($jurnal) {
            return response()->download(storage_path('app/public/jurnals/documents/' . $document_name));
        } else {
            return response()->json(['message' => 'jurnal not found'], 404);
        }
    }
}
