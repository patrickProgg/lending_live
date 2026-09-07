<style>
    .modal-dimmed {
        filter: brightness(0.4);
        pointer-events: none;
        transition: filter 0.2s ease;
    }

    #payment_table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }

    label {
        font-weight: normal !important;
    }

    #viewLoaner .modal-body .form-label {
        display: flex;
        justify-content: space-between;
        align-items: left;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f8f9fa;
        font-weight: 500;
        font-size: 14px;
        transition: background 0.3s, box-shadow 0.3s;
    }

    #viewLoaner .modal-body .form-label span {
        font-weight: bold;
        color: #333;
    }

    /* Form control focus effects */
    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Quick amount buttons hover */
    .quick-capital:hover {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
</style>

<section id="content">
    <main>
        <div class="table-data">
            <div class="order pt-2" style="background-color:transparent">
                <div class="row align-items-end mb-3">
                    <div class="col-auto me-3 d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoaner">
                                <i class="fas fa-user-plus me-1"></i> Add New
                            </button>
                            <button class="btn btn-success" id="generate_daily">
                                <i class="fas fa-download me-1"></i> Daily Report
                            </button>
                            <button class="btn btn-secondary" id="generate_weekly">
                                <i class="fas fa-download me-1"></i> Weekly Report
                            </button>
                            <button class="btn btn-warning" id="generate_monthly">
                                <i class="fas fa-download me-1"></i> Monthly Report
                            </button>
                            <button class="btn btn-info" id="bulk_payment">
                                <i class="fas fa-credit-card me-1"></i> Bulk Payment
                            </button>

                            <input type="date"
                                style="width: 140px; display: inline-block; height: 34px; background-color: white; color: #444242; border-radius: 6px; border:1px solid var(--bs-secondary)"
                                class="form-control" id="selected_date" name="selected_date"
                                value="<?= date('Y-m-d') ?>">
                        </div>

                        <button class="btn btn-danger" id="variance_tracking">
                            <i class="fas fa-balance-scale me-1"></i> Variance Tracking
                        </button>
                    </div>
                </div>
                <table id="client_table" class="table table-hover" style="width:100%">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width:100px; text-align:center">ACC NO</th>
                            <th>FULL NAME</th>
                            <th>ADDRESS</th>
                            <th style="width:110px">CONTACT NO</th>
                            <th style="width:110px">CLIENT SINCE</th>
                            <th style="width:150px; text-align:center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $today = new DateTime();

        // 15th of last month
        $start_date = new DateTime(date('Y-m-15', strtotime('-1 month')));

        // 15th of current month
        $expiry_date = new DateTime(date('Y-m-15'));
        ?>

        <div class="modal fade" id="hostingStatusModal" tabindex="-1" aria-labelledby="hostingStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="hostingStatusModalLabel">
                            🏦 LOAN MONITORING SYSTEM
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="text-center mb-4">

                            <?php if ($today < $expiry_date): ?>
                                <span class="badge bg-warning text-dark fs-6">⚠️ ACTIVE</span>
                            <?php elseif ($today->format('Y-m-d') == $expiry_date->format('Y-m-d')): ?>
                                <span class="badge bg-warning text-dark fs-6">⚠️ EXPIRES TODAY</span>
                            <?php else: ?>
                                <span class="badge bg-danger fs-6">⚠️ EXPIRED</span>
                            <?php endif; ?>

                            <h5 class="mt-3 mb-1">loan-monitoring.alwaysdata.net</h5>

                            <p class="mb-1 fw-bold">
                                Hosting Subscription - 1GB Storage
                            </p>

                            <span class="badge bg-primary">
                                ₱600 / month | $10 USD
                            </span>

                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Start Date</th>
                                <td><?= $start_date->format('F d, Y'); ?></td>
                            </tr>
                            <tr>
                                <th>Expiry Date</th>
                                <td class="text-danger fw-bold">
                                    <?= $expiry_date->format('F d, Y'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Auto Renew</th>
                                <td>❌ Disabled</td>
                            </tr>
                            <tr>
                                <th>Last Payment</th>
                                <td>₱600 <small class="text-muted">($10 USD)</small></td>
                            </tr>
                        </table>

                        <?php if ($today < $expiry_date): ?>

                            <?php $days_left = $today->diff($expiry_date)->days; ?>

                            <div class="alert alert-warning mb-0">
                                <strong>⚠️ Your subscription will expire on
                                    <?= $expiry_date->format('F d, Y'); ?>.</strong><br>
                                There <?= $days_left == 1 ? 'is' : 'are'; ?>
                                <strong><?= $days_left; ?></strong>
                                <?= $days_left == 1 ? 'day' : 'days'; ?>
                                remaining before your hosting subscription expires.
                                Please contact your developer to renew it.
                            </div>

                        <?php elseif ($today->format('Y-m-d') == $expiry_date->format('Y-m-d')): ?>

                            <div class="alert alert-danger mb-0">
                                <strong>⚠️ Your subscription expires today!</strong><br>
                                Today (<?= $expiry_date->format('F d, Y'); ?>) is the last day of your hosting subscription.
                                Please contact your developer immediately to avoid service interruption.
                            </div>

                        <?php else: ?>

                            <?php $days_expired = $expiry_date->diff($today)->days; ?>

                            <div class="alert alert-danger mb-0">
                                <strong>⚠️ Your subscription has expired!</strong><br>
                                Your hosting subscription expired on
                                <strong><?= $expiry_date->format('F d, Y'); ?></strong>
                                (<?= $days_expired; ?>     <?= $days_expired == 1 ? 'day' : 'days'; ?> ago).<br>
                                Your website and data may be suspended.
                                Please contact your developer immediately.
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ADD MODAL -->
        <div class="modal fade" id="addLoaner" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" style="max-width:700px; margin-top: 10px;">
                <div class="modal-content">

                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-user-plus me-2 text-primary"></i>
                            Client Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container p-0">
                            <!-- Client Information Card -->
                            <form id="client_form">
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-header bg-white border-0">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-id-card me-2 text-primary"></i>
                                            Client Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-8">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-user me-1"></i> FULL NAME
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Fullname" id="full_name" name="full_name"
                                                    autocomplete="off">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-phone me-1"></i> CONTACT #
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Contact #" id="contact_no" name="contact_no"
                                                    autocomplete="off" maxlength="11">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-8">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-map-marker-alt me-1"></i> ADDRESS
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Address" id="address" name="address">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calendar-alt me-1"></i> DATE
                                                </label>
                                                <input type="date" class="form-control form-control-lg" id="date_added"
                                                    name="date_added" value="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <!-- Loan Details Card -->
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-white border-0">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-hand-holding-usd me-2 text-success"></i>
                                            Loan Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-coins me-1"></i> AMOUNT
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input type="number" class="form-control form-control-lg"
                                                        id="capital_amt" name="capital_amt" placeholder="0.00" min="0"
                                                        step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-percent me-1"></i> INTEREST %
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control form-control-lg"
                                                        id="interest" name="interest" value="5" min="0" step="0.1">
                                                    <span class="input-group-text bg-light border-0">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calculator me-1"></i> MONTHLY INTEREST
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input type="text"
                                                        class="form-control form-control-lg fw-bold text-success"
                                                        id="monthly_interest" name="monthly_interest" readonly
                                                        style="background-color: #f8f9fa;">
                                                </div>
                                                <small class="text-muted">Auto-calculated</small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calendar-alt me-1"></i> DATE RELEASE
                                                </label>
                                                <input type="date" class="form-control form-control-lg"
                                                    id="date_released" name="date_released"
                                                    value="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label fw-bold text-muted small mb-2">
                                                <i class="fas fa-bolt me-1"></i> QUICK SELECT AMOUNT
                                            </label>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-capital"
                                                    data-amount="1000">₱1,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-capital"
                                                    data-amount="2000">₱2,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-capital"
                                                    data-amount="3000">₱3,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-capital"
                                                    data-amount="5000">₱5,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-capital"
                                                    data-amount="10000">₱10,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-capital"
                                                    data-amount="20000">₱20,000</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="add_client" name="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Client
                                    </button>
                                    <button type="button" class="btn btn-light ms-2" data-bs-dismiss="modal"
                                        id="closeModalBtn">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- ADD MODAL -->

        <!-- EDIT MODAL -->
        <div class="modal fade" id="editLoaner" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" style="max-width:700px; margin-top: 10px;">
                <div class="modal-content">

                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-edit me-2 text-primary"></i>
                            Edit Client Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container p-0">
                            <!-- Hidden ID field -->
                            <input type="hidden" id="edit_client_id" name="edit_client_id">

                            <!-- Client Information Card -->
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white border-0">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-id-card me-2 text-primary"></i>
                                        Client Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form id="edit_client_form">
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-hashtag me-1"></i> ACC NO.
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Acc No." id="edit_acc_no" name="edit_acc_no"
                                                    autocomplete="off">
                                            </div>
                                            <div class="col-md-9">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-user me-1"></i> FULL NAME
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Fullname" id="edit_full_name"
                                                    name="edit_full_name" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-muted small mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i> ADDRESS
                                            </label>
                                            <input type="text" class="form-control form-control-lg"
                                                placeholder="Enter Address" id="edit_address" name="edit_address">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-phone me-1"></i> CONTACT #
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Contact #" id="edit_contact_no_1"
                                                    name="edit_contact_no_1" autocomplete="off" maxlength="11">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-phone-alt me-1"></i> ALT CONTACT #
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    placeholder="Enter Contact #" id="edit_contact_no_2"
                                                    name="edit_contact_no_2" autocomplete="off" maxlength="11">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calendar-alt me-1"></i> DATE STARTED
                                                </label>
                                                <input type="date" class="form-control form-control-lg"
                                                    id="edit_start_date" name="edit_start_date"
                                                    value="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" id="deleteBtn" class="btn btn-outline-danger me-auto">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                    <button type="button" id="update_client" name="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-light ms-2" data-bs-dismiss="modal"
                                        id="closeModalBtn">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- EDIT MODAL -->

        <!-- VIEW MODAL -->
        <div class="modal fade" id="viewLoaner" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" style="max-width: 90%; width: 800px; margin-top: 10px;">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-file-invoice me-2 text-primary"></i>
                            Loan Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body px-3 pb-0">
                        <div class="container-fluid px-0">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body pb-0 pt-0">

                                    <!-- Client Details - 3 Columns with Icons -->
                                    <div class="row mb-0">
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <div class="text-muted small"><i class="fas fa-hashtag me-1"></i>
                                                    Account Number</div>
                                                <span class="fw-bold fs-6" id="header_acc_no"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <div class="text-muted small"><i class="fas fa-user me-1"></i> Full Name
                                                </div>
                                                <span class="fw-bold fs-6" id="header_name"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <div class="text-muted small"><i class="fas fa-map-marker-alt me-1" "></i> Address</div>
                                                <span class=" fw-bold fs-6" id="header_address"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center mb-2 mt-2 pt-2 border-top">
                                            <div class="d-flex align-items-center gap-2 mb-0">
                                                <h5 class="text-dark fw-bold mb-0">
                                                    <i class="fas fa-history me-2 text-primary"></i>Loan History
                                                </h5>
                                                <button class="btn btn-sm btn-danger d-inline-block ms-2"
                                                    id="deleteLoanDetails">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- <div class="col-3"> -->
                                                <div class="mb-2">
                                                    <div class="text-muted small"><i
                                                            class="fas fa-check-circle me-1"></i> Status / <i
                                                            class="far fa-calendar-check me-1"></i> Date Completed</div>
                                                    <span class="fw-bold text-success">
                                                        <span id="header_status"></span>
                                                        <span id="header_date_completed"></span>
                                                    </span>
                                                </div>
                                                <!-- </div> -->
                                                <!-- Date Filter Dropdown -->
                                                <div class="dropdown" style="width: 167px;">
                                                    <button
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 text-start"
                                                        type="button" id="dateDropdownBtn" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="height: 30px;">
                                                        <i class="fas fa-filter me-1"></i> Select Date Range
                                                    </button>
                                                    <ul class="dropdown-menu" id="header_date_arr"
                                                        style="max-height: 200px; overflow-y: auto; z-index: 9999;">
                                                        <!-- Options will be appended here -->
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                        <hr class="my-0">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-3 pb-3">
                            <div class="table-responsive"
                                style="max-height: 390px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px;">
                                <table id="payment_table" class="table table-sm table-bordered mb-0">
                                    <thead class="table-light sticky-top"
                                        style="font-size:13px; color:var(--dark); height:40px; vertical-align: middle;">
                                        <tr class="text-center table-bordered">
                                            <th class="text-center"
                                                style="width:4%; color:var(--dark); font-weight: bold;">
                                                #</th>
                                            <th class="text-center"
                                                style="width:15%; color:var(--dark); font-weight: bold;">
                                                BILLING PERIOD</th>
                                            <th class="text-center"
                                                style="width:15%; color:var(--dark); font-weight: bold;">
                                                PAYMENT DATE</th>
                                            <th class="text-center"
                                                style="width:11%; color:var(--dark); font-weight: bold;">
                                                DEBIT</th>
                                            <th class="text-center"
                                                style="width:11%; color:var(--dark); font-weight: bold;">
                                                CREDIT</th>
                                            <th class="text-center"
                                                style="width:11%; color:var(--dark); font-weight: bold;">
                                                PRINCIPAL</th>
                                            <th class="text-center"
                                                style="width:14%; color:var(--dark); font-weight: bold;">
                                                INTEREST</th>
                                            <th class="text-center"
                                                style="width:11%; color:var(--dark); font-weight: bold;">
                                                BALANCE</th>
                                            <th class="text-center"
                                                style="width:8%; color:var(--dark); font-weight: bold;">
                                                ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentTableBody">
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                No payment records found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Summary Section -->

                            </div>

                            <div class="pt-2">
                                <div class="row">
                                    <!-- Summary Cards -->
                                    <div class="col-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Paid</h6>
                                                <h5 class="card-title text-success" id="total_paid">₱0.00</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Principal Paid</h6>
                                                <h5 class="card-title text-info" id="total_principal_paid">₱0.00</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Interest Paid</h6>
                                                <h5 class="card-title text-primary" id="total_interest_paid">₱0.00</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Remaining Balance</h6>
                                                <h5 class="card-title text-danger" id="remaining_balance">₱0.00</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-end gap-2 mt-0">
                                    <button type="button" id="addNewLoan" class="btn btn-primary"
                                        onclick="openAddNewLoanModal()">
                                        <i class="fas fa-plus me-1"></i> New Loan
                                    </button>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Close
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="header_id">
                        <input type="hidden" id="header_loan_id">
                    </div>
                </div>
            </div>
            <!-- VIEW MODAL -->

            <!-- OVERDUE -->
            <div class="modal fade" id="overdueModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog" style="max-width:600px; margin-top:10px">
                    <div class="modal-content">
                        <div class="modal-header bg-light border-bottom">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                                Overdue Loan Processing
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body pb-0">
                            <div class="container p-0">
                                <!-- Overdue Details Card -->
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-white border-0">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-calculator me-2 text-danger"></i>
                                            Overdue Loan Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- Capital Amount -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-coins me-1"></i> CAPITAL AMOUNT
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input id="new_capital_amt" type="number"
                                                        class="form-control form-control-lg" placeholder="0.00" min="0"
                                                        step="0.01" />
                                                </div>
                                            </div>

                                            <!-- Interest Rate -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-percent me-1"></i> INTEREST RATE
                                                </label>
                                                <div class="input-group">
                                                    <input id="new_interest" type="number"
                                                        class="form-control form-control-lg" value="15" min="0"
                                                        step="0.1" />
                                                    <span class="input-group-text bg-light border-0">%</span>
                                                </div>
                                            </div>

                                            <!-- Added Amount -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-plus-circle me-1"></i> ADDED AMOUNT
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input id="new_added_amt" type="number"
                                                        class="form-control form-control-lg" placeholder="0.00" min="0"
                                                        step="0.01" />
                                                </div>
                                            </div>

                                            <!-- Total Amount -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calculator me-1"></i> TOTAL AMOUNT
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input id="new_total_amt" type="text"
                                                        class="form-control form-control-lg fw-bold text-success"
                                                        readonly style="background-color: #f8f9fa;" />
                                                </div>
                                                <small class="text-muted">Capital + Interest + Added Amount</small>
                                            </div>

                                            <!-- New Start Date -->
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calendar-alt me-1"></i> NEW START DATE
                                                </label>
                                                <input id="new_start_date" type="date"
                                                    class="form-control form-control-lg" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-danger" id="modalContinueBtn">
                                <i class="fas fa-check-circle me-1"></i> Continue
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- OVERDUE -->

            <!-- ADD LOAN SAME CLIENT -->
            <div class="modal fade" id="addLoanSameClient" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog" style="max-width:600px; margin-top:10px">
                    <div class="modal-content">
                        <div class="modal-header bg-light border-bottom">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-hand-holding-usd me-2 text-success"></i>
                                New Loan for Existing Client
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body pb-0">
                            <div class="container p-0">
                                <!-- Loan Details Card -->
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-white border-0">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-calculator me-2 text-success"></i>
                                            Loan Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- Capital Amount -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-coins me-1"></i> CAPITAL AMOUNT
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input id="add_capital_amt" type="number"
                                                        class="form-control form-control-lg" placeholder="0.00" min="0"
                                                        step="0.01" />
                                                </div>
                                            </div>

                                            <!-- Interest Rate -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-percent me-1"></i> INTEREST RATE
                                                </label>
                                                <div class="input-group">
                                                    <input id="add_interest" type="number"
                                                        class="form-control form-control-lg" value="5" min="0"
                                                        step="0.1" />
                                                    <span class="input-group-text bg-light border-0">%</span>
                                                </div>
                                            </div>

                                            <!-- Total Amount -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calculator me-1"></i> MONTHLY INTEREST
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0 fw-bold">₱</span>
                                                    <input id="add_monthly_interest" type="text"
                                                        class="form-control form-control-lg fw-bold text-success"
                                                        readonly style="background-color: #f8f9fa;" />
                                                </div>
                                                <small class="text-muted">Auto-calculated</small>
                                            </div>

                                            <!-- Start Date -->
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold text-muted small mb-2">
                                                    <i class="fas fa-calendar-alt me-1"></i> NEW START DATE
                                                </label>
                                                <input id="add_start_date" type="date"
                                                    class="form-control form-control-lg" value="<?= date('Y-m-d') ?>" />
                                            </div>
                                        </div>

                                        <!-- Quick Amount Selector -->
                                        <div class="mt-4">
                                            <label class="form-label fw-bold text-muted small mb-2">
                                                <i class="fas fa-bolt me-1"></i> QUICK SELECT AMOUNT
                                            </label>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-loan-amount"
                                                    data-amount="1000">₱1,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-loan-amount"
                                                    data-amount="2000">₱2,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-loan-amount"
                                                    data-amount="3000">₱3,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-loan-amount"
                                                    data-amount="5000">₱5,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-loan-amount"
                                                    data-amount="10000">₱10,000</button>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm quick-loan-amount"
                                                    data-amount="20000">₱20,000</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Hidden Fields -->
                                <input id="new_type" type="hidden" />
                                <input id="client_id" type="hidden" />
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-success" id="addLoanBtn">
                                <i class="fas fa-check-circle me-1"></i> Process Loan
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ADD LOAN SAME CLIENT -->

            <!-- BULK PAYMENT -->
            <div class="modal fade" id="bulk_payment_modal" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg" style="max-width: 6  00px; margin-top: 10px;">
                    <div class="modal-content">
                        <!-- Header -->
                        <div class="modal-header bg-light border-bottom">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                Bulk Payment For: <span id="bulk_date" class="text-success ms-1"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body p-3">
                            <!-- Summary Card -->
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div class="card-body p-3 pt-0">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-center p-2 bg-light rounded-3">
                                                <small class="text-muted d-block">Total Clients</small>
                                                <span class="fw-bold fs-5" id="total_clients_count">0</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center p-2 bg-light rounded-3">
                                                <small class="text-muted d-block">Total Payments</small>
                                                <span class="fw-bold fs-5 text-success"
                                                    id="total_payments_sum">₱0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Table -->
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white border-0 pt-3 px-3">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-list me-2 text-success"></i>
                                        Payment Entries
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 444px; overflow-y: auto;">
                                        <table id="bulk_payment_table" class="table table-sm table-hover mb-0">
                                            <thead class="table-light sticky-top" style="background-color: #f8f9fa;">
                                                <!-- Headers will be dynamically generated -->
                                            </thead>
                                            <tbody id="bulk_payment_body">
                                                <!-- Rows will be dynamically generated -->
                                            </tbody>
                                            <tfoot class="table-light">
                                                <!-- Footer row for totals will be dynamically generated -->
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="save_bulk_payments" class="btn btn-success me-2">
                                        <i class="fas fa-save me-1"></i> Save Payments
                                    </button>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BULK PAYMENT -->

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">
                                <i class="fas fa-hand-holding-usd text-success"></i> Make Payment
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="paymentForm">
                                <input type="hidden" id="pay_loan_id" name="loan_id">
                                <input type="hidden" id="pay_billing_period" name="billing_period">

                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-bold mb-0">Billing Period:</label>
                                    <span class="form-control-static" id="pay_billing_period_display"
                                        style="font-weight: 500;"></span>
                                </div>

                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-bold mb-0">Interest Rate:</label>
                                    <span class="form-control-static" id="pay_interest_rate_display"
                                        style="font-weight: 500;"></span>
                                </div>

                                <!-- Current Balance -->
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-bold mb-0">Current Balance:</label>
                                    <span class="form-control-static" id="pay_current_balance"
                                        style="font-weight: 500; color: #dc3545;"></span>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="pay_payment_date" class="form-label fw-bold">Payment Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="pay_payment_date" required>
                                        <small class="text-muted" id="pay_date_validation_msg"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pay_interest_amount" class="form-label fw-bold">Interest
                                            Amount</label>
                                        <input type="text" class="form-control" id="pay_interest_amount" readonly
                                            style="background-color: #e9ecef; color: #28a745; font-weight: bold;">
                                        <small class="text-muted">Auto-calculated</small>
                                    </div>
                                </div>

                                <!-- Row 2: Principal Amount & Total Amount -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="pay_principal_amount" class="form-label fw-bold">Principal
                                            Amount</label>
                                        <input type="number" class="form-control" id="pay_principal_amount" step="0.01"
                                            min="0" placeholder="0.00" value="0">
                                        <small class="text-muted">Enter the principal amount to pay</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pay_total_amount" class="form-label fw-bold">Total Amount</label>
                                        <input type="text" class="form-control" id="pay_total_amount" readonly
                                            style="background-color: #e9ecef; font-weight: bold; color: #007bff;">
                                        <small class="text-muted">Interest + Principal</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="pay_remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="pay_remarks" rows="2"
                                        placeholder="Optional remarks"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="confirmPaymentBtn">
                                <i class="fas fa-check"></i> Confirm Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

    </main>
</section>

<script>

    let startDate = '';
    let endDate = '';

    $('#datefilter').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('#datefilter').on('apply.daterangepicker', function (ev, picker) {
        startDate = picker.startDate.format('YYYY/MM/DD');
        endDate = picker.endDate.format('YYYY/MM/DD');

        $(this).val(startDate + ' - ' + endDate);

        client_table.ajax.reload();
    });

    $('#datefilter').on('cancel.daterangepicker', function () {
        startDate = '';
        endDate = '';

        $(this).val('');

        client_table.ajax.reload();
    });

    var client_table = $("#client_table").DataTable({
        columnDefs: [{ targets: '_all', orderable: true }],
        lengthMenu: [10, 25, 50, 100],
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        ajax: {
            url: '<?php echo site_url('Monitoring_cont/get_client'); ?>',
            type: 'POST',
            data: function (d) {
                d.start = d.start || 0;
                d.length = d.length || 10;
                d.startDate = startDate;
                d.endDate = endDate;
            },
            dataType: 'json',
            error: function (xhr, status, error) {
                console.error("AJAX request failed: " + error);
            }
        },
        columns: [
            {
                data: 'id',
                class: 'text-center'
            },
            {
                data: 'fullname',
                render: function (data) {
                    if (!data) return '';
                    return data.replace(/\b\w/g, char => char.toUpperCase());
                }
            },
            {
                data: 'address',
                render: function (data) {
                    if (!data) return '';
                    return data.replace(/\b\w/g, char => char.toUpperCase());
                }
            },
            {
                data: 'contact_no',
                render: function (data) {

                    let numbers = data.split('|')
                        .map(n => n.trim())
                        .filter(n => n !== '');

                    return numbers.length > 0 ? numbers.join(' | ') : '';
                }
            },
            { data: 'date_added', class: 'text-center' },
            {
                data: 'id',
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-success me-1" onclick="openEditModal('${data}', '${row.acc_no}', '${row.full_name}', '${row.address}', '${row.contact_no_1}', '${row.contact_no_2}', '${row.date_added}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="openViewModal('${data}', '${row.fullname}', '${row.address}')">
                            <i class="fas fa-eye"></i> View
                        </button>
                    `;
                }
            }

        ]
    });

    $("#add_client").on('click', function (e) {
        e.preventDefault();

        var name = $("#full_name").val();
        var interest = $("#interest").val();

        if (!name) {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Name field are required' });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to add this client?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, add it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Adding client...',
                    html: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('Monitoring_cont/add_client'); ?>",
                    data: $('#client_form').serialize(),
                    dataType: 'json',
                    success: function (response) {
                        Swal.close();
                        if (response.status === "success") {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 800,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                            document.getElementById('client_form').reset();
                            $('#addLoaner').modal('hide');
                            client_table.ajax.reload();
                        } else if (response.status === "exist") {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                showConfirmButton: true,
                            });

                            return;
                        }
                    }
                });
            }
        });
    });

    $('.quick-capital').click(function () {
        let amount = $(this).data('amount');
        $('#capital_amt').val(amount);

        // Optional: Trigger change event to update calculations
        $('#capital_amt').trigger('input');

        // Optional: Add visual feedback
        $(this).addClass('active').siblings().removeClass('active');
        calculateTotal();
    });

    $('#client_form').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#add_client').trigger('click');
        }
    });

    function calculateTotal() {
        let capital = parseFloat($('#capital_amt').val()) || 0;
        let interestRaw = $('#interest').val().replace('%', '');
        let interest = parseFloat(interestRaw) || 0;

        let monthly_interest = (capital * (interest / 100));

        $('#monthly_interest').val(monthly_interest.toFixed(2));
    }

    $('#capital_amt, #interest').on('input', calculateTotal);

    function openEditModal(id, acc_no, fullname, address, contact_1, contact_2, date_added) {
        $('#editLoaner').modal('show');
        $('#edit_acc_no').val(acc_no);
        $('#edit_full_name').val(fullname);
        $('#edit_address').val(address);
        $('#edit_contact_no_1').val(contact_1);
        $('#edit_contact_no_2').val(contact_2);
        $('#edit_start_date').val(date_added);

        $('#edit_client_form').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#update_client').trigger('click');
            }
        });

        $("#update_client").on('click', function (e) {
            e.preventDefault();

            var name = $("#edit_full_name").val();

            if (!name) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: "Can't leave full name empty" });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update this client?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel',
                allowEnterKey: false
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Updating client...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('Monitoring_cont/update_client'); ?>",
                        data: $('#edit_client_form').serialize() + '&id=' + id,
                        dataType: 'json',
                        success: function (response) {
                            Swal.close();
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 800,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                            $('#editLoaner').modal('hide');
                            client_table.ajax.reload();
                        }
                    });
                }
            });
        });

        $("#deleteBtn").on('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "This action will move data to history!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                allowEnterKey: false
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Deleting client...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('Monitoring_cont/delete_id'); ?>",
                        data: { id: id },
                        dataType: 'json',
                        success: function (response) {
                            Swal.close();
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 800,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                            $('#editLoaner').modal('hide');
                            client_table.ajax.reload();
                        }
                    });
                }
            });
        });

    }

    function openViewModal(id, fullname, address) {
        $('#viewLoaner').modal('show');

        $('#header_id').val(id);
        $('#header_acc_no').text(id);
        $('#header_name').text(fullname.replace(/\b\w/g, c => c.toUpperCase()));
        $('#header_address').text(address.replace(/\b\w/g, c => c.toUpperCase()));

        $.ajax({
            url: "<?php echo base_url('Monitoring_cont/get_loan_id'); ?>",
            type: "POST",
            dataType: "json",
            data: { id: id },
            success: function (response) {
                $('#header_date_arr').empty();

                if (!response || response.length === 0) {
                    $('#dateDropdownBtn').text('No Dates Available');
                    $('#selected_date_id').val('');
                    $('#header_loan_id').val('');
                    $('#payment_table tbody').html('<tr><td colspan="4" class="text-center">No data available</td></tr>');
                    return;
                }

                const firstStatus = response[0].status;
                let firstItemId = null;
                let firstFormattedDate = '';

                // Loop through response
                $.each(response, function (index, item) {
                    let release_date = formatDate(item.release_date) || 'No Date';
                    let status = item.status || '';

                    if (index === 0) {
                        firstItemId = item.id;
                        firstFormattedDate = release_date;
                    }

                    $('#header_date_arr').append(
                        `<li>
                        <a class="dropdown-item" href="#" 
                            data-id="${item.id}"
                            data-status="${status}"
                            data-formatted="${release_date}">
                            ${release_date}
                        </a>
                    </li>`
                    );
                });

                // Set initial values
                if (firstItemId) {
                    $('#dateDropdownBtn').text(firstFormattedDate);
                    $('#selected_date_id').val(firstItemId);
                    $('#header_loan_id').val(firstItemId);

                    triggerLoanDetails(firstItemId);
                    Swal.fire({
                        title: 'Loading details...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                }

                // Handle dropdown item selection - Remove any existing handlers first
                $(document).off('click', '#header_date_arr .dropdown-item').on('click', '#header_date_arr .dropdown-item', function (e) {
                    e.preventDefault();

                    let loanId = $(this).data('id');
                    let status = $(this).data('status');
                    let formattedDate = $(this).data('formatted');

                    // Update button text to show selected date
                    $('#dateDropdownBtn').text(formattedDate);

                    // Store selected values
                    $('#selected_date_id').val(loanId);
                    $('#header_loan_id').val(loanId);

                    // Trigger the loan details
                    triggerLoanDetails(loanId);
                    Swal.fire({
                        title: 'Loading details...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        });
    }

    function triggerLoanDetails(loanId) {

        $('#header_loan_id').val(loanId)

        $.ajax({
            url: "<?php echo base_url('Monitoring_cont/get_loan_details'); ?>",
            type: "POST",
            dataType: "json",
            data: { id: loanId },
            success: function (response) {

                Swal.close();

                const loan = response[0];
                const status = loan.status;

                const format = d => new Date(d).toLocaleDateString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                // Header Information
                $('#header_loan_date').text(format(loan.release_date));
                $('#header_capital_amt').text(Number(loan.principal).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#header_interest').text(loan.principal_interest + '%');

                let dateDisplay = loan.closed_date ? format(loan.closed_date) : '';
                if (dateDisplay) {
                    $('#header_date_completed').html(`
                    ${status}
                    <span class="mx-1">—</span>
                    ${dateDisplay}
                    <i class="fas fa-check-circle ms-1" style="color: #28a745; font-size: 0.9rem;"></i>
                `);
                } else {
                    $('#header_date_completed').html('');
                }

                // Build payment map by billing_period
                let paymentMap = {};
                let totalPaid = 0;
                let totalInterestPaid = 0;
                let totalPrincipalPaid = 0;

                response.forEach(item => {
                    if (item.billing_period) {
                        const interestPaid = parseFloat(item.interest_paid || 0);
                        const principalPaid = parseFloat(item.principal_paid || 0);
                        const totalPayment = interestPaid + principalPaid;

                        paymentMap[item.billing_period] = {
                            amount: totalPayment,
                            interest_paid: interestPaid,
                            principal_paid: principalPaid,
                            payment_date: item.payment_date,
                            interest_rate: parseFloat(item.payment_interest || item.principal_interest || 0)
                        };

                        totalPaid += totalPayment;
                        totalInterestPaid += interestPaid;
                        totalPrincipalPaid += principalPaid;
                    }
                });

                const releaseDate = new Date(loan.release_date);
                const currentDate = new Date();
                let tableBody = '';
                let rowIndex = 0;

                let runningBalance = parseFloat(loan.principal || 0);

                // ========== FIX: Store the original day from release date ==========
                const originalDay = releaseDate.getDate();

                // Start from the NEXT month after release
                let currentMonth = new Date(releaseDate);
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                // Apply the original day logic
                if (currentMonth.getDate() !== originalDay) {
                    currentMonth.setDate(0);
                }

                let endDate = new Date(releaseDate);
                if (status === "CLOSED" && loan.close_date) {
                    endDate = new Date(loan.close_date);
                } else {
                    endDate = new Date(currentDate);
                    endDate.setMonth(endDate.getMonth() + 24);
                }
                const interestRate = parseFloat(loan.principal_interest || 0);

                // ========== FIRST ROW: PRINCIPAL AS DEBIT ==========
                const principalAmount = parseFloat(loan.principal || 0);
                const formattedPrincipal = principalAmount.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Format release date for display
                const releaseDateDisplay = formatMonthShortDay(releaseDate);

                // Add principal row as first row
                tableBody += `
                    <tr class="table-primary">
                        <td class="text-center"></td>
                        <td class="text-start">${releaseDateDisplay}</td>
                        <td class="text-center"></td>
                        <td class="text-end fw-bold text-danger">${formattedPrincipal}</td>
                        <td class="text-end"></td>
                        <td class="text-end fw-bold"></td>
                        <td class="text-end"></td>
                        <td class="text-end fw-bold" style="color:var(--dark">${formattedPrincipal}</td>
                        <td class="text-center"></td>
                    </tr>
                `;

                rowIndex = 0;

                // ========== SUBSEQUENT ROWS: PAYMENTS ==========
                // Reset running balance for payment rows
                runningBalance = parseFloat(loan.principal || 0);

                // Generate payment rows
                while (currentMonth <= endDate) {
                    const billingPeriod = currentMonth.getFullYear() + '-' +
                        String(currentMonth.getMonth() + 1).padStart(2, '0');

                    const paymentData = paymentMap[billingPeriod] || null;
                    const paymentAmt = paymentData ? paymentData.amount : 0;
                    const interestPaid = paymentData ? paymentData.interest_paid : 0;
                    const principalPaid = paymentData ? paymentData.principal_paid : 0;
                    const paymentInterestRate = paymentData ? paymentData.interest_rate : interestRate;
                    const paymentDate = paymentData ? formatMonthShortDay(new Date(paymentData.payment_date)) : '';

                    const monthDisplay = formatMonthShortDay(currentMonth);

                    const billingDate = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), currentMonth.getDate());
                    const today = new Date();
                    const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                    const isPastRelease = billingDate < todayDate;

                    let rowClass = '';

                    if (paymentAmt > 0) {
                        rowClass = 'table-success';
                    } else if (isPastRelease) {
                        rowClass = 'table-danger';
                    }

                    const displayDebit = paymentAmt > 0 ? paymentAmt.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) : '-';

                    const displayCredit = paymentAmt > 0 ? paymentAmt.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) : '-';

                    const displayPrincipal = principalPaid > 0 ? principalPaid.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) : '-';

                    const displayInterest = interestPaid > 0 ? `${paymentInterestRate}% (${interestPaid.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })})` : '-';

                    const displayTotal = paymentAmt > 0 ? paymentAmt.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) : '-';

                    // Update running balance
                    if (paymentAmt > 0) {
                        runningBalance -= principalPaid;
                    }

                    const displayBalance = runningBalance.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Action button
                    let actionButton = '';
                    if (paymentAmt > 0) {
                        actionButton = `
                            <button class="btn btn-sm btn-danger delete-btn py-0 px-1" 
                                    onclick="deletePayment(${loanId}, '${billingPeriod}', '${monthDisplay}', this.closest('tr'), ${runningBalance})">
                                <i class="fas fa-trash fa-xs"></i>
                            </button>
                        `;
                    } else if (status === "ACTIVE" && runningBalance > 0) {
                        actionButton = `
                            <button class="btn btn-sm btn-success pay-btn py-0 px-1" 
                                    onclick="showPayModal(${loanId}, '${billingPeriod}', '${monthDisplay}', this.closest('tr'), ${runningBalance})">
                                <i class="fas fa-hand-holding-usd fa-xs"></i> Pay
                            </button>
                        `;
                    }

                    // Only add payment row if there's a payment or the loan is still active
                    if (paymentAmt > 0 || (status === "ACTIVE" && runningBalance > 0)) {
                        // Add payment row
                        tableBody += `
                        <tr class="${rowClass}">
                            <td class="text-center">${rowIndex + 1}</td>
                            <td class="text-end">${monthDisplay}</td>
                            <td class="text-center">${paymentDate || '-'}</td>
                            <td class="text-end"></td>
                            <td class="text-end">${displayCredit}</td>
                            <td class="text-end">${displayPrincipal}</td>
                            <td class="text-end">${displayInterest}</td>
                            <td class="text-end fw-bold" style="color:var(--dark">${displayBalance}</td>
                            <td class="text-center">${actionButton}</td>
                        </tr>
                    `;
                    }

                    // ========== FIX: Move to next month using original day ==========
                    // Get the year and month for the next month
                    let nextYear = currentMonth.getFullYear();
                    let nextMonth = currentMonth.getMonth() + 1;

                    // Create the next month's date using the original day
                    const nextDate = new Date(nextYear, nextMonth, originalDay);

                    // If the day rolled over (e.g., 31 → 1 in 30-day month), set to last day
                    if (nextDate.getDate() !== originalDay) {
                        nextDate.setDate(0);
                    }

                    currentMonth = nextDate;

                    rowIndex++;

                    if (runningBalance <= 0 && status === "CLOSED") {
                        break;
                    }
                }

                // If no payment rows, show a message
                if (tableBody === '') {
                    tableBody = '<tr><td colspan="9" class="text-center py-4 text-muted">' +
                        '<i class="fas fa-inbox fa-2x mb-2"></i><br>No payment records found</td></tr>';
                }

                $('#payment_table tbody').html(tableBody);

                // Update summary totals
                $('#total_paid').text(totalPaid.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#total_interest_paid').text(totalInterestPaid.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#total_principal_paid').text(totalPrincipalPaid.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#remaining_balance').text(runningBalance.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                if (runningBalance <= 0 && status === "ACTIVE") {
                    updateStatus(loanId, status, has_balance = false);
                } else if (runningBalance > 0 && status === "CLOSED") {
                    updateStatus(loanId, status, has_balance = true);
                }

                Swal.close();
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        });
    };

    function updateStatus(loan_id, status, has_balance) {

        let newStatus = has_balance ? "ACTIVE" : "CLOSED";

        $.ajax({
            url: "<?php echo base_url('Monitoring_cont/update_status'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                loan_id: loan_id,
                status: newStatus
            },
            success: function (response) {
                if (response.status === 'success') {
                    triggerLoanDetails(loan_id);

                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error!', 'Failed to update status', 'error');
            }
        });
    }

    function openAddNewLoanModal() {
        $('#addLoanSameClient').modal('show');


        $('.quick-loan-amount').click(function () {
            let amount = $(this).data('amount');
            $('#add_capital_amt').val(amount).trigger('input');

            // Visual feedback
            $(this).addClass('active').siblings().removeClass('active');

            calculateNewTotal();
        });

        function calculateNewTotal() {
            let capital = parseFloat($('#add_capital_amt').val()) || 0;
            let interest = parseFloat($('#add_interest').val()) || 0;

            let monthly_interest = capital * (interest / 100);
            $('#add_monthly_interest').val(monthly_interest.toFixed(2));
        }

        $('#add_capital_amt, #add_interest').off('input').on('input', calculateNewTotal);

        calculateNewTotal();

        $('#addLoanBtn').off('click').on('click', function () {
            const $btn = $(this);
            const cl_id = $('#header_id').val();
            const capital_amt = $('#add_capital_amt').val();
            const interest = $('#add_interest').val();
            const start_date = $('#add_start_date').val();
            const fullname = $('#header_name').text();
            const address = $('#header_address').text();
            const acc_no = $('#header_acc_no').text();

            if (capital_amt === '') {
                Swal.fire('Error', 'Please input capital amount.', 'error');
                return;
            }

            $btn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: "<?php echo base_url('Monitoring_cont/add_new_loan_same_client'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    cl_id: cl_id,
                    capital_amt: capital_amt,
                    interest: interest,
                    start_date: start_date
                },
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 500,
                            timerProgressBar: true,

                        });
                        client_table.ajax.reload();
                        $('#addNewLoan').show();
                        openViewModal(cl_id, fullname, address, acc_no);
                        $('#addLoanSameClient').modal('hide');
                    }

                    $btn.prop('disabled', false).text('Add Loan');
                }
            });

        });
    }

    $('#deleteLoanDetails').on('click', function () {
        const loan_id = $('#header_loan_id').val();
        const id = $('#header_id').val();
        const acc_no = $('#header_acc_no').text();
        const full_name = $('#header_name').text();
        const address = $('#header_address').text();

        swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo base_url('Monitoring_cont/delete_loan_id'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: loan_id },
                    success: function (res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 500
                            });
                            $('#header_date_arr').val($('#header_date_arr option:first').val()).trigger('change');
                            openViewModal(id, full_name, address, acc_no);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to delete variance record', 'error');
                    }
                });
            }
        });
    });

    // Global variables
    let currentPaymentRow = null;
    let currentLoanData = null;
    let running_bal = null;

    // Show Payment Modal
    function showPayModal(loanId, billingPeriod, monthDisplay, rowElement, running_balance) {
        // Store the row element for later use
        currentPaymentRow = rowElement;

        // Reset form
        $('#paymentForm')[0].reset();
        $('#pay_interest_amount').val('');
        $('#pay_total_amount').val('');
        $('#pay_remarks').val('');
        $('#pay_principal_amount').val(0);

        // Get loan details
        $.ajax({
            url: "<?php echo base_url('Monitoring_cont/get_loan_details'); ?>",
            type: "POST",
            dataType: "json",
            data: { id: loanId },
            success: function (response) {
                if (response && response.length > 0) {
                    const loan = response[0];
                    currentLoanData = loan;
                    running_bal = running_balance;

                    const interestRate = parseFloat(loan.principal_interest || 0);

                    // Set form values
                    $('#pay_loan_id').val(loanId);
                    $('#pay_billing_period').val(billingPeriod);
                    $('#pay_billing_period_display').text(monthDisplay);
                    $('#pay_interest_rate_display').text(interestRate + '%');
                    $('#pay_current_balance').text('₱' + running_balance.toFixed(2));

                    // Set default payment date to today
                    $('#pay_payment_date').val(new Date(monthDisplay).toISOString().split('T')[0]);

                    // Parse billing period
                    const billingParts = billingPeriod.split('-');
                    const billingYear = parseInt(billingParts[0]);
                    const billingMonth = parseInt(billingParts[1]) - 1;
                    const billingDate = new Date(billingYear, billingMonth, 1);

                    // Set min date
                    const minDate = billingDate.toISOString().split('T')[0];
                    $('#pay_payment_date').attr('min', minDate);
                    $('#pay_date_validation_msg').text(`Payment date must be on or before ${monthDisplay}`);

                    // Show modal
                    $('#paymentModal').modal('show');
                }
            },
            error: function () {
                Swal.fire('Error', 'Could not load loan details.', 'error');
            }
        });
    }

    function deletePayment(loanId, billingPeriod, monthDisplay, rowElement, runningBalance) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete the payment for <strong>${monthDisplay}</strong>.<br><br>
               <span style="color: #dc3545; font-weight: bold;">This action cannot be undone!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "<?php echo base_url('Monitoring_cont/delete_payment'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        loan_id: loanId,
                        billing_period: billingPeriod
                    },
                    success: function (response) {

                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Payment has been deleted successfully.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Remove the row from the table
                            $(rowElement).fadeOut(500, function () {
                                $(this).remove();

                                triggerLoanDetails(loanId);
                            });

                        } else {
                            Swal.fire('Error', response.message || 'Failed to delete payment.', 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        });
    }

    // Calculate interest and update totals
    function calculatePaymentTotals() {
        const paymentDate = $('#pay_payment_date').val();

        const displayDate = $('#pay_billing_period_display').text();
        const date = new Date(displayDate);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        const billingPeriod = `${year}-${month}-${day}`;
        const principalAmount = parseFloat($('#pay_principal_amount').val()) || 0;

        if (!paymentDate || !currentLoanData) {
            return;
        }

        const loan = currentLoanData;
        const interestRate = parseFloat(loan.principal_interest || 0);
        const currentBalance = running_bal;

        let interest = 0;
        let appliedRate = interestRate;

        if (paymentDate > billingPeriod) {
            appliedRate = 10;
        } else {
            appliedRate = 5;
        }

        const monthlyRate = appliedRate / 100;
        interest = currentBalance * monthlyRate;

        // Display interest
        $('#pay_interest_amount').val('₱' + interest.toFixed(2));

        // Calculate total
        const total = principalAmount + interest;
        $('#pay_total_amount').val('₱' + total.toFixed(2));

        // Store interest amount for submission
        $('#pay_interest_amount').data('interest-amount', interest.toFixed(2));
    }

    // Event listeners for modal
    $(document).ready(function () {
        // Calculate on payment date change
        $('#pay_payment_date').on('change', function () {
            calculatePaymentTotals();
        });

        // Calculate on principal amount change
        $('#pay_principal_amount').on('input', function () {
            calculatePaymentTotals();
        });

        // Confirm payment button
        $('#confirmPaymentBtn').on('click', function () {
            confirmPayment();
        });
    });

    // Confirm Payment
    function confirmPayment() {
        const loanId = $('#pay_loan_id').val();
        const billingPeriod = $('#pay_billing_period').val();
        const paymentDate = $('#pay_payment_date').val();
        const principalAmount = parseFloat($('#pay_principal_amount').val()) || 0;
        const interestAmount = parseFloat($('#pay_interest_amount').data('interest-amount') || 0);
        const totalAmount = parseFloat($('#pay_total_amount').val().replace(/[₱,]/g, '')) || 0;
        const remarks = $('#pay_remarks').val();

        if (!paymentDate) {
            Swal.fire('Error', 'Please select a payment date.', 'error');
            return;
        }

        if (principalAmount < 0) {
            Swal.fire('Error', 'Principal amount cannot be negative.', 'error');
            return;
        }

        if (totalAmount <= 0) {
            Swal.fire('Error', 'Total amount must be greater than 0.', 'error');
            return;
        }

        const displayDate = $('#pay_billing_period_display').text();
        const date = new Date(displayDate);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        const billingDate = `${year}-${month}-${day}`;

        let interestRate = 5;

        if (paymentDate > billingDate) {
            interestRate = 10;
        }

        // Prepare payment data
        const paymentData = {
            loan_id: loanId,
            billing_period: billingPeriod,
            interest_paid: interestAmount,
            principal_paid: principalAmount,
            total_paid: totalAmount,
            payment_date: paymentDate,
            remarks: remarks,
            interest_rate: interestRate
        };

        $('#paymentModal').modal('hide');

        processPayment(paymentData, loanId);
    }

    function processPayment(paymentData, loanId) {

        $.ajax({
            url: "<?php echo base_url('Monitoring_cont/save_payment'); ?>",
            type: "POST",
            dataType: "json",
            data: paymentData,
            success: function (response) {
                if (response.success) {
                    triggerLoanDetails(loanId);

                } else {
                    Swal.fire('Error', response.message || 'Failed to save payment.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            }
        });
    }

    $(document).on('click', '#generate_daily', function () {

        const selectedDate = $('#selected_date').val();

        if (!selectedDate) {
            Swal.fire('Error', 'Please select a valid date.', 'error');
            return;
        }

        $.ajax({
            url: '<?php echo site_url('Monitoring_cont/get_daily_report'); ?>',
            type: 'POST',
            data: { date: selectedDate },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (blob, status, xhr) {

                var filename = 'Daily_Report_' + selectedDate + '.xlsx';
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }

                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

                Swal.fire('Success!', 'Report downloaded to your computer.', 'success');
            },
            error: function () {
                Swal.fire('Error', 'Failed to generate report.', 'error');
            }
        });
    });

    $(document).on('click', '#generate_weekly', function () {
        const selectedDate = $('#selected_date').val();

        if (!selectedDate) {
            Swal.fire('Error', 'Please select a valid date.', 'error');
            return;
        }

        $.ajax({
            url: '<?php echo site_url('Monitoring_cont/get_weekly_report'); ?>',
            type: 'POST',
            data: { date: selectedDate },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (blob, status, xhr) {

                var filename = 'Daily_Report_' + selectedDate + '.xlsx';
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }

                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

                Swal.fire('Success!', 'Report downloaded to your computer.', 'success');
            },
            error: function () {
                Swal.fire('Error', 'Failed to generate report.', 'error');
            }
        });
    });

    $(document).on('click', '#generate_monthly', function () {
        const selectedDate = $('#selected_date').val();

        if (!selectedDate) {
            Swal.fire('Error', 'Please select a valid date.', 'error');
            return;
        }

        $.ajax({
            url: '<?php echo site_url('Monitoring_cont/get_monthly_report'); ?>',
            type: 'POST',
            data: { date: selectedDate },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (blob, status, xhr) {

                var filename = 'Daily_Report_' + selectedDate + '.xlsx';
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }

                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

                Swal.fire('Success!', 'Report downloaded to your computer.', 'success');
            },
            error: function () {
                Swal.fire('Error', 'Failed to generate report.', 'error');
            }
        });
    });

    function formatMonthShortDay(dateString) {
        const date = new Date(dateString);

        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const month = monthNames[date.getMonth()];

        const day = date.getDate();
        const year = date.getFullYear();


        return `${month} ${day}, ${year}`;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
    }

    const viewLoanerEl = document.getElementById('viewLoaner');
    const overdueModalEl = document.getElementById('overdueModal');
    const addNewModalEl = document.getElementById('addLoanSameClient');
    const paymentModal = document.getElementById('paymentModal');

    viewLoanerEl.addEventListener('hidden.bs.modal', () => {
        $('#dateDropdownBtn').prop('disabled', false);

        $('#cancelEdit').hide();
        const btn = $('#editLoanDetails');

        btn.data('mode', 'edit');
        btn.prop('disabled', false);
        btn.html('<i class="fas fa-edit"></i> Edit');
    });

    overdueModalEl.addEventListener('show.bs.modal', () => {
        viewLoanerEl.classList.add('modal-dimmed');
        addNewModalEl.classList.add('modal-dimmed');
    });

    overdueModalEl.addEventListener('hidden.bs.modal', () => {
        viewLoanerEl.classList.remove('modal-dimmed');
        addNewModalEl.classList.remove('modal-dimmed');
    });

    addNewModalEl.addEventListener('show.bs.modal', () => {
        viewLoanerEl.classList.add('modal-dimmed');
    });

    addNewModalEl.addEventListener('hidden.bs.modal', () => {
        viewLoanerEl.classList.remove('modal-dimmed');
    });

    paymentModal.addEventListener('show.bs.modal', () => {
        viewLoanerEl.classList.add('modal-dimmed');
    });

    paymentModal.addEventListener('hidden.bs.modal', () => {
        viewLoanerEl.classList.remove('modal-dimmed');
    });
</script>