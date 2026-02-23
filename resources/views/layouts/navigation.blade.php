 <!-- Main navbar -->
 <div class="navbar navbar-expand-lg navbar-static border-bottom border-bottom-white border-opacity-10" style="min-height: 63px; background-color: ;">
     <div class="container-fluid">
         <div class="d-flex d-lg-none me-2">
             <button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                 <i class="ph ph-list"></i>
             </button>
         </div>

         <div class="navbar-brand flex-1 flex-lg-0 h-32px justify-content-between">
             <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center">
                 <img src="{{ asset('assets/images/default/logo_icon.png') }}" class="h-38px"> 
                 <img src="{{ asset('assets/images/default/logo_text_light.png') }}" class="d-none d-sm-inline-block h-38px ms-1">
             </a>
            <div class="arrow">
                <button type="button" class="btn btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                    <i class="ph ph-arrows-left-right text-primary" style="font-size: 1.7rem"></i>
                </button>
            </div>
         </div>
         <ul class="nav flex-row justify-content-end order-1 order-lg-2">
             <li class="nav-item nav-item-dropdown-lg dropdown language-switch">
                 <a href="javascript:void(0)" class="navbar-nav-link navbar-nav-link-icon rounded-pill lang-flag-text text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="{{ asset('assets/images/lang/' . app()->getLocale() . '.png') }}" class="lang-flag">
                     <span class="d-none d-lg-inline-block ms-2 me-1">{{ __('global.' . app()->getLocale()) }}</span>
                 </a>
                 <div class="dropdown-menu dropdown-menu-end">
                     <a href="{{ route('lang', 'en') }}" class="dropdown-item lang-flag-text text-decoration-none">
                         <img src="{{ asset('assets/images/lang/en.png') }}" class="lang-flag">
                         <span class="ms-2">english</span>
                     </a>
                     <a href="{{ route('lang', 'kh') }}" class="dropdown-item lang-flag-text text-decoration-none">
                         <img src="{{ asset('assets/images/lang/kh.png') }}" class="lang-flag">
                         <span class="ms-2">ខ្មែរ</span>
                     </a>
                 </div>
             </li>
             @if (auth()->check())
                 <li class="nav-item ms-lg-2">
                     <a href="index.html#" class="navbar-nav-link navbar-nav-link-icon rounded-pill text-decoration-none" data-bs-toggle="offcanvas" data-bs-target="#notifications">
                         <i class="ph ph-bell"></i>
                         <span class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1">2</span>
                     </a>
                 </li>
                 <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                     <a href="index.html#" class="navbar-nav-link align-items-center rounded-pill p-1 text-decoration-none" data-bs-toggle="dropdown">
                         <div class="status-indicator-container">
                             <img src="{{ asset('assets/images/default/male-avatar.jpg') }}" class="w-32px h-32px rounded-pill">
                             <span class="status-indicator bg-success"></span>
                         </div>
                         <span class="d-none d-lg-inline-block mx-lg-2">{{ auth()->user()->{'name_'.app()->getLocale()} }}</span>
                     </a>

                     <div class="dropdown-menu dropdown-menu-end">
                         <a href="{{ route('users-management.users.account') }}" class="dropdown-item">
                             <i class="ph ph-user-circle me-2"></i>
                             {{ auth()->user()->{'name_'.app()->getLocale()} }}
                         </a>
                         <div class="dropdown-divider"></div>
                         <a href="javascript:void(0)" class="dropdown-item" onclick="clearCache()">
                             <i class="ph ph-eraser me-2"></i>
                             {{ __('global.clear_cache') }}
                         </a>
                         <a href="javascript:void(0)" class="dropdown-item" onclick="logout()">
                             <i class="ph ph-sign-out me-2"></i>
                             {{ __('global.logout') }}
                         </a>
                     </div>
                     
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                 </li>
             @endif
         </ul>
     </div>
 </div>
 @push('scripts')
     <script>
         function logout() {
             event.preventDefault();
             document.getElementById('logout-form').submit();
         }

         function clearCache() {
             event.preventDefault();
             $.ajax({
                 url: '{{ route('clear-cache') }}',
                 type: 'POST',
                 data: {
                     _token: '{{ csrf_token() }}'
                 },
                 success: function(response) {
                     if (response.success) {
                         Swal.fire({
                             icon: 'success',
                             title: '{{ __('global.success') }}',
                             text: response.message,
                             timer: 2000,
                             showConfirmButton: false
                         });
                     } else {
                         Swal.fire({
                             icon: 'error',
                             title: '{{ __('global.error') }}',
                             text: response.message,
                         });
                     }
                 },
                 error: function(xhr) {
                     Swal.fire({
                         icon: 'error',
                         title: '{{ __('global.error') }}',
                         text: xhr.responseText,
                     });
                 }
             });
         }
     </script>
     
 @endpush
 @include('layouts.partials.notifications')
 <!-- /main navbar -->
