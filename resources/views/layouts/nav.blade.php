<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a
                class="navbar-brand"
                @can('manage-bills')
                    href="{{ route('home') }}"
                @else
                    href="{{ route('settings.users') }}"
                @endcan
        >
            {{ __('Home') }}
        </a>
        <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
                class="collapse navbar-collapse"
                id="navbarSupportedContent"
        >
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @can('manage-bills')
                    <li class="nav-item">
                        <a
                                data-url="transactions"
                                class="nav-link"
                                href="{{ route('transactions') }}"
                        >
                            {{ __('Transactions') }}
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                @endcan
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a
                                class="nav-link"
                                href="{{ route('login') }}"
                        >
                            {{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a
                                    class="nav-link"
                                    href="{{ route('register') }}"
                            >
                                {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a
                                id="navbarDropdown"
                                class="nav-link
                                dropdown-toggle"
                                href="#"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false" v-pre
                        >
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            <span class="caret"></span>
                        </a>

                        <div
                                class="dropdown-menu dropdown-menu-right"
                                aria-labelledby="navbarDropdown"
                        >
                            @can('manage-settings')
                                <a
                                        data-url="settings"
                                        class="dropdown-item"
                                        href="{{ route('settings.users') }}"
                                >
                                    {{ __('Settings') }}
                                </a>
                            @endcan
                            @can('manage-bills')
                                <a
                                    href="#"
                                    class="dropdown-item modal-open-btn"
                                    data-toggle="modal"
                                    data-target="#modal"
                                    data-target-url="{{ route('settings.users.change-password', auth()->id()) }}"
                                    title="{{ __('Change password') }}"
                                >
                                    {{ __('Change password') }}
                                </a>
                            @endcan


                                <a
                                        class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"
                                >
                                {{ __('Logout') }}
                            </a>
                            <form
                                    id="logout-form"
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    style="display: none;"
                            >
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
