<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id='addCustomer' action="<?php echo base_url('admin/add_user'); ?>"
                method="post" enctype="multipart/form-data" role="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Marketing">Account Name:</label>
                        <input type="text" class="form-control" id="accountName"
                            oninput="this.value = this.value.toUpperCase()" name="accountName" autocomplete="off"
                            required>
                    </div>
                    <!-- <input type="hidden" class="form-control" name="password" id="password" value="123456"> -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="Agen">Agen</option>
                            <option value="Admin">Admin</option>
                            <option value="CCC">CCC</option>
                            <option value="CS">CS</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Kacab">Kepala Cabang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="agentArea">Agent Area:</label>
                        <input type="text" class="form-control" id="agentArea"
                            oninput="this.value = this.value.toUpperCase()" name="agentArea" autocomplete="off"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="accountId">Account Id/Username</label>
                        <input type="text" class="form-control" id="accountId" name="accountId" autocomplete="off"
                            required>
                    </div>

                    <input type="hidden" id="ModaladdCustomerModal_csrf"
                        name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>" />
                    <!-- Add more form fields as needed -->
                    <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to toggle Agent Area field based on the selected role
        function toggleAgentArea() {
            var selectedRole = $("#role").val();
            var agentAreaField = $("#agentArea");

            if (selectedRole === "Agen") {
                // Disable the Agent Area field if the role is "Agen"
                agentAreaField.prop("disabled", false);
            } else {
                // Enable the Agent Area field for other roles
                agentAreaField.prop("disabled", true);
            }
        }

        // Initial state on page load
        toggleAgentArea();

        // Bind the function to the change event of the Role dropdown
        $("#role").change(function () {
            toggleAgentArea();
        });
    });
</script>