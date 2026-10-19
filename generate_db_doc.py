import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = OxmlElement('w:shd')
    shd.set(qn('w:val'), 'clear')
    shd.set(qn('w:color'), 'auto')
    shd.set(qn('w:fill'), fill_hex)
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def create_document():
    doc = docx.Document()
    
    # Page Margins
    sections = doc.sections
    for section in sections:
        section.top_margin = Inches(0.8)
        section.bottom_margin = Inches(0.8)
        section.left_margin = Inches(0.8)
        section.right_margin = Inches(0.8)

    # Styles
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Arial'
    normal_style.font.size = Pt(10.5)
    normal_style.font.color.rgb = RGBColor(0x33, 0x33, 0x33)

    # TITLE
    title_p = doc.add_paragraph()
    title_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    title_run = title_p.add_run("TÀI LIỆU THIẾT KẾ CƠ SỞ DỮ LIỆU\nWEBSITE THƯƠNG MẠI ĐIỆN TỬ BÁN VEST & ĐẶT LỊCH HẸN TẠI TIỆM")
    title_run.font.size = Pt(16)
    title_run.font.bold = True
    title_run.font.color.rgb = RGBColor(0x1A, 0x52, 0x76) # Dark Blue

    sub_p = doc.add_paragraph()
    sub_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    sub_run = sub_p.add_run("Dự án: Hệ thống E-Commerce & May Đo Âu Phục Cao Cấp (Laravel Framework)\nPhiên bản tài liệu: 2.0 | Tổng số bảng: 18 bảng")
    sub_run.font.size = Pt(10)
    sub_run.font.italic = True
    sub_run.font.color.rgb = RGBColor(0x7F, 0x8C, 0x8D)

    doc.add_paragraph() # Spacing

    # 1. GIỚI THIỆU TỔNG QUAN
    h1 = doc.add_heading("I. TỔNG QUAN VỀ HỆ THỐNG VÀ CÁC PHÂN HỆ", level=1)
    h1.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    intro_p = doc.add_paragraph(
        "Hệ thống cơ sở dữ liệu được thiết kế chuyên biệt cho mô hình kinh doanh đồ Âu/Vest cao cấp kết hợp giữa Bán lẻ trực tuyến (E-commerce) và Trải nghiệm dịch vụ trực tiếp tại cửa hàng (Fitting & Tailoring Appointment). Toàn bộ 18 bảng dữ liệu được quy hoạch vào 4 phân hệ chính:"
    )
    
    modules = [
        ("1. Phân hệ Tài khoản, Nhân sự & Khách hàng (5 bảng):", "users, hang_thanh_viens, khach_hangs, nhan_viens, so_do_khach_hangs"),
        ("2. Phân hệ Danh mục, Sản phẩm, Bộ sưu tập & Kho Size (5 bảng):", "danh_mucs, bo_suu_taps, bo_suu_tap_san_phams, san_phams, sizes"),
        ("3. Phân hệ Chiến dịch Khuyến mãi & Quảng cáo (2 bảng):", "giam_gias, giam_gia_san_phams"),
        ("4. Phân hệ Giao dịch Đơn hàng, Đánh giá & Dịch vụ Lịch hẹn (6 bảng):", "gio_hangs, chi_tiet_gio_hangs, hoa_dons, chi_tiet_hoa_dons, danh_gias, lich_hens")
    ]
    for title, desc in modules:
        p = doc.add_paragraph(style='List Bullet')
        r1 = p.add_run(title + " ")
        r1.bold = True
        r2 = p.add_run(desc)
        r2.font.italic = True

    doc.add_paragraph()

    # 2. BẢN ĐỒ CHỨC NĂNG NGHIỆP VỤ
    h2 = doc.add_heading("II. BẢN ĐỒ CHỨC NĂNG NGHIỆP VỤ (CÁC BẢNG PHỤC VỤ CHỨC NĂNG GÌ)", level=1)
    h2.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    features = [
        ("1. Chức năng Xác thực & Phân quyền đăng nhập", 
         "Bảng [users] quản lý email và mật khẩu được mã hóa. Hệ thống nhận diện role (Khách hàng / Nhân viên / Admin) để điều hướng tới trang cá nhân khách hoặc trang quản trị (Admin Panel)."),
        
        ("2. Chức năng Quản lý Hồ sơ Khách hàng & Hạng thành viên (Loyalty Program)", 
         "Bảng [khach_hangs] kết nối với [hang_thanh_viens]. Mỗi khi mua hàng thành công, khách được cộng 'diem_tich_luy'. Khi đủ điểm, khách tự động thăng hạng (Đồng -> Bạc -> Vàng -> VIP) và nhận chiết khấu giảm giá trực tiếp theo % quy định của từng hạng."),
        
        ("3. Chức năng Quản lý Số đo May Vest Cá Nhân (Tailor Profile)", 
         "Bảng [so_do_khach_hangs] phục vụ nhu cầu đặc thù của vest. Khách hàng có thể lưu nhiều bộ số đo (vai, ngực, eo, dài áo, dài quần) cho bản thân hoặc người thân, giúp tiệm may chỉnh sửa trang phục vừa vặn trước khi giao."),
        
        ("4. Chức năng Quản trị Nhân viên & Điều phối đơn hàng", 
         "Bảng [nhan_viens] lưu trữ thông tin nhân viên, chức vụ, trạng thái làm việc. Bảng này liên kết với [hoa_dons] để xác định nhân viên nào duyệt/gói hàng, và liên kết [lich_hens] để phân công nhân viên tư vấn đón tiếp khách."),
        
        ("5. Chức năng Phân loại Danh mục & Bộ sưu tập Tiếp thị (Marketing Collection)", 
         "Bảng [danh_mucs] phân loại đồ theo chuẩn vật lý (Áo vest, Quần âu, Sơ mi, Phụ kiện). Trong khi đó, [bo_suu_taps] và [bo_suu_tap_san_phams] dùng cho chiến dịch marketing theo mùa/chủ đề (BST Vest Cưới, BST Dạ Tiệc) để khách dễ chọn trọn bộ phong cách."),
        
        ("6. Chức năng Quản lý Sản phẩm & Tồn kho theo từng Size", 
         "Bảng [san_phams] chứa thông tin form dáng, chất liệu, giá niêm yết. Bảng [sizes] gắn thẳng vào sản phẩm để kiểm soát chính xác số lượng tồn kho của từng size (S, M, L, XL), đồng thời hiển thị gợi ý chiều cao/cân nặng để khách tự chọn size."),
        
        ("7. Chức năng Chiến dịch Giảm giá / Flash Sale chạy quảng cáo", 
         "Bảng [giam_gias] và [giam_gia_san_phams] cho phép admin thiết lập các đợt khuyến mãi có hẹn giờ (bắt đầu - kết thúc) với mức % giảm giá cụ thể. Có thể linh hoạt giảm toàn bộ shop hoặc chỉ giảm cho danh sách các mẫu vest được chỉ định chạy Ads."),
        
        ("8. Chức năng Giỏ hàng & Đồng bộ thiết bị", 
         "Bảng [gio_hangs] và [chi_tiet_gio_hangs] lưu trên database giúp khách hàng không bị mất giỏ hàng khi chuyển từ điện thoại sang máy tính, đồng thời cho phép shop thống kê giỏ hàng bỏ quên."),
        
        ("9. Chức năng Đặt hàng & Xử lý Hóa đơn mua sắm", 
         "Bảng [hoa_dons] và [chi_tiet_hoa_dons] lưu lại toàn bộ lịch sử mua sắm với đơn giá cố định tại thời điểm mua. Đơn hàng kết nối tới [sizes] để hệ thống tự động trừ kho hàng ngay khi đặt thành công."),
        
        ("10. Chức năng Đánh giá, Phản hồi thực tế (Customer Reviews)", 
         "Bảng [danh_gias] liên kết trực tiếp với sản phẩm và khách hàng, cho phép chấm điểm sao, viết nhận xét và đăng ảnh thực tế người mặc vest, gia tăng độ tin cậy cho khách hàng mới."),
        
        ("11. Chức năng Đặt lịch hẹn đến cửa tiệm (Fitting / Tailoring Appointment)", 
         "Bảng [lich_hens] đáp ứng tính năng khách hàng đặt trước ngày giờ đến tiệm để: thử vest có sẵn, đo may bộ mới, hoặc chỉnh sửa áo. Có thể liên kết mẫu vest khách thích ([san_pham_id]) và nhân viên phụ trách đón tiếp ([nhan_vien_id]).")
    ]

    for title, desc in features:
        p = doc.add_paragraph()
        r1 = p.add_run(title + "\n")
        r1.bold = True
        r1.font.color.rgb = RGBColor(0x28, 0x74, 0xA6)
        r2 = p.add_run(desc)

    doc.add_paragraph()

    # 3. CHI TIẾT CẤU TRÚC TỪNG BẢNG
    h3 = doc.add_heading("III. CHI TIẾT CẤU TRÚC 18 BẢNG DỮ LIỆU", level=1)
    h3.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    table_definitions = [
        # 1. users
        ("1. Bảng users (Tài khoản định danh hệ thống)", 
         "Lưu trữ tài khoản đăng nhập cho toàn bộ người dùng trong hệ thống (Khách hàng, Nhân viên, Quản trị viên).",
         [
             ("id", "BIGINT", "PK, Auto", "Mã tài khoản tự tăng"),
             ("email", "VARCHAR(255)", "UNIQUE, Not Null", "Email đăng nhập hệ thống"),
             ("password", "VARCHAR(255)", "Not Null", "Mật khẩu mã hóa (Bcrypt)"),
             ("vai_tro", "ENUM", "Default 'khach_hang'", "Vai trò: khach_hang, nhan_vien, admin"),
             ("trang_thai", "BOOLEAN", "Default TRUE", "Trạng thái hoạt động của tài khoản"),
             ("created_at / updated_at", "TIMESTAMP", "Nullable", "Thời gian tạo và cập nhật")
         ]),
        
        # 2. hang_thanh_viens
        ("2. Bảng hang_thanh_viens (Hạng thành viên & Chính sách)", 
         "Cấu hình các cấp độ thành viên thân thiết và % giảm giá tự động khi mua đồ vest.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã hạng thành viên"),
             ("ten_hang", "VARCHAR(50)", "UNIQUE, Not Null", "Tên hạng: Đồng, Bạc, Vàng, Kim Cương / VIP"),
             ("diem_toi_thieu", "INT", "Default 0", "Số điểm tích lũy cần đạt để thăng hạng"),
             ("phan_tram_chiet_khau", "INT", "Default 0", "Tỷ lệ % giảm giá tự động khi đặt đơn"),
             ("mo_ta", "TEXT", "Nullable", "Mô tả đặc quyền của hạng thành viên")
         ]),
        
        # 3. khach_hangs
        ("3. Bảng khach_hangs (Hồ sơ khách hàng)", 
         "Lưu trữ thông tin cá nhân, liên lạc và tích điểm của khách hàng (Quan hệ 1-1 với users).",
         [
             ("id", "BIGINT", "PK, Auto", "Mã hồ sơ khách hàng"),
             ("user_id", "BIGINT", "FK (users), UNIQUE", "Liên kết 1-1 với tài khoản users"),
             ("hang_thanh_vien_id", "BIGINT", "FK (hang_thanh_viens)", "Hạng thành viên hiện tại của khách"),
             ("ma_kh", "VARCHAR(20)", "UNIQUE, Nullable", "Mã hiển thị khách hàng (VD: KH0001)"),
             ("ho_ten", "VARCHAR(100)", "Not Null", "Họ tên đầy đủ"),
             ("so_dien_thoai", "VARCHAR(15)", "Nullable", "Số điện thoại liên hệ"),
             ("dia_chi", "VARCHAR(255)", "Nullable", "Địa chỉ giao hàng mặc định"),
             ("ngay_sinh", "DATE", "Nullable", "Ngày sinh nhật (dùng tặng quà/voucher)"),
             ("gioi_tinh", "ENUM", "Nam, Nu, Khac", "Giới tính để tư vấn form vest"),
             ("diem_tich_luy", "INT", "Default 0", "Tổng điểm thưởng đã tích lũy")
         ]),

        # 4. nhan_viens
        ("4. Bảng nhan_viens (Hồ sơ nhân viên)", 
         "Quản lý thông tin cán bộ công nhân viên cửa hàng vest (Tư vấn, Thợ may, Quản lý).",
         [
             ("id", "BIGINT", "PK, Auto", "Mã hồ sơ nhân viên"),
             ("user_id", "BIGINT", "FK (users), UNIQUE", "Liên kết 1-1 với tài khoản đăng nhập"),
             ("ma_nv", "VARCHAR(20)", "UNIQUE", "Mã nhân viên (VD: NV01, TM02)"),
             ("ho_ten", "VARCHAR(100)", "Not Null", "Họ và tên nhân viên"),
             ("so_dien_thoai", "VARCHAR(15)", "Nullable", "Số điện thoại liên lạc"),
             ("chuc_vu", "VARCHAR(50)", "Not Null", "Chức vụ: Quản lý, Nhân viên bán hàng, Thợ may đo"),
             ("luong_co_ban", "DECIMAL(18,0)", "Nullable", "Mức lương cơ bản"),
             ("ngay_vao_lam", "DATE", "Nullable", "Ngày bắt đầu công tác"),
             ("trang_thai", "VARCHAR(30)", "Default 'Đang làm việc'", "Tình trạng làm việc")
         ]),

        # 5. so_do_khach_hangs
        ("5. Bảng so_do_khach_hangs (Số đo cơ thể may đo)", 
         "Lưu các chỉ số đo may vest (vai, ngực, eo, mông, dài áo, dài quần) để may đo hoặc chỉnh sửa.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã bản ghi số đo"),
             ("khach_hang_id", "BIGINT", "FK (khach_hangs)", "Thuộc về khách hàng nào"),
             ("ten_ho_so", "VARCHAR(50)", "Not Null", "Tên hồ sơ: Số đo của tôi, Số đo dự tiệc cưới..."),
             ("vong_co", "DECIMAL(5,1)", "Nullable (cm)", "Chu vi vòng cổ"),
             ("rong_vai", "DECIMAL(5,1)", "Nullable (cm)", "Độ rộng bờ vai"),
             ("vong_nguc", "DECIMAL(5,1)", "Nullable (cm)", "Vòng 1 (ngực)"),
             ("vong_eo", "DECIMAL(5,1)", "Nullable (cm)", "Vòng 2 (bụng/eo)"),
             ("vong_mong", "DECIMAL(5,1)", "Nullable (cm)", "Vòng 3 (mông)"),
             ("dai_tay_ao", "DECIMAL(5,1)", "Nullable (cm)", "Chiều dài tay áo vest"),
             ("dai_ao_vest", "DECIMAL(5,1)", "Nullable (cm)", "Chiều dài thân áo vest"),
             ("dai_quan", "DECIMAL(5,1)", "Nullable (cm)", "Chiều dài quần âu"),
             ("vong_dui", "DECIMAL(5,1)", "Nullable (cm)", "Chu vi vòng đùi"),
             ("ghi_chu", "TEXT", "Nullable", "Ghi chú dáng người (gù lưng, vai xuôi...)")
         ]),

        # 6. danh_mucs
        ("6. Bảng danh_mucs (Danh mục sản phẩm chủng loại)", 
         "Phân loại hàng hóa theo cấu trúc vật lý.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã danh mục"),
             ("ten_danh_muc", "VARCHAR(50)", "Not Null", "Tên: Áo Vest, Quần âu, Áo gile, Sơ mi, Phụ kiện"),
             ("mo_ta", "TEXT", "Nullable", "Mô tả phân loại")
         ]),

        # 7. bo_suu_taps
        ("7. Bảng bo_suu_taps (Bộ sưu tập thời trang Marketing)", 
         "Nhóm sản phẩm theo chủ đề, concept thiết kế hoặc mùa thời trang.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã bộ sưu tập"),
             ("ten_bo_suu_tap", "VARCHAR(100)", "Not Null", "Tên: BST Vest Cưới 2026, Gentleman Luxury..."),
             ("hinh_banner", "VARCHAR(255)", "Nullable", "Ảnh banner khổ lớn đại diện BST"),
             ("mo_ta", "TEXT", "Nullable", "Ý tưởng thiết kế & thông điệp BST")
         ]),

        # 8. bo_suu_tap_san_phams
        ("8. Bảng bo_suu_tap_san_phams (Bảng trung gian N - N)", 
         "Liên kết nhiều sản phẩm vào trong một hoặc nhiều bộ sưu tập.",
         [
             ("bo_suu_tap_id", "BIGINT", "PK, FK (bo_suu_taps)", "Mã bộ sưu tập"),
             ("san_pham_id", "BIGINT", "PK, FK (san_phams)", "Mã sản phẩm tham gia BST")
         ]),

        # 9. san_phams
        ("9. Bảng san_phams (Thông tin sản phẩm Vest)", 
         "Quản lý thông tin chi tiết các mẫu vest bán ra.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã sản phẩm"),
             ("danh_muc_id", "BIGINT", "FK (danh_mucs)", "Thuộc danh mục chủng loại nào"),
             ("ma_sp", "VARCHAR(50)", "UNIQUE", "Mã sản phẩm (VD: VEST-NAVY-01)"),
             ("ten_sp", "VARCHAR(100)", "Not Null", "Tên gọi sản phẩm"),
             ("chat_lieu", "VARCHAR(50)", "Nullable", "Chất vải: Wool Ý, Tuytsi Hàn, Cotton lụa..."),
             ("xuat_xu", "VARCHAR(50)", "Nullable", "Nguồn gốc xuất xứ"),
             ("form_dang", "VARCHAR(50)", "Nullable", "Form áo: Slim-fit (ôm), Regular (vừa vặn)"),
             ("gia_ban", "DECIMAL(18,0)", "Not Null", "Đơn giá niêm yết chuẩn"),
             ("hinh_anh", "VARCHAR(255)", "Nullable", "Hình ảnh đại diện sản phẩm"),
             ("mo_ta", "TEXT", "Nullable", "Mô tả chi tiết ve áo, khuy cài, kiểu dệt")
         ]),

        # 10. sizes
        ("10. Bảng sizes (Kích cỡ & Quản lý tồn kho theo Size)", 
         "Lưu trữ từng size cụ thể của từng mẫu vest và số lượng tồn kho tương ứng.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã size biến thể"),
             ("san_pham_id", "BIGINT", "FK (san_phams)", "Áo vest nào sở hữu size này"),
             ("ten_size", "VARCHAR(10)", "Not Null", "Ký hiệu size: XS, S, M, L, XL, XXL"),
             ("so_luong_ton", "INT", "Default 0", "Số lượng còn trong kho của size này"),
             ("chieu_cao_goi_y", "VARCHAR(40)", "Nullable", "Gợi ý chiều cao phù hợp (VD: 1m68 - 1m75)"),
             ("can_nang_goi_y", "VARCHAR(40)", "Nullable", "Gợi ý cân nặng phù hợp (VD: 62kg - 68kg)")
         ]),

        # 11. giam_gias
        ("11. Bảng giam_gias (Chiến dịch khuyến mãi / Quảng cáo)", 
         "Quản lý các đợt Flash Sale, giảm giá mùa lễ hội có thời hạn.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã chương trình giảm giá"),
             ("tieu_de", "VARCHAR(150)", "Not Null", "Tiêu đề: Flash Sale Thu Đông, Xả kho vest hè"),
             ("phan_tram_giam", "INT", "Not Null", "Tỷ lệ giảm giá (VD: 20 là giảm 20%)"),
             ("ap_dung_tat_ca", "BOOLEAN", "Default FALSE", "TRUE: Giảm toàn bộ shop; FALSE: Giảm lẻ"),
             ("thoi_gian_bat_dau", "DATETIME", "Not Null", "Ngày giờ bắt đầu có hiệu lực"),
             ("thoi_gian_ket_thuc", "DATETIME", "Not Null", "Ngày giờ hết hạn khuyến mãi"),
             ("trang_thai", "BOOLEAN", "Default TRUE", "Công tắc bật/tắt thủ công của quản trị")
         ]),

        # 12. giam_gia_san_phams
        ("12. Bảng giam_gia_san_phams (Sản phẩm áp dụng giảm giá)", 
         "Gắn danh sách sản phẩm lẻ tham gia vào chương trình giảm giá khi ap_dung_tat_ca = FALSE.",
         [
             ("giam_gia_id", "BIGINT", "PK, FK (giam_gias)", "Mã chương trình giảm giá"),
             ("san_pham_id", "BIGINT", "PK, FK (san_phams)", "Mã sản phẩm được áp dụng giảm giá")
         ]),

        # 13. gio_hangs
        ("13. Bảng gio_hangs (Giỏ hàng của khách)", 
         "Đại diện cho giỏ hàng của từng khách hàng trên hệ thống.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã giỏ hàng"),
             ("khach_hang_id", "BIGINT", "FK (khach_hangs), UNIQUE", "Thuộc sở hữu của khách hàng nào")
         ]),

        # 14. chi_tiet_gio_hangs
        ("14. Bảng chi_tiet_gio_hangs (Mặt hàng đang trong giỏ)", 
         "Chi tiết các món đồ vest khách đã chọn size và số lượng trước khi bấm mua.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã chi tiết giỏ hàng"),
             ("gio_hang_id", "BIGINT", "FK (gio_hangs)", "Thuộc giỏ hàng nào"),
             ("san_pham_id", "BIGINT", "FK (san_phams)", "Mẫu áo vest nào"),
             ("size_id", "BIGINT", "FK (sizes)", "Size cụ thể đã chọn"),
             ("so_luong", "INT", "Default 1", "Số lượng sản phẩm muốn mua")
         ]),

        # 15. hoa_dons
        ("15. Bảng hoa_dons (Đơn đặt hàng)", 
         "Quản lý thông tin chung về hóa đơn mua sắm của khách.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã hóa đơn nội bộ"),
             ("khach_hang_id", "BIGINT", "FK (khach_hangs)", "Khách hàng đặt mua đơn này"),
             ("nhan_vien_duyet_id", "BIGINT", "FK (nhan_viens), Nullable", "Nhân viên phụ trách duyệt/giao đơn"),
             ("ma_don_hang", "VARCHAR(30)", "UNIQUE", "Mã đơn hàng hiển thị (VD: DH20261018-001)"),
             ("ngay_dat", "DATETIME", "Default NOW", "Thời điểm đặt hàng"),
             ("trang_thai", "VARCHAR(50)", "Default 'Chờ xử lý'", "Chờ xử lý, Đang may/gói, Đang giao, Hoàn thành, Hủy"),
             ("tong_tien", "DECIMAL(18,0)", "Not Null", "Tổng giá trị cần thanh toán"),
             ("phuong_thuc_thanh_toan", "VARCHAR(50)", "Default 'COD'", "COD, Chuyển khoản VNPay, Momo..."),
             ("dia_chi_giao_hang", "TEXT", "Not Null", "Địa chỉ giao nhận hàng của đơn"),
             ("ghi_chu", "TEXT", "Nullable", "Ghi chú giao hàng từ khách")
         ]),

        # 16. chi_tiet_hoa_dons
        ("16. Bảng chi_tiet_hoa_dons (Sản phẩm trong hóa đơn)", 
         "Lưu chi tiết từng sản phẩm, size và giá bán cố định của đơn hàng.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã chi tiết hóa đơn"),
             ("hoa_don_id", "BIGINT", "FK (hoa_dons)", "Thuộc hóa đơn đơn hàng nào"),
             ("san_pham_id", "BIGINT", "FK (san_phams)", "Sản phẩm được mua"),
             ("size_id", "BIGINT", "FK (sizes)", "Size được chọn (dùng để trừ tồn kho)"),
             ("don_gia", "DECIMAL(18,0)", "Not Null", "Đơn giá chốt cứng lúc mua"),
             ("so_luong", "INT", "Not Null", "Số lượng mua"),
             ("thanh_tien", "DECIMAL(18,0)", "Not Null", "Thành tiền (Đơn giá x Số lượng)")
         ]),

        # 17. danh_gias
        ("17. Bảng danh_gias (Đánh giá & Review sản phẩm)", 
         "Lưu nhận xét thực tế, số sao và hình ảnh từ khách hàng đã mua vest.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã đánh giá"),
             ("san_pham_id", "BIGINT", "FK (san_phams)", "Sản phẩm được đánh giá"),
             ("khach_hang_id", "BIGINT", "FK (khach_hangs)", "Khách hàng viết đánh giá"),
             ("so_sao", "INT", "Check 1..5", "Số sao đánh giá (1 đến 5 sao)"),
             ("noi_dung", "TEXT", "Nullable", "Cảm nhận về chất vải, form áo, đường may"),
             ("hinh_anh", "VARCHAR(255)", "Nullable", "Ảnh chụp thực tế khi khách mặc vest")
         ]),

        # 18. lich_hens
        ("18. Bảng lich_hens (Lịch hẹn thử đồ & may đo tại tiệm)", 
         "Quản lý lịch hẹn của khách hàng đến cửa tiệm để thử trang phục, lấy số đo.",
         [
             ("id", "BIGINT", "PK, Auto", "Mã lịch hẹn"),
             ("khach_hang_id", "BIGINT", "FK (khach_hangs)", "Khách hàng hẹn lịch"),
             ("nhan_vien_tiep_don_id", "BIGINT", "FK (nhan_viens), Nullable", "Nhân viên/Thợ may được phân công tiếp đón"),
             ("san_pham_quan_tam_id", "BIGINT", "FK (san_phams), Nullable", "Mẫu vest khách xem trên web muốn chuẩn bị sẵn"),
             ("ma_lich_hen", "VARCHAR(30)", "UNIQUE", "Mã lịch hẹn (VD: LH0098)"),
             ("thoi_gian_hen", "DATETIME", "Not Null", "Ngày và giờ hẹn đến tiệm"),
             ("loai_dich_vu", "VARCHAR(50)", "Not Null", "Thử vest có sẵn, Lấy số đo may đo, Thử đồ cưới"),
             ("so_nguoi_di_cung", "INT", "Default 1", "Số người đi cùng (chuẩn bị phòng tiếp khách)"),
             ("trang_thai", "VARCHAR(50)", "Default 'Chờ xác nhận'", "Chờ xác nhận, Đã xác nhận, Đã đến tiệm, Hoàn tất, Hủy"),
             ("ghi_chu", "TEXT", "Nullable", "Ghi chú đặc biệt từ khách hàng")
         ])
    ]

    for title, desc, cols in table_definitions:
        p_t = doc.add_paragraph()
        r_t = p_t.add_run(title)
        r_t.bold = True
        r_t.font.size = Pt(11.5)
        r_t.font.color.rgb = RGBColor(0x0E, 0x62, 0x51)

        p_d = doc.add_paragraph()
        r_d = p_d.add_run("Mô tả chức năng: " + desc)
        r_d.font.italic = True
        r_d.font.size = Pt(9.5)

        # Create Table
        t = doc.add_table(rows=1, cols=4)
        t.alignment = WD_TABLE_ALIGNMENT.CENTER
        t.autofit = False

        # Header Row
        hdr_cells = t.rows[0].cells
        hdr_titles = ["Tên cột (Field)", "Kiểu dữ liệu", "Ràng buộc (Key / Constraint)", "Ý nghĩa nghiệp vụ"]
        col_widths = [Inches(1.8), Inches(1.3), Inches(1.8), Inches(2.3)]

        for i, h_title in enumerate(hdr_titles):
            cell = hdr_cells[i]
            cell.text = h_title
            cell.width = col_widths[i]
            set_cell_background(cell, "2C3E50") # Dark Charcoal
            set_cell_margins(cell, top=120, bottom=120, left=120, right=120)
            p = cell.paragraphs[0]
            p.runs[0].font.bold = True
            p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
            p.runs[0].font.size = Pt(9.5)

        for col_name, data_type, constraint, meaning in cols:
            row_cells = t.add_row().cells
            row_data = [col_name, data_type, constraint, meaning]
            for i, val in enumerate(row_data):
                cell = row_cells[i]
                cell.text = val
                cell.width = col_widths[i]
                set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
                p = cell.paragraphs[0]
                p.runs[0].font.size = Pt(9)
                if i == 0:
                    p.runs[0].font.bold = True
                if "PK" in constraint:
                    set_cell_background(cell, "FCF3CF") # Light yellow
                elif "FK" in constraint:
                    set_cell_background(cell, "E8F8F5") # Light mint

        doc.add_paragraph() # Spacing

    # 4. MỐI QUAN HỆ VÀ RÀNG BUỘC LIÊN KẾT
    h4 = doc.add_heading("IV. BẢNG TỔNG HỢP MỐI QUAN HỆ & LIÊN KẾT GIỮA CÁC BẢNG", level=1)
    h4.runs[0].font.color.rgb = RGBColor(0x1B, 0x4F, 0x72)

    doc.add_paragraph("Dưới đây là chi tiết toàn bộ các ràng buộc khóa ngoại (Foreign Keys) liên kết 18 bảng dữ liệu trong hệ thống:")

    rel_table = doc.add_table(rows=1, cols=5)
    rel_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    
    r_hdr = rel_table.rows[0].cells
    r_hdr_titles = ["Bảng gốc (Parent)", "Bảng phụ (Child)", "Khóa ngoại (FK)", "Quan hệ", "Ý nghĩa liên kết nghiệp vụ"]
    r_widths = [Inches(1.4), Inches(1.5), Inches(1.5), Inches(0.9), Inches(2.2)]

    for i, title in enumerate(r_hdr_titles):
        cell = r_hdr[i]
        cell.text = title
        cell.width = r_widths[i]
        set_cell_background(cell, "1A5276") # Deep Blue
        set_cell_margins(cell, top=120, bottom=120, left=120, right=120)
        p = cell.paragraphs[0]
        p.runs[0].font.bold = True
        p.runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        p.runs[0].font.size = Pt(9.5)

    relationships = [
        ("users", "khach_hangs", "user_id", "1 - 1", "Một tài khoản người dùng gắn với một hồ sơ khách hàng"),
        ("users", "nhan_viens", "user_id", "1 - 1", "Một tài khoản nhân sự gắn với một hồ sơ nhân viên"),
        ("hang_thanh_viens", "khach_hangs", "hang_thanh_vien_id", "1 - N", "Một hạng thẻ thành viên có nhiều khách hàng trực thuộc"),
        ("khach_hangs", "so_do_khach_hangs", "khach_hang_id", "1 - N", "Một khách hàng có thể lưu nhiều bộ số đo may vest"),
        ("danh_mucs", "san_phams", "danh_muc_id", "1 - N", "Một danh mục chủng loại chứa nhiều mẫu sản phẩm"),
        ("bo_suu_taps", "bo_suu_tap_san_phams", "bo_suu_tap_id", "1 - N", "Bộ sưu tập liên kết nhiều sản phẩm (Quan hệ N-N)"),
        ("san_phams", "bo_suu_tap_san_phams", "san_pham_id", "1 - N", "Sản phẩm có thể nằm trong nhiều bộ sưu tập"),
        ("san_phams", "sizes", "san_pham_id", "1 - N", "Một sản phẩm có nhiều size với số lượng tồn kho riêng"),
        ("giam_gias", "giam_gia_san_phams", "giam_gia_id", "1 - N", "Chiến dịch giảm giá liên kết nhiều sản phẩm lẻ"),
        ("san_phams", "giam_gia_san_phams", "san_pham_id", "1 - N", "Sản phẩm được chọn tham gia chiến dịch giảm giá"),
        ("khach_hangs", "gio_hangs", "khach_hang_id", "1 - 1", "Mỗi khách hàng sở hữu một giỏ hàng trực tuyến"),
        ("gio_hangs", "chi_tiet_gio_hangs", "gio_hang_id", "1 - N", "Giỏ hàng chứa nhiều mặt hàng chờ thanh toán"),
        ("san_phams", "chi_tiet_gio_hangs", "san_pham_id", "1 - N", "Xác định sản phẩm nào đang nằm trong giỏ hàng"),
        ("sizes", "chi_tiet_gio_hangs", "size_id", "1 - N", "Xác định size cụ thể của sản phẩm trong giỏ"),
        ("khach_hangs", "hoa_dons", "khach_hang_id", "1 - N", "Khách hàng có thể thực hiện nhiều đơn đặt hàng"),
        ("nhan_viens", "hoa_dons", "nhan_vien_duyet_id", "0..1 - N", "Nhân viên phụ trách duyệt, đóng gói và xử lý hóa đơn"),
        ("hoa_dons", "chi_tiet_hoa_dons", "hoa_don_id", "1 - N", "Hóa đơn gồm nhiều dòng sản phẩm chi tiết"),
        ("san_phams", "chi_tiet_hoa_dons", "san_pham_id", "1 - N", "Sản phẩm xuất hiện trong hóa đơn bán hàng"),
        ("sizes", "chi_tiet_hoa_dons", "size_id", "1 - N", "Size cụ thể được mua (dùng trừ số lượng tồn kho)"),
        ("san_phams", "danh_gias", "san_pham_id", "1 - N", "Khách hàng đánh giá chất lượng của mẫu sản phẩm"),
        ("khach_hangs", "danh_gias", "khach_hang_id", "1 - N", "Khách hàng là tác giả của các nhận xét/review"),
        ("khach_hangs", "lich_hens", "khach_hang_id", "1 - N", "Khách hàng đặt lịch hẹn thử đồ/may đo tại tiệm"),
        ("nhan_viens", "lich_hens", "nhan_vien_tiep_don_id", "0..1 - N", "Nhân viên/Thợ may được phân công đón tiếp khách"),
        ("san_phams", "lich_hens", "san_pham_quan_tam_id", "0..1 - N", "Mẫu vest khách đã chọn trước trên web để thử")
    ]

    for p_tbl, c_tbl, fk_col, rel_type, purpose in relationships:
        r_cells = rel_table.add_row().cells
        data = [p_tbl, c_tbl, fk_col, rel_type, purpose]
        for i, val in enumerate(data):
            cell = r_cells[i]
            cell.text = val
            cell.width = r_widths[i]
            set_cell_margins(cell, top=70, bottom=70, left=80, right=80)
            p = cell.paragraphs[0]
            p.runs[0].font.size = Pt(8.5)
            if i in [0, 1]:
                p.runs[0].font.bold = True

    # Save document
    output_path = r"c:\Laragon\www\demo\Tai_Lieu_Thiet_Ke_CSDL_He_Thong_Vest.docx"
    doc.save(output_path)
    print(f"Document successfully created at: {output_path}")

if __name__ == '__main__':
    create_document()
