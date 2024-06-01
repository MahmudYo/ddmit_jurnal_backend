<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(string $query)
    {
        $journals = Jurnal::where('title', 'like', '%' . $query . '%')->get();
        return response()->json($journals);
    }
}
