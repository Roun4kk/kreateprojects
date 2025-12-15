<!-- Add Client Button (Top Right) -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">📋 Client Details</h2>
  <div class="ml-auto">
    <button id="add-client-btn" class="btn btn-primary btn-sm shadow-sm transition-all duration-200 hover:bg-primary-dark hover:shadow-md">
      + Add New Client
    </button>
  </div>
</div>

<!-- Client Table -->
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-bordered" id="client-table">
        <thead class="bg-primary text-white">
            <tr>
                <th>Portfolio Id</th>
                <th>Name</th>
                <th>Shortcode</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered In</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $client): ?>
                    <tr data-id="<?php echo htmlspecialchars($client->C_Pid); ?>" class="transition-all duration-200 hover:bg-gray-50">
                        <td data-field="C_Pid"><?php echo htmlspecialchars($client->C_Pid); ?></td>
                        <td data-field="C_Name"><?php echo htmlspecialchars($client->C_Name); ?></td>
                        <td data-field="C_Shortcode"><?php echo htmlspecialchars($client->C_Shortcode); ?></td>
                        <td data-field="C_Email"><?php echo htmlspecialchars($client->C_Email); ?></td>
                        <td data-field="C_Phone_No"><?php echo htmlspecialchars($client->C_Phone_No); ?></td>
                        <td data-field="registered_in" data-value='<?php echo htmlspecialchars(json_encode($client->registered_in ?? [])); ?>'>
                            <?php
                            if (!empty($client->registered_in)) {
                                $registered_ids = json_decode($client->registered_in, true);
                                $names = [];
                                if (is_array($registered_ids)) {
                                    foreach ($registered_ids as $reg_id) {
                                        if (isset($registrations[$reg_id])) {
                                            $names[] = htmlspecialchars($registrations[$reg_id]);
                                        }
                                    }
                                }
                                echo empty($names) ? 'None' : implode(', ', $names);
                            } else {
                                echo 'None';
                            }
                            ?>
                        </td>
                        <td class="actions">
                            <button class="btn btn-sm btn-warning edit-client transition-all duration-200 hover:bg-yellow-600">Edit</button>
                            <button class="btn btn-sm btn-danger delete-client transition-all duration-200 hover:bg-red-600">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="no-clients-row"><td colspan="7" class="text-center text-muted">No clients found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- JavaScript -->
<script>
$(document).ready(function() {
    const registrationsData = <?php echo json_encode($registrations); ?>;
    console.log('registrationsData:', registrationsData); // Debugging

    // Configure Toastr options
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Debugging: Confirm jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please ensure jQuery is included.');
        toastr.error('jQuery is not loaded. Please check your script inclusions.');
        return;
    }

    // Add New Client
    $('#add-client-btn').on('click', function() {
        console.log('Add New Client button clicked');
        if ($('tr.new-row').length) {
            toastr.warning('Please save or cancel the current new client first.');
            return;
        }
        $('.no-clients-row').remove();

        const newRowHtml = `
            <tr class="new-row editable-row bg-blue-50">
                <td><input type="text" class="form-control shadow-sm" name="C_Pid" maxlength="10" required></td>
                <td><input type="text" class="form-control shadow-sm" name="C_Name" required></td>
                <td><input type="text" class="form-control shadow-sm" name="C_Shortcode" maxlength="8" required></td>
                <td><input type="email" class="form-control shadow-sm" name="C_Email" required></td>
                <td><input type="text" class="form-control shadow-sm" name="C_Phone_No" maxlength="10" required></td>
                <td>
                    <select multiple class="form-control shadow-sm" name="registered_in[]">
                        <option value="all">All</option>
                        ${Object.entries(registrationsData).map(([id, name]) => {
                            const escapedName = name.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                            return `<option value="${id}">${escapedName}</option>`;
                        }).join('')}
                    </select>
                </td>
                <td class="actions">
                    <button class="btn btn-sm btn-success save-client transition-all duration-200 hover:bg-green-600">Save</button>
                    <button class="btn btn-sm btn-secondary cancel-new transition-all duration-200 hover:bg-gray-600">Cancel</button>
                </td>
            </tr>`;
        $('#client-table tbody').prepend(newRowHtml);
    });

    // Edit Existing Client
    $('#client-table').on('click', '.edit-client', function (e) {
        const row = $(this).closest('tr');
        const actionsCell = row.find('td.actions');
        
        console.log('Edit button clicked for row ID:', row.data('id'));
        console.log('Current actions cell content:', actionsCell.html());

        if (row.hasClass('editable-row')) {
            console.log('Row is already in editable mode, skipping.');
            return;
        }

        const originalState = {};
        row.find('td[data-field]').each(function () {
            const field = $(this).data('field');
            originalState[field] = {
                html: $(this).html(),
                value: $(this).data('value')
            };

            const currentText = $(this).text().trim();
            let input;

            if (field === 'registered_in') {
                let selectedValues = [];
                try {
                    const rawValue = $(this).data('value') || '[]';
                    console.log('Parsing registered_in data-value:', rawValue);
                    selectedValues = JSON.parse(rawValue);
                    if (!Array.isArray(selectedValues)) {
                        console.warn('registered_in is not an array, defaulting to []');
                        selectedValues = [];
                    }
                } catch (error) {
                    console.error('JSON.parse error for registered_in:', error.message, 'Raw value:', $(this).data('value'));
                    selectedValues = [];
                }
                const options = Object.entries(registrationsData).map(([id, name]) => {
                    const escapedName = name.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                    // Compare as strings to match data-value format
                    const selected = selectedValues.includes(id.toString()) ? 'selected' : '';
                    return `<option value="${id}" ${selected}>${escapedName}</option>`;
                }).join('');
                console.log('Generated select options:', options);
                input = `<select multiple class="form-control shadow-sm" name="registered_in[]"><option value="all">All</option>${options}</select>`;
            } else {
                input = `<input type="text" class="form-control shadow-sm" name="${field}" value="${currentText.replace(/"/g, '&quot;')}">`;
            }

            $(this).html(input);
        });

        row.data('original-state', originalState);
        row.addClass('editable-row bg-blue-50');

        // Explicitly update actions cell
        console.log('Updating actions cell to Save/Cancel');
        actionsCell.empty().html(`
            <button class="btn btn-sm btn-success save-client transition-all duration-200 hover:bg-green-600">Save</button>
            <button class="btn btn-sm btn-secondary cancel-edit transition-all duration-200 hover:bg-gray-600">Cancel</button>
        `);

        // Force DOM repaint
        setTimeout(() => {
            actionsCell.css('display', 'none').css('display', '');
            actionsCell[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            console.log('Forced DOM repaint for actions cell');
        }, 100);
    });

    // Save Client
    $('#client-table').on('click', '.save-client', function() {
        console.log('Save button clicked');
        const row = $(this).closest('tr');
        const isNew = row.hasClass('new-row');
        const id = row.data('id');
        const url = isNew ? '<?php echo base_url('client/add'); ?>' : '<?php echo base_url('client/update/'); ?>' + id;

        const data = {};
        let isValid = true;
        row.find('input[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            toastr.error('Please fill all required fields.');
            return;
        }

        row.find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name === 'registered_in[]') {
                data['registered_in'] = $(this).val() || [];
            } else if (name) {
                data[name] = $(this).val();
            }
        });

        sendAjax(url, data, function(response) {
            if (isNew) {
                toastr.success('Client added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                toastr.success('Client updated successfully!');
                updateRowAfterSave(row, data);
            }
        });
    });

    // Cancel Edit
    $('#client-table').on('click', '.cancel-edit', function () {
        console.log('Cancel edit button clicked');
        const row = $(this).closest('tr');
        const originalState = row.data('original-state');

        row.find('td[data-field]').each(function () {
            const field = $(this).data('field');
            $(this).html(originalState[field].html);
        });

        row.removeClass('editable-row bg-blue-50');

        const actionsCell = row.find('td.actions');
        actionsCell.empty().html(`
            <button class="btn btn-sm btn-warning edit-client transition-all duration-200 hover:bg-yellow-600">Edit</button>
            <button class="btn btn-sm btn-danger delete-client transition-all duration-200 hover:bg-red-600">Delete</button>
        `);
        
        toastr.info('Edit cancelled.');
    });

    // Cancel New
    $('#client-table').on('click', '.cancel-new', function() {
        console.log('Cancel new button clicked');
        $(this).closest('tr').remove();
        if ($('#client-table tbody tr').length === 0) {
            $('#client-table tbody').html('<tr class="no-clients-row"><td colspan="7" class="text-center text-muted">No clients found.</td></tr>');
        }
        toastr.info('New client creation cancelled.');
    });

    // Delete Client with SweetAlert-style confirmation
    $('#client-table').on('click', '.delete-client', function() {
        console.log('Delete button clicked');
        const row = $(this).closest('tr');
        const clientName = row.find('[data-field="C_Name"]').text();
        const id = row.data('id');
        
        // Create custom confirmation toast - store the instance
        const confirmToastr = toastr.warning(
            `<div>
                <p>Are you sure you want to delete client: <strong>${clientName}</strong>?</p>
                <div style="margin-top: 10px;">
                    <button type="button" class="btn btn-sm btn-danger confirm-delete" data-id="${id}" data-client-name="${clientName}" style="margin-right: 5px;">Yes, Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary cancel-delete">Cancel</button>
                </div>
            </div>`,
            'Confirm Deletion',
            {
                allowHtml: true,
                timeOut: 0,
                extendedTimeOut: 0,
                tapToDismiss: false,
                closeButton: false,
                onShown: function() {
                    // Store the toastr instance reference for the buttons
                    $('.confirm-delete, .cancel-delete').data('toastr-instance', confirmToastr);
                }
            }
        );
    });

    // Handle delete confirmation
    $(document).on('click', '.confirm-delete', function() {
        const id = $(this).data('id');
        const clientName = $(this).data('client-name');
        const row = $(`tr[data-id="${id}"]`);
        const toastrInstance = $(this).data('toastr-instance');
        
        // Remove the specific confirmation toastr
        if (toastrInstance) {
            toastrInstance.remove();
        }
        
        toastr.info('Deleting client...', 'Please wait');
        
        sendAjax('<?php echo base_url('client/delete/'); ?>' + id, {}, function() {
            toastr.success('Client deleted successfully!');
            row.fadeOut(300, function() {
                $(this).remove();
                if ($('#client-table tbody tr').length === 0) {
                    $('#client-table tbody').html('<tr class="no-clients-row"><td colspan="7" class="text-center text-muted">No clients found.</td></tr>');
                }
            });
        });
    });

    // Handle delete cancellation
    $(document).on('click', '.cancel-delete', function() {
        const toastrInstance = $(this).data('toastr-instance');
        
        // Remove the specific confirmation toastr
        if (toastrInstance) {
            toastrInstance.remove();
        }
        
        toastr.info('Deletion cancelled.');
    });

    // Update Row After Save (Edit)
    function updateRowAfterSave(row, newData) {
        console.log('Updating row after save');
        row.removeClass('editable-row bg-blue-50');
        row.find('td[data-field]').each(function() {
            const field = $(this).data('field');
            if (field === 'registered_in') {
                const selectedIds = newData.registered_in || [];
                const names = selectedIds.map(id => registrationsData[id] || 'Unknown').join(', ');
                $(this).text(names || 'None');
                $(this).data('value', JSON.stringify(selectedIds));
            } else {
                $(this).text(newData[field]);
            }
        });
        const actionsCell = row.find('td.actions');
        actionsCell.empty().html(`
            <button class="btn btn-sm btn-warning edit-client transition-all duration-200 hover:bg-yellow-600">Edit</button>
            <button class="btn btn-sm btn-danger delete-client transition-all duration-200 hover:bg-red-600">Delete</button>
        `);
    }

    // AJAX Helper
    function sendAjax(url, data, successCallback) {
        console.log('Sending AJAX request to:', url, 'with data:', data);
        $.ajax({
            url: url,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log('AJAX success:', response);
                    successCallback(response);
                } else {
                    const errorMsg = Array.isArray(response.errors) ? response.errors.join('<br>') : 'An unknown error occurred.';
                    console.error('AJAX response error:', errorMsg);
                    toastr.error(errorMsg, 'Error', { allowHtml: true });
                }
            },
            error: function(xhr) {
                console.error('AJAX error:', xhr.responseText);
                toastr.error('A server error occurred. Please try again.', 'Server Error');
            }
        });
    }

    // Handle "All" selection
    $('#client-table').on('change', 'select[name="registered_in[]"]', function() {
        console.log('Registered_in select changed');
        const selected = $(this).val();
        if (selected && selected.includes('all')) {
            $(this).find('option[value!="all"]').prop('selected', true);
            $(this).find('option[value="all"]').prop('selected', false);
        }
    });
});
</script>

<!-- Inline CSS for UI Enhancements -->
<!-- Inline CSS for UI Enhancements -->
<style>
.transition-all {
    transition: all 0.2s ease;
}
.bg-primary-dark {
    background-color: #2563eb;
}
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}
.btn-lg {
    padding: 0.75rem 1.5rem;
}
.bg-blue-50 {
    background-color: #eff6ff;
}
.text-muted {
    color: #6b7280 !important;
}
.actions {
    min-width: 150px;
}
.hover:bg-gray-50:hover {
    background-color: #f9fafb;
}
.hover:bg-yellow-600:hover {
    background-color: #d97706;
}
.hover:bg-red-600:hover {
    background-color: #dc2626;
}
.hover:bg-green-600:hover {
    background-color: #16a34a;
}
.hover:bg-gray-600:hover {
    background-color: #4b5563;
}
.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
.form-control-plaintext {
    display: block;
    width: 100%;
    padding: 0.375rem 0;
    margin-bottom: 0;
    line-height: 1.5;
    color: #495057;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

/* Custom toast styles for confirmation */
.toast-warning .toast-message {
    text-align: center;
}

/* Loading state styles */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Improved form validation styles */
.form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-control:valid {
    border-color: #28a745;
}

/* Enhanced button styles */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Table row animation */
/* Table row animation */
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

tr {
    animation: fadeIn 0.3s ease-in-out;
}

/* Button press animation */
.btn:active {
    transform: scale(0.98);
    box-shadow: 0 1px 2px rgba(0,0,0,0.15);
}

/* Better appearance for select inputs */
select.form-control {
    background-color: #fff;
    border: 1px solid #ced4da;
}

/* Placeholder color for inputs */
::placeholder {
    color: #9ca3af;
    opacity: 1;
}

/* Darker placeholder on focus */
input:focus::placeholder {
    color: #6b7280;
}