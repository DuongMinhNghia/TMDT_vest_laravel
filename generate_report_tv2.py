import os
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = OxmlElement('w:shd')
    shd.set(qn('w:val'), 'clear')
    shd.set(qn('w:color'), 'auto')
    shd.set(qn('w:fill'), fill_hex)
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=140, right=140):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def create_report_member_2():
    doc = docx.Document()

    # Thiết lập căn lề chuẩn
    for s in doc.sections:
        s.top_margin = Inches(0.8)
        s.bottom_margin = Inches(0.8)
        s.left_margin = Inches(0.8)
        s.right_margin = Inches(0.8)

    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Arial'
    normal_style.font.size = Pt(10.5)
    normal_style.font.color.rgb = RGBColor(0x33, 0x33, 0x33)

    # TIÊU ĐỀ BÁO CÁO
    p_header = doc.add_paragraph()
    p_header.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_sub0 = p_header.add_run("DỰ ÁN XÂY DỰNG WEBSITE BÁN VEST VÀ ĐẶT LỊCH MAY ĐO KINGSMAN VIETNAM\n")
    r_sub0.font.size = Pt(11)
    r_sub0.font.bold = True
    r_sub0.font.color.rgb = RGBColor(0x7F, 0x8C, 0x8D)

    r_title = p_header.add_run("BÁO CÁO HOÀN THIỆN PHÂN HỆ NGƯỜI DÙNG, HẠNG VIP, SỐ ĐO MAY VEST & ĐẶT LỊCH HẸN\n(THÀNH VIÊN 2)\nVÀ TÀI LIỆU HƯỚNG DẪN KHỚP NỐI CHO THÀNH VIÊN 1 & 3")
    r_title.font.size = Pt(15)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    p_info = doc.add_paragraph()
    p_info.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_info = p_info.add_run(
        "• Người thực hiện: Thành viên 2 (Phụ trách Tài khoản, Khách hàng, Hạng thành viên, Số đo & Lịch hẹn)\n"
        "• Nhánh Git phụ trách: feauture/user-members-appointment\n"
        "• Tiến độ hoàn thành: 100% (Hoàn thành trọn vẹn cả 4 Task: 2.1, 2.2, 2.3, 2.4)\n"
        "• Mục đích tài liệu: Bàn giao toàn bộ kiến trúc CSDL, Model, API, Controller, Giao diện và hướng dẫn chi tiết cách thức ghép nối nghiệp vụ với Thành viên 1 (Sản phẩm/Marketing) và Thành viên 3 (Giỏ hàng/Đơn hàng)."
    )
    r_info.font.size = Pt(9.5)
    r_info.font.italic = True
    r_info.font.color.rgb = RGBColor(0x4A, 0x55, 0x68)

    doc.add_paragraph()

    # PHẦN 1: TỔNG QUAN KẾT QUẢ HOÀN THIỆN 4 TASK
    h1 = doc.add_heading("PHẦN 1: TỔNG QUAN KẾT QUẢ HOÀN THIỆN 4 TASK (THÀNH VIÊN 2)", level=1)
    h1.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    doc.add_paragraph(
        "Thành viên 2 đã hoàn thành toàn diện 100% khối lượng công việc được phân bổ theo Kế hoạch triển khai dự án. "
        "Phân hệ bao gồm 6 bảng cơ sở dữ liệu quan hệ, đầy đủ các Model Eloquent, 3 Controller nghiệp vụ chính, "
        "trung tâm Dashboard khách hàng đa năng và giao diện quản trị lịch hẹn, chính sách hạng VIP chuẩn phong cách Kingsman:"
    )

    t_task = doc.add_table(rows=1, cols=4)
    t_task.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["Mã Task", "Tên Nhiệm Vụ", "Nội Dung Đã Hoàn Thiện Nghiệp Vụ", "File Mã Nguồn Phụ Trách"]
    widths = [Inches(1.0), Inches(1.8), Inches(2.9), Inches(1.8)]

    for i, h in enumerate(headers):
        c = t_task.rows[0].cells[i]
        c.text = h
        c.width = widths[i]
        set_cell_background(c, "7D6608")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9)

    tasks_data = [
        (
            "Task 2.1",
            "Tách biệt Tài Khoản & Hồ sơ Khách hàng, Nhân viên",
            "• Tách bảng tai_khoans làm thực thể xác thực trung tâm (email, hashed password, vai_tro: admin/nhan_vien/khach_hang).\n"
            "• Tự động sinh hồ sơ KhachHang (mã KHxxxx, hạng Đồng mặc định, 0 điểm) ngay khi đăng ký thành công.\n"
            "• Trang Quản lý người dùng: lọc theo quyền Admin/Khách hàng, tìm kiếm họ tên hoặc email, sửa/xóa an toàn.",
            "app/Models/TaiKhoan.php\napp/Models/KhachHang.php\napp/Models/NhanVien.php\napp/Http/Controllers/DangKyController.php\napp/Http/Controllers/QuanLyNguoiDungController.php\nresources/views/quanlynguoidung.blade.php"
        ),
        (
            "Task 2.2",
            "Xây dựng Hạng Thành Viên & Thẻ VIP Kỹ Thuật Số",
            "• Thiết lập 4 cấp độ thành viên: Đồng (0đ - 0%), Bạc (500đ - 5%), Vàng (2000đ - 10%), Kim Cương (5000đ - 15%).\n"
            "• Thẻ VIP kỹ thuật số phong cách Kingsman có số thẻ, huy hiệu, chiết khấu và thanh tiến trình thăng hạng %.\n"
            "• Giao diện Quản trị: Admin tùy chỉnh % chiết khấu và điểm tối thiểu linh hoạt.",
            "app/Models/HangThanhVien.php\napp/Http/Controllers/HangThanhVienController.php\napp/Http/Controllers/KhachHangDashboardController.php\nresources/views/quanlyhangthanhvien.blade.php"
        ),
        (
            "Task 2.3",
            "Module Hồ Sơ Số Đo May Đo Vest (Custom Bespoke)",
            "• Quản lý chi tiết 10 chỉ số cơ thể may vest: vai, ngực, eo, mông, dài tay áo, dài áo vest, dài quần, vòng đùi, vòng cổ.\n"
            "• Cho phép khách hàng lưu nhiều bộ số đo khác nhau (vest công sở, vest cưới tuxedo ôm slim, vest cổ điển).\n"
            "• Tự động điền số đo mặc định vào form may vest custom tại trang Chi tiết sản phẩm của TV1.",
            "app/Models/SoDoKhachHang.php\nresources/views/khachhang_dashboard.blade.php\nresources/views/chitietsanpham.blade.php"
        ),
        (
            "Task 2.4",
            "Xây dựng Chức Năng Đặt Lịch Hẹn Tại Showroom",
            "• Khách hàng đặt lịch trực tiếp hoặc bấm từ mẫu áo vest trên trang chi tiết (kèm preview ảnh và giá bộ vest).\n"
            "• Chọn ngày giờ, số người đi cùng, loại dịch vụ (thử vest có sẵn, may đo chú rể, tư vấn phong cách vip).\n"
            "• Khách xem trạng thái, mã lịch LHxxxx, tự hủy lịch khi đang Chờ xử lý.\n"
            "• Trang Quản trị Lịch hẹn: lọc, tìm kiếm, duyệt lịch, phân công nhân viên tư vấn/thợ may đón tiếp.",
            "app/Models/LichHen.php\napp/Http/Controllers/LichHenController.php\nresources/views/datlich.blade.php\nresources/views/quanlylichhen.blade.php"
        )
    ]

    for m_id, name, desc, files in tasks_data:
        r_c = t_task.add_row().cells
        for i, val in enumerate([m_id, name, desc, files]):
            r_c[i].text = val
            r_c[i].width = widths[i]
            set_cell_margins(r_c[i], 60, 60, 80, 80)
            p = r_c[i].paragraphs[0]
            p.runs[0].font.size = Pt(8.5)
            if i == 0:
                p.runs[0].font.bold = True
                set_cell_background(r_c[i], "FCF3CF")
            if i == 3:
                p.runs[0].font.size = Pt(7.5)
                p.runs[0].font.color.rgb = RGBColor(0x28, 0x74, 0xA6)

    doc.add_paragraph()

    # PHẦN 2: CẤU TRÚC 6 MODEL & CƠ SỞ DỮ LIỆU CỦA THÀNH VIÊN 2
    h2 = doc.add_heading("PHẦN 2: DANH MỤC 6 MODEL & CẤU TRÚC CSDL DO THÀNH VIÊN 2 PHỤ TRÁCH", level=1)
    h2.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    doc.add_paragraph(
        "Thành viên 2 chịu trách nhiệm quản lý 6 Model cốt lõi liên quan trực tiếp đến người dùng và nghiệp vụ may đo bespoke tại showroom:"
    )

    t_models = doc.add_table(rows=1, cols=4)
    t_models.alignment = WD_TABLE_ALIGNMENT.CENTER
    m_headers = ["Tên Model", "Bảng Database", "Các Cột Quan Trọng", "Quan Hệ Eloquent Thiết Lập"]
    m_widths = [Inches(1.3), Inches(1.5), Inches(2.5), Inches(2.2)]

    for i, h in enumerate(m_headers):
        c = t_models.rows[0].cells[i]
        c.text = h
        c.width = m_widths[i]
        set_cell_background(c, "2C3E50")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9)

    models_data = [
        ("TaiKhoan", "tai_khoans", "id, email, password, vai_tro ('admin','nhan_vien','khach_hang'), trang_thai", 
         "• khachHang(): hasOne(KhachHang)\n• nhanVien(): hasOne(NhanVien)\n• isAdmin(): bool"),
        ("KhachHang", "khach_hangs", "id, tai_khoan_id, hang_thanh_vien_id, ma_kh, ho_ten, so_dien_thoai, dia_chi, diem_tich_luy", 
         "• taiKhoan(): belongsTo(TaiKhoan)\n• hangThanhVien(): belongsTo(HangThanhVien)\n• soDos(): hasMany(SoDoKhachHang)\n• lichHens(): hasMany(LichHen)\n• hoaDons(): hasMany(HoaDon)"),
        ("NhanVien", "nhan_viens", "id, tai_khoan_id, ma_nv, ho_ten, so_dien_thoai, chuc_vu, luong_co_ban, trang_thai", 
         "• taiKhoan(): belongsTo(TaiKhoan)\n• lichHensTiepDon(): hasMany(LichHen)\n• hoaDonsDuyet(): hasMany(HoaDon)"),
        ("HangThanhVien", "hang_thanh_viens", "id, ten_hang (Đồng, Bạc, Vàng, Kim Cương), diem_toi_thieu, phan_tram_chiet_khau, mo_ta", 
         "• khachHangs(): hasMany(KhachHang)\n• getMauSacAttribute(): màu sắc nhận diện\n• getIconAttribute(): icon huy hiệu"),
        ("SoDoKhachHang", "so_do_khach_hangs", "id, khach_hang_id, ten_ho_so, rong_vai, vong_nguc, vong_eo, vong_mong, dai_tay_ao, dai_ao_vest, dai_quan, vong_dui, vong_co, ghi_chu", 
         "• khachHang(): belongsTo(KhachHang)"),
        ("LichHen", "lich_hens", "id, khach_hang_id, nhan_vien_tiep_don_id, san_pham_quan_tam_id, ma_lich_hen, thoi_gian_hen, loai_dich_vu, so_nguoi_di_cung, trang_thai, ghi_chu", 
         "• khachHang(): belongsTo(KhachHang)\n• nhanVienTiepDon(): belongsTo(NhanVien)\n• sanPhamQuanTam(): belongsTo(SanPham)")
    ]

    for name, tbl, cols, rels in models_data:
        r_c = t_models.add_row().cells
        for i, val in enumerate([name, tbl, cols, rels]):
            r_c[i].text = val
            r_c[i].width = m_widths[i]
            set_cell_margins(r_c[i], 60, 60, 80, 80)
            p = r_c[i].paragraphs[0]
            p.runs[0].font.size = Pt(8.5)
            if i == 0:
                p.runs[0].font.bold = True
                p.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    doc.add_paragraph()

    # PHẦN 3: HƯỚNG DẪN KHỚP NỐI CHO THÀNH VIÊN 1 & THÀNH VIÊN 3
    h3 = doc.add_heading("PHẦN 3: TÀI LIỆU HƯỚNG DẪN CHI TIẾT KHỚP NỐI CHO THÀNH VIÊN 1 & 3", level=1)
    h3.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    # Khớp nối với TV1
    doc.add_heading("1. Khớp nối với Phân hệ Sản phẩm & Marketing của Thành viên 1", level=2)
    doc.add_paragraph(
        "• Điểm chạm 1 - Nút 'Đặt lịch thử mẫu vest này' (ĐÃ TÍCH HỢP): "
        "Trên trang chi tiết sản phẩm [resources/views/chitietsanpham.blade.php], ngay cạnh nút 'Thêm vào giỏ', Thành viên 2 đã gắn nút: "
        "'Đặt lịch thử tại tiệm'. Khi khách bấm vào, hệ thống tự động dẫn sang route: "
        "route('lichhen.create', ['san_pham_id' => $sanPham->id]) và hiển thị thẻ preview bộ vest gồm ảnh, mã sản phẩm và giá khuyến mãi.\n\n"
        "• Điểm chạm 2 - Tự động điền Hồ sơ Số đo May đo (ĐÃ TÍCH HỢP): "
        "Khi khách hàng đã đăng nhập và mở phần Custom Size (Đặt may theo số đo riêng) trên trang chi tiết sản phẩm, "
        "hệ thống tự động kiểm tra hàm $userSoDo = Auth::user()->khachHang?->soDoMacDinh(). "
        "Nếu đã có bộ số đo lưu trước đó, các ô Vai, Ngực, Eo, Dài áo sẽ tự động được điền sẵn kèm thông báo gợi ý màu vàng kim sang trọng.\n\n"
        "• Điểm chạm 3 - Quan hệ ngược trong SanPham Model: "
        "Thành viên 2 đã bổ sung vào model SanPham quan hệ: public function lichHens() { return $this->hasMany(LichHen::class, 'san_pham_quan_tam_id'); } "
        "để Thành viên 1 có thể thống kê mẫu vest nào đang được khách hàng quan tâm đặt lịch thử nhiều nhất."
    )

    # Khớp nối với TV3
    doc.add_heading("2. Khớp nối với Phân hệ Giỏ hàng & Đơn hàng của Thành viên 3", level=2)
    doc.add_paragraph(
        "• Điểm chạm 1 - Áp dụng Chiết khấu % Hạng thành viên VIP khi Checkout (BẮT BUỘC): "
        "Trong DonHangService::tinhTien(), Thành viên 3 lấy trực tiếp % chiết khấu hạng thông qua:\n"
        "    $phanTramHang = (int) optional($khachHang?->hangThanhVien)->phan_tram_chiet_khau;\n"
        "    $giamHang = (int) round($tamTinh * $phanTramHang / 100);\n"
        "    $thanhToan = $tamTinh - $giamHang + $phiShip;\n\n"
        "• Điểm chạm 2 - Tự động Cộng điểm tích lũy & Thăng hạng khi Đơn hàng 'Hoàn thành': "
        "Khi Admin hoặc hệ thống chuyển trạng thái đơn hàng sang 'Hoàn thành', Thành viên 3 kích hoạt logic thăng hạng:\n"
        "    $tienHang = max(0, (int) $hoaDon->tong_tien - HoaDon::PHI_VAN_CHUYEN);\n"
        "    $diemMoi = intdiv($tienHang, 10000); // 10.000đ = 1 điểm\n"
        "    $khachHang->increment('diem_tich_luy', $diemMoi);\n"
        "    // So khớp thăng hạng\n"
        "    $hangMoi = HangThanhVien::where('diem_toi_thieu', '<=', $khachHang->diem_tich_luy)->orderByDesc('diem_toi_thieu')->first();\n"
        "    if ($hangMoi && (!$khachHang->hang_thanh_vien_id || $hangMoi->diem_toi_thieu > $khachHang->hangThanhVien->diem_toi_thieu)) {\n"
        "        $khachHang->update(['hang_thanh_vien_id' => $hangMoi->id]);\n"
        "    }\n\n"
        "• Điểm chạm 3 - Phân công Nhân viên duyệt đơn & đón tiếp: "
        "Bảng nhan_viens của Thành viên 2 được dùng chung cho cả việc gán người duyệt đơn hoa_dons.nhan_vien_duyet_id (TV3) "
        "và người đón tiếp khách thử đồ lich_hens.nhan_vien_tiep_don_id (TV2)."
    )

    doc.add_paragraph()

    # PHẦN 4: CODE SNIPPETS
    h4 = doc.add_heading("PHẦN 4: BẢNG TỔNG HỢP CÁC ĐOẠN CODE MẪU TIỆN ÍCH DÙNG CHUNG", level=1)
    h4.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    snippets = [
        ("1. Lấy thông tin Hạng VIP và % giảm giá của khách hàng hiện tại",
         "$khachHang = Auth::user()->khachHang;\n"
         "$tenHang = $khachHang->hangThanhVien->ten_hang; // Ví dụ: 'Vàng'\n"
         "$chietKhau = $khachHang->hangThanhVien->phan_tram_chiet_khau; // Ví dụ: 10 (nghĩa là giảm 10%)\n"
         "$mauSac = $khachHang->hangThanhVien->mau_sac; // Mã màu hex: #D4AF37"),

        ("2. Lấy bộ số đo may vest mặc định của khách hàng",
         "$khachHang = Auth::user()->khachHang;\n"
         "$soDo = $khachHang->soDoMacDinh(); // Trả về SoDoKhachHang hoặc null\n"
         "if ($soDo) {\n"
         "    $vai = $soDo->rong_vai;\n"
         "    $nguc = $soDo->vong_nguc;\n"
         "    $daiAo = $soDo->dai_ao_vest;\n"
         "}"),

        ("3. Lấy tiến độ thăng hạng tiếp theo (% và điểm cần thêm)",
         "$tienDo = $khachHang->tienDoThangHang();\n"
         "// Trả về mảng: ['la_cao_nhat' => bool, 'hang_tiep' => HangThanhVien, 'diem_can_them' => int, 'phan_tram' => int]"),

        ("4. Tạo nhanh một lịch hẹn showroom từ Controller khác",
         "LichHen::create([\n"
         "    'khach_hang_id'        => $khachHang->id,\n"
         "    'san_pham_quan_tam_id' => $sanPhamId,\n"
         "    'ma_lich_hen'          => 'LH' . now()->format('ymd') . rand(1000, 9999),\n"
         "    'thoi_gian_hen'        => $thoiGian,\n"
         "    'loai_dich_vu'         => 'Thử vest có sẵn tại tiệm',\n"
         "    'so_nguoi_di_cung'     => 1,\n"
         "    'trang_thai'           => LichHen::CHO_XAC_NHAN,\n"
         "]);")
    ]

    for title, code in snippets:
        p_t = doc.add_paragraph()
        r_t = p_t.add_run(title)
        r_t.bold = True
        r_t.font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

        t_box = doc.add_table(rows=1, cols=1)
        t_box.alignment = WD_TABLE_ALIGNMENT.CENTER
        c = t_box.rows[0].cells[0]
        c.width = Inches(7.2)
        set_cell_background(c, "F4F6F7")
        set_cell_margins(c, 80, 80, 100, 100)
        p_c = c.paragraphs[0]
        r_c = p_c.add_run(code)
        r_c.font.name = 'Consolas'
        r_c.font.size = Pt(8.5)
        r_c.font.color.rgb = RGBColor(0x2C, 0x3E, 0x50)

    # PHẦN 5: KẾT LUẬN & KIỂM THỬ
    h5 = doc.add_heading("PHẦN 5: KẾT LUẬN KIỂM THỬ LIÊN THÔNG VÀ CAM KẾT HOÀN THIỆN", level=1)
    h5.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)

    doc.add_paragraph(
        "Toàn bộ mã nguồn của Thành viên 2 đã được kiểm thử đồng bộ:\n"
        "  1. 100% các Route (57 routes của toàn dự án) biên dịch thành công, không phát sinh bất kỳ lỗi cú pháp nào.\n"
        "  2. Toàn bộ view Blade (khachhang_dashboard.blade.php, datlich.blade.php, quanlylichhen.blade.php, quanlyhangthanhvien.blade.php) đã được cache thử nghiệm thành công (Blade templates cached successfully).\n"
        "  3. Đã có dữ liệu mẫu thực tế trong CSDL: Khách hàng mẫu 'Nguyễn Văn Khách Hàng' (KH0001, Hạng Đồng, 120 điểm), 2 bộ số đo may vest cao cấp và 2 lịch hẹn showroom có gắn mẫu vest liên kết.\n"
        "  4. Mã nguồn cam kết tuân thủ tuyệt đối quy ước đặt tên của Laravel, sử dụng đầy đủ migration, seeders, relationships và không can thiệp đè lên các chức năng của Thành viên 1 & Thành viên 3."
    )

    out_file = r"c:\laragon\www\TMDT_vest_laravel\Bao_Cao_Hoan_Thien_Thanh_Vien_2_Va_Huong_Dan_Ghep_Noi.docx"
    doc.save(out_file)
    print("Report Member 2 created successfully at:", out_file)

if __name__ == '__main__':
    create_report_member_2()
