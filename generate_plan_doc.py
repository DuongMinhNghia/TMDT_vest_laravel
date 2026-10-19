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

def create_plan_document():
    doc = docx.Document()

    # Margins
    for s in doc.sections:
        s.top_margin = Inches(0.8)
        s.bottom_margin = Inches(0.8)
        s.left_margin = Inches(0.8)
        s.right_margin = Inches(0.8)

    # Styles
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Arial'
    normal_style.font.size = Pt(10.5)
    normal_style.font.color.rgb = RGBColor(0x33, 0x33, 0x33)

    # HEADER / TITLE
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_title = p_title.add_run("KẾ HOẠCH TRIỂN KHAI DỰ ÁN & PHÂN CHIA CÔNG VIỆC\nWEBSITE BÁN VEST & ĐẶT LỊCH HẸN TẠI TIỆM")
    r_title.font.size = Pt(16)
    r_title.font.bold = True
    r_title.font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    p_sub = doc.add_paragraph()
    p_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_sub = p_sub.add_run("Mô hình phân công 3 thành viên làm việc song song (Sprint & Milestone Plan)\nCông nghệ: Laravel Framework & MySQL | Cơ sở dữ liệu: 18 Bảng")
    r_sub.font.size = Pt(10)
    r_sub.font.italic = True
    r_sub.font.color.rgb = RGBColor(0x7F, 0x8C, 0x8D)

    doc.add_paragraph()

    # SECTION 1: HIỆN TRẠNG
    h1 = doc.add_heading("I. ĐÁNH GIÁ HIỆN TRẠNG NGUỒN LỰC VÀ MÃ NGUỒN HIỆN TẠI", level=1)
    h1.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    doc.add_paragraph(
        "Dựa trên việc rà soát thực tế mã nguồn dự án tại thời điểm hiện tại so với bản thiết kế 18 bảng trong tài liệu thiết kế CSDL, mức độ hoàn thiện của từng phân hệ được đo lường cụ thể như sau:"
    )

    t_eval = doc.add_table(rows=1, cols=5)
    t_eval.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["Phân hệ công việc", "Bảng dữ liệu liên quan", "Mã nguồn hiện có", "Khối lượng đã làm", "Đánh giá mức độ"]
    widths = [Inches(1.8), Inches(1.8), Inches(1.8), Inches(1.1), Inches(1.0)]

    for i, h in enumerate(headers):
        c = t_eval.rows[0].cells[i]
        c.text = h
        c.width = widths[i]
        set_cell_background(c, "2C3E50")
        set_cell_margins(c, 100, 100, 100, 100)
        p = c.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9)

    eval_data = [
        ("Phần 1: Sản phẩm, Kho Size & Khuyến mãi", "danh_mucs, san_phams, sizes, bo_suu_taps, giam_gias", "Đã có CRUD Danh mục & Sản phẩm; Size rời rạc", "Khoảng 40%", "Cần làm thêm"),
        ("Phần 2: Người dùng, Thành viên & Lịch hẹn", "users, hang_thanh_viens, khach_hangs, nhan_viens, so_do, lich_hens", "Mới có Đăng nhập & Đăng ký tài khoản cơ bản", "Khoảng 20%", "Làm mới nhiều"),
        ("Phần 3: Giỏ hàng, Đơn hàng & Đánh giá", "gio_hangs, chi_tiet_gio_hangs, hoa_dons, chi_tiet_hoa_dons, danh_gias", "Giỏ hàng Session, Checkout đơn, Thống kê doanh thu", "Khoảng 70%", "Nhiều nhất ⭐")
    ]

    for row in eval_data:
        r_cells = t_eval.add_row().cells
        for i, val in enumerate(row):
            r_cells[i].text = val
            r_cells[i].width = widths[i]
            set_cell_margins(r_cells[i], 70, 70, 80, 80)
            p = r_cells[i].paragraphs[0]
            p.runs[0].font.size = Pt(9)
            if i == 0:
                p.runs[0].font.bold = True
            if "Nhiều nhất" in val:
                set_cell_background(r_cells[i], "D5F5E3") # light green
            elif "Làm mới" in val:
                set_cell_background(r_cells[i], "FADBD8") # light red
            elif "Cần làm" in val:
                set_cell_background(r_cells[i], "FCF3CF") # light yellow

    doc.add_paragraph()

    # SECTION 2: PHÂN CHIA CHI TIẾT
    h2 = doc.add_heading("II. BẢN KẾ HOẠCH PHÂN CHIA CÔNG VIỆC CHO TỪNG THÀNH VIÊN", level=1)
    h2.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    # MEMBER 1
    m1_head = doc.add_heading("1. THÀNH VIÊN 1: PHÂN HỆ SẢN PHẨM, KHO SIZE & KHUYẾN MÃI MARKETING", level=2)
    m1_head.runs[0].font.color.rgb = RGBColor(0x0E, 0x62, 0x51)
    
    doc.add_paragraph(
        "• Vai trò chính: Quản lý toàn bộ danh mục sản phẩm đồ âu, cấu hình kích cỡ & tồn kho, bộ sưu tập tiếp thị và các chương trình giảm giá Flash Sale quảng cáo.\n"
        "• Bảng dữ liệu phụ trách (7 bảng): danh_mucs, san_phams, sizes, bo_suu_taps, bo_suu_tap_san_phams, giam_gias, giam_gia_san_phams.\n"
        "• Khối lượng hiện tại: Đã xong 40% (Đã có CRUD Sản phẩm & Danh mục cơ bản)."
    )

    t1 = doc.add_table(rows=1, cols=4)
    t1.alignment = WD_TABLE_ALIGNMENT.CENTER
    t1_headers = ["Mã Task", "Tên nhiệm vụ / Chức năng", "Chi tiết công việc cần làm", "Thời gian dự kiến"]
    t1_widths = [Inches(1.0), Inches(2.2), Inches(3.3), Inches(1.0)]

    for i, h in enumerate(t1_headers):
        c = t1.rows[0].cells[i]
        c.text = h
        c.width = t1_widths[i]
        set_cell_background(c, "117864")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9)

    m1_tasks = [
        ("Task 1.1", "Tái cấu trúc bảng sizes & Quản lý Tồn kho", 
         "Sửa migration sizes: liên kết san_pham_id, thêm cột so_luong_ton, chieu_cao_goi_y, can_nang_goi_y. Viết giao diện Admin cho phép nhập/sửa số lượng tồn kho theo từng size của mỗi áo vest.", "1.5 ngày"),
        ("Task 1.2", "Xây dựng Module Bộ sưu tập (Collections)", 
         "Tạo migration & Model bo_suu_taps và bo_suu_tap_san_phams. Viết giao diện Admin tạo BST thời trang (BST Vest Cưới, BST Quý ông), chọn sản phẩm vào BST. Hiển thị banner BST ngoài trang chủ.", "2.0 ngày"),
        ("Task 1.3", "Xây dựng Module Giảm giá Flash Sale", 
         "Tạo migration & Model giam_gias và giam_gia_san_phams. Cho phép Admin cài đặt chiến dịch giảm giá có hẹn giờ (bắt đầu - kết thúc), mức % giảm. Hỗ trợ tùy chọn giảm toàn shop hoặc chọn sản phẩm chạy Ads.", "2.0 ngày"),
        ("Task 1.4", "Nâng cấp Giao diện Chi tiết Sản phẩm", 
         "Hiển thị bảng chọn size động kèm tồn kho (hết hàng thì disabled), gợi ý thể hình, badge % giảm giá nếu sản phẩm đang trong đợt sale. Cung cấp hàm Helper tinhGiaBan() cho Thành viên 3.", "1.5 ngày")
    ]

    for m_id, name, desc, est in m1_tasks:
        r_c = t1.add_row().cells
        for i, val in enumerate([m_id, name, desc, est]):
            r_c[i].text = val
            r_c[i].width = t1_widths[i]
            set_cell_margins(r_c[i], 60, 60, 80, 80)
            p = r_c[i].paragraphs[0]
            p.runs[0].font.size = Pt(8.5)
            if i == 0:
                p.runs[0].font.bold = True

    doc.add_paragraph()

    # MEMBER 2
    m2_head = doc.add_heading("2. THÀNH VIÊN 2: PHÂN HỆ TÀI KHOẢN, HẠNG THÀNH VIÊN & ĐẶT LỊCH HẸN TẠI TIỆM", level=2)
    m2_head.runs[0].font.color.rgb = RGBColor(0x7D, 0x66, 0x08)
    
    doc.add_paragraph(
        "• Vai trò chính: Quản lý định danh tài khoản, phân tách khách hàng & nhân sự, chương trình khách hàng thân thiết (Loyalty), hồ sơ số đo may đo vest và dịch vụ đặt lịch thử đồ tại cửa hàng.\n"
        "• Bảng dữ liệu phụ trách (6 bảng): users, hang_thanh_viens, khach_hangs, nhan_viens, so_do_khach_hangs, lich_hens.\n"
        "• Khối lượng hiện tại: Đã xong 20% (Mới có đăng nhập/đăng ký cơ bản trên users cũ)."
    )

    t2 = doc.add_table(rows=1, cols=4)
    t2.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, h in enumerate(t1_headers):
        c = t2.rows[0].cells[i]
        c.text = h
        c.width = t1_widths[i]
        set_cell_background(c, "B7950B")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9)

    m2_tasks = [
        ("Task 2.1", "Tách bảng users, khach_hangs, nhan_viens", 
         "Tách users làm bảng xác thực trung tâm (email, password, vai_tro). Tạo migration khach_hangs và nhan_viens (quan hệ 1-1). Viết Model Observer tự động sinh hồ sơ khách hàng khi đăng ký tài khoản role Khách.", "2.0 ngày"),
        ("Task 2.2", "Xây dựng Hạng thành viên & Điểm tích lũy", 
         "Tạo bảng hang_thanh_viens (Đồng, Bạc, Vàng, VIP, % chiết khấu). Viết hàm tự động cập nhật điểm thưởng và thăng hạng khi Thành viên 3 báo đơn hàng hoàn tất. Hiển thị thẻ VIP trên trang cá nhân.", "1.5 ngày"),
        ("Task 2.3", "Xây dựng Module Hồ sơ Số đo May Vest", 
         "Tạo bảng so_do_khach_hangs. Xây dựng form cho khách hàng lưu nhiều bộ số đo cơ thể (vòng ngực, eo, vai, mông, dài áo, dài quần, vòng đùi) để gửi tiệm may đo hoặc chỉnh sửa trang phục.", "1.5 ngày"),
        ("Task 2.4", "Xây dựng Chức năng Đặt lịch hẹn đến tiệm", 
         "Tạo bảng lich_hens. Xây dựng trang Đặt lịch hẹn: khách chọn ngày giờ, loại dịch vụ (thử đồ cưới, may đo mới), số người đi cùng. Xây dựng trang Admin: duyệt lịch, chỉ định nhân viên/thợ may đón tiếp.", "2.0 ngày")
    ]

    for m_id, name, desc, est in m2_tasks:
        r_c = t2.add_row().cells
        for i, val in enumerate([m_id, name, desc, est]):
            r_c[i].text = val
            r_c[i].width = t1_widths[i]
            set_cell_margins(r_c[i], 60, 60, 80, 80)
            p = r_c[i].paragraphs[0]
            p.runs[0].font.size = Pt(8.5)
            if i == 0:
                p.runs[0].font.bold = True

    doc.add_paragraph()

    # MEMBER 3
    m3_head = doc.add_heading("3. THÀNH VIÊN 3: PHÂN HỆ GIỎ HÀNG, ĐƠN HÀNG, TRỪ KHO & ĐÁNH GIÁ SẢN PHẨM", level=2)
    m3_head.runs[0].font.color.rgb = RGBColor(0x1F, 0x61, 0x8D)
    
    doc.add_paragraph(
        "• Vai trò chính: Chuẩn hóa toàn bộ quy trình mua bán: chuyển giỏ hàng vào Database, hoàn thiện quy trình Checkout chốt đơn, logic tự động trừ kho hàng và trải nghiệm đánh giá phản hồi thực tế của khách.\n"
        "• Bảng dữ liệu phụ trách (5 bảng): gio_hangs, chi_tiet_gio_hangs, hoa_dons, chi_tiet_hoa_dons, danh_gias.\n"
        "• Khối lượng hiện tại: Đã xong 65% - 70% (Đã có sẵn luồng giỏ hàng Session, Checkout đơn và Thống kê doanh thu)."
    )

    t3 = doc.add_table(rows=1, cols=4)
    t3.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, h in enumerate(t1_headers):
        c = t3.rows[0].cells[i]
        c.text = h
        c.width = t1_widths[i]
        set_cell_background(c, "2471A3")
        set_cell_margins(c, 80, 80, 100, 100)
        p = c.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9)

    m3_tasks = [
        ("Task 3.1", "Chuyển Giỏ hàng từ Session sang Database", 
         "Tạo migration gio_hangs và chi_tiet_gio_hangs. Nâng cấp GioHangController để lưu các mặt hàng vào DB theo khach_hang_id (lưu san_pham_id, size_id, so_luong) để đồng bộ trên mọi thiết bị.", "1.5 ngày"),
        ("Task 3.2", "Nâng cấp Checkout & Trừ tồn kho tự động", 
         "Nâng cấp bảng hoa_dons (mã đơn ma_don_hang, nhan_vien_duyet_id). Tích hợp logic: khi đặt hàng thành công, tự động giảm số lượng tồn kho so_luong_ton trong bảng sizes của TV1. Áp dụng giảm giá chiết khấu hạng thành viên của TV2.", "1.5 ngày"),
        ("Task 3.3", "Nâng cấp Quản trị Đơn hàng & Lịch sử mua", 
         "Cải tiến giao diện xem chi tiết đơn hàng cho khách. Trang Admin: duyệt đơn, phân công nhân_vien_duyet_id, cập nhật trạng thái đơn (Chờ xử lý -> Đang may/gói -> Đang giao -> Hoàn thành). Kích hoạt cộng điểm tích lũy khi đơn hoàn tất.", "1.5 ngày"),
        ("Task 3.4", "Xây dựng Module Đánh giá & Review sản phẩm", 
         "Tạo bảng danh_gias. Cho phép khách hàng đã mua sản phẩm được chấm điểm sao (1-5 sao), viết nhận xét form dáng, chất liệu và tải ảnh thực tế khi mặc vest. Hiển thị danh sách đánh giá tại trang chi tiết sản phẩm.", "1.5 ngày")
    ]

    for m_id, name, desc, est in m3_tasks:
        r_c = t3.add_row().cells
        for i, val in enumerate([m_id, name, desc, est]):
            r_c[i].text = val
            r_c[i].width = t1_widths[i]
            set_cell_margins(r_c[i], 60, 60, 80, 80)
            p = r_c[i].paragraphs[0]
            p.runs[0].font.size = Pt(8.5)
            if i == 0:
                p.runs[0].font.bold = True

    doc.add_paragraph()

    # SECTION 3: QUY TRÌNH PHỐI HỢP
    h3 = doc.add_heading("III. QUY TRÌNH PHỐI HỢP GIT & CƠ CHẾ KHỚP NỐI (ĐỠ ĐỢI NHAU)", level=1)
    h3.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    steps = [
        ("Giai đoạn 1: Chuẩn hóa CSDL chung (Ngày 1 - Cả nhóm)", 
         "Chạy toàn bộ 18 file migration lên cơ sở dữ liệu. Nhóm trưởng commit lên nhánh chính 'main'. Tất cả 3 thành viên kéo code về (git pull) và chạy 'php artisan migrate' để đảm bảo cấu trúc database của 3 máy hoàn toàn đồng nhất."),
        
        ("Giai đoạn 2: Tách nhánh làm việc độc lập (Ngày 2 đến Ngày 7)", 
         "Mỗi thành viên làm việc trên nhánh riêng của mình:\n"
         "  • Thành viên 1: git checkout -b feature/catalog-products-sale\n"
         "  • Thành viên 2: git checkout -b feature/users-membership-appointments\n"
         "  • Thành viên 3: git checkout -b feature/cart-orders-reviews\n"
         "Trong giai đoạn này, 3 người code trên các Controller và View hoàn toàn tách biệt, không sửa chéo file của nhau nên không sợ bị xung đột (Merge Conflict)."),
        
        ("Giai đoạn 3: Khớp nối luồng dữ liệu (Ngày 8 - Tích hợp)", 
         "Các điểm nối nghiệp vụ giữa 3 người:\n"
         "  1. Thành viên 3 lấy danh sách Size và Tồn kho từ bảng sizes của Thành viên 1 để hiển thị vào giỏ hàng và trừ kho.\n"
         "  2. Thành viên 3 gọi hàm tính chiết khấu hạng thành viên từ bảng hang_thanh_viens của Thành viên 2 khi tính tiền đơn hàng.\n"
         "  3. Thành viên 2 hiển thị nút 'Đặt lịch đến thử mẫu vest này' ngay trên trang chi tiết sản phẩm của Thành viên 1."),
        
        ("Giai đoạn 4: Kiểm thử liên thông & Đóng gói (Ngày 9)", 
         "Cả nhóm test thông luồng: Đăng ký khách hàng -> Chọn vest xem size -> Thêm vào giỏ -> Đặt hàng trừ kho -> Admin duyệt đơn cộng điểm thưởng -> Khách hàng viết đánh giá -> Đặt lịch hẹn thử đồ tại showroom.")
    ]

    for title, desc in steps:
        p = doc.add_paragraph()
        r1 = p.add_run(title + "\n")
        r1.bold = True
        r1.font.color.rgb = RGBColor(0x28, 0x74, 0xA6)
        r2 = p.add_run(desc)

    # Save
    out_path = r"c:\Laragon\www\demo\Ke_Hoach_Trien_Khai_Du_An_Vest_Nhom_3_Nguoi.docx"
    doc.save(out_path)
    print(f"Plan document successfully created at: {out_path}")

if __name__ == '__main__':
    create_plan_document()
