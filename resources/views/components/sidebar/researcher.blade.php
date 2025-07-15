<ul class="p-0 m-0 sidebar-navbar">
    <li class="nav-item mb-2">
        <a href="{{ route("researcher.dashboard")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('researcher.dashboard') ? 'active' : ''}}">
            <i class="fa-solid fa-chart-column"></i>
            <span>{{ __('Researcher Dashboard') }}</span>
        </a>
    </li>
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Research Paper')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route("papers.create")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('papers.create') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('Submit Paper') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route("papers.submitted")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('papers.submitted') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('View Submitted Papers') }}</span>
        </a>
    </li>

     <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Book Chapters')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route("book-chapters.create")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('book-chapters.create') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('Submit Book Chapter') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route("chapters.submitted")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('chapters.submitted') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('View Submitted Chapters') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Payments')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route("researcher.invoices")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('researcher.invoices') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('View Invoices') }}</span>
        </a>
    </li>
    
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Intellectual Property')}}</li>
    <li class="nav-item mb-2">
        <a href="{{ route("researcher.patents.create")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('researcher.patents.create') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('File Patents') }}</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route("researcher.patents.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill fs-5
         {{Route::is('researcher.patents.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
         <span>{{__('View Patents Filed') }}</span>
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