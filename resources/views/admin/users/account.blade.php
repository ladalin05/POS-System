<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <!-- Inner container -->
        <div class="d-lg-flex align-items-lg-start">

            <!-- Left sidebar component -->
            <div class="sidebar sidebar-component sidebar-expand-lg bg-transparent shadow-none me-lg-3">
                <!-- Sidebar content -->
                <div class="sidebar-content">

                    <!-- Navigation -->
                    <!-- Navigation -->
                    <div class="card">
                        <div class="sidebar-section-body text-center">
                            <div class="card-img-actions d-inline-block mb-3">
                                <img class="img-fluid rounded-circle" src="{{ asset('assets/images/default/male-avatar.jpg') }}" width="150" height="150">
                                <div class="card-img-actions-overlay card-img rounded-circle">
                                    <a href="user_pages_profile_tabbed.html#" class="btn btn-outline-white btn-icon rounded-pill">
                                        <i class="ph ph-pencil"></i>
                                    </a>
                                </div>
                            </div>

                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <span class="text-muted">{{ auth()->user()->roles->implode('name', ', ') }}</span>
                        </div>
                        <ul class="nav nav-sidebar">
                            <li class="nav-item">
                                <a href="#account" class="nav-link active" data-bs-toggle="tab">
                                    <i class="ph ph-user me-2"></i>
                                    {{ __('global.account') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#security" class="nav-link" data-bs-toggle="tab">
                                    <i class="ph ph-user-focus me-2"></i>
                                    {{ __('global.security') }}
                                    <span class="fs-sm fw-normal text-muted ms-auto">02:56pm</span>
                                </a>
                            </li>
                            <li class="nav-item-divider"></li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link" onclick="logout()">
                                    <i class="ph ph-sign-out me-2"></i>
                                    {{ __('global.logout') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /navigation -->
                </div>
                <!-- /sidebar content -->
            </div>
            <!-- /left sidebar component -->
            <!-- Right content -->
            <div class="tab-content flex-fill">
                <div class="tab-pane fade" id="account">
                    <!-- Account info -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account information</h5>
                        </div>

                        <div class="card-body">
                            <form action="user_pages_profile_tabbed.html#">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" value="Victoria" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Full name</label>
                                            <input type="text" value="Smith" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address line 1</label>
                                            <input type="text" value="Ring street 12" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address line 2</label>
                                            <input type="text" value="building D, flat #67" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" value="Munich" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">State/Province</label>
                                            <input type="text" value="Bayern" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">ZIP code</label>
                                            <input type="text" value="1031" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" readonly="readonly" value="victoria@smith.com" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Your country</label>
                                            <select class="form-select">
                                                <option value="germany" selected>Germany</option>
                                                <option value="france">France</option>
                                                <option value="spain">Spain</option>
                                                <option value="netherlands">Netherlands</option>
                                                <option value="other">...</option>
                                                <option value="uk">United Kingdom</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone #</label>
                                            <input type="text" value="+99-99-9999-9999" class="form-control">
                                            <div class="form-text text-muted">+99-99-9999-9999</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Account info -->


                    <!-- Account settings -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account settings</h5>
                        </div>

                        <div class="card-body">
                            <form action="user_pages_profile_tabbed.html#">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Profile visibility</label>

                                            <label class="form-check mb-2">
                                                <input type="radio" name="visibility" class="form-check-input" checked>
                                                <span class="form-check-label">Visible to everyone</span>
                                            </label>

                                            <label class="form-check mb-2">
                                                <input type="radio" name="visibility" class="form-check-input">
                                                <span class="form-check-label">Visible to friends only</span>
                                            </label>

                                            <label class="form-check mb-2">
                                                <input type="radio" name="visibility" class="form-check-input">
                                                <span class="form-check-label">Visible to my connections only</span>
                                            </label>

                                            <label class="form-check">
                                                <input type="radio" name="visibility" class="form-check-input">
                                                <span class="form-check-label">Visible to my colleagues only</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Notifications</label>

                                            <label class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked>
                                                <span class="form-check-label">Password expiration notification</span>
                                            </label>

                                            <label class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked>
                                                <span class="form-check-label">New message notification</span>
                                            </label>

                                            <label class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked>
                                                <span class="form-check-label">New task notification</span>
                                            </label>

                                            <label class="form-check">
                                                <input type="checkbox" class="form-check-input">
                                                <span class="form-check-label">New contact request notification</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /account settings -->

                </div>

                <div class="tab-pane fade active show" id="security">
                    <!-- security -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Two-Factor Authentication (2FA)</h5>
                        </div>
                        <div class="list-group list-group-borderless py-2">
                            <div class="list-group-item fw-semibold">Security Protection</div>

                            <div class="list-group-item hstack gap-3">
                                <a href="user_pages_list.html#" class="status-indicator-container">
                                    <img src="{{ asset('assets/images/icons/authenticator-app.png') }}" class="w-40px h-40px rounded-pill"/>
                                    <span class="status-indicator bg-danger"></span>
                                </a>

                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ __('global.authenticator_app') }}</div>
                                    <span class="text-muted">{{ __('global.use_an_authenticator') }}.
                                </div>

                                <div class="align-self-center ms-3">
                                    <div class="d-inline-flex">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="twoFactor(event)" href="{{ base64_encode(url('/user/two-factor-authentication')) }}">
                                            <i class="ph ph-lock me-2"></i>
                                            {{ __('global.manage') }}
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="list-group-item hstack gap-3">
                                <a href="user_pages_list.html#" class="status-indicator-container">
                                    <img src="{{ asset('assets/images/icons/safe-mail.png') }}" class="w-40px h-40px rounded-pill">
                                    <span class="status-indicator bg-danger"></span>
                                </a>

                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ __('global.email') }}</div>
                                    <span class="text-muted">Use Email to protect your account.</span>
                                </div>

                                <div class="align-self-center ms-3">
                                    <div class="d-inline-flex">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="ph ph-lock me-2"></i>
                                            {{ __('global.manage') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item hstack gap-3">
                                <a href="user_pages_list.html#" class="status-indicator-container">
                                    <img src="{{ asset('assets/images/icons/otp.png') }}" class="w-40px h-40px rounded-pill">
                                    <span class="status-indicator bg-danger"></span>
                                </a>

                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ __('global.sms_text') }}</div>
                                    <span class="text-muted">{{ __('global.used_an_sms') }}.
                                </div>

                                <div class="align-self-center ms-3">
                                    <div class="d-inline-flex">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="ph ph-lock me-2"></i>
                                            {{ __('global.manage') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item hstack gap-3">
                                <a href="user_pages_list.html#" class="status-indicator-container">
                                    <img src="{{ asset('assets/images/icons/login-password.png') }}" class="w-40px h-40px rounded-pill">
                                    <span class="status-indicator bg-danger"></span>
                                </a>

                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ __('global.login_password') }}</div>
                                    <span class="text-muted">Login password is used to log in to your account.</span>
                                </div>

                                <div class="align-self-center ms-3">
                                    <div class="d-inline-flex">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="actionModal(event)" href="{{ route('users-management.users.account.change-password') }}">
                                            <i class="ph ph-lock me-2"></i>
                                            {{ __('global.manage') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Devices and Activities</h5>
                        </div>
                        <div class="list-group list-group-borderless py-2">
                            <div class="list-group-item fw-semibold">Security Protection</div>

                            <div class="list-group-item hstack gap-3">
                                <a href="user_pages_list.html#" class="status-indicator-container">
                                    <img src="{{ asset('assets/images/icons/devices.png') }}" class="w-40px h-40px rounded-pill"/>
                                    <span class="status-indicator bg-success"></span>
                                </a>

                                <div class="flex-fill">
                                    <div class="fw-semibold">My Devices</div>
                                    <span class="text-muted">Manage devices that have login status, and view your device history.</span>
                                </div>

                                <div class="align-self-center ms-3">
                                    <div class="d-inline-flex">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="ph ph-lock me-2"></i>
                                            {{ __('global.manage') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /security -->

                </div>

                <div class="tab-pane fade" id="inbox">

                    <!-- My inbox -->
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="mb-0">My Inbox</h5>

                            <div class="ms-auto">
                                <span class="badge bg-primary">25 today</span>
                            </div>
                        </div>

                        <!-- Action toolbar -->
                        <div class="card-body d-flex align-items-start flex-wrap border-bottom">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light btn-icon btn-checkbox-all">
                                    <input type="checkbox" class="form-check-input">
                                </button>
                                <button type="button" class="btn btn-light btn-icon dropdown-toggle" data-bs-toggle="dropdown"></button>
                                <div class="dropdown-menu">
                                    <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Select all</a>
                                    <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Select read</a>
                                    <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Select unread</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Clear selection</a>
                                </div>
                            </div>

                            <div class="d-inline-flex hstack gap-2 gap-lg-3 ms-3">
                                <button type="button" class="btn btn-primary">
                                    <i class="ph ph-pencil"></i>
                                    <span class="d-none d-lg-inline-block ms-2">Compose</span>
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light">
                                        <i class="ph ph-trash"></i>
                                        <span class="d-none d-lg-inline-block ms-2">Delete</span>
                                    </button>
                                    <button type="button" class="btn btn-light">
                                        <i class="ph ph-warning-octagon"></i>
                                        <span class="d-none d-lg-inline-block ms-2">Spam</span>
                                    </button>
                                </div>
                            </div>

                            <div class="d-inline-flex align-items-center hstack gap-2 gap-lg-3 w-100 w-lg-auto mt-2 mt-lg-0 ms-lg-auto">
                                <div><span class="fw-semibold">1-50</span> of <span class="fw-semibold">528</span></div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-light btn-icon disabled">
                                        <i class="ph ph-arrow-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-icon">
                                        <i class="ph ph-arrow-right"></i>
                                    </button>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-light btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ph ph-gear"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Action</a>
                                        <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Another action</a>
                                        <a href="user_pages_profile_tabbed.html#" class="dropdown-item">Something else here</a>
                                        <a href="user_pages_profile_tabbed.html#" class="dropdown-item">One more line</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /action toolbar -->


                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-inbox">
                                <tbody>
                                    <tr class="unread">
                                        <td class="table-inbox-checkbox">
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td class="table-inbox-star">
                                            <a href="user_pages_profile_tabbed.html#">
                                                <i class="ph ph-star text-muted opacity-25"></i>
                                            </a>
                                        </td>
                                        <td class="table-inbox-image">
                                            <img src="{{ asset('assets/images/default/male-avatar.jpg') }}" class="rounded-circle" width="32" height="32">
                                        </td>
                                        <td class="table-inbox-name">
                                            <a href="mail_read.html">
                                                <div class="letter-icon-title text-body">Spotify</div>
                                            </a>
                                        </td>
                                        <td class="text-truncate">
                                            <span class="table-inbox-subject">On Tower-hill, as you go down &nbsp;-&nbsp;</span>
                                            <span class="text-muted fw-normal">To the London docks, you may have seen a crippled beggar (or KEDGER, as the sailors say) holding a painted
                                                board before him, representing the tragic scene in which he lost his leg</span>
                                        </td>
                                        <td class="table-inbox-attachment">
                                            <i class="ph ph-paperclip text-muted"></i>
                                        </td>
                                        <td class="table-inbox-time">
                                            11:09 pm
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /table -->
                    </div>
                    <!-- /my inbox -->
                </div>
            </div>
            <!-- /right content -->
        </div>
        <!-- /inner container -->
    </div>
    <x-basic.modal id="action-modal">
        <x-basic.form id="action-form" validation>
        </x-basic.form>
    </x-basic.modal>
    <!-- /content area -->
    @push('scripts')
        <script>
            function twoFactor(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        confirmed: '123456'
                    },
                    success: function(res) {
                        $('#action-modal #action-form').empty();
                        if (res.status == 'success') {
                            $('#action-modal').modal('show');
                            $('#action-modal .modal-title').text(res.title);
                            $('#action-modal #action-form').html(res.html);
                            $('#action-modal form').attr('action', url);
                        }
                        $('#action-modal').modal('show');
                        $('#action-modal #action-form').attr('action', url);
                    }
                });
            }
            function actionModal(e) {
                e.preventDefault();
                var url = $(event.target).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#action-modal #action-form').html('').removeClass('was-validated');
                        if (res.status == 'success') {
                            $('#action-modal .modal-title').text(res.title);
                            $('#action-modal #action-form').html(res.html);
                            $('#action-modal form').attr('action', url);
                            $('#action-modal').modal('show');
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
