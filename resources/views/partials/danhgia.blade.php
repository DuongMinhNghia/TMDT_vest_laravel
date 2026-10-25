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

<div id="danh-gia" style="background:#141720; padding:50px 0; border-top:1px solid rgba(255,255,255,0.08);">
    <div class="container" style="color:#F1F5F9;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <h4 style="color:#D4B26F; font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; margin:0;">
                <i class="bi bi-star-fill me-2"></i>Đánh Giá Từ Khách Hàng
            </h4>
            @if($danhGias->count())
            <div style="font-size:0.95rem; color:#94A3B8; background:#181B24; border:1px solid rgba(255,255,255,0.08); padding:6px 16px; border-radius:20px;">
                Điểm trung bình: <strong style="color:#D4B26F; font-size:1.1rem;">{{ number_format($diemTB, 1) }}</strong>/5 <span style="color:#64748B;">({{ $danhGias->count() }} đánh giá)</span>
            </div>
            @endif
        </div>

        @if(session('success'))
        <div class="alert mb-4" style="background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.3); color:#6EE7B7; border-radius:10px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert mb-4" style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.3); color:#FCA5A5; border-radius:10px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert mb-4" style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.3); color:#FCA5A5; border-radius:10px;">
            @foreach($errors->all() as $e)<div><i class="bi bi-x-circle me-1"></i>{{ $e }}</div>@endforeach
        </div>
        @endif

        {{-- Form đánh giá: chỉ hiện với khách đã mua và đơn đã hoàn thành --}}
        @if($daMua)
        <form method="POST" action="{{ route('danhgia.luu', $sanPham->id) }}" enctype="multipart/form-data"
              style="background:#181B24; border:1px solid rgba(212,178,111,0.3); border-radius:14px; padding:24px 28px; margin-bottom:35px; box-shadow:0 8px 24px rgba(0,0,0,0.3);">
            @csrf
            <div style="font-weight:700; color:#D4B26F; font-size:1.05rem; margin-bottom:14px;">
                <i class="bi bi-pencil-square me-2"></i>{{ $danhGiaCuaToi ? 'Cập nhật đánh giá của bạn' : 'Viết đánh giá của bạn' }}
            </div>

            <div style="margin-bottom:14px;" class="d-flex align-items-center gap-3 flex-wrap">
                <span style="color:#CBD5E1; font-size:0.88rem; font-weight:600;">Chấm sao chất lượng:</span>
                <div class="d-flex gap-3">
                    @for($i = 1; $i <= 5; $i++)
                    <label style="cursor:pointer; display:flex; align-items:center; gap:4px; font-weight:600; color:#F1F5F9; font-size:0.9rem;">
                        <input type="radio" name="so_sao" value="{{ $i }}" {{ (int) old('so_sao', $danhGiaCuaToi->so_sao ?? 5) === $i ? 'checked' : '' }} style="accent-color:#D4B26F;">
                        {{ $i }} <i class="bi bi-star-fill" style="color:#D4B26F; font-size:0.85rem;"></i>
                    </label>
                    @endfor
                </div>
            </div>

            <textarea name="noi_dung" rows="3" maxlength="1000" placeholder="Nhận xét về form dáng may đo, chất liệu vải, độ hoàn thiện của vest..."
                style="width:100%; background:#111317; border:1px solid rgba(255,255,255,0.1); color:#F1F5F9; border-radius:8px; padding:12px; margin-bottom:14px; font-size:0.9rem; transition:border 0.25s;"
                onfocus="this.style.borderColor='#D4B26F'; this.style.outline='none';"
                onblur="this.style.borderColor='rgba(255,255,255,0.1)';">{{ old('noi_dung', $danhGiaCuaToi->noi_dung ?? '') }}</textarea>

            <div style="margin-bottom:16px;">
                <label style="color:#94A3B8; display:block; font-size:0.82rem; margin-bottom:6px;">Ảnh thực tế khi mặc thử vest (Tối đa 2MB)</label>
                <input type="file" name="hinh_anh" accept="image/*" style="color:#94A3B8; font-size:0.85rem;">
            </div>

            <button type="submit" style="background:linear-gradient(135deg,#D4B26F,#B89246); color:#0c0e12; border:none; border-radius:8px; padding:11px 26px; font-weight:700; font-size:0.9rem; cursor:pointer; transition:all 0.25s ease;"
                onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <i class="bi bi-send-fill me-1"></i> Gửi Đánh Giá
            </button>
        </form>
        @elseif($khDangNhap)
        <div style="color:#94A3B8; font-size:0.88rem; margin-bottom:30px; background:#181B24; border:1px solid rgba(255,255,255,0.06); padding:14px 20px; border-radius:10px;">
            <i class="bi bi-info-circle text-warning me-2"></i> Quý khách có thể đánh giá sau khi đơn hàng chứa sản phẩm này đã hoàn thành.
        </div>
        @endif

        {{-- Danh sách đánh giá --}}
        <div class="row g-3">
            @forelse($danhGias as $dg)
            <div class="col-12">
                <div style="background:#181B24; border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:18px 22px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong style="color:#F1F5F9; font-size:0.96rem;">
                            <i class="bi bi-person-check-fill me-1" style="color:#D4B26F;"></i>{{ $dg->khachHang->ho_ten ?? 'Khách hàng Kingsman' }}
                        </strong>
                        <span style="color:#64748B; font-size:0.8rem;">{{ $dg->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div style="color:#D4B26F; margin-bottom:8px; font-size:0.9rem;">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $dg->so_sao ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    @if($dg->noi_dung)
                    <p style="margin:0 0 10px; color:#CBD5E1; font-size:0.92rem; line-height:1.6;">{{ $dg->noi_dung }}</p>
                    @endif
                    @if($dg->hinh_anh)
                    <img src="{{ asset('images/' . $dg->hinh_anh) }}" alt="Ảnh đánh giá" style="max-width:180px; max-height:140px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,0.1); margin-top:4px;">
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12">
                <p style="color:#64748B; font-size:0.9rem; margin:0;">Chưa có đánh giá nào cho sản phẩm này.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
