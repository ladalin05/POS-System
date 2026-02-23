<x-guest-layout>
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Password recovery form -->
        <form class="login-form needs-validation" action="{{ route('password.email') }}" method="POST" novalidate>
            @csrf
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-3 mb-3 mt-1">
                            <i class="ph ph-arrows-counter-clockwise ph-2x"></i>
                        </div>
                        <h5 class="mb-0">{{ __('global.password_recovery') }}</h5>
                        <span class="d-block text-muted">We'll send you instructions in email</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('global.email') }}</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="email" class="form-control" placeholder="xxx@edu.kh" name="email" autocomplete="email" required value="{{ old('email') }}">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-at text-muted"></i>
                            </div>
                            <div class="invalid-feedback">Invalid feedback</div>
                            <div class="valid-feedback">Valid feedback</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ph ph-arrow-counter-clockwise me-2"></i>
                        {{ __('global.reset_password') }}
                    </button>
                </div>
            </div>
        </form>
        <!-- /password recovery form -->

    </div>
    <!-- /content area -->
</x-guest-layout>
