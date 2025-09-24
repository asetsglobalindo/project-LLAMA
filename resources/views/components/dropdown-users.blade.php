<div x-data="{ open: false }">
    <button @click="open = !open" class="px-4 py-1 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 16 16">
            <path fill="#6a6a6a" d="M11 7c0 1.66-1.34 3-3 3S5 8.66 5 7s1.34-3 3-3s3 1.34 3 3" />
            <path fill="#6a6a6a" fill-rule="evenodd"
                d="M16 8c0 4.42-3.58 8-8 8s-8-3.58-8-8s3.58-8 8-8s8 3.58 8 8M4 13.75C4.16 13.484 5.71 11 7.99 11c2.27 0 3.83 2.49 3.99 2.75A6.98 6.98 0 0 0 14.99 8c0-3.87-3.13-7-7-7s-7 3.13-7 7c0 2.38 1.19 4.49 3.01 5.75"
                clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-12 bg-white py-3 rounded-lg shadow-lg w-auto">
        <div class="flex flex-col">
            @auth
                <div class="flex items-center px-4 py-2">
                    <div>
                        <img class="w-10 h-10 rounded-full"
                            src="{{ Auth::user()->photo_user ? Auth::user()->photo_user : 'https://img.freepik.com/free-icon/user_318-563642.jpg' }}"
                            alt="user photo">
                    </div>
                    <div>
                        <span class="block ml-3 font-medium truncate">{{ Auth::user()->name }}</span>
                        <span class="block ml-3 font-medium truncate">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <div>
                    <a href="{{ route('profile-user') }}"
                        class="flex items-center  gap-2 px-4 py-2  {{ request()->routeIs('profile-user') ? 'bg-custom-primary text-white' : '' }}"><svg
                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#565656"
                                d="M12 11a4 4 0 1 0-4-4a4 4 0 0 0 4 4m6 10a1 1 0 0 0 1-1a7 7 0 0 0-14 0a1 1 0 0 0 1 1Z" />
                        </svg> Profile</a>
                </div>

                <div>
                    <a href="{{ route('wishlist-user') }}"
                        class="flex items-center  gap-2 px-4 py-2 {{ request()->routeIs('wishlist-user') ? 'bg-custom-primary text-white' : '' }} "><svg
                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="#505250" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2">
                                <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                <path d="M10 17H6V3H4" />
                                <path
                                    d="m6 5l14 1l-.717 5.016M11.5 13H6m12 9l3.35-3.284a2.143 2.143 0 0 0 .005-3.071a2.24 2.24 0 0 0-3.129-.006l-.224.22l-.223-.22a2.24 2.24 0 0 0-3.128-.006a2.143 2.143 0 0 0-.006 3.071z" />
                            </g>
                        </svg> Wishlist</a>
                </div>

                <div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center w-full  gap-2 px-4 py-2 hover:bg-gray-100  "><svg
                                xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <g fill="none" stroke="#505250" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2">
                                    <path d="M14 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2" />
                                    <path d="M9 12h12l-3-3m0 6l3-3" />
                                </g>
                            </svg> Logout
                    </form>
                </div>
            @else
                <div>
                    <a href="{{ route('login-form') }}"
                        class="flex font-semibold items-center  gap-2 px-4 py-2 hover:bg-custom-primary hover:text-white"><svg
                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#3d2369"
                                d="M12 21v-2h7V5h-7V3h7q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm-2-4l-1.375-1.45l2.55-2.55H3v-2h8.175l-2.55-2.55L10 7l5 5z" />
                        </svg> Login</a>
                </div>
                <div>
                    <a href="{{ route('register-form') }}"
                        class="flex items-center font-semibold  gap-2 px-4 py-2 hover:bg-custom-primary hover:text-white  "><svg
                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                            <g fill="none">
                                <path stroke="#3d2369" stroke-linejoin="round" stroke-width="4"
                                    d="M19 20a7 7 0 1 0 0-14a7 7 0 0 0 0 14Z" />
                                <path d="M36 29v12zm-6 6h12z" clip-rule="evenodd" />
                                <path stroke="#3d2369" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                    d="M36 29v12m-6-6h12m-15-7h-8.2c-4.48 0-6.72 0-8.432.872a8 8 0 0 0-3.496 3.496C6 34.08 6 36.32 6 40.8V42h21" />
                            </g>
                        </svg> Sign
                        Up</a>
                </div>

            @endauth
        </div>
    </div>
</div>
