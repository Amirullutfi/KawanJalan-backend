<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with('category');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('featured') && $request->featured != '') {
            $query->where('featured', filter_var($request->featured, FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:150',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:150',
            'featured' => 'nullable',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('main_image');
        $data['slug'] = Str::slug($request->title) . '-' . rand(1000, 9999);
        $data['featured'] = filter_var($request->featured ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $fileName = 'dest_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads directory exists
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $destination = Destination::create($data);

        return response()->json([
            'message' => 'Destinasi berhasil ditambahkan',
            'data' => $destination
        ], 201);
    }

    public function show($id)
    {
        $destination = Destination::with('category')->find($id);
        
        if (!$destination) {
            // Check by slug as well
            $destination = Destination::with('category')->where('slug', $id)->first();
        }

        if (!$destination) {
            return response()->json(['message' => 'Destinasi tidak ditemukan'], 404);
        }

        return response()->json($destination);
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['message' => 'Destinasi tidak ditemukan'], 404);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:150',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:150',
            'featured' => 'nullable',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('main_image');
        $data['slug'] = Str::slug($request->title) . '-' . rand(1000, 9999);
        $data['featured'] = filter_var($request->featured ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('main_image')) {
            // Delete old file if exists
            if ($destination->main_image && File::exists(public_path($destination->main_image))) {
                File::delete(public_path($destination->main_image));
            }

            $file = $request->file('main_image');
            $fileName = 'dest_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $destination->update($data);

        return response()->json([
            'message' => 'Destinasi berhasil diperbarui',
            'data' => $destination
        ]);
    }

    public function destroy($id)
    {
        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['message' => 'Destinasi tidak ditemukan'], 404);
        }

        if ($destination->main_image && File::exists(public_path($destination->main_image))) {
            File::delete(public_path($destination->main_image));
        }

        $destination->delete();

        return response()->json([
            'message' => 'Destinasi berhasil dihapus'
        ]);
    }
}
