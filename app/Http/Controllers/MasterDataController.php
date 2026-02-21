<?php

namespace App\Http\Controllers;

use App\Models\MasterProduk;
use App\Models\MasterDriver;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        $stats = [
            'produk' => [
                'total' => MasterProduk::count(),
                'bb' => MasterProduk::where('kategori', 'BB')->count(),
                'isian' => MasterProduk::where('kategori', 'ISIAN')->count(),
                'ga' => MasterProduk::where('kategori', 'GA')->count(),
                'route' => 'master-produk.index',
                'label' => 'Kelola Produk',
                'icon' => 'box'
            ],
            'driver' => [
                'total' => MasterDriver::count(),
                'route' => 'master-driver.index',
                'label' => 'Kelola Kurir/Driver',
                'icon' => 'truck'
            ],
            'cabang' => [
                'total' => Cabang::count(),
                'route' => 'cabang.index',
                'label' => 'Kelola Cabang',
                'icon' => 'office-building'
            ],
            'user' => [
                'total' => User::count(),
                'route' => 'users.index',
                'label' => 'User Management',
                'icon' => 'users'
            ]
        ];

        return view('master.index', compact('stats'));
    }
}
