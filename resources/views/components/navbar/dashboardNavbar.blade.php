<nav class="navbar p-3 navbar-light bg-white shadow-sm sticky-top">      
    <div class="d-flex justify content-between align-items-center gap-5">
        <a class="navbar-brand d-lg-block d-none me-5" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>

        <!-- Left Side Of Navbar -->
        <div class="navbar-nav me-auto">
            <button id="menuToggleBtn" class="btn btn-outline-secondary  me-2" type="button" >
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
    
    {{-- Search bar --}}
    <div class="search-bar px-2 me-auto">
        <form class="d-none d-lg-flex rounded-2 search-form border border-dark-subtle" method="GET" action="{{ route('papers.search') }}">
            <input type="text" name="query" class="form-control ps-2 border-0" placeholder="{{ __('Search Here')}}">
            <button type="submit" class="btn text-secondary">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Search icon and mobile search bar -->
    <div class="d-lg-none me-4 fs-5">
        <button id="toggleSearchBtn" class="btn border-0 p-0 text-secondary" type="button">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>

    @php
        $unreadNotifications = Auth::user()->unreadNotifications;
    @endphp
    <div class="dropdown me-4 fs-5 text-secondary">
        <a class="nav-link position-relative" href="#" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" >
            <i class="fa-solid fa-bell"></i>
            @if($unreadNotifications->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle rounded-pill badge bg-primary text-white p-1" id="notificationCount">   {{ $unreadNotifications->count() }}</span>
            @else
                <span class="position-absolute top-0 start-100 translate-middle rounded-pill badge bg-primary text-white p-1" id="notificationCount">0</span>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end p-0 shadow notification-dropdown" aria-labelledby="notificationDropdown" id="notificationListContainer" style="width: 400px;">
            
            <div id="notificationList" style="max-height: 250px; overflow-y: auto;">
                <span class="dropdown-item text-center">{{ __('No new notifications')}}</span>
            </div>
        </ul>
    </div>
    
<div class="dropdown me-4">
    <a class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        @if(app()->getLocale() == 'fr')
           <img src="{{ asset('images/france.png') }}" alt="" class="flag rounded-circle"> {{ __('French')}}
        @elseif (app()->getLocale() == 'hi')
            <img src="{{ asset('images/india.png') }}" alt="" class="flag rounded-circle"> {{ __('Hindi')}}
        @elseif (app()->getLocale() == 'es')
            <img src="{{ asset('images/spain.png') }}" alt="" class="flag rounded-circle"> {{ __('Spanish')}}
        @else
            <img src="{{ asset('images/united-kingdom.png') }}" alt="" class="flag rounded-circle"> {{ __('English')}}
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('change.language', 'en') }}">
                <img src="{{ asset('images/united-kingdom.png') }}" alt="" class="flag rounded-circle"> {{ __('English')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() == 'fr' ? 'active' : '' }}" href="{{ route('change.language', 'fr') }}">
                <img src="{{ asset('images/france.png') }}" alt="" class="flag rounded-circle"> {{ __('French')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() == 'hi' ? 'active' : '' }}" href="{{ route('change.language', 'hi') }}">
                <img src="{{ asset('images/india.png') }}" alt="" class="flag rounded-circle"> {{ __('Hindi')}}
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() == 'es' ? 'active' : '' }}" href="{{ route('change.language', 'es') }}">
                <img src="{{ asset('images/spain.png') }}" alt="" class="flag rounded-circle"> {{ __('Spanish')}}
            </a>
        </li>
    </ul>
</div>


    <div class="me-2">
        <img src="{{ asset('images/profile.png')}}" alt="user-img" class="img-fluid rounded-circle">
    </div>

    <!-- Right Side Of Navbar -->
    <ul class="p-0 m-0" style="list-style: none">
        <!-- Authentication Links -->
        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif

            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end shadow mt-2" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>   
</nav>

<!-- Mobile Search Collapse Section -->
<div class="collapse w-100 shadow-sm d-lg-none" id="mobileSearchBar">
    <div class="bg-white p-3">
        <form method="GET" action="{{ route('papers.search') }}" class="border border-dark-subtle rounded-2">
            <div class="input-group">
                <input type="text" name="query" class="form-control ps-2 border-0" placeholder="Search Here">
                <button type="submit" class="btn text-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('toggleSearchBtn').addEventListener('click', function () {
        const el = document.getElementById('mobileSearchBar');
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el);
        bsCollapse.toggle();
    });
    
    function fetchNotifications() {
        fetch('{{ route("notifications") }}', {
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            const countSpan = document.getElementById('notificationCount');
            const list = document.getElementById('notificationList');
            list.innerHTML = '';

            // Always use the notifications array length for count
            const notifications = data.notifications || [];
            const count = notifications.length;
            countSpan.textContent = count;

            if (count > 0) {
                const scrollWrapper = document.createElement('div');
                notifications.forEach(n => {
                    const div = document.createElement('div');
                    div.classList.add('dropdown-item', 'notification-entry');
                    div.style.cursor = 'pointer';
                    div.id = `notification-${n.id}`;
                    div.innerHTML = `
                        <a href="${n.link}" class="text-decoration-none text-dark" onclick="markSingleNotificationRead(event, '${n.id}', '${n.link}')">
                            <strong style="display:block; white-space: normal; word-break: break-word;">${n.title}</strong>
                            <span style="display:block; white-space: normal; word-break: break-word;">${n.message}</span>
                            <small>${n.created_at}</small>
                        </a>
                    `;
                    scrollWrapper.appendChild(div);
                });

                list.appendChild(scrollWrapper);

                const markReadBtn = document.createElement('div');
                markReadBtn.innerHTML = `<a href="#" class="dropdown-item text-center text-primary" onclick="markNotificationsRead()">{{ __('Mark all as read')}}</a>`;
                list.appendChild(markReadBtn);
            } else {
                list.innerHTML = `<span class="dropdown-item">{{ __('No new notifications')}}</span>`;
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
    }

    function markSingleNotificationRead(event, id, link) {
    event.preventDefault(); // Prevent default link navigation
    fetch(`{{ url('/notification/mark-as-one-read') }}/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    }).then(() => {
        // Remove the notification from the DOM
        const notifElem = document.getElementById(`notification-${id}`);
        if (notifElem) notifElem.remove();

        // Update the count
        const countSpan = document.getElementById('notificationCount');
        let count = parseInt(countSpan.textContent, 10);
        count = Math.max(0, count - 1);
        countSpan.textContent = count;

        // Optionally, redirect to the link after marking as read
        window.location.href = link;
    });
}

    function markNotificationsRead() {
        fetch('{{ route("notifications.markAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        }).then(() => {
            fetchNotifications();
        });
    }

    // Fetch notifications on page load and every 30 seconds
    @if(!Request::is('change-password'))
        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    @endif
</script>
