<div class="form-container">
    <h2 class="form-title">Add New Employee</h2>
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-error">
        ❌ <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            ✅ <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?= form_open('employees/add'); ?>

    <div class="form-row">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" value="<?= set_value('name') ?>" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" value="<?= set_value('email') ?>" placeholder="Enter email address" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" value="<?= set_value('phone') ?>" placeholder="Enter 10-digit phone number" maxlength="10" required>
        </div>

        <div class="form-group">
            <label for="hire_date">Hire Date</label>
            <input type="date" name="hire_date" value="<?= set_value('hire_date') ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="flex: 1;">
            <label for="department_id">Department</label>
            <select name="department_id" required>
                <option value="">Select Department</option>
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept->id ?>" <?= set_select('department_id', $dept->id) ?>>
                            <?= htmlspecialchars($dept->name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <input type="submit" value="✨ Add Employee" class="submit-btn">

    </form>
</div>