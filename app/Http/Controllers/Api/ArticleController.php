<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function index()
    {
        return response()->json(Article::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->except(['main_image', 'gallery_images']);
        $data['slug'] = Str::slug($request->title) . '-' . rand(1000, 9999);

        // Upload main image
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $fileName = 'art_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $article = Article::create($data);

        // Upload gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $fileName = 'art_gal_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                
                $path = public_path('uploads');
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                $file->move($path, $fileName);

                ArticleImage::create([
                    'article_id' => $article->id,
                    'image_name' => 'uploads/' . $fileName
                ]);
            }
        }

        return response()->json([
            'message' => 'Artikel berhasil dibuat',
            'data' => Article::with('images')->find($article->id)
        ], 201);
    }

    public function show($id)
    {
        $article = Article::with('images')->find($id);
        if (!$article) {
            $article = Article::with('images')->where('slug', $id)->first();
        }

        if (!$article) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->except(['main_image', 'gallery_images']);
        $data['slug'] = Str::slug($request->title) . '-' . rand(1000, 9999);

        // Upload main image
        if ($request->hasFile('main_image')) {
            // Delete old file
            if ($article->main_image && File::exists(public_path($article->main_image))) {
                File::delete(public_path($article->main_image));
            }

            $file = $request->file('main_image');
            $fileName = 'art_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $article->update($data);

        // Upload new gallery images if provided
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $fileName = 'art_gal_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                
                $path = public_path('uploads');
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                $file->move($path, $fileName);

                ArticleImage::create([
                    'article_id' => $article->id,
                    'image_name' => 'uploads/' . $fileName
                ]);
            }
        }

        return response()->json([
            'message' => 'Artikel berhasil diperbarui',
            'data' => Article::with('images')->find($article->id)
        ]);
    }

    public function destroy($id)
    {
        $article = Article::with('images')->findOrFail($id);

        // Delete main image
        if ($article->main_image && File::exists(public_path($article->main_image))) {
            File::delete(public_path($article->main_image));
        }

        // Delete gallery images
        foreach ($article->images as $img) {
            if ($img->image_name && File::exists(public_path($img->image_name))) {
                File::delete(public_path($img->image_name));
            }
            $img->delete();
        }

        $article->delete();

        return response()->json([
            'message' => 'Artikel berhasil dihapus'
        ]);
    }

    // Delete single image from gallery
    public function destroyGalleryImage($id)
    {
        $img = ArticleImage::findOrFail($id);
        
        if ($img->image_name && File::exists(public_path($img->image_name))) {
            File::delete(public_path($img->image_name));
        }

        $img->delete();

        return response()->json([
            'message' => 'Foto galeri berhasil dihapus'
        ]);
    }
}
