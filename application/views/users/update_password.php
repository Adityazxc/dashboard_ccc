
<link rel="icon" href="<?= base_url('public/img/voucher.png') ?>" type="image/png">

<style>
  .input-group {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;

    /* Adjust width as needed */
  }

  .input-group .form-control {
    border: none;
    box-shadow: none;
  }

  .input-group .input-group-append .input-group-text {
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 5px;
  }
</style>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Halo, <b>
            <?= $username ?>
            (<?=$role ?>)
          </b> untuk reset password masukkan password lama terdahulu! </div>
        <div class="card-body">
          <form id="resetPasswordForm" method='post'
            action="<?php echo base_url('reset_password/process_reset_password') ?>">
            <label for="oldPassword">Old Password</label>
            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control" name="oldPassword" id="oldPassword" autocomplete="off"
                  required>
                <span class="input-group-text">
                  <a href="#" onclick="toggleOldPasswordVisibility()">
                    <i id="toggleOldPassword" class="fa fa-eye-slash" aria-hidden="true"></i>
                  </a>
                </span>
              </div>


              <label for="password">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control" name="newPassword" id="newPassword" autocomplete="off"
                  required>
                  <span class="input-group-text">
                  <a href="#" onclick="toggleNewPasswordVisibility()">
                    <i id="toggleIcon" class="fa fa-eye-slash" aria-hidden="true"></i>
                  </a>
                </span>
              </div>

              <label for="confirmPassword">Confirm Password</label>
              <div class="input-group">
                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword"
                  autocomplete="off" required>
                  <span class="input-group-text">
                  <a href="#" onclick="toggleNewPasswordVisibility()">
                    <i id="toggleIcon" class="fa fa-eye-slash" aria-hidden="true"></i>
                  </a>
                </span>
              </div>

              <input type="hidden" id="ModaladdCustomerModal_csrf" name="<?= $this->security->get_csrf_token_name() ?>"
                value="<?= $this->security->get_csrf_hash() ?>" />
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- <script src="<?= base_url() ?>public/js/users/update_password.js"></script> -->