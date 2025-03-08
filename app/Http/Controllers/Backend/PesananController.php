<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BuzzerPanelService;
use Illuminate\Support\Facades\Cache;

class PesananController extends Controller
{
    protected $buzzerPanelService;

    public function __construct(BuzzerPanelService $buzzerPanelService)
    {
        $this->buzzerPanelService = $buzzerPanelService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cek apakah data sudah ada di cache
        $categories = Cache::remember('services_data', 60, function () { // Simpan data selama 60 menit
            $services = $this->buzzerPanelService->getServices();

            // Kelompokkan layanan berdasarkan kategori
            $categories = [];
            if ($services && isset($services['data'])) {
                foreach ($services['data'] as $service) {
                    $categories[$service['category']][] = $service;
                }
            }

            return $categories;
        });

        $data = [
            'title' => 'Pesanan Baru',
            'pages' => 'Pesanan Baru',
            'categories' => $categories,
        ];

        return view('backend.pesanan.index', $data);
    }

    public function getLayanan(Request $request)
    {
        $category = $request->input('category');

        // Ambil data dari cache atau API
        $categories = Cache::remember('services_data', 60, function () {
            $services = $this->buzzerPanelService->getServices();
            $categories = [];

            if ($services && isset($services['data'])) {
                foreach ($services['data'] as $service) {
                    $categories[$service['category']][] = $service;
                }
            }

            return $categories;
        });

        if (isset($categories[$category])) {
            return response()->json([
                'status' => true,
                'data' => $categories[$category],
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
        ]);
    }

    public function buatPesanan(Request $request)
    {
        $request->validate([
            'service_id' => 'required|numeric',
            'data_target' => 'required|string',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Ambil data dari request
        $serviceId = $request->input('service_id');
        $dataTarget = $request->input('data_target');
        $quantity = $request->input('quantity');

        // Panggil method createOrder dari BuzzerPanelService
        $response = $this->buzzerPanelService->createOrder($serviceId, $dataTarget, $quantity);

        if ($response && isset($response['status']) && $response['status']) {
            return response()->json([
                'status' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'data' => $response['data'],
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Gagal membuat pesanan: ' . ($response['message'] ?? 'Terjadi kesalahan'),
        ]);
    }
}
