@php
    $adminUser = auth('admin')->user();
@endphp

<header class="header">
    {{-- <form class="search-bar" method="GET">
        <input type="text" name="search" placeholder="Search products, orders, or categories..."
               value="{{ request('search') }}">
    </form> --}}

    <div class="header-actions">
        <div class="user-profile">
            <img src="https://i.pravatar.cc/150?u={{ $adminUser->email ?? 'admin' }}"
                alt="{{ $adminUser->name ?? 'Admin User' }}" class="avatar">

            <div class="user-info">
                <span class="user-name">
                    {{ $adminUser->name ?? 'Admin User' }}
                </span>

                <span class="user-role">
                    {{ $adminUser->role ?? 'Super Admin' }}
                </span>
            </div>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-link logout-btn">
                Logout
            </button>
        </form>
    </div>
    @php
        $currentProductLanguage = session('admin_product_language', 'pt');

        $languageLabels = [
            'pt' => 'Portuguese',
            'ja' => 'Japan',
            // 'en' => 'English',
        ];
    @endphp

    <div class="admin-language-dropdown"  style="display:none;">
        <button type="button" class="admin-language-btn">
            {{ $languageLabels[$currentProductLanguage] ?? 'Portuguese' }} ▾
        </button>

        <div class="admin-language-menu">
            <a href="{{ route('admin.product-language.switch', 'pt') }}">Portuguese</a>
            <a href="{{ route('admin.product-language.switch', 'ja') }}">Japan</a>
            {{-- <a href="{{ route('admin.product-language.switch', 'en') }}">English</a> --}}
        </div>
    </div>
</header>
