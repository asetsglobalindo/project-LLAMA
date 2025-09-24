@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    {{-- <style>
        .swiper-container {
            width: 100%;
            overflow: hidden;
        }
    </style>

    <div class="flex flex-col w-full lg:flex-row md:justify-between gap-9 items-center ">
        <div class="text-white lg:text-start text-center flex flex-col justify-center ">
            <h1 class="text-6xl leading-relaxed font-bold">Contact Us</h1>
            <p class="leading-relaxed">Stay informed on the latest developments at Asets. Our investor relations section
                delivers comprehensive insights into our business, showcasing our financial performance and highlighting
                exciting growth opportunities on the horizon.
            </p>
           
            <div class="mt-5 flex gap-4 justify-center lg:justify-start md:flex-row">
                <div class="">
                    <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20lebih%20lanjut" target="_blank">

                        <svg class="w-[48px] h-[48px]  text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z"
                                clip-rule="evenodd" />
                            <path fill="currentColor"
                                d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z" />
                        </svg>
                    </a>
                </div>

                <div class="">
                    <a href="https://www.instagram.com/asets.co/" target="_blank">
                        <svg class="w-[48px] h-[48px] text-gray-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>

        <div class="relative w-full lg:w-1/3  lg:flex flex-col ">
            <div class="swiper-container" id="slider">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <x-card-sliders
                            image="https://kfmap.asia/storage/thumbs/storage/photos/ID.JKT.OF.GAG1/ID.JKT.OF.GAG1_1.png"
                            title="Head Office :"
                            description="Gedung Artha Graha, 26 floor (SCBD) Jalan Jend. Sudirman No. 52-53, Kelurahan Senayan, Kec. Kebayoran Baru, Kota Adm Jakarta Selatan, Provinsi DKI Jakarta. Zip Code 12190."
                            qr="" />
                    </div>

                    <div class="swiper-slide">
                        <x-card-sliders
                            image="https://kfmap.asia/storage/thumbs/storage/photos/ID.JKT.OF.CEO/ID.JKT.OF.CEO_1.jpg"
                            title="Back Office"
                            description="Gedung The CEO Tower Lantai 15 Jl.TB Simatupang, Cilandak, Jakarta Selatan."
                            qr="" />
                    </div>

                    <div class="swiper-slide">
                        <x-card-sliders
                            image="https://kfmap.asia/storage/thumbs/storage/photos/ID.JKT.OF.PO1/ID.JKT.OF.PO1_2.png"
                            title="Operation Services"
                            description="PALMA ONE Lantai 2 Jl.H.R Rasuna Said Kav. X2 No.4 Kuningan, Jakarta Selatan 12950, Indonesia."
                            qr="" />
                    </div>
                </div>
            </div>

            <div class="flex lg:justify-start justify-around gap-5 mt-4">
                <x-button-icons id="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-11">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </x-button-icons>
                <x-button-icons id="next">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-11">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </x-button-icons>
            </div>

        </div>

    </div> --}}

    <div class="overflow-hidden"
        style="background-image: url('https://images.pexels.com/photos/936722/pexels-photo-936722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'); background-size: cover; background-position: center; background-repeat: no-repeat">
        <div class=" bg-black bg-opacity-70 w-full min-h-screen ">
            <div
                class="flex relative top-0 flex-col w-full py-16 lg:flex-row md:justify-between px-6 gap-9 items-center lg:py-32 lg:px-14">
                <div>
                    <x-steppers />
                </div>
                <div class="text-white lg:text-start text-center  flex flex-col justify-center">
                    <h1 class="text-6xl leading-relaxed font-bold">Contact Us</h1>

                    <p class="leading-relaxed font-light text-justify">Stay informed on the latest developments at Asets. Our
                        investor
                        relations section delivers comprehensive insights into our business, showcasing our financial
                        performance and highlighting exciting growth opportunities on the horizon.
                    </p>
                    <div class="mt-5 flex gap-4 justify-center lg:justify-start md:flex-row">
                        <div class="">
                            <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20lebih%20lanjut"
                                target="_blank">

                                <svg class="w-[48px] h-[48px]  text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z"
                                        clip-rule="evenodd" />
                                    <path fill="currentColor"
                                        d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z" />
                                </svg>
                            </a>
                        </div>

                        <div class="">
                            <a href="https://www.instagram.com/asets.co/" target="_blank">
                                <svg class="w-[48px] h-[48px] text-gray-50" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>

                <div class=" w-full lg:w-[3500px]">

                    @if (session('success'))
                        <div id="toast-simple"
                            class="flex items-center w-full max-w-xs p-4 space-x-4 bg-white/15 backdrop-blur-sm border-0 rounded-2xl  text-white"
                            role="alert">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-500 rotate-45" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 17 8 2L9 1 1 19l8-2Zm0 0V9" />
                            </svg>
                            <div class="ps-4 text-sm font-normal">Message sent successfully.</div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="toast-simple"
                            class="flex items-center w-full max-w-xs p-4 space-x-4 bg-white/15 backdrop-blur-sm border-0 rounded-2xl  text-white"
                            role="alert">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-500 rotate-45" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 17 8 2L9 1 1 19l8-2Zm0 0V9" />
                            </svg>
                            <div class="ps-4 text-sm font-normal">Message failed.</div>
                        </div>
                    @endif

                    <form action="{{ route('sendMessage') }}" method="POST" class="space-y-5 w-full">
                        @csrf

                        <div>
                            <input type="text" name="names" placeholder="Your name..."
                                class=" bg-white/15 backdrop-blur-sm p-4 border-0 rounded-2xl  text-white w-full " required>
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="your email..."
                                class=" bg-white/15 backdrop-blur-sm p-4 border-0  rounded-2xl  text-white w-full "
                                required>
                        </div>

                        <div class="sm:col-span-2">
                            <textarea id="message" name="message" rows="4" placeholder="Your message..."
                                class="border-0 w-full backdrop-blur-sm bg-white/15 rounded-2xl text-white"></textarea>
                        </div>
                        <div class="mt-2">
                            <x-button-primary type="submit">Send</x-button-primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
