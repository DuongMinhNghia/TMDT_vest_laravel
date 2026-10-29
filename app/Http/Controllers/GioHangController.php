<?php

namespace App\Http\Controllers;

use App\Models\ChiTietGioHang;
use App\Models\GioHang;
use App\Models\SanPham;
use App\Models\Size;
use App\Services\DonHangService;
use App\Services\VietnamAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

/**
 * Task 3.1 (giỏ hàng lưu Database) + Task 3.2 (checkout, trừ kho, chiết khấu hạng)
 */
class GioHangController extends Controller
{
    public function __construct(
        private DonHangService $donHangService,
        private VietnamAddressService $vietnamAddressService,
    ) {}

    private function khachHang()
    {
        return Auth::user()->khachHang; // null nếu là admin/nhân viên
    }

    private function gioHangCuaToi(): GioHang
    {
        return GioHang::firstOrCreate(['khach_hang_id' => $this->khachHang()->id]);
    }

    private function chanNeuKhongPhaiKhach()
    {
        if (!$this->khachHang()) {
            return redirect()->route('sanpham.index')
                ->with('error', 'Chỉ tài khoản khách hàng mới sử dụng được giỏ hàng.');
        }
        return null;
    }

    public function index()
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $khachHang = $this->khachHang();
        $chiTiets  = $this->gioHangCuaToi()->chiTiets()->with(['sanPham', 'size'])->get();

        $gioHang = [];
        foreach ($chiTiets as $ct) {
            $sanPham = $ct->sanPham;
            $size = $ct->size;
            $khaDung = $sanPham && !$sanPham->trashed() && $size
                && (int) $size->san_pham_id === (int) $ct->san_pham_id;

            $gioHang[$ct->id] = [
                'id'       => $ct->san_pham_id,
                'ten_sp'   => $sanPham?->ten_sp ?? 'Sản phẩm không còn tồn tại',
                'gia'      => $khaDung ? $sanPham->gia_khuyen_mai : 0,
                'gia_goc'  => $khaDung ? $sanPham->gia_ban : 0,
                'sale'     => $khaDung ? $sanPham->phan_tram_giam : 0,
                'hinh_anh' => $sanPham?->hinh_anh,
                'size'     => $size?->ten_size ?? 'Không xác định',
                'so_luong' => $ct->so_luong,
                'ton'      => $khaDung ? $size->so_luong_ton : 0,
                'kha_dung' => $khaDung,
            ];
        }

        $tien           = $this->donHangService->tinhTien($chiTiets, $khachHang);
        $tongTien       = $tien['tam_tinh'];
        $diaChiMacDinh  = $khachHang->dia_chi;
        $tenHang        = optional($khachHang->hangThanhVien)->ten_hang;
        $coSanPhamKhongKhaDung = collect($gioHang)->contains(fn ($item) => !$item['kha_dung']);
        try {
            $tinhThanhs = $this->vietnamAddressService->provinces();
            $loiDuLieuDiaChi = null;
        } catch (RuntimeException $e) {
            $tinhThanhs = [];
            $loiDuLieuDiaChi = $e->getMessage();
        }

        return view('giohang', compact(
            'gioHang', 'tongTien', 'tien', 'diaChiMacDinh', 'tenHang', 'coSanPhamKhongKhaDung',
            'tinhThanhs', 'loiDuLieuDiaChi', 'khachHang'
        ));
    }

    public function phuongXas(Request $request)
    {
        $provinceCode = trim((string) $request->query('tinh_thanh_code'));

        try {
            if (!$this->vietnamAddressService->provinceExists($provinceCode)) {
                return response()->json(['message' => 'Tỉnh/Thành phố không hợp lệ.'], 422);
            }

            return response()->json(['data' => $this->vietnamAddressService->wards($provinceCode)]);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    public function addToCart(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $sanPham = SanPham::withTrashed()->find($request->id);
        if (!$sanPham || $sanPham->trashed()) {
            return back()->with('error', 'Sản phẩm này hiện không còn kinh doanh và không thể thêm vào giỏ hàng.');
        }
        $size    = Size::where('san_pham_id', $sanPham->id)->where('ten_size', $request->size)->first();

        if (!$size) {
            return back()->with('error', 'Size "' . $request->size . '" chưa hỗ trợ đặt trực tiếp. Với size may đo, vui lòng liên hệ cửa hàng.');
        }

        $chiTiet  = ChiTietGioHang::firstOrNew([
            'gio_hang_id' => $this->gioHangCuaToi()->id,
            'san_pham_id' => $sanPham->id,
            'size_id'     => $size->id,
        ]);
        $soLuongMoi = ((int) $chiTiet->so_luong) + 1;

        // TV1: kiểm tra tồn kho trước khi cho vào giỏ
        if (!$sanPham->kiemTraTonKhoSize($size->ten_size, $soLuongMoi)) {
            return back()->with('error', 'Size ' . $size->ten_size . ' hiện không đủ số lượng trong kho!');
        }

        $chiTiet->so_luong = $soLuongMoi;
        $chiTiet->save();

        return redirect($this->diaChiQuayLai($request))->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function updateSL(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $chiTiet = ChiTietGioHang::where('gio_hang_id', $this->gioHangCuaToi()->id)
            ->with(['sanPham', 'size'])
            ->findOrFail($request->key);

        $soLuongMoi = $chiTiet->so_luong + (int) $request->type;

        if (!$chiTiet->sanPham || $chiTiet->sanPham->trashed() || !$chiTiet->size
            || (int) $chiTiet->size->san_pham_id !== (int) $chiTiet->san_pham_id) {
            return redirect()->route('giohang.index')
                ->with('error', 'Sản phẩm này hiện không còn kinh doanh. Vui lòng xóa khỏi giỏ hàng.');
        }

        if ($soLuongMoi <= 0) {
            $chiTiet->delete();
        } elseif (!$chiTiet->sanPham->kiemTraTonKhoSize($chiTiet->size->ten_size, $soLuongMoi)) {
            return redirect()->route('giohang.index')
                ->with('error', 'Size ' . $chiTiet->size->ten_size . ' chỉ còn ' . $chiTiet->size->so_luong_ton . ' sản phẩm!');
        } else {
            $chiTiet->update(['so_luong' => $soLuongMoi]);
        }

        return redirect()->route('giohang.index');
    }

    public function remove(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        ChiTietGioHang::where('gio_hang_id', $this->gioHangCuaToi()->id)
            ->where('id', $request->key)
            ->delete();

        return redirect()->route('giohang.index');
    }

    public function checkout(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $request->merge([
            'ten_nguoi_nhan' => preg_replace('/\s+/u', ' ', trim((string) $request->input('ten_nguoi_nhan'))),
            'so_dien_thoai_nhan' => $this->chuanHoaSoDienThoai((string) $request->input('so_dien_thoai_nhan')),
            'dia_chi_chi_tiet' => preg_replace('/\s+/u', ' ', trim((string) $request->input('dia_chi_chi_tiet'))),
            'tinh_thanh_code' => trim((string) $request->input('tinh_thanh_code')),
            'phuong_xa_code' => trim((string) $request->input('phuong_xa_code')),
        ]);

        $validator = Validator::make($request->all(), [
            'ten_nguoi_nhan' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s\.\'’\-]+$/u'],
            'so_dien_thoai_nhan' => ['required', 'regex:/^0(?:3|5|7|8|9)\d{8}$/'],
            'tinh_thanh_code' => ['required', 'string', 'max:10'],
            'phuong_xa_code' => ['required', 'string', 'max:10'],
            'dia_chi_chi_tiet' => ['required', 'string', 'min:2', 'max:200'],
            'phuong_thuc' => 'required|in:COD,Chuyển khoản QR',
            'ghi_chu'     => 'nullable|string|max:500',
        ], [
            'ten_nguoi_nhan.required' => 'Vui lòng nhập tên người nhận.',
            'ten_nguoi_nhan.min' => 'Tên người nhận phải có ít nhất 2 ký tự.',
            'ten_nguoi_nhan.max' => 'Tên người nhận không được vượt quá 100 ký tự.',
            'ten_nguoi_nhan.regex' => 'Tên người nhận không hợp lệ.',
            'so_dien_thoai_nhan.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'so_dien_thoai_nhan.regex' => 'Số điện thoại người nhận không hợp lệ.',
            'tinh_thanh_code.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'phuong_xa_code.required' => 'Vui lòng chọn Phường/Xã.',
            'dia_chi_chi_tiet.required' => 'Vui lòng nhập số nhà, tên đường hoặc địa chỉ giao hàng chi tiết.',
            'dia_chi_chi_tiet.min' => 'Vui lòng nhập số nhà, tòa nhà, thôn hoặc ấp cụ thể.',
            'dia_chi_chi_tiet.max' => 'Địa chỉ chi tiết không được vượt quá 200 ký tự.',
        ]);

        $diaChiHanhChinh = null;
        $validator->after(function ($validator) use ($request, &$diaChiHanhChinh) {
            if ($validator->errors()->hasAny(['tinh_thanh_code', 'phuong_xa_code'])) {
                return;
            }

            try {
                if (!$this->vietnamAddressService->provinceExists($request->tinh_thanh_code)) {
                    $validator->errors()->add('tinh_thanh_code', 'Tỉnh/Thành phố đã chọn không tồn tại.');
                    return;
                }

                $diaChiHanhChinh = $this->vietnamAddressService->resolve(
                    $request->tinh_thanh_code,
                    $request->phuong_xa_code
                );
                if (!$diaChiHanhChinh) {
                    $validator->errors()->add('phuong_xa_code', 'Phường/Xã đã chọn không thuộc Tỉnh/Thành phố này.');
                }
            } catch (RuntimeException $e) {
                $validator->errors()->add('tinh_thanh_code', $e->getMessage());
            }

            $detail = (string) $request->dia_chi_chi_tiet;
            $compact = preg_replace('/[\s,\.\-\/]+/u', '', $detail);
            if ($compact !== '' && (preg_match('/^(.)\1{5,}$/u', $compact)
                || count(array_unique(preg_split('//u', mb_strtolower($compact), -1, PREG_SPLIT_NO_EMPTY))) < 3)) {
                $validator->errors()->add('dia_chi_chi_tiet', 'Địa chỉ chi tiết chưa có ý nghĩa. Vui lòng nhập địa chỉ giao hàng cụ thể.');
            }
        });

        $data = $validator->validate();
        $data['dia_chi'] = implode(', ', [
            $data['dia_chi_chi_tiet'],
            $diaChiHanhChinh['ward']['name'],
            $diaChiHanhChinh['province']['name'],
        ]);

        try {
            $hoaDon = $this->donHangService->taoDonTuGioHang($this->khachHang(), $this->gioHangCuaToi(), $data);
        } catch (RuntimeException $e) {
            return redirect()->route('giohang.index')->with('error', $e->getMessage());
        }

        if ($data['phuong_thuc'] === 'Chuyển khoản QR') {
            return redirect()->route('donhang.thanhtoan', $hoaDon->id)
                ->with('success', 'Đơn hàng đã được tạo. Vui lòng quét QR và chuyển khoản đúng số tiền với nội dung là mã đơn hàng.');
        }

        return redirect()->route('donhang.chitiet', $hoaDon->id)
            ->with('success', 'Đặt hàng thành công! Mã đơn: ' . $hoaDon->ma_don_hang . '. Chờ xác nhận từ cửa hàng.');
    }

    private function chuanHoaSoDienThoai(string $phone): string
    {
        $phone = preg_replace('/[\s\.\-\(\)]+/', '', trim($phone));
        if (str_starts_with($phone, '+84')) {
            return '0' . substr($phone, 3);
        }
        if (str_starts_with($phone, '84')) {
            return '0' . substr($phone, 2);
        }

        return $phone;
    }

    /** Chỉ cho quay lại trang cùng domain, tránh open-redirect */
    private function diaChiQuayLai(Request $request): string
    {
        $url = $request->url;
        if ($url && parse_url($url, PHP_URL_HOST) === $request->getHost()) {
            return $url;
        }
        return route('sanpham.index');
    }
}
