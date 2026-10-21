<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HangThanhVien;

class HangThanhVienController extends Controller
{
    public function index()
    {
        $hangs = HangThanhVien::withCount('khachHangs')->orderBy('diem_toi_thieu', 'asc')->get();
        return view('quanlyhangthanhvien', compact('hangs'));
    }

    public function capNhat(Request $request, $id)
    {
        $hang = HangThanhVien::findOrFail($id);

        $request->validate([
            'diem_toi_thieu'       => 'required|integer|min:0',
            'phan_tram_chiet_khau' => 'required|integer|min:0|max:100',
            'mo_ta'                => 'nullable|string|max:500',
        ]);

        $hang->update($request->only(['diem_toi_thieu', 'phan_tram_chiet_khau', 'mo_ta']));

        return redirect()->route('hangthanhvien.index')->with('success', "Cập nhật chính sách hạng {$hang->ten_hang} thành công!");
    }
}

