<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Search Client</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 align-items-start">
                        <div class="col-md-8 position-relative">
                            <input type="text" class="form-control" id="searchInput" placeholder="Enter C_Pid, Client Name">
                            <div id="clientSuggestions" class="list-group mt-1 shadow-sm" style="max-height: 200px; overflow-y: auto; position: absolute; width: 100%; z-index: 999; display: none;"></div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="searchBtn">Search</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" id="clearBtn">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="clientInfoSection" class="row mb-3 d-none">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Client Found:</strong> <span id="clientName"></span> (C_Pid: <span id="clientPid"></span>)
            </div>
        </div>
    </div>

    <div id="addNocBtnContainer" class="row mb-3 d-none">
        <div class="col-md-12 text-end">
            <button class="btn btn-success" id="addNocBtn">+ Add NOC</button>
        </div>
    </div>

    <div id="nocFormContainer" class="row d-none">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 id="formTitle">Add New NOC</h5>
                </div>
                <div class="card-body">
                    <form id="nocForm" enctype="multipart/form-data">
                        <input type="hidden" name="C_Pid" id="hiddenCpid">
                        <input type="hidden" name="sno" id="nocSno">
                        <div class="form-group mb-3">
                            <div class="toggle-container">
                                <label class="form-label"><strong>NOC TYPE *</strong></label>
                                <div class="custom-checkbox-group">
                                    <label class="custom-checkbox">
                                        <input type="radio" name="noc_type" value="1" checked>
                                        <span class="checkmark"></span>
                                        <span>Fresh</span>
                                    </label>
                                    <label class="custom-checkbox">
                                        <input type="radio" name="noc_type" value="0">
                                        <span class="checkmark"></span>
                                        <span>Additional</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="toggle-container">
                                <label class="form-label"><strong>BUY/SELL *</strong></label>
                                <div class="custom-checkbox-group">
                                    <label class="custom-checkbox">
                                        <input type="radio" name="buy_sell" value="1" checked>
                                        <span class="checkmark"></span>
                                        <span>Buy</span>
                                    </label>
                                    <label class="custom-checkbox">
                                        <input type="radio" name="buy_sell" value="0">
                                        <span class="checkmark"></span>
                                        <span>Sell</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="toggle-container">
                                <label class="form-label"><strong>EXCHANGE *</strong></label>
                                <div class="custom-checkbox-group">
                                    <label class="custom-checkbox">
                                        <input type="radio" name="exchange" value="1" checked>
                                        <span class="checkmark"></span>
                                        <span>IEX</span>
                                    </label>
                                    <label class="custom-checkbox">
                                        <input type="radio" name="exchange" value="0">
                                        <span class="checkmark"></span>
                                        <span>PXIL</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="toggle-container">
                                <label class="form-label"><strong>PERIPHERY *</strong></label>
                                <div class="custom-checkbox-group">
                                    <label class="custom-checkbox">
                                        <input type="radio" name="periphery" value="consumer" checked>
                                        <span class="checkmark"></span>
                                        <span>Consumer</span>
                                    </label>
                                    <label class="custom-checkbox">
                                        <input type="radio" name="periphery" value="state">
                                        <span class="checkmark"></span>
                                        <span>State</span>
                                    </label>
                                    <label class="custom-checkbox">
                                        <input type="radio" name="periphery" value="regional">
                                        <span class="checkmark"></span>
                                        <span>Regional</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>TIME BLOCK WISE QUANTUM *</strong></label>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Quantum Entries</span>
                                    <div class="d-flex gap-2">
                                        <input type="file" id="excelUploadInput" accept=".xlsx, .xls" style="display: none;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="document.getElementById('excelUploadInput').click()">Upload Excel</button>
                                        <button type="button" class="btn btn-sm btn-primary" id="addQuantumBtn">+ Add Time Block</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="quantumContainer">
                                        <div class="row mb-2 quantum-row">
                                            <div class="col-md-3">
                                                <label class="form-label">From Date</label>
                                                <input type="date" class="form-control" name="delivery_start" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">To Date</label>
                                                <input type="date" class="form-control" name="delivery_end" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">From Time</label>
                                                <input type="time" class="form-control" name="from_time[]" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">To Time</label>
                                                <input type="time" class="form-control" name="to_time[]" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Quantum</label>
                                                <input type="number" step="0.01" min="0.01" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label"> </label>
                                                <button type="button" class="btn btn-sm btn-danger removeQuantumBtn">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="toggle-container">
                                <label class="form-label"><strong>STATUS *</strong></label>
                                <div class="custom-checkbox-group">
                                    <label class="custom-checkbox">
                                        <input type="radio" name="status" value="approve" checked>
                                        <span class="checkmark"></span>
                                        <span>Approve</span>
                                    </label>
                                    <label class="custom-checkbox">
                                        <input type="radio" name="status" value="reject">
                                        <span class="checkmark"></span>
                                        <span>Reject</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>ATTACHMENT</strong></label>
                            <input type="file" class="form-control" name="attachment" accept=".pdf,.jpg,.png,.jpeg,.doc,.docx">
                            <small class="form-text text-muted">Max file size: 10MB. Allowed types: PDF, JPG, PNG, DOC, DOCX</small>
                        </div>
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-secondary me-2" id="cancelNocBtn">Cancel</button>
                            <button type="submit" class="btn btn-success">Save NOC</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="nocTableContainer" class="row mt-4">
        <div id="noc_results">
            <p class="text-muted">Search for a client to view NOC records.</p>
        </div>
    </div>

    <div class="modal fade" id="quantumModal" tabindex="-1" aria-labelledby="quantumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quantumModalLabel">Blockwise Quantum Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>From Time</th>
                                    <th>To Time</th>
                                    <th>Quantum</th>
                                </tr>
                            </thead>
                            <tbody id="quantumModalBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        const baseUrl = '<?= base_url() ?>';

        $('#searchInput').on('input', function() {
            const searchVal = $(this).val().trim();
            if (!searchVal) {
                $('#clientSuggestions').hide().empty();
                return;
            }

            $.ajax({
                url: baseUrl + 'Noc/search_client_noc',
                type: 'POST',
                data: { query: searchVal },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.clients.length) {
                        const suggestionsHtml = response.clients.map(client => `
                            <a href="#" class="list-group-item list-group-item-action client-suggestion" 
                               data-cpid="${client.C_Pid}" data-name="${client.C_Name}">
                                ${client.C_Name} (${client.C_Pid})<br><small>${client.C_Email}</small>
                            </a>
                        `).join('');
                        $('#clientSuggestions').html(suggestionsHtml).show();
                    } else {
                        $('#clientSuggestions').hide().empty();
                    }
                },
                error: function() {
                    $('#clientSuggestions').hide().empty();
                }
            });
        });

        $(document).on('click', '.client-suggestion', function(e) {
            e.preventDefault();
            const cpid = $(this).data('cpid');
            const name = $(this).data('name');
            $('#searchInput').val(`${cpid} - ${name}`);
            $('#clientSuggestions').hide().empty();
            fetchClientDetails(cpid);
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#searchInput, #clientSuggestions').length) {
                $('#clientSuggestions').hide();
            }
        });

        document.getElementById('excelUploadInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const worksheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(worksheet, { defval: '' });

                const container = document.getElementById('quantumContainer');
                container.innerHTML = '';

                let deliveryStartDate = null, deliveryEndDate = null;
                for (let row of rows) {
                    let fromDate = row['From Date'] || row['from_date'] || row['fromDate'];
                    let toDate = row['To Date'] || row['to_date'] || row['toDate'];

                    if (fromDate && toDate) {
                        if (typeof fromDate === 'number') {
                            deliveryStartDate = XLSX.SSF.format('yyyy-mm-dd', fromDate);
                        } else if (typeof fromDate === 'string') {
                            const parsedDate = new Date(fromDate);
                            if (!isNaN(parsedDate.getTime())) {
                                deliveryStartDate = parsedDate.toISOString().split('T')[0];
                            } else {
                                const dateParts = fromDate.split('/');
                                if (dateParts.length === 3) {
                                    deliveryStartDate = `${dateParts[2]}-${dateParts[1].padStart(2, '0')}-${dateParts[0].padStart(2, '0')}`;
                                }
                            }
                        }

                        if (typeof toDate === 'number') {
                            deliveryEndDate = XLSX.SSF.format('yyyy-mm-dd', toDate);
                        } else if (typeof toDate === 'string') {
                            const parsedDate = new Date(toDate);
                            if (!isNaN(parsedDate.getTime())) {
                                deliveryEndDate = parsedDate.toISOString().split('T')[0];
                            } else {
                                const dateParts = toDate.split('/');
                                if (dateParts.length === 3) {
                                    deliveryEndDate = `${dateParts[2]}-${dateParts[1].padStart(2, '0')}-${dateParts[0].padStart(2, '0')}`;
                                }
                            }
                        }
                        if (deliveryStartDate && deliveryEndDate) break;
                    }
                }

                let validRowsCount = 0, isFirstRow = true;
                rows.forEach(row => {
                    let fromTime = row['From Time'] || row['from_time'] || row['fromTime'];
                    let toTime = row['To Time'] || row['to_time'] || row['toTime'];
                    let quantum = row['Quantum'] || row['quantity'];

                    if (fromTime && typeof fromTime === 'string') {
                        fromTime = fromTime.substring(0, 5);
                    } else if (fromTime && typeof fromTime === 'number') {
                        const hours = Math.floor(fromTime * 24);
                        const minutes = Math.floor((fromTime * 24 * 60) % 60);
                        fromTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    }

                    if (toTime && typeof toTime === 'string') {
                        toTime = toTime.substring(0, 5);
                    } else if (toTime && typeof toTime === 'number') {
                        const hours = Math.floor(toTime * 24);
                        const minutes = Math.floor((toTime * 24 * 60) % 60);
                        toTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    }

                    if (!fromTime || !toTime || !quantum) return;

                    const rowHtml = `
                        <div class="row mb-2 quantum-row">
                            <div class="col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" name="delivery_start" value="${deliveryStartDate || ''}" ${isFirstRow ? 'required' : 'disabled'}>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" name="delivery_end" value="${deliveryEndDate || ''}" ${isFirstRow ? 'required' : 'disabled'}>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">From Time</label>
                                <input type="time" class="form-control" name="from_time[]" value="${fromTime}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Time</label>
                                <input type="time" class="form-control" name="to_time[]" value="${toTime}" required>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Quantum</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" name="quantity[]" value="${quantum}" required>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label"> </label>
                                <button type="button" class="btn btn-sm btn-danger removeQuantumBtn">Remove</button>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', rowHtml);
                    validRowsCount++;
                    isFirstRow = false;
                });

                alert(validRowsCount > 0 
                    ? `Successfully imported ${validRowsCount} quantum entries from Excel file.` 
                    : 'No valid data found in the Excel file.');
                document.getElementById('excelUploadInput').value = '';
            };
            reader.readAsArrayBuffer(file);
        });

        function fetchClientDetails(cpid) {
            $.ajax({
                url: baseUrl + 'Noc/get_client_nocs',
                type: 'POST',
                data: { cpid: cpid },
                dataType: 'json',
                success: function(response) {
                    showClientFound(response);
                },
                error: function() {
                    alert('Failed to fetch client details.');
                }
            });
        }

        function showClientFound(response) {
            $('#clientName').text(response.client_name);
            $('#clientPid').text(response.cpid);
            $('#hiddenCpid').val(response.cpid);
            $('#clientInfoSection').removeClass('d-none').addClass('show');
            $('#addNocBtnContainer').removeClass('d-none').addClass('show');
            $('#nocFormContainer').addClass('d-none').removeClass('show');

            let tableHtml = `
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>SNO</th>
                            <th>Delivery Period</th>
                            <th>Buy/Sell</th>
                            <th>Type</th>
                            <th>Exchange</th>
                            <th>Quantum</th>
                            <th>Periphery</th>
                            <th>Edit/Delete</th>
                            <th>Status</th>
                            <th>Actions</th>
                            <th>Send Mail</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            if (response.nocs && response.nocs.length) {
                let i = 1;
                response.nocs.forEach(noc => {
                    tableHtml += `
                        <tr data-noc-id="${noc.sno}">
                            <td>${i++}</td>
                            <td>${noc.delivery_start} to ${noc.delivery_end}</td>
                            <td>${noc.buy_sell == 1 ? 'Buy' : 'Sell'}</td>
                            <td>${noc.noc_type == 1 ? 'Fresh' : 'Additional'}</td>
                            <td>${noc.exchange == 1 ? 'IEX' : 'PXIL'}</td>
                            <td><button class="btn btn-sm btn-info view-quantum" data-id="${noc.sno}">Blockwise Quantum</button></td>
                            <td>${noc.periphery}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-noc" data-id="${noc.sno}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-noc" data-id="${noc.sno}">Delete</button>
                            </td>
                            <td>${noc.status.toUpperCase()}</td>
                            <td>
                                ${noc.file_name 
                                    ? `<a href="${baseUrl + noc.file_path}" class="btn btn-sm btn-success" target="_blank">Show File</a>`
                                    : `<button class="btn btn-sm btn-primary upload-file" data-id="${noc.sno}">Upload File</button>`
                                }
                            </td>
                            <td class="text-center text-danger fs-5">❌</td>
                        </tr>`;
                });
            } else {
                tableHtml += '<tr><td colspan="11" class="text-center">No NOC records found.</td></tr>';
            }
            tableHtml += '</tbody></table>';
            $('#noc_results').html(tableHtml);
            showSuccessMessage(`Client "${response.client_name}" found successfully!`);
        }

        function showSuccessMessage(message) {
            const successAlert = $(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            $('#clientInfoSection').before(successAlert);
            setTimeout(() => successAlert.fadeOut(() => $(this).remove()), 3000);
        }

        $('#clearBtn').click(function() {
            clearSearch();
            $('#searchInput').focus();
        });

        function clearSearch() {
            $('#searchInput').val('');
            $('#clientInfoSection').addClass('d-none').removeClass('show');
            $('#addNocBtnContainer').addClass('d-none').removeClass('show');
            $('#nocFormContainer').addClass('d-none').removeClass('show');
            $('#noc_results').html('<p class="text-muted">Search for a client to view NOC records.</p>');
            $('.alert-success').remove();
        }

        $('#addNocBtn').click(function() {
            $('#nocFormContainer').removeClass('d-none').addClass('show');
            $('#addNocBtnContainer').addClass('d-none').removeClass('show');
            resetNocForm();
            $('#formTitle').text('Add New NOC');
            $('#nocForm').attr('action', baseUrl + 'Noc/add_noc');
            $('#nocSno').remove();
            $('input[name="delivery_start"]').focus();
        });

        function resetNocForm() {
            $('#nocForm')[0].reset();
            $('input[name="noc_type"][value="1"]').prop('checked', true);
            $('input[name="buy_sell"][value="1"]').prop('checked', true);
            $('input[name="exchange"][value="1"]').prop('checked', true);
            $('input[name="periphery"][value="consumer"]').prop('checked', true);
            $('input[name="status"][value="approve"]').prop('checked', true);
            $('#quantumContainer').html(`
                <div class="row mb-2 quantum-row">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="delivery_start" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="delivery_end" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From Time</label>
                        <input type="time" class="form-control" name="from_time[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Time</label>
                        <input type="time" class="form-control" name="to_time[]" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Quantum</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label"> </label>
                        <button type="button" class="btn btn-sm btn-danger removeQuantumBtn">Remove</button>
                    </div>
                </div>
            `);
        }

        $('#cancelNocBtn').click(function() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                $('#nocFormContainer').addClass('d-none').removeClass('show');
                $('#addNocBtnContainer').removeClass('d-none').addClass('show');
            }
        });

        $('#addQuantumBtn').click(function() {
            const deliveryStart = $('input[name="delivery_start"]').val();
            const deliveryEnd = $('input[name="delivery_end"]').val();
            $('#quantumContainer').append(`
                <div class="row mb-2 quantum-row">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" value="${deliveryStart}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" value="${deliveryEnd}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From Time</label>
                        <input type="time" class="form-control" name="from_time[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Time</label>
                        <input type="time" class="form-control" name="to_time[]" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Quantum</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label"> </label>
                        <button type="button" class="btn btn-sm btn-danger removeQuantumBtn">Remove</button>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.removeQuantumBtn', function() {
            if ($('.quantum-row').length > 1) {
                $(this).closest('.quantum-row').remove();
            } else {
                alert('At least one quantum entry is required');
            }
        });

        $('#nocForm').submit(function(e) {
            e.preventDefault();
            const cpid = $('#hiddenCpid').val();
            if (!cpid) {
                alert('Please select a client first');
                return;
            }

            let hasValidQuantum = false;
            $('.quantum-row').each(function() {
                const fromTime = $(this).find('input[name="from_time[]"]').val();
                const toTime = $(this).find('input[name="to_time[]"]').val();
                const quantity = $(this).find('input[name="quantity[]"]').val();
                if (fromTime && toTime && quantity && parseFloat(quantity) > 0) {
                    hasValidQuantum = true;
                }
            });

            if (!hasValidQuantum) {
                alert('Please add at least one valid quantum entry');
                return;
            }

            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);
            const formData = new FormData(this);

            $.ajax({
                url: $('#nocForm').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#nocFormContainer').addClass('d-none').removeClass('show');
                        $('#addNocBtnContainer').removeClass('d-none').addClass('show');
                        fetchClientDetails(cpid); // Refresh table after adding NOC
                    } else {
                        alert(response.message || 'Failed to save NOC');
                    }
                },
                error: function(jqXHR) {
                    let errorMessage = 'Error saving NOC';
                    try {
                        const errorResponse = JSON.parse(jqXHR.responseText);
                        errorMessage = errorResponse.message || errorMessage;
                    } catch (e) {}
                    alert(errorMessage);
                },
                complete: function() {
                    submitBtn.html('Save NOC').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.edit-noc', function() {
            const nocId = $(this).data('id');
            $.ajax({
                url: baseUrl + 'Noc/get_noc_by_id',
                type: 'POST',
                data: { noc_sno: nocId },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.noc) {
                        $('#formTitle').text('Edit NOC');
                        $('#hiddenCpid').val(response.noc.C_Pid);
                        $('input[name="noc_type"][value="' + response.noc.noc_type + '"]').prop('checked', true);
                        $('input[name="buy_sell"][value="' + response.noc.buy_sell + '"]').prop('checked', true);
                        $('input[name="exchange"][value="' + response.noc.exchange + '"]').prop('checked', true);
                        $('input[name="periphery"][value="' + response.noc.periphery + '"]').prop('checked', true);
                        $('input[name="status"][value="' + response.noc.status + '"]').prop('checked', true);
                        $('input[name="delivery_start"]').val(response.noc.delivery_start);
                        $('input[name="delivery_end"]').val(response.noc.delivery_end);
                        $('#nocSno').remove();
                        $('#nocForm').append(`<input type="hidden" name="sno" id="nocSno" value="${nocId}">`);

                        $.ajax({
                            url: baseUrl + 'Noc/get_quantums',
                            type: 'POST',
                            data: { noc_sno: nocId },
                            dataType: 'json',
                            success: function(quantumResponse) {
                                let quantumHtml = '';
                                if (quantumResponse.success && quantumResponse.quantums.length) {
                                    quantumResponse.quantums.forEach((quantum, index) => {
                                        quantumHtml += `
                                            <div class="row mb-2 quantum-row">
                                                <div class="col-md-3">
                                                    <label class="form-label">From Date</label>
                                                    <input type="date" class="form-control" name="delivery_start" value="${response.noc.delivery_start}" ${index === 0 ? '' : 'disabled'}>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">To Date</label>
                                                    <input type="date" class="form-control" name="delivery_end" value="${response.noc.delivery_end}" ${index === 0 ? '' : 'disabled'}>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">From Time</label>
                                                    <input type="time" class="form-control" name="from_time[]" value="${quantum.from_time}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">To Time</label>
                                                    <input type="time" class="form-control" name="to_time[]" value="${quantum.to_time}" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Quantum</label>
                                                    <input type="number" step="0.01" min="0.01" class="form-control" name="quantity[]" value="${quantum.quantity}" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label"> </label>
                                                    <button type="button" class="btn btn-sm btn-danger removeQuantumBtn">Remove</button>
                                                </div>
                                            </div>`;
                                    });
                                } else {
                                    quantumHtml = `
                                        <div class="row mb-2 quantum-row">
                                            <div class="col-md-3">
                                                <label class="form-label">From Date</label>
                                                <input type="date" class="form-control" name="delivery_start" value="${response.noc.delivery_start}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">To Date</label>
                                                <input type="date" class="form-control" name="delivery_end" value="${response.noc.delivery_end}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">From Time</label>
                                                <input type="time" class="form-control" name="from_time[]" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">To Time</label>
                                                <input type="time" class="form-control" name="to_time[]" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Quantum</label>
                                                <input type="number" step="0.01" min="0.01" class="form-control" name="quantity[]" placeholder="Enter quantity" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label"> </label>
                                                <button type="button" class="btn btn-sm btn-danger removeQuantumBtn">Remove</button>
                                            </div>
                                        </div>`;
                                }
                                $('#quantumContainer').html(quantumHtml);
                                $('#nocFormContainer').removeClass('d-none').addClass('show');
                                $('#addNocBtnContainer').addClass('d-none').removeClass('show');
                                $('#nocForm').attr('action', baseUrl + 'Noc/update_noc');
                            },
                            error: function() {
                                alert('Error fetching quantum data');
                            }
                        });
                    } else {
                        alert(response.message || 'Failed to load NOC data');
                    }
                },
                error: function() {
                    alert('Error fetching NOC data');
                }
            });
        });

        $(document).on('click', '.delete-noc', function() {
            const nocId = $(this).data('id');
            const cpid = $('#hiddenCpid').val();
            if (confirm('Are you sure you want to delete this NOC?')) {
                $.ajax({
                    url: baseUrl + 'Noc/delete_noc',
                    type: 'POST',
                    data: { sno: nocId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            fetchClientDetails(cpid); // Refresh table after deleting NOC
                        } else {
                            alert(response.message || 'Failed to delete NOC');
                        }
                    },
                    error: function(jqXHR) {
                        let errorMessage = 'Error deleting NOC';
                        try {
                            const errorResponse = JSON.parse(jqXHR.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {}
                        alert(errorMessage);
                    }
                });
            }
        });

        $(document).on('click', '.view-quantum', function() {
            const nocId = $(this).data('id');
            $.ajax({
                url: baseUrl + 'Noc/get_quantums',
                type: 'POST',
                data: { noc_sno: nocId },
                dataType: 'json',
                success: function(response) {
                    let quantumHtml = '';
                    if (response.success && response.quantums.length) {
                        response.quantums.forEach(quantum => {
                            quantumHtml += `
                                <tr>
                                    <td>${quantum.from_time}</td>
                                    <td>${quantum.to_time}</td>
                                    <td>${quantum.quantity}</td>
                                </tr>`;
                        });
                    } else {
                        quantumHtml = '<tr><td colspan="3" class="text-center">No quantum data found</td></tr>';
                    }
                    $('#quantumModalBody').html(quantumHtml);
                    $('#quantumModal').modal('show');
                },
                error: function() {
                    alert('Error fetching quantum details');
                }
            });
        });
    });
    </script>

    <style>
        .custom-checkbox-group {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .custom-checkbox .checkmark {
            height: 20px;
            width: 20px;
            background-color: #fff;
            border: 2px solid #6c757d;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            margin-right: 8px;
            transition: all 0.3s ease;
        }
        .custom-checkbox input:checked ~ .checkmark {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .custom-checkbox input:checked ~ .checkmark + span {
            font-weight: bold;
            color: #0d6efd;
        }
        .custom-checkbox .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .custom-checkbox input:checked ~ .checkmark:after {
            display: block;
        }
        #clientSuggestions .list-group-item {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 8px 12px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        #clientSuggestions .list-group-item small {
            color: #6c757d;
            margin-left: auto;
            font-size: 12px;
        }
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .toggle-container .form-label {
            margin-bottom: 0;
            min-width: 140px;
            flex-shrink: 0;
        }
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>