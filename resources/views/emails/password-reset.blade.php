<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SukaRobot E-Learning</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    
                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f97316 0%, #3b82f6 100%); padding: 40px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                🤖 SukaRobot E-Learning
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">
                                Platform Pembelajaran Digital Terbaik
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px 0; color: #1f2937; font-size: 22px; font-weight: 600;">
                                Halo, {{ $userName }}! 👋
                            </h2>
                            
                            <p style="margin: 0 0 25px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan proses reset password:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="background: linear-gradient(135deg, #fef3c7 0%, #dbeafe 100%); border-radius: 12px; padding: 30px; text-align: center; margin: 30px 0;">
                                <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                                    Kode OTP Anda
                                </p>
                                <div style="display: inline-block;">
                                    @foreach(str_split($otp) as $digit)
                                    <span style="display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; font-size: 32px; font-weight: 700; width: 50px; height: 60px; line-height: 60px; text-align: center; border-radius: 10px; margin: 0 4px; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);">{{ $digit }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Warning Box -->
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 15px 20px; margin: 25px 0;">
                                <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.5;">
                                    ⏰ <strong>Penting:</strong> Kode OTP ini hanya berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini kepada siapapun.
                                </p>
                            </div>
                            
                            <!-- Security Notice -->
                            <p style="margin: 25px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Jika Anda tidak meminta reset password, abaikan email ini. Akun Anda tetap aman.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px 40px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 13px;">
                                © {{ date('Y') }} SukaRobot E-Learning. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
                <!-- Additional Footer -->
                <table role="presentation" style="width: 100%; max-width: 600px; margin-top: 20px;">
                    <tr>
                        <td style="text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                Butuh bantuan? Hubungi kami di <a href="mailto:support@sukarobot.com" style="color: #3b82f6; text-decoration: none;">support@sukarobot.com</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
