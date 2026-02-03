<!DOCTYPE html>
<html>
<head>
    <title>Pesan Baru dari Website</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #0056b3;">Pesan Baru dari Formulir Kontak</h2>
    <p>Halo Admin,</p>
    <p>Anda telah menerima pesan baru dari website Sukarobot Academy. Berikut adalah detailnya:</p>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; width: 150px;">Nama Lengkap</td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $data['name'] }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Email</td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $data['email'] }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Nomor HP</td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $data['phone'] }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Pesan</td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                {!! nl2br(e($data['message'])) !!}
            </td>
        </tr>
    </table>

    <p style="margin-top: 30px; font-size: 0.9em; color: #666;">
        Email ini dikirim otomatis dari website Sukarobot Academy via formulir kontak.
    </p>
</body>
</html>
