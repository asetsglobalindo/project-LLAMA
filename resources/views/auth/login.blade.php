<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="Asets, properti, investasi, pengelolaan properti, solusi properti, Indonesia" />
    <title>Asets - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.6/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="poppins-regular">
    <main class="overflow-hidden">
        <div class="flex flex-col justify-center min-h-screen p-2 lg:p-0">
            <div class="mx-auto border w-full lg:w-1/3  p-7 rounded-xl">
                @if (session('success'))
                    <div class=" text-green-600 text-center p-4 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                        <ol>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                <div class="text-center flex flex-col ">
                    <h1 class="mb-3 text-xs font-semibold text-custom-primary">Login or Sign Up</h1>
                    <h1 class="font-bold text-xl">Welcome To Asets</h1>
                </div>
                <br>

                <form action="{{ route('login-process') }}" method="POST" class="flex flex-col w-full"
                    x-data="{ loading: false }" @submit.prevent="loading = true; $el.submit()">
                    @csrf
                    <input type="text" placeholder="Email" name="email" required
                        class="border border-gray-300 rounded-lg focus:outline-none p-3 mb-4">
                    <input type="password" placeholder="Password" name="password"
                        class="border border-gray-300  rounded-lg focus:outline-none p-3">
                    <br>
                    <div>
                        <a href="#" class="text-gray-700 text-xs hover:underline font-semibold underline">Privacy
                            Policy</a>
                    </div>
                    <br>
                    <div>
                        <button type="submit" :disabled="loading"
                            class="bg-custom-primary p-3 text-white rounded-lg w-full flex items-center justify-center">
                            <span x-show="!loading">Login</span>
                            <svg x-show="loading" class="animate-spin h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </button>
                    </div>
                    <br>
                    <div class="flex items-center">
                        <hr class="flex-grow border-t border-gray-400">
                        <span class="mx-4 text-gray-500">or</span>
                        <hr class="flex-grow border-t border-gray-400">
                    </div>
                    <br>
                    <div>
                        <a href="#"
                            class="bg-gray-50 text-gray-600 shadow-md p-3 flex items-center gap-3 justify-center rounded-lg font-semibold text-sm w-full hover:bg-gray-200">
                            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 128 128">
                                    <path fill="#fff"
                                        d="M44.59 4.21a63.28 63.28 0 0 0 4.33 120.9a67.6 67.6 0 0 0 32.36.35a57.13 57.13 0 0 0 25.9-13.46a57.44 57.44 0 0 0 16-26.26a74.3 74.3 0 0 0 1.61-33.58H65.27v24.69h34.47a29.72 29.72 0 0 1-12.66 19.52a36.2 36.2 0 0 1-13.93 5.5a41.3 41.3 0 0 1-15.1 0A37.2 37.2 0 0 1 44 95.74a39.3 39.3 0 0 1-14.5-19.42a38.3 38.3 0 0 1 0-24.63a39.25 39.25 0 0 1 9.18-14.91A37.17 37.17 0 0 1 76.13 27a34.3 34.3 0 0 1 13.64 8q5.83-5.8 11.64-11.63c2-2.09 4.18-4.08 6.15-6.22A61.2 61.2 0 0 0 87.2 4.59a64 64 0 0 0-42.61-.38" />
                                    <path fill="#e33629"
                                        d="M44.59 4.21a64 64 0 0 1 42.61.37a61.2 61.2 0 0 1 20.35 12.62c-2 2.14-4.11 4.14-6.15 6.22Q95.58 29.23 89.77 35a34.3 34.3 0 0 0-13.64-8a37.17 37.17 0 0 0-37.46 9.74a39.25 39.25 0 0 0-9.18 14.91L8.76 35.6A63.53 63.53 0 0 1 44.59 4.21" />
                                    <path fill="#f8bd00"
                                        d="M3.26 51.5a63 63 0 0 1 5.5-15.9l20.73 16.09a38.3 38.3 0 0 0 0 24.63q-10.36 8-20.73 16.08a63.33 63.33 0 0 1-5.5-40.9" />
                                    <path fill="#587dbd"
                                        d="M65.27 52.15h59.52a74.3 74.3 0 0 1-1.61 33.58a57.44 57.44 0 0 1-16 26.26c-6.69-5.22-13.41-10.4-20.1-15.62a29.72 29.72 0 0 0 12.66-19.54H65.27c-.01-8.22 0-16.45 0-24.68" />
                                    <path fill="#319f43"
                                        d="M8.75 92.4q10.37-8 20.73-16.08A39.3 39.3 0 0 0 44 95.74a37.2 37.2 0 0 0 14.08 6.08a41.3 41.3 0 0 0 15.1 0a36.2 36.2 0 0 0 13.93-5.5c6.69 5.22 13.41 10.4 20.1 15.62a57.13 57.13 0 0 1-25.9 13.47a67.6 67.6 0 0 1-32.36-.35a63 63 0 0 1-23-11.59A63.7 63.7 0 0 1 8.75 92.4" />
                                </svg></span>
                            Login with google
                        </a>
                    </div>
                    <br>

                    <div class="flex flex-col items-center space-y-3">
                        <p class="text-xs lg:text-sm text-gray-700">Don't have an account? <a
                                href="{{ route('register-form') }}"
                                class="text-custom-primary hover:underline font-semibold">Sign Up</a></p>
                        <a href="{{ route('home') }}" class="text-xs lg:text-sm underline">Back to home</a>
                    </div>

                </form>

            </div>

        </div>
    </main>
</body>

</html>
