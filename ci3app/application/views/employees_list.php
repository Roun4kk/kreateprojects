<div class="container">
    <div class="header">
        <h1>👥 Employee Management</h1>
    </div>
    <?php
    $success_msg = $this->session->flashdata('success');
    $error_msg = $this->session->flashdata('error');
    ?>

    <?php if (isset($editId)): ?>
        <?php if ($success_msg): ?>
            <div class="alert alert-success">✅ <?= $success_msg ?></div>
        <?php endif; ?>

        <?php if ($error_msg): ?>
            <div class="alert alert-error">❌ <?= $error_msg ?></div>
        <?php endif; ?>
    <?php endif; ?>



    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Hire Date</th>
                    <th>Department</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($employees) && is_array($employees)): ?>
                    <?php foreach ($employees as $emp): ?>
                        <?php if (isset($editId) && $editId == $emp->id): ?>
                        <form method="post" action="<?= base_url('employees/edit/' . $emp->id); ?>">
                        <tr>
                            <td><?= htmlspecialchars($emp->id) ?></td>

                            <td><input type="text" name="name" value="<?= set_value('name', $emp->name) ?>"></td>

                            <td><input type="email" name="email" value="<?= set_value('email', $emp->email) ?>"></td>

                            <td><input type="text" name="phone" value="<?= set_value('phone') ?: $emp->phone ?>"></td>

                            <td><input type="date" name="hire_date" value="<?= set_value('hire_date', $emp->hire_date) ?>"></td>

                            <td>
                                <select name="department_id">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept->id ?>" <?= ($dept->id == $emp->department_id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($dept->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <?php
                                    $current_dept = null;
                                    foreach ($departments as $d) {
                                        if ($d->id == $emp->department_id) {
                                            $current_dept = $d;
                                            break;
                                        }
                                    }
                                ?>
                                <input type="text" name="location" readonly value="<?= htmlspecialchars($current_dept->location ?? '') ?>">
                            </td>

                            <td class="actions">
                                <button class = "btn save-btn ">✅ Save</button>
                                <a href="<?= base_url('employees'); ?>" class="btn delete-btn">❌ Cancel</a>
                            </td>
                        </tr>
                        </form>

                        <?php else: ?>
                        <tr>
                            <td><?= htmlspecialchars($emp->id) ?></td>
                            <td><?= htmlspecialchars($emp->name) ?></td>
                            <td><?= htmlspecialchars($emp->email) ?></td>
                            <td><?= htmlspecialchars($emp->phone) ?></td>
                            <td><?= htmlspecialchars($emp->hire_date) ?></td>
                            <td><?= isset($emp->department_name) ? htmlspecialchars($emp->department_name) : 'N/A' ?></td>
                            <td><?= isset($emp->department_location) ? htmlspecialchars($emp->department_location) : 'N/A' ?></td>
                            <td class="actions">
                                <a href="?edit_id=<?= $emp->id ?>" class="btn edit-btn">
                                    ✏️ Edit
                                </a>
                                <a href="<?= base_url('employees/delete/' . $emp->id); ?>"
                                   class="btn delete-btn"
                                   onclick="return confirm('🗑️ Are you sure you want to delete this employee?');">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="no-data">
                            📋 No employees found. <a href="<?= base_url('employees/add'); ?>">Add the first employee!</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function updateEmployee(empId) {
    const formData = {
        id: empId,
        name: document.querySelector(`#name-${empId}`).value,
        email: document.querySelector(`#email-${empId}`).value,
        phone: document.querySelector(`#phone-${empId}`).value,
        hire_date: document.querySelector(`#hire-date-${empId}`).value,
        department_id: document.querySelector(`#department-${empId}`).value
    };

    axios.post('<?= base_url("employees/ajax_edit") ?>', formData)
        .then(response => {
            if (response.data.status) {
                alert("✅ Updated successfully!");
                location.reload(); 
            } else {
                alert("❌ Update failed: " + response.data.message);
            }
        })
        .catch(error => {
            console.error(error);
            alert("🚨 An error occurred while updating.");
        });
}
</script> -->
