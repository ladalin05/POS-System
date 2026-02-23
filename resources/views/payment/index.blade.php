<x-guest-layout>
    <!-- Content area -->
    <div class="content">
        <div class="card border border-primary shadow-sm">
            <div class="card-header text-white border-bottom-0 py-2">
                <h6 class="mb-0"></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                                    <div>
                                        <h6>Kinga Wallace</h6>
                                        <ul class="list list-unstyled mb-0">
                                            <li>Invoice #: <a href="#">0010</a></li>
                                            <li>Issued on: <span class="fw-semibold">2022/02/07</span></li>
                                        </ul>
                                    </div>

                                    <div class="text-sm-end mb-0 mt-3 mt-sm-0 ms-auto">
                                        <h6>$1,900</h6>
                                        <ul class="list list-unstyled mb-0">
                                            <li>Method: <span class="fw-semibold">Payoneer</span></li>
                                            <li class="dropdown">
                                                Status:
                                                <a href="#" class="link-danger fw-semibold d-inline-flex align-items-center dropdown-toggle ms-1" data-bs-toggle="dropdown">Overdue</a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#" class="dropdown-item active">
                                                        <i class="ph-warning-circle me-2"></i>
                                                        Overdue
                                                    </a>
                                                    <a href="#" class="dropdown-item">
                                                        <i class="ph-clock me-2"></i>
                                                        Pending
                                                    </a>
                                                    <a href="#" class="dropdown-item">
                                                        <i class="ph-check-circle me-2"></i>
                                                        Paid
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="#" class="dropdown-item">
                                                        <i class="ph-spinner-gap me-2"></i>
                                                        On hold
                                                    </a>
                                                    <a href="#" class="dropdown-item">
                                                        <i class="ph-x me-2"></i>
                                                        Canceled
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="ph-bell-ringing me-1"></i>
                                    Due:
                                    <span class="fw-semibold">2022/03/07</span>
                                </span>

                                <div class="d-inline-flex">
                                    <a href="#" class="text-body" data-bs-toggle="modal" data-bs-target="#invoice">
                                        <i class="ph-arrow-square-out"></i>
                                    </a>
                                    <div class="d-inline-flex dropdown ms-3">
                                        <a href="#" class="d-inline-flex align-items-center text-body dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ph-list"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#" class="dropdown-item">
                                                <i class="ph-printer me-2"></i>
                                                Print invoice
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <i class="ph-file-arrow-down me-2"></i>
                                                Download invoice
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" class="dropdown-item">
                                                <i class="ph-file-plus me-2"></i>
                                                Edit invoice
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <i class="ph-x me-2"></i>
                                                Remove invoice
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="list-group">
                            <label class="list-group-item d-flex align-items-center">
                                <img src="https://coolbeans.sgp1.digitaloceanspaces.com/legend-cinema-prod/b1fc6f67-877c-4841-a24e-3d4cd9831a78.png" class="me-2" style="height: 50px;">
                                <div class="ms-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="mb-0">
                                            ABA KHQR
                                        </h6>
                                    </div>
                                    Scan to pay with any banking app
                                </div>
                                <input type="radio" name="group-radio-payment-option" class="form-check-input ms-auto group-radio-payment-option">
                            </label>
                            <hr>
                            <label class="list-group-item d-flex align-items-center">
                                <img src="https://coolbeans.sgp1.digitaloceanspaces.com/legend-cinema-prod/b1fc6f67-877c-4841-a24e-3d4cd9831a78.png" class="me-2" style="height: 50px;">
                                <div class="ms-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="mb-0">
                                            ABA KHQR
                                        </h6>
                                    </div>
                                    Scan to pay with any banking app
                                </div>
                                <input type="radio" name="group-radio-payment-option" class="form-check-input ms-auto group-radio-payment-option">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('payment.school.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="button" class="btn btn-primary" id="checkout_button">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="aba_main_modal" class="aba-modal">
        <!— Modal content —>
            <div class="aba-modal-content">

                <!-- Include PHP class -->
                <?php
                
                $transactionId = time();
                $amount = '0.01';
                $firstName = 'test';
                $lastName = 'test';
                $phone = '012345678';
                $email = '';
                $req_time = time();
                $merchant_id = 'wintechsoftwarepartner';
                $payment_option = 'abapay_khqr';
                $payment_gate = '0';
                ?>

                <form method="POST" target="aba_webservice" action="{{ abaAction() }}" id="aba_merchant_request">
                    <input type="hidden" name="hash"
                        value="{{ abaHash($req_time . $merchant_id . $transactionId . $amount . $firstName . $lastName . $email . $phone . $payment_option) }}" id="hash" />
                    <input type="hidden" name="tran_id" value="{{ $transactionId }}" id="tran_id" />
                    <input type="hidden" name="amount" value="{{ $amount }}" id="amount" />
                    <input type="hidden" name="firstname" value="{{ $firstName }}" />
                    <input type="hidden" name="lastname" value="{{ $lastName }}" />
                    <input type="hidden" name="phone" value="{{ $phone }}" />
                    <input type="hidden" name="email" value="{{ $email }}" />
                    <input type="hidden" name="req_time" value="{{ $req_time }}" />
                    <input type="hidden" name="payment_gate" value="{{ $payment_gate }}" />
                    <input type="hidden" name="merchant_id" value="{{ $merchant_id }}" />
                    <input type="hidden" name="payment_option" id="payment_option" value="abapay_khqr"/>
                </form>
            </div>
            <!— end Modal content—>
    </div>
    @push('scripts')
        <script src="https://checkout.payway.com.kh/plugins/checkout2-0.js"></script>
        <script>
            $(document).ready(function() {
                $('#checkout_button').click(function() {
                    AbaPayway.checkout();
                });
            });
        </script>
    @endpush
</x-guest-layout>
