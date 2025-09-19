<!DOCTYPE html>
<html lang="id">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('public/img/camera.svg') ?>" type="image/svg+xml">
</head>

<body class="p-5">
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="gambar/depan/logos.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Google Authenticator</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .setup-container {
            background: #fff;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .setup-container h2 {
            margin-bottom: 10px;
            color: #333;
            font-size: 22px;
        }

        .setup-container p {
            font-size: 14px;
            color: #555;
        }

        .setup-container img {
            margin: 20px 0;
            width: 180px;
            height: 180px;
            border: 4px solid #e0e0e0;
            border-radius: 8px;
        }

        .setup-container input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 16px;
            box-sizing: border-box;
        }

        .setup-container button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            font-size: 15px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .setup-container button:hover {
            background-color: #0056b3;
        }

        .setup-container a.back-link {
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .setup-container a.back-link:hover {
            text-decoration: underline;
        }

        .setup-container p.error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .setup-container {
                padding: 20px;
            }

            .setup-container h2 {
                font-size: 20px;
            }

            .setup-container img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
    </head>

    <body>

        <div class="setup-container">
            <h2>Setup Google Authenticator</h2>
            <p>Scan QR Code ini di aplikasi Google Authenticator:</p>
            <img src="<?= $qr_url ?>" alt="QR Code">          
            <?php if ($this->session->flashdata('error_message')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error_message') ?></div>
            <?php endif; ?>
            <form method="post" action="<?= base_url('auth/verify_otp_setup') ?>">
                <label for="otp_code">Masukkan Kode OTP</label><br>
                <input type="text" name="otp_code" id="otp_code" autocomplete="off" required><br>
                <button type="submit">Verifikasi</button>
                <a href="<?= base_url('auth') ?>">← Kembali ke Validasi</a>

            </form>
        </div>

    </body>

</html>