<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrixAttachmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'attachment' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:5120', // 5MB
            ],
        ]);

        $file = $request->file('attachment');

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs(
            'trix-images/'.now()->format('Y/m'),
            $filename,
            'public'
        );

        $url = asset(Storage::url($path));

        return response()->json([
            'url' => $url,
            'href' => $url,
        ]);
    }
}