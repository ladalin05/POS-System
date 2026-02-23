<x-guest-layout>
    <div class="d-flex justify-content-center align-items-center py-2 bg-light" style="min-height: 50vh;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5" style="max-width: 448px;">
            <div class="card login-card p-3 p-sm-4 shadow">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-user-plus" style="color: #4f46e5; font-size: 2rem;"></i>
                    <h1 class="h3 fw-bold text-dark mb-1">Create Your Account</h1> 
                </div>

                {{-- Success message --}}
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="d-grid gap-3">
                    @csrf

                    {{-- Name --}}
                    <div class="input-icon-group position-relative">
                        <i class="fa-regular fa-user position-absolute" style="left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="text" name="name" class="form-control ps-5 py-3 rounded-3" placeholder="Username" value="{{ old('name') }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="input-icon-group position-relative">
                        <i class="fas fa-envelope position-absolute" style="left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="email" name="email" class="form-control ps-5 py-3 rounded-3" placeholder="Email Address" value="{{ old('email') }}" required>
                    </div>

                    {{-- Password --}}
                    <div class="input-icon-group position-relative">
                        <i class="fas fa-lock position-absolute" style="left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="password" name="password" class="form-control ps-5 py-3 rounded-3" placeholder="Password" required>
                        {{-- Password toggle JS needed --}}
                        <span class="password-toggle position-absolute" style="right: 2.4rem; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="input-icon-group position-relative">
                        <i class="fas fa-lock position-absolute" style="left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        <input type="password" name="password_confirmation" class="form-control ps-5 py-3 rounded-3" placeholder="Confirm Password" required>
                        <span class="password-toggle position-absolute" style="right: 2.4rem; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="row mt-2">
                        <div class="col-md-6 col-6 text-left">
                            <div class="res-box">
                                <input id="check-l" type="checkbox" name="remember">
                                <label for="check-l">Remember me</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 text-end">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold shadow-sm mt-1">
                        REGISTER
                    </button>

                    {{-- Login Link --}}
                    <p class="text-center small text-secondary mt-1">
                        Already registered? 
                        <a href="{{ route('login') }}" style="color: #4f46e5;" class="text-decoration-none fw-medium">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>