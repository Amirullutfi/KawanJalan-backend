<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index()
    {
        return response()->json(Subscriber::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:subscribers,email',
            'phone' => 'nullable|string|max:50',
        ], [
            'email.unique' => 'Email ini sudah terdaftar dalam newsletter.'
        ]);

        $subscriber = Subscriber::create($request->all());

        return response()->json([
            'message' => 'Terima kasih telah berlangganan newsletter kami!',
            'data' => $subscriber
        ], 201);
    }

    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return response()->json([
            'message' => 'Subscriber berhasil dihapus'
        ]);
    }

    public function export()
    {
        $subscribers = Subscriber::all();
        
        $csvFileName = 'subscribers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($subscribers) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['ID', 'Nama', 'Email', 'No Telepon/WA', 'Tanggal Daftar']);

            foreach ($subscribers as $sub) {
                fputcsv($file, [
                    $sub->id, 
                    $sub->name ?? '-', 
                    $sub->email, 
                    $sub->phone ?? '-', 
                    $sub->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
