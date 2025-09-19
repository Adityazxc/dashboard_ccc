<link rel="icon" href="<?= base_url('public/img/camera.svg') ?>" type="image/svg+xml">
    <link rel="icon" type="image/png" href="gambar/depan/logos.png" />
    <title>Verifikasi OTP</title>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 10px;
        }

        .otp-container {
            background-color: #fff;
            padding: 35px 30px;
            border-radius: 16px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-sizing: border-box;
        }

        .otp-container h2 {
            margin-bottom: 15px;
            color: #333;
            font-size: 22px;
        }

        .otp-container input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 16px;
            margin-top: 8px;
            box-sizing: border-box;
        }

        .otp-container button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            font-size: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .otp-container button:hover {
            background-color: #218838;
        }

        .otp-container a.back-link {
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .otp-container a.back-link:hover {
            text-decoration: underline;
        }

        .otp-container p.error {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }

        dotlottie-player {
            max-width: 200px;
            height: auto;
            margin: 0 auto 10px auto;
        }

        @media (max-width: 480px) {
            .otp-container {
                padding: 25px 20px;
            }

            .otp-container h2 {
                font-size: 20px;
            }

            dotlottie-player {
                max-width: 150px;
            }
        }
    </style>
</head>

<body>

    <div class="otp-container">
        <dotlottie-player src="https://lottie.host/1187e823-add9-4ca8-8b83-8a083183b8ee/LpupSbIo9b.lottie"
            background="transparent" speed="1" style="width: 200px; height: 200px;" loop autoplay>
        </dotlottie-player>

        <h2>Verifikasi OTP</h2>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error_message') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('auth/verify_otp') ?>">
            <div class="mb-3">
                <label for="otp" class="form-label">Masukkan 6 digit kode dari aplikasi Authenticator</label>
                <input type="text" class="form-control" name="otp" id="otp" required maxlength="6">
            </div>
            <button type="submit" class="btn btn-primary">Verifikasi</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const countdown = document.getElementById("countdown");
            if (countdown) {
                let time = parseInt(countdown.textContent);
                const timer = setInterval(() => {
                    time--;
                    countdown.textContent = time;
                    if (time <= 0) {
                        clearInterval(timer);
                        location.reload();
                    }
                }, 1000);
            }
        });
    </script>


</body>

</html> 