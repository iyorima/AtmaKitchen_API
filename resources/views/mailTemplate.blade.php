<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP untuk verifikasi email</title>
</head>

<body>
    <h2>{{ $details['title'] }}</h2>

    <p>Terima kasih telah menggunakan AtmaKitchen. Kami tahu bahwa kamu berusaha melakukan reset password. Berikut
        adalah kode OTP yang digunakan untuk reset password.</p>

    <center style="background-color: #ffedd5; padding: 8px">
        <h1><b>{{ $details['otp'] }}</b></h1>
    </center>

    <p>Bila kode tidak dapat digunakan, silakan mengirim ulang kode OTP baru.</p>
</body>

</html>
