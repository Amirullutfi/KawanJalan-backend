<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TourPackageController extends Controller
{
    public function index()
    {
        return response()->json(TourPackage::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'required|in:orang,unit,grup',
            'duration' => 'required|string|max:100',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('main_image');

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $fileName = 'pkg_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $package = TourPackage::create($data);

        return response()->json([
            'message' => 'Paket wisata berhasil ditambahkan',
            'data' => $package
        ], 201);
    }

    public function show($id)
    {
        $package = TourPackage::find($id);
        if (!$package) {
            return response()->json(['message' => 'Paket wisata tidak ditemukan'], 404);
        }
        return response()->json($package);
    }

    public function update(Request $request, $id)
    {
        $package = TourPackage::find($id);
        if (!$package) {
            return response()->json(['message' => 'Paket wisata tidak ditemukan'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'required|in:orang,unit,grup',
            'duration' => 'required|string|max:100',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('main_image');

        if ($request->hasFile('main_image')) {
            // Delete old file if exists
            if ($package->main_image && File::exists(public_path($package->main_image))) {
                File::delete(public_path($package->main_image));
            }

            $file = $request->file('main_image');
            $fileName = 'pkg_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $package->update($data);

        return response()->json([
            'message' => 'Paket wisata berhasil diperbarui',
            'data' => $package
        ]);
    }

    public function destroy($id)
    {
        $package = TourPackage::find($id);
        if (!$package) {
            return response()->json(['message' => 'Paket wisata tidak ditemukan'], 404);
        }

        if ($package->main_image && File::exists(public_path($package->main_image))) {
            File::delete(public_path($package->main_image));
        }

        $package->delete();

        return response()->json([
            'message' => 'Paket wisata berhasil dihapus'
        ]);
    }
}
