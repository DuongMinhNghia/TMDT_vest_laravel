<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class QuanLyNguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->tim_kiem, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->tim_kiem . '%')
                      ->orWhere('email', 'like', '%' . $request->tim_kiem . '%');
            })
            ->when($request->quyen, function ($query) use ($request) {
                $query->where('quyen', $request->quyen);
            })
            ->latest()
            ->get();

        return view('quanlynguoidung', compact('users'));
    }

    public function luuSua(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'quyen'   => 'required|in:Admin,Khách hàng',
            'phone'   => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($request->only(['name', 'email', 'quyen', 'phone', 'address']));

        return redirect()->route('nguoidung.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function xoa($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('nguoidung.index')->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();

        return redirect()->route('nguoidung.index')->with('success', 'Xóa người dùng thành công!');
    }
}