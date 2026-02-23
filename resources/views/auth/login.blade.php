<x-guest-layout>
    <!-- Content area -->
    <div class="d-flex justify-content-center align-items-center py-5 bg-light" style="min-height: 50vh;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5" style="max-width: 448px;">
            <div class="card login-card p-4 p-sm-5 shadow">
                <div class="text-center mb-4">
                    <i class="fas fa-sign-in-alt mb-3" style="color: #4f46e5; font-size: 2.5rem;"></i>
                    <h1 class="h3 fw-bold text-dark">Welcome Back</h1>
                    <p class="text-secondary">Sign in to continue to your dashboard.</p>
                </div>

                {{-- Success message --}}
                @if(session('success'))
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                {{-- Error message --}}
                @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-0"></i>
                    <ul class="mb-0 p-0">
                        @foreach($errors->all() as $error)
                            <ol class="m-0 ps-1">{{ $error }}</ol>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="d-grid gap-3">
                    @csrf

                    {{-- Email --}}
                    <div class="input-icon-group position-relative">
                        <i class="fas fa-envelope position-absolute" style="left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="email" name="email" class="form-control ps-5 py-3 rounded-3" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                    </div>

                    {{-- Password --}}
                    <div class="input-icon-group position-relative">
                        <i class="fas fa-lock position-absolute" style="left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="password" name="password" class="form-control ps-5 py-3 rounded-3" placeholder="Password" required id="password-field">

                        <span class="password-toggle position-absolute" style="right: 2.4rem; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </span>
                    </div>

                    {{-- Remember me & Forgot password --}}
                    <div class="row mt-2">
                        <div class="col-md-6 col-6 text-start">
                            <div class="form-check">
                                <input id="remember" type="checkbox" name="remember" class="form-check-input"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember" class="form-check-label">Remember me</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 text-end">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold shadow-sm">
                        LOGIN
                    </button>

                    {{-- Signup --}}
                    <p class="text-center small text-secondary mt-3">
                        Don’t have an account? 
                        <a href="{{ route('register') }}" class="text-decoration-none fw-medium" style="color: #4f46e5;">
                            Sign up
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>