<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\BoSuuTap;

class TrangChuController extends Controller
{
    public function index()
    {
        $sanPhamNoiBat = SanPham::with(['danhMuc', 'sizes'])->take(4)->get();
        $boSuuTaps     = BoSuuTap::with(['sanPhams'])->withCount('sanPhams')->latest()->get();

        return view('trangchu', compact('sanPhamNoiBat', 'boSuuTaps'));
    }
}
