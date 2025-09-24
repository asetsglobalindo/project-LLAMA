<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="Asets, properti, investasi, pengelolaan properti, solusi properti, Indonesia" />
    <title>Asets - Register</title>

    {{-- FONTS CDN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- SCRIPTS CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.6/dist/cdn.min.js"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="poppins-regular ">
    <main>
        <div class="flex flex-col justify-center min-h-screen bg-gray-50 py-4">
            <div class="mx-auto border lg:w-1/3 p-6 shadow rounded-xl  overflow-y-auto bg-white">
                @if (session('success'))
                    <div class="bg-green-700 text-white text-center p-4 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                        <ol class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                <div class="text-center flex flex-col ">
                    <h1 class="font-bold lg:text-xl text-2xl">Register Into Your Account</h1>
                </div>
                <br>
                <form action="{{ route('register-process') }}" method="POST" class="flex flex-col">
                    @csrf

                    <input id="name" type="text" placeholder="Your name" name="name" required
                        class="border border-gray-300 rounded-lg focus:outline-none p-3 mb-4">

                    <input type="email" placeholder="Email" name="email" required
                        class="border border-gray-300 rounded-lg focus:outline-none p-3 mb-4">

                    <input type="number" placeholder="Phone number" name="phone_number" required
                        class="border border-gray-300 rounded-lg focus:outline-none p-3 mb-4">

                    <input data-tooltip-target="tooltip-dob" type="date" name="date_of_birth" required
                        class="border border-gray-300 rounded-lg focus:outline-none p-3 mb-4">

                    <div id="tooltip-dob" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-xs  text-white transition-opacity duration-300 bg-custom-primary rounded-lg shadow-sm opacity-0 tooltip ">
                        Date of birth
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <div x-data="{ show: false }" class="relative w-full mb-4">
                        <!-- Input Password -->
                        <input :type="show ? 'text' : 'password'" name="password"
                            class="border border-gray-300 rounded-lg w-full p-3 pr-10 focus:outline-none "
                            placeholder="Enter your password"> <span class="text-gray-400 text-xs ms-3">*password min 8
                            character</span>

                        <!-- Toggle Button -->
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                        </button>
                    </div>

                    <div x-data="{ show: false }" class="relative w-full">
                        <!-- Input Confirm Password -->
                        <input :type="show ? 'text' : 'password'" name="password_confirmation"
                            class="border border-gray-300 rounded-lg w-full p-3 pr-10 focus:outline-none "
                            placeholder="Confirmation password">

                        <!-- Toggle Button -->
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                        </button>
                    </div>


                    <br>
                    <div>
                        <a href="#" class="text-gray-700 text-xs hover:underline font-semibold underline">Privacy
                            Policy</a>
                    </div>
                    <br>
                    <div>
                        <button type="submit" class="bg-purple-950 p-3 text-white rounded-lg w-full">Register</button>
                    </div>
                    <br>
                    <div class="flex flex-col items-center">
                        <p class=" text-gray-700">Already account? <a href="{{ route('login-form') }}"
                                class="text-purple-950 hover:underline font-semibold">Login</a></p>
                    </div>
                </form>
            </div>

        </div>
    </main>
</body>

</html>
