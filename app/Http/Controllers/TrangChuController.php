<?php

namespace App\Http\Controllers;

use App\Models\SanPham;

class TrangChuController extends Controller
{
    public function index()
    {
        $sanPhamNoiBat = SanPham::with('danhMuc')->take(4)->get();
        return view('trangchu', compact('sanPhamNoiBat'));
    }
}
