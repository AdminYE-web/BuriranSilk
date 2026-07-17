<aside class="sidebar">
    <div class="sidebar-header">
        <div class="brand-name">Admin</div>
        <div class="brand-subtitle">{{ request()->cookie('dev') == '1' ? 'Product Management' : 'プロダクトマネジメント' }}</div>
    </div>

    {{-- <a href="{{ route('admin.products.create') }}" class="add-btn">
        + Add New Product
    </a> --}}
    @php
    $adminUser = auth('admin')->user();
    $isNormalAdmin = $adminUser && $adminUser->role === 'admin';
@endphp

    <ul class="nav-list">
        {{-- <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li> --}}

        @php
            $productMenuActive = request()->routeIs(
                'admin.product-list-banners.*',
                'admin.categories.*',
                'admin.materials.*',
                'admin.products.*',
                'admin.product-details.*',
                'admin.product-price-tiers.*',
                'admin.option-groups.*',
                'admin.product-options.*',
                'admin.product-option-variants.*',
                'admin.option-dependencies.*',
                'admin.product-price-rules.*',
                'admin.product-artwork-templates.*',
                'admin.product-templates.*',
            );
        @endphp

        <li class="nav-item has-dropdown {{ $productMenuActive ? 'open' : '' }}">
            <button type="button" class="nav-link dropdown-toggle {{ $productMenuActive ? 'active' : '' }}"
                onclick="this.closest('.has-dropdown').classList.toggle('open')">
                <span>{{ request()->cookie('dev') == '1' ? 'Products' : '商品管理' }}</span>
                <span class="dropdown-arrow">▾</span>
            </button>

            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.product-list-banners.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.product-list-banners.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Product List Banners' : '商品一覧バナー' }}
                    </a>
                </li>
                <li><a href="{{ route('admin.categories.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Categories' : 'カテゴリ' }}
                    </a>
                </li>
                <li><a href="{{ route('admin.materials.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Materials' : '素材' }}
                    </a>
                </li>
                <li><a href="{{ route('admin.products.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Products' : '商品' }}
                    </a>
                </li>
                <li><a href="{{ route('admin.product-price-rules.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.product-price-rules.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Product Price Rules' : '商品価格ルール' }}
                    </a>
                </li>
                <li><a href="{{ route('admin.option-price-rules.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.option-price-rules.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Option Price Rules' : 'オプション価格ルール' }}
                    </a>
                </li>
                        
                <li><a href="{{ route('admin.option-groups.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.option-groups.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Option Groups' : 'オプショングループ' }}
                    </a></li>
                <li><a href="{{ route('admin.product-options.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.product-options.*', 'admin.product-option-variants.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Product Options' : '商品オプション' }}
                    </a></li>
                <li><a href="{{ route('admin.option-dependencies.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.option-dependencies.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Option Dependencies' : 'オプション依存関係' }}
                    </a></li>
                <li><a href="{{ route('admin.product-artwork-templates.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.product-artwork-templates.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Product Artwork Templates' : '商品アートワークテンプレート' }}
                    </a></li>
                <li>
                    <a href="{{ route('admin.product-templates.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.product-templates.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Product Templates' : '商品テンプレート' }}
                    </a>
                </li>

            </ul>
        </li>
        
 <li class="nav-item">
            <a href="{{ route('admin.menu-products.index') }}"
                class="nav-link {{ request()->routeIs('admin.menu-products.*') ? 'active' : '' }}">
                {{ request()->cookie('dev') == '1' ? 'Menu Products' : 'メニュープロダクト' }}
            </a>
        </li>


        @php
            $homepageMenuActive = request()->routeIs('admin.home-banners.*', 'admin.material-homes.*');
        @endphp

        <li class="nav-item has-dropdown {{ $homepageMenuActive ? 'open' : '' }}">
            <button type="button" class="nav-link dropdown-toggle {{ $homepageMenuActive ? 'active' : '' }}"
                onclick="this.closest('.has-dropdown').classList.toggle('open')">
                <span>{{ request()->cookie('dev') == '1' ? 'Homepage' : 'ホームページ' }}</span>
                <span class="dropdown-arrow">▾</span>
            </button>

            <ul class="sub-nav">

                <li>
                    <a href="{{ route('admin.home-banners.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.home-banners.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Home Banners' : 'ホームページバナー' }}
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.material-homes.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.material-homes.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Material Homes' : 'マテリアルホーム' }}
                    </a>
                </li>

            </ul>
        </li>
        @php
            $galleryMenuActive = request()->routeIs('admin.galleries.*', 'admin.gallery-banners.*');
        @endphp
        <li class="nav-item has-dropdown {{ $galleryMenuActive ? 'open' : '' }}">
            <button type="button" class="nav-link dropdown-toggle {{ $galleryMenuActive ? 'active' : '' }}"
                onclick="this.closest('.has-dropdown').classList.toggle('open')">
                <span>{{ request()->cookie('dev') == '1' ? 'Galleries' : 'ギャラリー' }}</span>
                <span class="dropdown-arrow">▾</span>
            </button>

            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.gallery-banners.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.gallery-banners.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Gallery Banners' : 'ギャラリーバナー' }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.galleries.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Galleries' : 'ギャラリー' }}
                    </a>
                </li>

            </ul>
        </li>
        @php
            $articleMenuActive = request()->routeIs('admin.articles.*', 'admin.article-banners.*');
        @endphp
        <li class="nav-item has-dropdown {{ $articleMenuActive ? 'open' : '' }}">
            <button type="button" class="nav-link dropdown-toggle {{ $articleMenuActive ? 'active' : '' }}"
                onclick="this.closest('.has-dropdown').classList.toggle('open')">
                <span>{{ request()->cookie('dev') == '1' ? 'Articles' : '記事' }}</span>
                <span class="dropdown-arrow">▾</span>
            </button>

            <ul class="sub-nav">
                <li>
                    <a href="{{ route('admin.articles.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Articles' : '記事' }}
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.article-banners.index') }}"
                        class="sub-nav-link {{ request()->routeIs('admin.article-banners.*') ? 'active' : '' }}">
                        {{ request()->cookie('dev') == '1' ? 'Article Banners' : '記事バナー' }}
                    </a>
                </li> --}}
            </ul>
        </li>
      @if(!$isNormalAdmin)
    @php
        $systemMenuActive = request()->routeIs('admin.system-management.*');
    @endphp

    <li class="nav-item has-dropdown {{ $systemMenuActive ? 'open' : '' }}">
        <button type="button" class="nav-link dropdown-toggle {{ $systemMenuActive ? 'active' : '' }}"
            onclick="this.closest('.has-dropdown').classList.toggle('open')">
            <span>{{ request()->cookie('dev') == '1' ? 'System' : 'システム' }}</span>
            <span class="dropdown-arrow">▾</span>
        </button>

        <ul class="sub-nav">
            <li>
                <a href="{{ route('admin.system-management.index') }}"
                    class="sub-nav-link {{ request()->routeIs('admin.system-management.*') ? 'active' : '' }}">
                    {{ request()->cookie('dev') == '1' ? 'System Management' : 'システム管理' }}
                </a>
            </li>
        </ul>
    </li>
@endif
        <li class="nav-item">
            <a href="{{ route('admin.orders.index') }}"
                class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                {{ request()->cookie('dev') == '1' ? 'Orders' : '注文' }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.quotations.index') }}"
                class="nav-link {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}">
                {{ request()->cookie('dev') == '1' ? 'Quotations' : '見積り' }}
            </a>
        </li>

      @if(!$isNormalAdmin)
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}"
            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            {{ request()->cookie('dev') == '1' ? 'Users' : 'ユーザー' }}
        </a>
    </li>
@endif
        <li class="nav-item">
            <a href="{{ route('admin.contact-submissions.index') }}"
                class="nav-link {{ request()->routeIs('admin.contact-submissions.*') ? 'active' : '' }}">
                {{ request()->cookie('dev') == '1' ? 'Contact List' : 'お問い合わせ一覧' }}
            </a>
        </li>

        <li>
            <a href="{{ route('admin.faqs.index') }}"
                class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                {{ request()->cookie('dev') == '1' ? 'FAQs' : 'よくある質問' }}
            </a>
        </li>

    </ul>

    <div class="sidebar-footer">
        <a href="#" class="nav-link">Help Center</a>

        <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
            @csrf

            <button type="submit" class="nav-link logout-btn">
                Logout
            </button>
        </form>
    </div>
</aside>


<a href="{{ route('admin.orders.index') }}"
    class="sub-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
    Orders
</a>
