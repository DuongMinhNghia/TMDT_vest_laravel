{{-- Task 3.4 - Khối đánh giá sản phẩm (Thành viên 3). Được nhúng vào chitietsanpham.blade.php --}}
@php
    $khDangNhap = Auth::check() ? Auth::user()->khachHang : null;
    $danhGias   = $sanPham->danhGias()->with('khachHang')->latest()->get();
    $diemTB     = $danhGias->avg('so_sao');
    $daMua      = false;
    $danhGiaCuaToi = null;

    if ($khDangNhap) {
        $daMua = \App\Models\ChiTietHoaDon::where('san_pham_id', $sanPham->id)
            ->whereHas('hoaDon', fn($q) => $q
                ->where('khach_hang_id', $khDangNhap->id)
                ->where('trang_thai', \App\Models\HoaDon::HOAN_THANH))
            ->exists();
        $danhGiaCuaToi = $danhGias->firstWhere('khach_hang_id', $khDangNhap->id);
    }
@endphp

<div id="danh-gia" style="background:#111; padding:40px 0; border-top:1px solid #222;">
    <div class="container" style="color:#e0e0e0;">
        <h4 style="color:#C5A059; font-family:'Playfair Display',serif; margin-bottom:20px;">
            <i class="bi bi-star-fill"></i> Đánh Giá Sản Phẩm
            @if($danhGias->count())
            <span style="font-size:1rem; color:#aaa; font-family:inherit;">
                — {{ number_format($diemTB, 1) }}/5 ({{ $danhGias->count() }} đánh giá)
            </span>
            @endif
        </h4>

        @if(session('success'))
        <div class="alert" style="background:rgba(197,160,89,0.15); border:1px solid #C5A059; color:#C5A059; border-radius:10px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert" style="background:rgba(255,68,68,0.12); border:1px solid #ff4444; color:#ff6b6b; border-radius:10px;">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert" style="background:rgba(255,68,68,0.12); border:1px solid #ff4444; color:#ff6b6b; border-radius:10px;">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        {{-- Form đánh giá: chỉ hiện với khách đã mua và đơn đã hoàn thành --}}
        @if($daMua)
        <form method="POST" action="{{ route('danhgia.luu', $sanPham->id) }}" enctype="multipart/form-data"
              style="background:#1a1a1a; border:1px solid #333; border-radius:12px; padding:20px; margin-bottom:30px;">
            @csrf
            <div style="font-weight:600; margin-bottom:10px;">{{ $danhGiaCuaToi ? 'Cập nhật đánh giá của bạn' : 'Viết đánh giá của bạn' }}</div>

            <div style="margin-bottom:12px;">
                <span style="color:#aaa; margin-right:10px;">Chấm sao:</span>
                @for($i = 1; $i <= 5; $i++)
                <label style="margin-right:12px; cursor:pointer;">
                    <input type="radio" name="so_sao" value="{{ $i }}" {{ (int) old('so_sao', $danhGiaCuaToi->so_sao ?? 5) === $i ? 'checked' : '' }}>
                    {{ $i }} <i class="bi bi-star-fill" style="color:#C5A059;"></i>
                </label>
                @endfor
            </div>

            <textarea name="noi_dung" rows="3" maxlength="1000" placeholder="Nhận xét về form dáng, chất liệu..."
                style="width:100%; background:#111; border:1px solid #333; color:#e0e0e0; border-radius:8px; padding:10px; margin-bottom:12px;">{{ old('noi_dung', $danhGiaCuaToi->noi_dung ?? '') }}</textarea>

            <div style="margin-bottom:12px;">
                <label style="color:#aaa; display:block; margin-bottom:6px;">Ảnh thực tế khi mặc (tối đa 2MB)</label>
                <input type="file" name="hinh_anh" accept="image/*" style="color:#aaa;">
            </div>

            <button type="submit" style="background:linear-gradient(135deg,#C5A059,#a08045); color:#000; border:none; border-radius:50px; padding:10px 28px; font-weight:700;">
                <i class="bi bi-send-fill me-1"></i> Gửi đánh giá
            </button>
        </form>
        @elseif($khDangNhap)
        <div style="color:#777; font-size:0.9rem; margin-bottom:25px;">
            <i class="bi bi-info-circle"></i> Bạn có thể đánh giá sau khi đơn hàng chứa sản phẩm này đã hoàn thành.
        </div>
        @endif

        {{-- Danh sách đánh giá --}}
        @forelse($danhGias as $dg)
        <div style="background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; padding:16px 20px; margin-bottom:12px;">
            <div class="d-flex justify-content-between">
                <strong>{{ $dg->khachHang->ho_ten ?? 'Khách hàng' }}</strong>
                <span style="color:#777; font-size:0.8rem;">{{ $dg->created_at->format('d/m/Y') }}</span>
            </div>
            <div style="color:#C5A059; margin:4px 0;">
                @for($i = 1; $i <= 5; $i++)
                <i class="bi {{ $i <= $dg->so_sao ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
            </div>
            @if($dg->noi_dung)<p style="margin:6px 0; color:#ccc;">{{ $dg->noi_dung }}</p>@endif
            @if($dg->hinh_anh)
            <img src="{{ asset('images/' . $dg->hinh_anh) }}" alt="Ảnh đánh giá" style="max-width:180px; border-radius:8px; margin-top:6px;">
            @endif
        </div>
        @empty
        <p style="color:#777;">Chưa có đánh giá nào cho sản phẩm này.</p>
        @endforelse
    </div>
</div>
