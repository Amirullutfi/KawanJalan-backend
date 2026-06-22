<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        $data = $request->except('main_image');
        $data['slug'] = Str::slug($request->title) . '-' . rand(1000, 9999);

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $fileName = 'evt_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $event = Event::create($data);

        return response()->json([
            'message' => 'Event berhasil ditambahkan',
            'data' => $event
        ], 201);
    }

    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            $event = Event::where('slug', $id)->first();
        }

        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        // Determine if registration is locked (date is in the past)
        $isExpired = Carbon::parse($event->date)->isPast();

        return response()->json([
            'event' => $event,
            'is_expired' => $isExpired
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);

        $data = $request->except('main_image');
        $data['slug'] = Str::slug($request->title) . '-' . rand(1000, 9999);

        if ($request->hasFile('main_image')) {
            // Delete old file if exists
            if ($event->main_image && File::exists(public_path($event->main_image))) {
                File::delete(public_path($event->main_image));
            }

            $file = $request->file('main_image');
            $fileName = 'evt_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $fileName);
            $data['main_image'] = 'uploads/' . $fileName;
        }

        $event->update($data);

        return response()->json([
            'message' => 'Event berhasil diperbarui',
            'data' => $event
        ]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->main_image && File::exists(public_path($event->main_image))) {
            File::delete(public_path($event->main_image));
        }

        $event->delete();

        return response()->json([
            'message' => 'Event berhasil dihapus'
        ]);
    }

    // Public visitor event registration
    public function register(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // Lock registration if event date is in the past
        if (Carbon::parse($event->date)->isPast()) {
            return response()->json([
                'message' => 'Pendaftaran gagal. Event ini telah berakhir.'
            ], 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
        ]);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'message' => 'Pendaftaran event berhasil dilakukan!',
            'data' => $registration
        ], 201);
    }

    // Admin view of registrations
    public function registrations()
    {
        $registrations = EventRegistration::with('event')->latest()->get();
        return response()->json($registrations);
    }

    // Admin delete registration
    public function destroyRegistration($id)
    {
        $reg = EventRegistration::findOrFail($id);
        $reg->delete();

        return response()->json([
            'message' => 'Pendaftaran peserta berhasil dihapus'
        ]);
    }

    // Export registrations to CSV
    public function exportRegistrations()
    {
        $registrations = EventRegistration::with('event')->get();
        
        $csvFileName = 'event_registrations_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($registrations) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['ID', 'Nama Peserta', 'Email', 'No WA/Telepon', 'Nama Event', 'Tanggal Event', 'Waktu Registrasi']);

            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->id,
                    $reg->name,
                    $reg->email,
                    $reg->phone,
                    $reg->event->title ?? 'Event Terhapus',
                    $reg->event ? Carbon::parse($reg->event->date)->format('Y-m-d H:i') : '-',
                    $reg->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
