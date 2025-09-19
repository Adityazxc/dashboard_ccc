<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashdata Demo</title>
</head>
<body>
    <?php if ($this->session->flashdata('success_message')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success_message') ?>
            <audio id="notification-sound" src="https://res.cloudinary.com/dxfq3iotg/video/upload/v1557233524/success.mp3"
                   autoplay onended="removeAudio()"></audio>
        </div>
    <?php endif; ?>

    <script>
        function removeAudio() {
            var audio = document.getElementById('notification-sound');
            if (audio) {
                audio.remove();
            }
        }
    </script>
</body>
</html>
