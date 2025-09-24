<nav x-data="{
    scrolled: false,
    activeLink: window.location.pathname,
    isDetailPage: ['/detail-spbu', '/users/profile', '/users/wishlist', '/information', '/detail/space'].some(path => window.location.pathname.split('?')[0].startsWith(path)),
}" x-init="scrolled = window.scrollY > 5;
window.addEventListener('scroll', () => { scrolled = window.scrollY > 5; })" :class="scrolled ? 'bg-white shadow py-2 lg:py-1' : 'bg-transparent'"
    class="fixed w-full z-20 top-0 start-0" x-transition x-cloak>
    <div class="flex items-center justify-between mx-auto lg:px-12 lg:py-2">

        {{-- Detail Page --}}
        <div x-show="isDetailPage" x-cloak class="flex items-center justify-between w-full">
            <a href="#" class="flex items-center space-x-3">
                <img class="h-14 p-0" :src="scrolled ? '/assets/img/logo-asets.png' : '/assets/img/logo-asets.png'"
                    :class="scrolled ? 'h-12' : 'h-14 p-0'" alt="asets-logo">
            </a>

            <button class="lg:hidden mr-5" type="button" data-drawer-target="drawer-mobiles"
                data-drawer-show="drawer-mobiles" aria-controls="drawer-mobiles">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                    <path fill="none" stroke="#949494" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M7 6h14M7 12h14M7 18h14M3 18" />
                </svg>
            </button>

            <div class="hidden md:flex space-x-2">
                <a href="{{ route('home') }}"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('home') ? 'bg-white/20 backdrop-blur-md ' : 'hover:underline' }}"
                    aria-current="page">Home</a>
                <a href="{{ route('location') }}"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('location') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Location</a>
                <a href="{{ route('about') }}"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('about') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">About
                    Us</a>
                <a href="{{ route('investor-relation') }}"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('investor-relation') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Investor
                    Relations</a>
                <a href="{{ route('partnership') }}"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('partnership') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Partnership</a>
                <a href="{{ route('contact-us') }}"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('contact-us') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Contact</a>

                <div class="flex">
                    <x-dropdown-users />
                </div>
            </div>
        </div>

        <!-- Navbar on Non-Detail Pages -->
        <div x-show="!isDetailPage" x-cloak class="flex items-center justify-between w-full px-3 lg:px-0">
            <a href="{{ route('home') }}" class="flex items-center">
                <img :src="scrolled ? '/assets/img/logo-asets.png' : '/assets/img/logo-aset-putih.png'"
                    :class="scrolled ? 'h-12' : 'h-20 p-0'" alt="asets-logo">
            </a>

            <button class="lg:hidden mr-5" type="button" data-drawer-target="drawer-mobiles"
                data-drawer-show="drawer-mobiles" aria-controls="drawer-mobiles">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                    <path fill="none" stroke="#949494" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M7 6h14M7 12h14M7 18h14M3 18" />
                </svg>
            </button>

            <div class="hidden lg:flex items-center flex-col lg:flex-row lg:space-x-2">
                <a href="{{ route('home') }}" :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('home') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}"
                    aria-current="page">Home</a>
                <a href="{{ route('location') }}" :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('location') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Location</a>
                <a href="{{ route('about') }}" :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('about') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">About
                    Us</a>
                <a href="{{ route('investor-relation') }}" :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('investor-relation') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Investor
                    Relations</a>
                <a href="{{ route('partnership') }}" :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('partnership') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Partnership</a>
                <a href="{{ route('contact-us') }}" :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="rounded-lg px-4 py-1 {{ request()->routeIs('contact-us') ? 'bg-white/20 backdrop-blur-md' : 'hover:underline' }}">Contact</a>

                <div class="flex">
                    <x-dropdown-users />
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- DRAWER NAVIGATION --}}
<div id="drawer-mobiles"
    class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 dark:bg-gray-800"
    tabindex="-1" aria-labelledby="drawer-label">
    <h5 id="drawer-label"
        class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
        Menu
    </h5>
    <button type="button" data-drawer-hide="drawer-mobiles" aria-controls="drawer-mobiles"
        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
        <span class="sr-only">Close menu</span>
    </button>

    <hr>

    <div class="flex flex-col space-y-4 mt-4">
        <a href="{{ route('home') }}"
            class="{{ request()->routeIs('home') ? 'bg-purple-950 text-white rounded p-2' : 'hover:underline' }}">Home</a>
        <a href="{{ route('location') }}"
            class="{{ request()->routeIs('location') ? 'bg-purple-950 text-white rounded p-2' : 'hover:underline' }}">Location</a>
        <a href="{{ route('about') }}"
            class="{{ request()->routeIs('about') ? 'bg-purple-950 text-white rounded p-2' : 'hover:underline' }}">About
            Us</a>
        <a href="{{ route('investor-relation') }}"
            class="{{ request()->routeIs('investor-relation') ? 'bg-purple-950 text-white rounded p-2' : 'hover:underline' }}">Investor
            Relations</a>
        <a href="{{ route('partnership') }}"
            class="{{ request()->routeIs('partnership') ? 'bg-purple-950 text-white rounded p-2' : 'hover:underline' }}">Partnership</a>
        <a href="{{ route('contact-us') }}"
            class="{{ request()->routeIs('contact-us') ? 'bg-purple-950 text-white rounded p-2' : 'hover:underline' }}">Contact
            Us</a>

        <hr />

        <div class="flex flex-col">
            <ul>
                @auth
                    <li>
                        <div class="flex items-center px-4 py-2">
                            <div>
                                <img class="w-10 h-10 rounded-full"
                                    src="{{ filter_var(Auth::user()->photo_user, FILTER_VALIDATE_URL) ? Auth::user()->photo_user : 'https://img.freepik.com/free-icon/user_318-563642.jpg' }}"
                                    alt="user photo">
                            </div>
                            <div>
                                <span class="block ml-3 font-medium truncate">{{ Auth::user()->name }}</span>
                                <span class="block ml-3 font-medium truncate">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('profile-user') }}"
                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">Profile</a>
                    </li>
                    <li>
                        <a href="{{ route('wishlist-user') }}"
                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">Wishlist</a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center text-center w-full font-semibold mt-2 gap-2 px-4 py-2 bg-gray-200 shadow rounded-lg hover:bg-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24">
                                    <g fill="none" stroke="#505250" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2">
                                        <path
                                            d="M14 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2" />
                                        <path d="M9 12h12l-3-3m0 6l3-3" />
                                    </g>
                                </svg> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login-form') }}"
                            class="flex items-center font-semibold gap-2 px-4 py-2 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#505250"
                                    d="M12 21v-2h7V5h-7V3h7q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm-2-4l-1.375-1.45l2.55-2.55H3v-2h8.175l-2.55-2.55L10 7l5 5z" />
                            </svg> Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register-form') }}"
                            class="flex items-center font-semibold gap-2 px-4 py-2 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                                <g fill="none">
                                    <path stroke="#505250" stroke-linejoin="round" stroke-width="4"
                                        d="M19 20a7 7 0 1 0 0-14a7 7 0 0 0 0 14Z" />
                                    <path d="M36 29v12zm-6 6h12z" clip-rule="evenodd" />
                                    <path stroke="#505250" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="4"
                                        d="M36 29v12m-6-6h12m-15-7h-8.2c-4.48 0-6.72 0-8.432.872a8 8 0 0 0-3.496 3.496C6 34.08 6 36.32 6 40.8V42h21" />
                                </g>
                            </svg> Sign Up
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login-form') }}"
                            class="flex items-center font-semibold gap-2 px-4 py-2 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <g fill="none" stroke="#505250" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2">
                                    <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                    <path d="M10 17H6V3H4" />
                                    <path
                                        d="m6 5l14 1l-.717 5.016M11.5 13H6m12 9l3.35-3.284a2.143 2.143 0 0 0 .005-3.071a2.24 2.24 0 0 0-3.129-.006l-.224.22l-.223-.22a2.24 2.24 0 0 0-3.128-.006a2.143 2.143 0 0 0-.006 3.071z" />
                                </g>
                            </svg> Wishlist
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
