<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,webp,svg,gif', 'max:10240'],
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(32) . '.' . $extension;

        $path = $file->storeAs('uploads', $filename, 'public');
        $url = '/storage/' . $path;

        return response()->json([
            'url' => $url,
            'path' => $path,
            'filename' => $filename,
        ], 201);
    }
}
