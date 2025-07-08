<ul class="p-0 m-0 sidebar-navbar">
    {{-- Dashboard --}}
    <li class="nav-item mb-2">
        <a href="{{ route('institute.dashboard') }}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{ Route::is('institute.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-column"></i>
            <span>{{ __('Institute Dashboard') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('User Management')}}</li>
    {{-- Department Management --}}
    <li class="nav-item mb-2">
        <a href="{{ route('institution.departments') }}" class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{ Route::is('institution.departments') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>{{ __('Manage Departments') }}</span>
        </a>
    </li>

    {{-- User Management --}}
    <li class="nav-item mb-2">
        <a href="{{ route('institution.users') }}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{ Route::is('institution.users') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i>
            <span>{{ __('Manage Users') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Submissions')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route('institution.submissions.index', ['type' => 'paper']) }}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{ request()->routeIs('institution.submissions.index') && request('type', 'paper') === 'paper' ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i>
            <span>{{ __('View Papers') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('institution.submissions.index', ['type' => 'chapter']) }}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{ request()->routeIs('institution.submissions.index') && request('type') === 'chapter' ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i>
            <span>{{ __('View Book Chapters') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Review Progress')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route('institution.reviews') }}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{ Route::is('institution.reviews') ? 'active' : '' }}">
            <i class="fa-solid fa-hourglass-half"></i>
            <span>{{ __('Review Progress') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Subscriptions')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route("institution.plans")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('institution.plans') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('Subscription Plans') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route("subscription.mine")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('subscription.mine') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__(' My Subscription') }}</span>
        </a>
    </li>

   <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Support')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route("supportTickets.create")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('supportTickets.create') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('Create Support Tickets') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route("supportTickets.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('supportTickets.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('View Support Tickets') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route("faqs.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('faqs.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('View FAQs') }}</span>
        </a>
    </li>

    {{-- Logout --}}
    <li class="nav-item">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="d-flex align-items-center gap-3
           fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
           {{ Route::is('logout') ? 'active' : '' }}">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>{{ __('Logout') }}</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>
