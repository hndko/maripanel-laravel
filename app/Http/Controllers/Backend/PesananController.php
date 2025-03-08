<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BuzzerPanelService;

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
        // Ambil data layanan dari API
        $services = $this->buzzerPanelService->getServices();

        // Kelompokkan layanan berdasarkan kategori
        $categories = [];
        if ($services && isset($services['data'])) {
            foreach ($services['data'] as $service) {
                $categories[$service['category']][] = $service;
            }
        }

        $data = [
            'title' => 'Pesanan Baru',
            'pages' => 'Pesanan Baru',
            'categories' => $categories,
        ];

        return view('backend.pesanan.index', $data);
    }
}
