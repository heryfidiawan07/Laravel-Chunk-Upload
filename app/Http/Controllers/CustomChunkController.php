<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ChunkUpload;

class CustomChunkController extends Controller
{
    public function index()
    {
        return view('chunk-custom');
    }

    public function store(Request $request)
    {
        ChunkUpload::upload('chunk', 'file', $request);
    }
}
