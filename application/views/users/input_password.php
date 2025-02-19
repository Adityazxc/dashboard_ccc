<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Halo, <b>
            <?= $username ?> (
            <?= $role ?>)
          </b> reset password terlebih dahulu!</div>
        <div class="card-body">
          <form id="resetPasswordForm" method='post' action="<?php echo base_url('reset_password/process_reset_password') ?>">
            <div class="form-group">
              <label for="password">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" autocomplete="off" required>
                <input type="hidden" id="ModaladdCustomerModal_csrf"
                  name="<?= $this->security->get_csrf_token_name() ?>"
                  value="<?= $this->security->get_csrf_hash() ?>" />
                <div class="input-group-append">
                  <button type="button" class="btn btn-outline-secondary"
                    onclick="togglePasswordVisibility()">Show</button>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('resetPasswordForm').addEventListener('submit', function (event) {
    var passwordInput = document.getElementById('password').value;

    // Check if the input is '123456'
    if (passwordInput === '123456') {
      alert('Password tidak boleh "123456".');
      event.preventDefault(); // Prevent form submission
      return;
    }

    // Check if the input has at least 6 characters
    if (passwordInput.length < 6) {
      alert("Password setidaknya memiliki 6 karakter");
      event.preventDefault(); // Prevent form submission
      return;
    }

  });

  function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var button = document.querySelector('#resetPasswordForm button');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      button.textContent = 'Hide';
    } else {
      passwordInput.type = 'password';
      button.textContent = 'Show';
    }
  }
  $(document).ready(function () {
    $("#resetPasswordForm").click(function () {
      $.getJSON('<?= base_url('admin/get_csrf_json') ?>', function (res) {
        if (res.status == "Success") {
          $('[id="ModaladdCustomerModal_csrf"]').val(res.get_csrf_hash);
        }
      });
    });
  });
</script>