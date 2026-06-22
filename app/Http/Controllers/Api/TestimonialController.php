<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    public function index()
    {
        return response()->json(Testimonial::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'testi_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['image'] = 'uploads/' . $fileName;
        }

        $testimonial = Testimonial::create($data);

        return response()->json([
            'message' => 'Testimoni berhasil ditambahkan',
            'data' => $testimonial
        ], 201);
    }

    public function show($id)
    {
        $testimonial = Testimonial::find($id);
        if (!$testimonial) {
            return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
        }
        return response()->json($testimonial);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::find($id);
        if (!$testimonial) {
            return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old file if exists
            if ($testimonial->image && File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }

            $file = $request->file('image');
            $fileName = 'testi_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['image'] = 'uploads/' . $fileName;
        }

        $testimonial->update($data);

        return response()->json([
            'message' => 'Testimoni berhasil diperbarui',
            'data' => $testimonial
        ]);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        if (!$testimonial) {
            return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
        }

        if ($testimonial->image && File::exists(public_path($testimonial->image))) {
            File::delete(public_path($testimonial->image));
        }

        $testimonial->delete();

        return response()->json([
            'message' => 'Testimoni berhasil dihapus'
        ]);
    }
}
