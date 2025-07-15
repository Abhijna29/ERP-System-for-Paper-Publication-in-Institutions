<ul class="p-0 m-0 sidebar-navbar">
    <li class="nav-item mb-1">
        <a href="{{ route("admin.dashboard")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.dashboard') ? 'active' : ''}}">
            <i class="fa-solid fa-chart-column"></i>
            <span>{{ __('Admin Dashboard') }}</span>
        </a>
    </li>

    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Institution')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("createInstitution")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('createInstitution') ? 'active' : ''}}">
         <i class="fa-regular fa-id-card"></i>
         <span>{{__('Create Institution') }}</span>
        </a>
    </li>

    {{-- Category Management --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Categories')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.category.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.category.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Category') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.subCategory.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.subCategory.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Sub Category') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.childCategory.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.childCategory.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Child Category') }}</span>
        </a>
    </li>

     {{-- Paper Management --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Paper Management')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.papers")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.papers') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{ __('Submitted Papers') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("published.papers")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('published.papers') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{ __('List Of Paper Published') }}</span>
        </a>
    </li>
    
    {{-- Journal Metadata --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Journal Metadata')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route('scopus.select')}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('scopus.select') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Journal Articles Scopus') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("web_of_science.select")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('web_of_science.select') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Journal Articles Web of Science') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("pubmed.select")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('pubmed.select') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Journal Articles Pub Med') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("abdc.select")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('abdc.select') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Journal Articles ABDC') }}</span>
        </a>
    </li>
    {{-- <li class="nav-item mb-1">
        <a href="{{ route("other.select")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('other.select') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Journal Articles Other') }}</span>
        </a>
    </li> --}}

    {{-- Book Chapters Group --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Book Chapter')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route('admin.books.create') }}" class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill
            {{ Route::is('admin.books.create') ? 'active' : '' }}">
            <i class="fa-solid fa-list"></i>
            <span>{{ __('Create Books') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route('admin.bookChapters.index') }}" class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill
            {{ Route::is('admin.bookChapters.index') ? 'active' : '' }}">
            <i class="fa-solid fa-list"></i>            
            <span>{{ __('Submitted Chapters') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
    <a href="{{ route("books.chapter-metadata")}}" class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill
        {{Route::is('books.chapter-metadata') ? 'active' : ''}}">
    <i class="fa-solid fa-list"></i>         
    <span>{{__('Book Chapter Metadata') }}</span>
    </a>
    </li>
    <li class="nav-item mb-1">
    <a href="{{ route("admin.bookChapters.published")}}" class="d-flex align-items-center gap-3 fw-bold text-decoration-none p-2 ps-4 rounded-pill
        {{Route::is('admin.bookChapters.published') ? 'active' : ''}}">
    <i class="fa-solid fa-list"></i>         
    <span>{{__('List of Published Book Chapter') }}</span>
    </a>
    </li>
    
    {{-- Payments --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Payments')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.invoices")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.invoices') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Invoice') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.paymentReport")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.paymentReport') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Payment Report') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.subscription.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.subscription.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Create Subscription Plans') }}</span>
        </a>
    </li>

    {{-- Support and FAQ --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Support')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.supportTickets")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.supportTickets') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Support Ticket') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.faq")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.faq') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('FAQ') }}</span>
        </a>
    </li>
    
    {{-- <li class="nav-item mb-1">
        <a href="{{ route("admin.supportTicket.reply")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.supportTicket.reply') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Reply To Support Ticket') }}</span>
        </a>
    </li> --}}
    
     {{-- Reports --}}
    {{-- <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Reports')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("generateReport")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('generateReport') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Generate Report') }}</span>
        </a>
    </li> --}}
    
    {{-- Intellectual Property --}}
    <li class="nav-item text-uppercase small text-muted mt-3">{{ __('Intellectual Property')}}</li>
    <li class="nav-item mb-1">
        <a href="{{ route("admin.patents.index")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('admin.patents.index') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('View Patents') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("copyrightFiled")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('copyrightFiled') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Copyright Filed') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("copyrightPublished")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('copyrightPublished') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Copyright Published') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("copyrightGranted")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('copyrightGranted') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Copyright Granted') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("tradeMarkFiled")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('tradeMarkFiled') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Trade Mark Filed') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("tradeMarkPublished")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('tradeMarkPublished') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Trade Mark Published') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("tradeMarkGranted")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('tradeMarkGranted') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Trade Mark Granted') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("designFiled")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('designFiled') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Design Filed') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("designPublished")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('designPublished') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Design Published') }}</span>
        </a>
    </li>
    <li class="nav-item mb-1">
        <a href="{{ route("designGranted")}}" class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('designGranted') ? 'active' : ''}}">
        <i class="fa-solid fa-list"></i>         
        <span>{{__('Design Granted') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a  href="{{ route('logout') }}"
        onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"
        class="d-flex align-items-center gap-3
         fw-bold text-decoration-none p-2 ps-4 rounded-pill
         {{Route::is('logout') ? 'active' : ''}}">
         <i class="fa-solid fa-arrow-right-from-bracket"></i>
         <span>{{ __('Logout') }}</span>
     </a>

     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
         @csrf
     </form>
    </li>
</ul>