<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaiKhoan;

class QuanLyNguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $query = TaiKhoan::with(['khachHang', 'nhanVien']);

        if ($request->filled('tim_kiem')) {
            $search = $request->tim_kiem;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                  ->orWhereHas('khachHang', function ($kh) use ($search) {
                      $kh->where('ho_ten', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('nhanVien', function ($nv) use ($search) {
                      $nv->where('ho_ten', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('quyen')) {
            $vaiTro = match ($request->quyen) {
                'Admin' => TaiKhoan::ROLE_ADMIN,
                'Nhân viên' => TaiKhoan::ROLE_STAFF,
                default => TaiKhoan::ROLE_CUSTOMER,
            };
            $query->where('vai_tro', $vaiTro);
        }

        $users = $query->latest()->get();

        return view('quanlynguoidung', compact('users'));
    }

    public function luuSua(Request $request, $id)
    {
        /** @var TaiKhoan $user */
        $user = TaiKhoan::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:tai_khoans,email,' . $id,
            'quyen'   => 'required|in:Admin,Nhân viên,Khách hàng',
            'phone'   => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $vaiTro = match ($request->quyen) {
            'Admin' => TaiKhoan::ROLE_ADMIN,
            'Nhân viên' => TaiKhoan::ROLE_STAFF,
            default => TaiKhoan::ROLE_CUSTOMER,
        };
        $user->update([
            'email'   => $request->email,
            'vai_tro' => $vaiTro,
        ]);

        if ($user->khachHang) {
            $user->khachHang->update([
                'ho_ten'        => $request->name,
                'so_dien_thoai' => $request->phone,
                'dia_chi'       => $request->address,
            ]);
        } elseif ($user->nhanVien) {
            $user->nhanVien->update([
                'ho_ten'        => $request->name,
                'so_dien_thoai' => $request->phone,
            ]);
        }

        return redirect()->route('nguoidung.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function xoa($id)
    {
        /** @var TaiKhoan $user */
        $user = TaiKhoan::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('nguoidung.index')->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();

        return redirect()->route('nguoidung.index')->with('success', 'Xóa người dùng thành công!');
    }
}
