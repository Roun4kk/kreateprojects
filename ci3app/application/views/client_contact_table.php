<?php if (!empty($clients)): ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
      <h2 class="mb-0 mr-3">Client Contact Details</h2>
      <div class="input-group" style="width: 300px;">
        <input type="text" class="form-control form-control-sm" placeholder="Search by Client PID or Name..." id="search-input">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary btn-sm" type="button" id="search-btn">
            🔍 Search
          </button>
        </div>
      </div>
    </div>
    <button id="add-contact-btn" class="btn btn-primary btn-sm shadow-sm transition-all duration-200 hover:bg-primary-dark hover:shadow-md">
      + Add New Contact
    </button>
  </div>

  <div id="search-info" class="alert alert-info" style="display: none;">
    <strong>Search Results:</strong> <span id="search-results-text"></span>
    <button type="button" class="btn btn-sm btn-outline-primary ml-2" id="clear-search">Show All</button>
  </div>

  <div class="table-responsive shadow-sm rounded">
      <table class="table table-striped table-bordered" id="contact-table">
          <thead class="bg-primary text-white">
              <tr>
                  <th>Client PID</th>
                  <th>Client Name</th>
                  <th>Contact Name</th>
                  <th>Contact Email</th>
                  <th>Contact Phone</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Settings</th>
                  <th style="width: 150px;">Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php if (!empty($client_contacts)): ?>
                  <?php foreach ($client_contacts as $contact): ?>
                      <tr data-id="<?php echo htmlspecialchars($contact->Contact_ID); ?>" 
                          class="transition-all duration-200 hover:bg-gray-50"
                          data-email='<?php echo htmlspecialchars(json_encode($contact->email ?? [])); ?>'
                          data-sms='<?php echo htmlspecialchars(json_encode($contact->sms ?? [])); ?>'>
                          <td data-field="C_Pid"><?php echo htmlspecialchars($contact->C_Pid); ?></td>
                          <td data-field="C_Name"><?php echo htmlspecialchars($contact->C_Name ?? 'N/A'); ?></td>
                          <td data-field="Contact_Name"><?php echo htmlspecialchars($contact->Contact_Name); ?></td>
                          <td data-field="Contact_Email"><?php echo htmlspecialchars($contact->Contact_Email); ?></td>
                          <td data-field="Contact_Phone"><?php echo htmlspecialchars($contact->Contact_Phone); ?></td>
                          <td data-field="Designation" data-value="<?php echo htmlspecialchars($contact->Designation); ?>">
                              <?php echo htmlspecialchars($contact->Designation_Name ?? 'N/A'); ?>
                          </td>
                          <td data-field="Department" data-value="<?php echo htmlspecialchars($contact->Department); ?>">
                              <?php echo htmlspecialchars($contact->Department_Name ?? 'N/A'); ?>
                          </td>
                          <td class="text-center">
                              <button class="btn btn-sm btn-outline-secondary settings-btn" title="Settings">
                                  ⚙️
                              </button>
                          </td>
                          <td class="actions">
                              <button class="btn btn-sm btn-warning edit-contact transition-all duration-200 hover:bg-yellow-600">Edit</button>
                              <button class="btn btn-sm btn-danger delete-contact transition-all duration-200 hover:bg-red-600">Delete</button>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr class="no-contacts-row"><td colspan="9" class="text-center text-muted">No client contacts found.</td></tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>
  
<?php else: ?>
  <div class="alert alert-warning text-center" role="alert">
    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> No Clients Found!</h4>
    <p>There are currently no clients registered in the system. You must add a client before you can manage their contact details.</p>
    <hr>
    <a href="<?php echo base_url('client'); ?>" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i> Add a New Client
    </a>
  </div>
<?php endif; ?>
<!-- Settings Modal -->
<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-custom" role="document">
    <div class="modal-content modal-content-custom">
      <div class="modal-header modal-header-fixed">
        <h5 class="modal-title" id="settingsModalLabel">Contact Settings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body modal-body-scrollable">
        <p>Configure Email and SMS notification settings for each registration type.</p>
        <div class="table-container">
          <table class="table table-bordered" id="settings-table">
            <thead>
              <tr>
                <th>Registered In</th>
                <th>Email</th>
                <th>SMS</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($registrations as $reg_id => $reg_name): ?>
                <tr>
                  <td><?php echo htmlspecialchars($reg_name); ?></td>
                  <td><input type="checkbox" class="email-checkbox" data-reg-id="<?php echo $reg_id; ?>"></td>
                  <td><input type="checkbox" class="sms-checkbox" data-reg-id="<?php echo $reg_id; ?>"></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="modal-footer modal-footer-fixed">
        <button type="button" class="btn btn-primary" id="save-settings">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!-- JavaScript -->
<script>
$(document).ready(function() {
    const designationsData = <?php echo json_encode($designations); ?>;
    const departmentsData = <?php echo json_encode($departments); ?>;
    const clientsData = <?php echo json_encode($clients); ?>;
    const registrationsData = <?php echo json_encode($registrations); ?>;

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

    // Search functionality
    $('#search-btn').on('click', function () {
        const searchTerm = $('#search-input').val().trim();
        if (searchTerm) {
            performSearch(searchTerm);
        } else {
            toastr.warning('Please enter a search term.');
        }
    });

    $('#search-input').on('keypress', function (e) {
        if (e.which === 13) {
            const searchTerm = $(this).val().trim();
            if (searchTerm) {
                performSearch(searchTerm);
            } else {
                toastr.warning('Please enter a search term.');
            }
        }
    });

    // Clear search
    $('#clear-search').on('click', function() {
        $('#search-input').val('');
        $('#search-info').hide();
        toastr.info('Search cleared. Showing all contacts.');
        location.reload();
    });

    // Perform search
    function performSearch(searchTerm) {
        $.ajax({
            url: '<?php echo base_url('client_contact/search'); ?>',
            method: 'POST',
            data: { search_term: searchTerm },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateTableWithSearchResults(response.client_contacts, response.client_info);
                    $('#search-results-text').text(`Found ${response.client_contacts.length} contacts for client: ${response.client_info.C_Name} (${response.client_info.C_Pid})`);
                    $('#search-info').show();
                    toastr.success(`Found ${response.client_contacts.length} contacts for ${response.client_info.C_Name}`, 'Search Results');
                } else {
                    const errorMsg = Array.isArray(response.errors) ? response.errors.join('<br>') : 'Search failed';
                    toastr.error(errorMsg, 'Search Error', { allowHtml: true });
                }
            },
            error: function() {
                toastr.error('Search failed. Please try again.', 'Search Error');
            }
        });
    }

    // Update table with search results
    function updateTableWithSearchResults(contacts, clientInfo) {
        const tbody = $('#contact-table tbody');
        tbody.empty();
        
        if (contacts.length === 0) {
            tbody.html('<tr class="no-contacts-row"><td colspan="9" class="text-center text-muted">No contacts found for this client.</td></tr>');
            return;
        }

        contacts.forEach(function(contact) {
            const row = `
                <tr data-id="${contact.Contact_ID}" 
                    class="transition-all duration-200 hover:bg-gray-50"
                    data-email='${JSON.stringify(contact.email || [])}'
                    data-sms='${JSON.stringify(contact.sms || [])}'>
                    <td data-field="C_Pid">${contact.C_Pid}</td>
                    <td data-field="C_Name">${contact.C_Name || 'N/A'}</td>
                    <td data-field="Contact_Name">${contact.Contact_Name}</td>
                    <td data-field="Contact_Email">${contact.Contact_Email}</td>
                    <td data-field="Contact_Phone">${contact.Contact_Phone}</td>
                    <td data-field="Designation" data-value="${contact.Designation}">${contact.Designation_Name || 'N/A'}</td>
                    <td data-field="Department" data-value="${contact.Department}">${contact.Department_Name || 'N/A'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary settings-btn" title="Settings">⚙️</button>
                    </td>
                    <td class="actions">
                        <button class="btn btn-sm btn-warning edit-contact transition-all duration-200 hover:bg-yellow-600">Edit</button>
                        <button class="btn btn-sm btn-danger delete-contact transition-all duration-200 hover:bg-red-600">Delete</button>
                    </td>
                </tr>`;
            tbody.append(row);
        });
    }

    // Settings button click
    let currentContactId = null;
    $('#contact-table').on('click', '.settings-btn', function() {
        const row = $(this).closest('tr');
        currentContactId = row.data('id');
        const contactName = row.find('[data-field="Contact_Name"]').text();
        let emailData = [];
        let smsData = [];
        
        try {
            emailData = JSON.parse(row.data('email') || '[]');
            smsData = JSON.parse(row.data('sms') || '[]');
        } catch (error) {
            console.error('JSON.parse error for email/sms:', error.message);
            emailData = [];
            smsData = [];
        }

        // Update modal title
        $('#settingsModalLabel').text(`Settings for Contact: ${contactName} (ID: ${currentContactId})`);

        // Update checkboxes based on email and sms data
        $('#settings-table tbody tr').each(function() {
            const regId = $(this).find('.email-checkbox').data('reg-id').toString();
            $(this).find('.email-checkbox').prop('checked', emailData.includes(regId));
            $(this).find('.sms-checkbox').prop('checked', smsData.includes(regId));
        });

        // Show modal
        $('#settingsModal').modal('show');
    });

    // Save settings
    $('#save-settings').on('click', function() {
        const emailSelections = [];
        const smsSelections = [];
        
        $('#settings-table tbody tr').each(function() {
            const regId = $(this).find('.email-checkbox').data('reg-id').toString();
            if ($(this).find('.email-checkbox').prop('checked')) {
                emailSelections.push(regId);
            }
            if ($(this).find('.sms-checkbox').prop('checked')) {
                smsSelections.push(regId);
            }
        });

        const data = {
            email: emailSelections,
            sms: smsSelections
        };

        $.ajax({
            url: '<?php echo base_url('client_contact/update_settings/'); ?>' + currentContactId,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Settings updated successfully!', 'Success');
                    const row = $(`tr[data-id="${currentContactId}"]`);
                    row.data('email', JSON.stringify(emailSelections));
                    row.data('sms', JSON.stringify(smsSelections));
                    $('#settingsModal').modal('hide');
                } else {
                    const errorMsg = Array.isArray(response.errors) ? response.errors.join('<br>') : 'Failed to update settings';
                    toastr.error(errorMsg, 'Error', { allowHtml: true });
                }
            },
            error: function() {
                toastr.error('Failed to save settings. Please try again.', 'Server Error');
            }
        });
    });

    // Add New Contact
    $('#add-contact-btn').on('click', function() {
        if ($('tr.new-row').length) {
            toastr.warning('Please save or cancel the current new contact first.');
            return;
        }
        $('.no-contacts-row').remove();

        const clientOptions = Object.entries(clientsData).map(([pid, name]) => {
            return `<option value="${pid}">${name} (${pid})</option>`;
        }).join('');

        const designationOptions = Object.entries(designationsData).map(([id, name]) => {
            return `<option value="${id}">${name}</option>`;
        }).join('');

        const departmentOptions = Object.entries(departmentsData).map(([id, name]) => {
            return `<option value="${id}">${name}</option>`;
        }).join('');

        const newRowHtml = `
            <tr class="new-row editable-row bg-blue-50">
                <td>
                    <select class="form-control shadow-sm" name="C_Pid" required>
                        <option value="">Select Client</option>
                        ${clientOptions}
                    </select>
                </td>
                <td>-</td>
                <td><input type="text" class="form-control shadow-sm" name="Contact_Name" required></td>
                <td><input type="email" class="form-control shadow-sm" name="Contact_Email" required></td>
                <td><input type="text" class="form-control shadow-sm" name="Contact_Phone" maxlength="10" required></td>
                <td>
                    <select class="form-control shadow-sm" name="Designation" required>
                        <option value="">Select Designation</option>
                        ${designationOptions}
                    </select>
                </td>
                <td>
                    <select class="form-control shadow-sm" name="Department" required>
                        <option value="">Select Department</option>
                        ${departmentOptions}
                    </select>
                </td>
                <td class="text-center">-</td>
                <td class="actions">
                    <button class="btn btn-sm btn-success save-contact transition-all duration-200 hover:bg-green-600">Save</button>
                    <button class="btn btn-sm btn-secondary cancel-new transition-all duration-200 hover:bg-gray-600">Cancel</button>
                </td>
            </tr>`;
        $('#contact-table tbody').append(newRowHtml);
        toastr.info('New contact row added. Fill in the details and click Save.');
    });

    // Edit Existing Contact
    $('#contact-table').on('click', '.edit-contact', function () {
        const row = $(this).closest('tr');
        const actionsCell = row.find('td.actions');
        
        if (row.hasClass('editable-row')) {
            toastr.warning('This contact is already being edited.');
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

            if (field === 'C_Pid') {
                const clientOptions = Object.entries(clientsData).map(([pid, name]) => {
                    const selected = pid === currentText ? 'selected' : '';
                    return `<option value="${pid}" ${selected}>${name} (${pid})</option>`;
                }).join('');
                input = `<select class="form-control shadow-sm" name="C_Pid" required>${clientOptions}</select>`;
            } else if (field === 'Designation') {
                const designationOptions = Object.entries(designationsData).map(([id, name]) => {
                    const selected = id === $(this).data('value').toString() ? 'selected' : '';
                    return `<option value="${id}" ${selected}>${name}</option>`;
                }).join('');
                input = `<select class="form-control shadow-sm" name="Designation" required><option value="">Select Designation</option>${designationOptions}</select>`;
            } else if (field === 'Department') {
                const departmentOptions = Object.entries(departmentsData).map(([id, name]) => {
                    const selected = id === $(this).data('value').toString() ? 'selected' : '';
                    return `<option value="${id}" ${selected}>${name}</option>`;
                }).join('');
                input = `<select class="form-control shadow-sm" name="Department" required><option value="">Select Department</option>${departmentOptions}</select>`;
            } else if (field === 'C_Name') {
                input = `<span class="form-control-plaintext">${currentText}</span>`;
            } else if (field === 'Contact_Email') {
                input = `<input type="email" class="form-control shadow-sm" name="${field}" value="${currentText.replace(/"/g, '"')}" required>`;
            } else if (field === 'Contact_Phone') {
                input = `<input type="text" class="form-control shadow-sm" name="${field}" value="${currentText.replace(/"/g, '"')}" maxlength="10" required>`;
            } else {
                input = `<input type="text" class="form-control shadow-sm" name="${field}" value="${currentText.replace(/"/g, '"')}" required>`;
            }

            $(this).html(input);
        });

        row.data('original-state', originalState);
        row.addClass('editable-row bg-blue-50');

        actionsCell.empty().html(`
            <button class="btn btn-sm btn-success save-contact transition-all duration-200 hover:bg-green-600">Save</button>
            <button class="btn btn-sm btn-secondary cancel-edit transition-all duration-200 hover:bg-gray-600">Cancel</button>
        `);
    });
    // Save Contact
    $('#contact-table').on('click', '.save-contact', function() {
        console.log('Save contact button clicked');
        const row = $(this).closest('tr');
        const isNew = row.hasClass('new-row');
        const id = row.data('id');
        const url = isNew ? '<?php echo base_url('client_contact/add'); ?>' : '<?php echo base_url('client_contact/update/'); ?>' + id;

        const data = {};
        let isValid = true;
        
        row.find('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            toastr.error('Please fill all required fields.', 'Validation Error');
            return;
        }

        // Validate email format
        const emailInput = row.find('input[name="Contact_Email"]');
        if (emailInput.length && emailInput.val()) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.val())) {
                emailInput.addClass('is-invalid');
                toastr.error('Please enter a valid email address.', 'Validation Error');
                return;
            }
        }

        // Validate phone number
        const phoneInput = row.find('input[name="Contact_Phone"]');
        if (phoneInput.length && phoneInput.val()) {
            const phonePattern = /^\d{10}$/;
            if (!phonePattern.test(phoneInput.val())) {
                phoneInput.addClass('is-invalid');
                toastr.error('Please enter a valid 10-digit phone number.', 'Validation Error');
                return;
            }
        }

        row.find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name) {
                data[name] = $(this).val();
            }
        });
        
        sendAjax(url, data, function(response) {
            if (isNew) {
                toastr.success('Contact added successfully!', 'Success');
                setTimeout(() => location.reload(), 1000);
            } else {
                updateRowAfterSave(row, data);
                toastr.success('Contact updated successfully!', 'Success');
            }
        });
    });

    // Cancel Edit
    $('#contact-table').on('click', '.cancel-edit', function () {
        const row = $(this).closest('tr');
        const originalState = row.data('original-state');

        row.find('td[data-field]').each(function () {
            const field = $(this).data('field');
            $(this).html(originalState[field].html);
        });

        row.removeClass('editable-row bg-blue-50');

        const actionsCell = row.find('td.actions');
        actionsCell.empty().html(`
            <button class="btn btn-sm btn-warning edit-contact transition-all duration-200 hover:bg-yellow-600">Edit</button>
            <button class="btn btn-sm btn-danger delete-contact transition-all duration-200 hover:bg-red-600">Delete</button>
        `);
        
        toastr.info('Edit cancelled. Changes discarded.');
    });

    // Cancel New
    $('#contact-table').on('click', '.cancel-new', function() {
        console.log('Cancel new button clicked');
        $(this).closest('tr').remove();
        if ($('#contact-table tbody tr').length === 0) {
            $('#contact-table tbody').html('<tr class="no-contacts-row"><td colspan="9" class="text-center text-muted">No client contacts found.</td></tr>');
        }
        toastr.info('New contact cancelled.');
    });

    // Delete Contact
    $('#contact-table').on('click', '.delete-contact', function() {
        console.log('Delete button clicked');
        const row = $(this).closest('tr');
        const contactName = row.find('[data-field="Contact_Name"]').text();
        const id = row.data('id');
        
        // Custom confirmation using Toastr - store the instance
        const confirmToastr = toastr.warning(
            `<div>Are you sure you want to delete contact "${contactName}"?</div>
            <div style="margin-top: 10px;">
                <button type="button" id="confirm-delete" class="btn btn-sm btn-danger" style="margin-right: 10px;">Yes, Delete</button>
                <button type="button" id="cancel-delete" class="btn btn-sm btn-secondary">Cancel</button>
            </div>`,
            'Confirm Delete',
            {
                allowHtml: true,
                closeButton: false,
                timeOut: 0,
                extendedTimeOut: 0,
                tapToDismiss: false,
                onShown: function() {
                    $('#confirm-delete').off('click').on('click', function() {
                        confirmToastr.remove(); // Remove specific toastr
                        toastr.info('Deleting contact...', 'Please wait');
                        sendAjax('<?php echo base_url('client_contact/delete/'); ?>' + id, {}, function() {
                            row.fadeOut(300, function() {
                                $(this).remove();
                                if ($('#contact-table tbody tr').length === 0) {
                                    $('#contact-table tbody').html('<tr class="no-contacts-row"><td colspan="9" class="text-center text-muted">No client contacts found.</td></tr>');
                                }
                                toastr.success(`Contact "${contactName}" deleted successfully!`, 'Deleted');
                            });
                        });
                    });
                    
                    $('#cancel-delete').off('click').on('click', function() {
                        confirmToastr.remove(); // Remove specific toastr instead of clearing all
                        toastr.info('Delete cancelled.');
                    });
                }
            }
        );
    });

    // Update Row After Save (Edit)
    function updateRowAfterSave(row, newData) {
        console.log('Updating row after save');
        row.removeClass('editable-row bg-blue-50');
        row.find('td[data-field]').each(function() {
            const field = $(this).data('field');
            if (field === 'Designation') {
                const designationName = designationsData[newData.Designation] || 'N/A';
                $(this).text(designationName);
                $(this).data('value', newData.Designation);
            } else if (field === 'Department') {
                const departmentName = departmentsData[newData.Department] || 'N/A';
                $(this).text(departmentName);
                $(this).data('value', newData.Department);
            } else if (field === 'C_Name') {
                const clientName = clientsData[newData.C_Pid] || 'N/A';
                $(this).text(clientName);
            } else if (newData[field]) {
                $(this).text(newData[field]);
            }
        });
        const actionsCell = row.find('td.actions');
        actionsCell.empty().html(`
            <button class="btn btn-sm btn-warning edit-contact transition-all duration-200 hover:bg-yellow-600">Edit</button>
            <button class="btn btn-sm btn-danger delete-contact transition-all duration-200 hover:bg-red-600">Delete</button>
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
                let errorMessage = 'A server error occurred. Please try again.';
                
                // Try to parse error response
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    // Use default error message
                }
                
                toastr.error(errorMessage, 'Server Error');
            }
        });
    }

    // Client PID change handler for new rows
    $('#contact-table').on('change', 'select[name="C_Pid"]', function() {
        const selectedPid = $(this).val();
        const clientName = clientsData[selectedPid] || '-';
        $(this).closest('tr').find('td:nth-child(2)').text(clientName);
    });

    // Input validation on blur
    $('#contact-table').on('blur', 'input[required], select[required]', function() {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
        }
    });

    // Real-time phone number validation
    $('#contact-table').on('input', 'input[name="Contact_Phone"]', function() {
        const value = $(this).val();
        // Remove non-digit characters
        const cleanValue = value.replace(/\D/g, '');
        $(this).val(cleanValue);
        
        if (cleanValue.length > 0 && cleanValue.length !== 10) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Real-time email validation
    $('#contact-table').on('blur', 'input[name="Contact_Email"]', function() {
        const email = $(this).val();
        if (email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        }
    });
});
</script>

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
/* Custom modal styles to ensure proper height and margins */
.modal-dialog-custom {
    max-width: 600px;
    margin: 4rem auto !important;
    max-height: calc(100vh - 8rem);
    display: flex;
    flex-direction: column;
}

.modal-content-custom {
    max-height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.modal-body-scrollable {
    overflow-y: auto;
    flex: 1;
    max-height: calc(100vh - 16rem);
}

.modal-header-fixed {
    flex-shrink: 0;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer-fixed {
    flex-shrink: 0;
    border-top: 1px solid #dee2e6;
}

/* Larger checkbox styling */
.email-checkbox, .sms-checkbox {
    transform: scale(1.5);
    margin: 0;
}

/* Center checkboxes in their cells */
#settings-table td {
    text-align: center;
    vertical-align: middle;
}

#settings-table th {
    text-align: center;
    vertical-align: middle;
}

/* Table container for responsive scrolling */
.table-container {
    overflow-x: auto;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-dialog-custom {
        margin: 2rem auto !important;
        max-height: calc(100vh - 4rem);
    }
    
    .modal-body-scrollable {
        max-height: calc(100vh - 12rem);
    }
}

@media (max-width: 576px) {
    .modal-dialog-custom {
        margin: 1rem !important;
        max-height: calc(100vh - 2rem);
    }
    
    .modal-body-scrollable {
        max-height: calc(100vh - 8rem);
    }
}