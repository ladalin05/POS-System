<x-guest-layout>
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">
        <form method="POST" action="{{ route('password.store') }}" class="login-form">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                            <img src="{{ asset('assets/images/default/logo.png') }}" class="h-48px">
                        </div>
                        <h5 class="mb-0">{{ __('global.login_to_your_account') }}</h5>
                        <span class="d-block text-muted">{{ __('global.enter_your_credentials_below') }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('global.email') }}</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="text" class="form-control" placeholder="xxx@edu.kh" name="email" autocomplete="email" required value="{{ old('email', $request->email) }}">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-user-circle text-muted"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('global.password') }}</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" class="form-control" placeholder="•••••••••••" name="password" autocomplete="new-password" required>
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-lock text-muted"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('global.password_confirmation') }}</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" class="form-control" placeholder="•••••••••••" name="password_confirmation" autocomplete="new-password" required>
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-lock text-muted"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">{{ __('global.reset_password') }}</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
