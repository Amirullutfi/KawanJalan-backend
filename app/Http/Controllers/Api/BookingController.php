<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    private function autoCancelExpiredBookings()
    {
        Booking::where('payment_status', 'waiting_payment')
            ->where('payment_deadline', '<', Carbon::now())
            ->update(['payment_status' => 'cancelled']);
    }

    public function index(Request $request)
    {
        // Run auto cancel clean up first
        $this->autoCancelExpiredBookings();

        $query = Booking::with('package');

        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->where('payment_status', $request->status);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'travel_date' => 'required|date|after_or_equal:today',
            'num_people' => 'required|integer|min:1',
            'photo_package_name' => 'nullable|string|in:MINI,SHORT,MEDIUM,LONG,SUKA SUKA',
            'addon_drone' => 'nullable|boolean',
        ]);

        $package = TourPackage::find($request->package_id) ?? (object)[
            'title' => 'Paket Wisata ' . $request->package_id,
            'price' => 500000,
            'price_unit' => 'orang'
        ];

        // Calculate pricing
        // If unit is per person (orang), multiply. Else, it is a flat package price.
        $baseTourPrice = $package->price;
        if ($package->price_unit === 'orang') {
            $baseTourPrice = $package->price * $request->num_people;
        }

        // Calculate Photo Package Price
        $photoPackagePrices = [
            'MINI' => 400000,
            'SHORT' => 450000,
            'MEDIUM' => 500000,
            'LONG' => 600000,
            'SUKA SUKA' => 800000
        ];
        
        $photoPackageName = $request->input('photo_package_name');
        $photoPackagePrice = 0;
        if ($photoPackageName && array_key_exists($photoPackageName, $photoPackagePrices)) {
            $photoPackagePrice = $photoPackagePrices[$photoPackageName];
        } else {
            $photoPackageName = null;
        }

        // Calculate Add-on Drone Price
        $addonDrone = (bool) $request->input('addon_drone', false);
        $addonDronePrice = $addonDrone ? 500000 : 0;

        // Total Price includes: Tour package + photo package + drone addon
        $totalPrice = $baseTourPrice + $photoPackagePrice + $addonDronePrice;

        $dpAmount = $totalPrice * 0.3; // DP 30%
        $remainingAmount = $totalPrice - $dpAmount; // Pelunasan di tempat 70%
        $uniqueCode = rand(100, 999); // 3-digit random unique code
        $deadline = Carbon::now()->addHour(); // 1 hour payment window

        // Generate unique booking code
        do {
            $bookingCode = 'EXP-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (Booking::where('booking_code', $bookingCode)->exists());

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'package_id' => $request->package_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'travel_date' => $request->travel_date,
            'num_people' => $request->num_people,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'unique_code' => $uniqueCode,
            'photo_package_name' => $photoPackageName,
            'photo_package_price' => $photoPackagePrice,
            'addon_drone' => $addonDrone,
            'addon_drone_price' => $addonDronePrice,
            'payment_deadline' => $deadline,
            'payment_status' => 'waiting_payment'
        ]);

        // WhatsApp notifications via Fonnte
        $adminPhone = env('FONNTE_ADMIN_WHATSAPP', '6281234567890');
        $formattedBasePrice = number_format($baseTourPrice, 0, ',', '.');
        $formattedPhotoPrice = number_format($photoPackagePrice, 0, ',', '.');
        $formattedDronePrice = number_format($addonDronePrice, 0, ',', '.');
        $formattedPrice = number_format($totalPrice, 0, ',', '.');
        $formattedDp = number_format($dpAmount, 0, ',', '.');
        $formattedRemaining = number_format($remainingAmount, 0, ',', '.');
        $formattedUniqueCode = number_format($uniqueCode, 0, ',', '.');
        $formattedTotalTransfer = number_format($dpAmount + $uniqueCode, 0, ',', '.');

        $breakdownDetails = "- Paket Tour: {$package->title} (Rp {$formattedBasePrice})";
        if ($photoPackageName) {
            $breakdownDetails .= "\n- Jasa Dokumentasi: Paket {$photoPackageName} (Rp {$formattedPhotoPrice})";
        }
        if ($addonDrone) {
            $breakdownDetails .= "\n- Layanan Ekstra Drone: Ya (Rp {$formattedDronePrice})";
        }
        
        $customerMsg = "Halo {$request->customer_name},\n\nTerima kasih telah memesan paket wisata di KawanJalan Tour & Travel!\n\nDetail Pemesanan:\n- Kode Booking: {$booking->booking_code}\n- Tanggal Keberangkatan: {$request->travel_date}\n- Jumlah Peserta: {$request->num_people} {$package->price_unit}\n\nRincian Layanan:\n{$breakdownDetails}\n\nRincian Pembayaran Uang Muka (DP 30%):\n- Total Biaya: Rp {$formattedPrice}\n- Uang Muka (DP 30%): Rp {$formattedDp}\n- Sisa Pelunasan (Di Tempat): Rp {$formattedRemaining}\n- Kode Unik: Rp {$formattedUniqueCode}\n- Total Wajib Transfer (DP + Kode Unik): *Rp {$formattedTotalTransfer}*\n- Batas Waktu Bayar: {$deadline->format('d-m-Y H:i:s')} WIB (1 Jam)\n\nSilakan transfer nominal diatas secara PERSIS (termasuk kode unik) ke Rekening BCA: 123-456-7890 a.n Kawan Jalan Indonesia, lalu kirimkan bukti transfer Anda ke nomor ini atau konfirmasi melalui website.\nSisa pelunasan sebesar Rp {$formattedRemaining} dibayarkan secara langsung di lokasi kepada driver/guide kami.\n\nSalam,\nKawanJalan Tour & Travel";
        
        $adminMsg = "NOTIFIKASI BOOKING BARU!\n\nDetail:\n- Kode Booking: {$booking->booking_code}\n- Pelanggan: {$request->customer_name}\n- No WA: {$request->customer_phone}\n\nRincian Layanan:\n{$breakdownDetails}\n- Total Biaya: Rp {$formattedPrice}\n- Wajib Transfer DP (DP + Kode Unik): Rp {$formattedTotalTransfer} (Kode Unik: {$uniqueCode})\n- Sisa Pelunasan (Di Tempat): Rp {$formattedRemaining}\n\nHarap cek mutasi rekening BCA dan bersiap melakukan konfirmasi status pembayaran di Dashboard Admin.";

        // Send to Customer
        $this->sendWhatsAppNotification($request->customer_phone, $customerMsg);
        // Send to Admin
        $this->sendWhatsAppNotification($adminPhone, $adminMsg);

        return response()->json([
            'message' => 'Pemesanan berhasil dibuat. Harap selesaikan pembayaran DP Anda.',
            'booking_id' => $booking->booking_code
        ], 201);
    }

    public function show($id)
    {
        $booking = null;

        // Try searching by booking_code first
        $booking = Booking::with('package')->where('booking_code', $id)->first();

        // If not found, try searching by id if it is numeric
        if (!$booking && is_numeric($id)) {
            $booking = Booking::with('package')->find($id);
        }

        // If not found, check if it starts with EXP- or #EXP- and search by that prefix
        if (!$booking) {
            $cleaned = ltrim($id, '#');
            $booking = Booking::with('package')->where('booking_code', $cleaned)->first();
        }

        if (!$booking) {
            // Check if it's #EXP-ID or EXP-ID where ID is numeric
            $cleaned = ltrim($id, '#');
            if (preg_match('/^EXP-(\d+)$/i', $cleaned, $matches)) {
                $booking = Booking::with('package')->find($matches[1]);
            }
        }

        if (!$booking) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        // Run specific check for this booking
        if ($booking->payment_status === 'waiting_payment' && Carbon::now()->greaterThan($booking->payment_deadline)) {
            $booking->update(['payment_status' => 'cancelled']);
            $booking->payment_status = 'cancelled';
        }

        // Calculate seconds remaining for countdown
        $secondsRemaining = 0;
        if ($booking->payment_status === 'waiting_payment') {
            $secondsRemaining = Carbon::now()->diffInSeconds($booking->payment_deadline, false);
            if ($secondsRemaining < 0) {
                $secondsRemaining = 0;
            }
        }

        return response()->json([
            'booking' => $booking,
            'seconds_remaining' => $secondsRemaining
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = null;
        if (is_numeric($id)) {
            $booking = Booking::find($id);
        }
        if (!$booking) {
            $booking = Booking::where('booking_code', $id)->first();
        }
        if (!$booking) {
            $cleaned = ltrim($id, '#');
            $booking = Booking::where('booking_code', $cleaned)->first();
        }

        if (!$booking) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        $request->validate([
            'payment_status' => 'required|in:waiting_payment,paid_dp,completed,cancelled'
        ]);

        $oldStatus = $booking->payment_status;
        $booking->update([
            'payment_status' => $request->payment_status
        ]);

        // Send WA update to customer if status changed
        if ($oldStatus !== $request->payment_status) {
            $statusLabel = '';
            $extraMsg = '';
            
            if ($request->payment_status === 'paid_dp') {
                $statusLabel = 'DP TERBAYAR';
                $remainingAmount = $booking->total_price - $booking->dp_amount;
                $formattedRemaining = number_format($remainingAmount, 0, ',', '.');
                $extraMsg = "Pembayaran uang muka (DP 30%) Anda telah diverifikasi dan diterima. E-Tiket Anda sekarang sudah aktif dan dapat diunduh di halaman detail pemesanan. Sisa pelunasan sebesar Rp {$formattedRemaining} wajib dibayarkan langsung di lokasi kepada driver/guide sebelum memulai trip.";
            } elseif ($request->payment_status === 'completed') {
                $statusLabel = 'SELESAI';
                $extraMsg = 'Trip wisata Anda telah selesai. Terima kasih telah melakukan perjalanan bersama KawanJalan Tour & Travel!';
            } elseif ($request->payment_status === 'cancelled') {
                $statusLabel = 'DIBATALKAN';
                $extraMsg = 'Pemesanan Anda telah dibatalkan secara otomatis atau oleh administrator karena batas waktu pembayaran telah habis.';
            }

            if ($statusLabel !== '') {
                $msg = "Halo {$booking->customer_name},\n\nStatus pemesanan Anda {$booking->booking_code} telah diubah menjadi: *{$statusLabel}*.\n\n{$extraMsg}\n\nTerima kasih,\nKawanJalan Tour & Travel";
                $this->sendWhatsAppNotification($booking->customer_phone, $msg);
            }
        }

        return response()->json([
            'message' => 'Status pembayaran berhasil diperbarui',
            'data' => $booking
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json([
            'message' => 'Pemesanan berhasil dihapus'
        ]);
    }

    private function sendWhatsAppNotification($phone, $message)
    {
        $token = env('FONNTE_API_TOKEN', 'mock_token');
        if ($token === 'mock_token' || empty($token)) {
            \Log::info("WhatsApp Mock Send to {$phone}: {$message}");
            return true;
        }

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $phone,
                    'message' => $message,
                    'countryCode' => '62',
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            \Log::info("Fonnte API Response: " . $response);
            return true;
        } catch (\Exception $e) {
            \Log::error("Fonnte API Error: " . $e->getMessage());
            return false;
        }
    }
}
