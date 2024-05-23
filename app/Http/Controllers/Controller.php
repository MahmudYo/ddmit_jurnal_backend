<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected function saveStorageData($requestFile, string $dataType, string $prefix,  string $pathName, $currentId, $old = null, $imgWidth = null, $imgHeigth = null)
    {
        // prefix => prefixname/
        if ($dataType === 'image') {
            if ($old) {
                Storage::disk('public')->delete($prefix . $old->image);
                $currentId = $old->id;
            }
            $imgFile = $requestFile;
            $imgFilename = $currentId . $pathName .  '.' . $imgFile->getClientOriginalExtension();
            $img = Image::read($imgFile);
            if ($imgWidth && $imgHeigth) {
                $img->resize($imgWidth, $imgHeigth);
            } else if ($imgWidth) {
                $img->resize()->width($imgWidth);
            } else if ($imgHeigth) {
                $img->resize()->height($imgHeigth);
            }
            $path = public_path('/storage/' . $prefix . $imgFilename);
            $img->save($path, 100);
            return $imgFilename;
        } else if ($dataType === 'document') {
            if ($old) {
                Storage::disk('public')->delete($prefix . $old->jurnal);
                $currentId = $old->id;
            }
            $file = $requestFile;
            $filename = $currentId . $pathName . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($prefix, $filename, 'public');
            return $filename;
        }
    }
}
