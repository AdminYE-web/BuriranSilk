<style>
    .account-sidebar {
        background: #fff;
        min-height: 560px;
        padding: 42px 34px;
    }

    .sidebar-profile {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e5e5e5;
        margin-bottom: 18px;
    }

    .sidebar-avatar {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #9ca3af;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sidebar-avatar-placeholder,
    .account-avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #9ca3af;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
    }

    .sidebar-name {
        font-size: 15px;
        font-weight: 600;
        color: #111;
    }

    .sidebar-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #1683ff;
        text-decoration: none;
    }

    .sidebar-edit img {
        width: 14px;
        height: 14px;
        display: block;
        object-fit: contain;
    }

    .account-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .account-menu li {
        margin-bottom: 18px;
    }

    .account-menu a {
        color: #111;
        text-decoration: none;
        font-size: 14px;
    }

    .sign-out-box {
        margin-top: 90px;
    }

    .sign-out-btn {
        border: 0;
        background: transparent;
        color: #777;
        padding: 0;
        font-size: 14px;
        cursor: pointer;
    }

    .account-menu-toggle {
        width: 100%;
        border: 0;
        background: transparent;
        padding: 0;
        color: #111;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }

    .account-submenu {
        list-style: none;
        margin: 14px 0 0 18px;
        padding: 0;
        display: none;
    }

    .account-menu-dropdown.is-open .account-submenu {
        display: block;
    }

    .account-menu-arrow {
        width: 10px;
        /* height: 10px; */
        object-fit: contain;
        transition: transform 0.2s ease;
    }

    .account-menu-dropdown.is-open .account-menu-arrow {
        transform: rotate(180deg);
    }

    .account-submenu li {
        margin-bottom: 14px;
    }

    .account-submenu a {
        color: #888;
        text-decoration: none;
    }

    .account-submenu a.active {
        color: #1683ff;
        border-left: 2px solid #1683ff;
        padding-left: 40px;
        margin-left: -44px;
    }
</style>
<aside class="account-sidebar">
    <div class="sidebar-profile">
        <div class="sidebar-avatar">
            @if (!empty($user->avatar))
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
            @else
                <div class="sidebar-avatar-placeholder">
                    {{ strtoupper(substr($user->first_name ?? ($user->name ?? 'U'), 0, 1)) }}
                </div>
            @endif
        </div>

        <div>
            <div class="sidebar-name">
                {{ $user->name }}
            </div>

            <a href="{{ route('account.index') }}" class="sidebar-edit">
                <img src="{{ asset('assets/images/account/Group (1).png') }}" alt="">
                アカウント編集
            </a>
        </div>
    </div>

    @php
        $isAddressMenuOpen = request()->routeIs('account.addresses.*');
    @endphp

    <ul class="account-menu">
        <li>
            <a href="{{ route('account.contacts.index') }}"
                class="{{ request()->routeIs('account.contacts.*') ? 'active' : '' }}">
                連絡先情報
            </a>
        </li>

        <li class="account-menu-dropdown {{ $isAddressMenuOpen ? 'is-open' : '' }}">
            <button type="button" class="account-menu-toggle">
                <span>住所一覧</span>
                <img src="{{ asset('assets/images/icon/Vector (8).png') }}" alt="" class="account-menu-arrow">
            </button>

            <ul class="account-submenu">
                <li>
                    <a href="{{ route('account.addresses.index', 'shipping') }}"
                        class="{{ request()->is('account/addresses/shipping*') ? 'active' : '' }}">
                        お届け先住所
                    </a>
                </li>

                <li>
                    <a href="{{ route('account.addresses.index', 'billing') }}"
                        class="{{ request()->is('account/addresses/billing*') ? 'active' : '' }}">
                        請求先住所
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{ route('account.orders.index') }}"
                class="{{ request()->routeIs('account.orders.*') ? 'active' : '' }}">
                注文履歴
            </a>
        </li>
        <li><a href="#">リワード</a></li>
    </ul>

    <div class="sign-out-box">
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="sign-out-btn">
                &larr; ログアウト
            </button>
        </form>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.account-menu-toggle').forEach(function(button) {
            button.addEventListener('click', function() {
                const dropdown = this.closest('.account-menu-dropdown');

                dropdown.classList.toggle('is-open');
            });
        });
    });
</script>
