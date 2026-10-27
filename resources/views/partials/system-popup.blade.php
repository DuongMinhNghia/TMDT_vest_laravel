@php
    $systemPopup = null;

    if (session('success')) {
        $systemPopup = ['type' => 'success', 'title' => 'Thành công', 'messages' => [session('success')]];
    } elseif (session('error')) {
        $systemPopup = ['type' => 'danger', 'title' => 'Thao tác chưa thành công', 'messages' => [session('error')]];
    } elseif (session('warning')) {
        $systemPopup = ['type' => 'warning', 'title' => 'Lưu ý', 'messages' => [session('warning')]];
    } elseif (session('info')) {
        $systemPopup = ['type' => 'info', 'title' => 'Thông báo', 'messages' => [session('info')]];
    } else {
        $validationMessages = collect($errors->getBags())
            ->flatMap(fn ($bag) => $bag->all())
            ->unique()
            ->values()
            ->all();

        if ($validationMessages) {
            $systemPopup = ['type' => 'danger', 'title' => 'Vui lòng kiểm tra lại thông tin', 'messages' => $validationMessages];
        }
    }
@endphp

@if($systemPopup)
<style>
    .system-popup .modal-content {
        background: #181B24;
        color: #F1F5F9;
        border: 1px solid rgba(212, 178, 111, 0.45);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.65);
    }

    .system-popup .modal-header {
        background: #222734;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 18px 22px;
    }

    .system-popup .modal-title {
        color: #D4B26F;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .system-popup .modal-title i { color: #D4B26F; }
    .system-popup .modal-body { color: #E2E8F0; padding: 22px; font-size: 1rem; line-height: 1.65; }
    .system-popup .modal-footer { border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 14px 22px; }
    .system-popup .system-popup-message + .system-popup-message { margin-top: 10px; }
    .system-popup .btn-system-popup {
        background: linear-gradient(135deg, #E8CA8C, #D4B26F);
        border: 0;
        color: #111317;
        font-weight: 700;
        padding: 9px 22px;
    }
    .system-popup .btn-system-popup:hover { background: #F3D89B; color: #111317; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notification = @json($systemPopup);
        const popup = document.createElement('div');
        const iconByType = {
            success: 'bi-check-circle-fill',
            danger: 'bi-exclamation-triangle-fill',
            warning: 'bi-exclamation-circle-fill',
            info: 'bi-info-circle-fill'
        };

        popup.className = 'modal fade system-popup';
        popup.tabIndex = -1;
        popup.setAttribute('aria-labelledby', 'systemPopupTitle');
        popup.setAttribute('aria-hidden', 'true');
        popup.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="systemPopupTitle">
                            <i class="bi ${iconByType[notification.type]} me-2"></i>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-system-popup" data-bs-dismiss="modal">Đã hiểu</button>
                    </div>
                </div>
            </div>`;

        popup.querySelector('#systemPopupTitle').append(document.createTextNode(notification.title));
        const body = popup.querySelector('.modal-body');
        notification.messages.forEach(function (message) {
            const item = document.createElement('div');
            item.className = 'system-popup-message';
            item.textContent = message;
            body.appendChild(item);
        });

        document.body.appendChild(popup);
        const modal = new bootstrap.Modal(popup);
        popup.addEventListener('hidden.bs.modal', function () {
            popup.remove();
        });
        modal.show();
    });
</script>
@endif
