<ul class="p-0 m-0 sidebar-navbar">
    <li class="nav-item mb-2">
        <a href="{{ route("reviewer.dashboard")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('reviewer.dashboard') ? 'active' : ''}}">
            <i class="fa-solid fa-chart-column"></i>
            <span>{{ __('Reviewer Dashboard') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Review Assignments')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route('reviewer.reviews', ['type' => 'chapter']) }}" 
        class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
                {{ request()->route('type') === 'chapter' ? 'active' : '' }}">
            <i class="fa-solid fa-book"></i>
            <span>{{ __('Review Chapters') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('reviewer.reviews', ['type' => 'paper']) }}" 
        class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
                {{ request()->route('type') === 'paper' ? 'active' : '' }}">
            <i class="fa-solid fa-file-alt"></i>
            <span>{{ __('Review Papers') }}</span>
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

    <li class="nav-item">
        <a  href="{{ route('logout') }}"
        onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"
        class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('logout') ? 'active' : ''}}">
         <i class="fa-solid fa-arrow-right-from-bracket"></i>
         <span>{{ __('Logout') }}</span>
     </a>

     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
         @csrf
     </form>
    </li>
</ul>