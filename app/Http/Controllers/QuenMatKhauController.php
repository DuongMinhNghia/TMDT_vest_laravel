<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Rules\StrongPassword;
use Throwable;

class QuenMatKhauController extends Controller
{
    private const OTP_TTL_MINUTES = 10;
    private const OTP_MAX_ATTEMPTS = 5;

    public function formGuiMa()
    {
        return view('quenmatkhau');
    }

    public function guiMa(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $email = mb_strtolower($data['email']);
        $key = 'password-reset-otp:' . $request->ip() . ':' . $email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withInput()->with('error', 'Bạn đã yêu cầu mã quá nhiều lần. Vui lòng thử lại sau ít phút.');
        }

        $taiKhoan = TaiKhoan::where('email', $email)->where('trang_thai', true)->first();
        if (!$taiKhoan) {
            RateLimiter::hit($key, 600);
            return back()->withInput()->with('success', 'Nếu email tồn tại, mã xác thực đã được gửi. Vui lòng kiểm tra hộp thư.');
        }

        if (config('mail.default') !== 'smtp') {
            return back()->withInput()->with('error', 'Dịch vụ gửi email chưa được cấu hình. Vui lòng liên hệ quản trị viên.');
        }

        $otp = (string) random_int(100000, 999999);

        try {
            Mail::raw(
                "Mã xác thực đặt lại mật khẩu Kingsman của bạn là: {$otp}\n\nMã có hiệu lực trong " . self::OTP_TTL_MINUTES . ' phút. Không chia sẻ mã này với bất kỳ ai.',
                function ($message) use ($email) {
                    $message->to($email)->subject('Mã xác thực đặt lại mật khẩu - Kingsman');
                }
            );
        } catch (Throwable) {
            return back()->withInput()->with('error', 'Không thể gửi email xác thực lúc này. Vui lòng thử lại sau.');
        }

        RateLimiter::hit($key, 600);
        $request->session()->put('password_reset_otp', [
            'email' => $email,
            'hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES)->timestamp,
            'attempts' => 0,
        ]);
        $request->session()->forget('password_reset_verified_email');

        return redirect()->route('matkhau.xacnhan.form')->with('success', 'Mã xác thực gồm 6 chữ số đã được gửi đến email của bạn.');
    }

    public function formXacNhan(Request $request)
    {
        abort_unless($request->session()->has('password_reset_otp'), 403);

        return view('xacnhanotp');
    }

    public function xacNhan(Request $request)
    {
        $data = $request->validate(['otp' => 'required|digits:6']);
        $otpData = $request->session()->get('password_reset_otp');

        if (!$otpData || now()->timestamp > $otpData['expires_at']) {
            $request->session()->forget('password_reset_otp');
            return redirect()->route('matkhau.quen.form')->with('error', 'Mã xác thực đã hết hạn. Vui lòng yêu cầu mã mới.');
        }

        if ($otpData['attempts'] >= self::OTP_MAX_ATTEMPTS || !Hash::check($data['otp'], $otpData['hash'])) {
            $otpData['attempts']++;
            $request->session()->put('password_reset_otp', $otpData);
            return back()->with('error', 'Mã xác thực không chính xác.');
        }

        $request->session()->put('password_reset_verified_email', $otpData['email']);
        $request->session()->forget('password_reset_otp');

        return redirect()->route('matkhau.datlai.form');
    }

    public function formDatLai(Request $request)
    {
        abort_unless($request->session()->has('password_reset_verified_email'), 403);

        return view('datlaimatkhau');
    }

    public function datLai(Request $request)
    {
        $email = $request->session()->get('password_reset_verified_email');
        abort_unless($email, 403);

        $data = $request->validate([
            'password' => ['required', 'confirmed', new StrongPassword],
        ], [
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ]);

        $taiKhoan = TaiKhoan::where('email', $email)->where('trang_thai', true)->firstOrFail();
        if (Hash::check($data['password'], $taiKhoan->password)) {
            return back()->withErrors(['password' => 'Mật khẩu mới phải khác mật khẩu cũ.']);
        }

        $taiKhoan->update(['password' => Hash::make($data['password'])]);

        $request->session()->forget('password_reset_verified_email');
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
