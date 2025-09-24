@extends('layouts.app')

@section('title', 'Details SPBU')

@section('content')
    <style>
        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper {
            width: 100%;
            height: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .swiper-slide {
            background-size: cover;
            background-position: center;
        }

        .mySwiper2 {
            height: 80%;
            width: 100%;
        }

        .mySwiper {
            height: 20%;
            box-sizing: border-box;
            padding: 10px 0;
        }

        .mySwiper .swiper-slide {
            width: 25%;
            height: 100%;
            opacity: 0.4;
        }

        .mySwiper .swiper-slide-thumb-active {
            opacity: 1;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-button-next,
        .swiper-button-prev {
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            color: white;
            border-radius: 50%;
            padding: 2rem;
        }

        #pay-devices {
            display: block;
        }

        #pay-mobile {
            display: none;
        }

        @media (max-width: 768px) {
            #pay-devices {
                display: none;
            }

            #pay-mobile {
                display: block;
            }
        }
    </style>

    <section class=" overflow-hidden">
        <div class="py-20 px-6 lg:px-14 bg-gray-100">
            <div class="lg:grid lg:grid-cols-3 lg:gap-5 mb-3 bg-white p-0 lg:p-4 rounded-lg">
                <div class="col-span-2">

                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                        <div class="swiper-wrapper">
                            @if (!empty($listingDetail['data']['photos']))
                                @foreach ($listingDetail['data']['photos'] as $photo)
                                    @if (isset($photo['image_url']))
                                        <div class="swiper-slide">
                                            <img src="{{ $photo['image_url'] }}" alt="Photo"
                                                class="object-cover w-full h-full rounded-lg" />
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-gray-500">Tidak ada foto tersedia.</p>
                            @endif

                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>

                    </div>
                    <div thumbsSlider="" class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($listingDetail['data']['photos'] as $photo)
                                <div class="swiper-slide">
                                    <img src="{{ $photo['image_url'] }} " class="rounded-lg" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 lg:mt-0">
                    <div class="">
                        <div class="rounded-2xl border flex flex-col p-4 space-y-2">
                            <div class="flex">
                                <p>
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" fill="#551275"
                                            d="M4 4a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2v14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2V5a1 1 0 0 1-1-1Zm5 2a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-1Zm-5 4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-1Zm-3 4a2 2 0 0 0-2 2v3h2v-3h2v3h2v-3a2 2 0 0 0-2-2h-2Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </p>
                                <p class="leading-relaxed ml-2 font-semibold">
                                    {{ implode(' ', array_slice(explode(' ', $listingDetail['data']['name']), 3)) }}
                                </p>
                            </div>
                            <div>
                                <div class="mb-2 flex gap-2">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-5 text-purple-950">
                                            <path fill-rule="evenodd"
                                                d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                                clip-rule="evenodd" />
                                        </svg>

                                    </p>
                                    <h5 class="font-bold text-gray-900 ">{{ $listingDetail['data']['address'] }}
                                    </h5>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <div class="flex gap-2 ">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                            <path fill="#551275"
                                                d="M21 19h2v2H1v-2h2V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v15h2V9h3a1 1 0 0 1 1 1zM7 11v2h4v-2zm0-4v2h4V7z" />
                                        </svg>

                                    </p>
                                    <p class="leading-relaxed font-semibold">
                                        {{ $listingDetail['data']['total_space_available'] }} Total Space Available
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="flex flex-col border rounded p-4">
                        <div class="flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full object-contain border border-black"
                                    src="/assets/img/logo-asets.png" alt="logo asets">
                            </div>
                            <div class="flex-1 min-w-0 ms-2">
                                <p class="text-sm font-bold text-gray-900 ">
                                    Contact Center
                                </p>
                                <p class="text-xs text-gray-700  ">
                                    Asets
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-3">
                            <div class="flex items-center gap-6">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="#341d5b"
                                            d="M19.95 21q-3.125 0-6.175-1.362t-5.55-3.863t-3.862-5.55T3 4.05q0-.45.3-.75t.75-.3H8.1q.35 0 .625.238t.325.562l.65 3.5q.05.4-.025.675T9.4 8.45L6.975 10.9q.5.925 1.187 1.787t1.513 1.663q.775.775 1.625 1.438T13.1 17l2.35-2.35q.225-.225.588-.337t.712-.063l3.45.7q.35.1.575.363T21 15.9v4.05q0 .45-.3.75t-.75.3" />
                                    </svg></span>
                                <p class="text-sm font-medium text-gray-700"> 0811102239</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                        <path fill="#341d5b"
                                            d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2m0 4l-8 5l-8-5V6l8 5l8-5z" />
                                    </svg></span>
                                <p class="text-sm font-medium text-gray-700"> ceooffice.asets@gmail.com</p>
                            </div>
                        </div>
                        <br>

                        <div class="flex">
                            <div class="bg-[#03892B] w-full rounded-lg py-2 px-6">
                                <a href="https://wa.me/62811102239?text=Permisi%2C%20saya%20ingin%20menyewa%20ruangan%20pada%20lokasi%20SPBU%20{{ $listingDetail['data']['address'] }}"
                                    target="_blank" class="flex justify-center font-semibold items-center gap-3 text-white">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            viewBox="0 0 256 258">
                                            <defs>
                                                <linearGradient id="logosWhatsappIcon0" x1="50%" x2="50%" y1="100%" y2="0%">
                                                    <stop offset="0%" stop-color="#1faf38" />
                                                    <stop offset="100%" stop-color="#60d669" />
                                                </linearGradient>
                                                <linearGradient id="logosWhatsappIcon1" x1="50%" x2="50%" y1="100%" y2="0%">
                                                    <stop offset="0%" stop-color="#f9f9f9" />
                                                    <stop offset="100%" stop-color="#fff" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#logosWhatsappIcon0)"
                                                d="M5.463 127.456c-.006 21.677 5.658 42.843 16.428 61.499L4.433 252.697l65.232-17.104a123 123 0 0 0 58.8 14.97h.054c67.815 0 123.018-55.183 123.047-123.01c.013-32.867-12.775-63.773-36.009-87.025c-23.23-23.25-54.125-36.061-87.043-36.076c-67.823 0-123.022 55.18-123.05 123.004" />
                                            <path fill="url(#logosWhatsappIcon1)"
                                                d="M1.07 127.416c-.007 22.457 5.86 44.38 17.014 63.704L0 257.147l67.571-17.717c18.618 10.151 39.58 15.503 60.91 15.511h.055c70.248 0 127.434-57.168 127.464-127.423c.012-34.048-13.236-66.065-37.3-90.15C194.633 13.286 162.633.014 128.536 0C58.276 0 1.099 57.16 1.071 127.416m40.24 60.376l-2.523-4.005c-10.606-16.864-16.204-36.352-16.196-56.363C22.614 69.029 70.138 21.52 128.576 21.52c28.3.012 54.896 11.044 74.9 31.06c20.003 20.018 31.01 46.628 31.003 74.93c-.026 58.395-47.551 105.91-105.943 105.91h-.042c-19.013-.01-37.66-5.116-53.922-14.765l-3.87-2.295l-40.098 10.513z" />
                                            <path fill="#fff"
                                                d="M96.678 74.148c-2.386-5.303-4.897-5.41-7.166-5.503c-1.858-.08-3.982-.074-6.104-.074c-2.124 0-5.575.799-8.492 3.984c-2.92 3.188-11.148 10.892-11.148 26.561s11.413 30.813 13.004 32.94c1.593 2.123 22.033 35.307 54.405 48.073c26.904 10.609 32.379 8.499 38.218 7.967c5.84-.53 18.844-7.702 21.497-15.139c2.655-7.436 2.655-13.81 1.859-15.142c-.796-1.327-2.92-2.124-6.105-3.716s-18.844-9.298-21.763-10.361c-2.92-1.062-5.043-1.592-7.167 1.597c-2.124 3.184-8.223 10.356-10.082 12.48c-1.857 2.129-3.716 2.394-6.9.801c-3.187-1.598-13.444-4.957-25.613-15.806c-9.468-8.442-15.86-18.867-17.718-22.056c-1.858-3.184-.199-4.91 1.398-6.497c1.431-1.427 3.186-3.719 4.78-5.578c1.588-1.86 2.118-3.187 3.18-5.311c1.063-2.126.531-3.986-.264-5.579c-.798-1.593-6.987-17.343-9.819-23.64" />
                                        </svg></span> Contact the seller
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- DESCRIPTIONS / LOCATION --}}
            <div class="lg:grid lg:grid-cols-3 lg:gap-5  py-6 px-5 bg-white rounded-lg mb-3">
                <div class="col-span-2">
                    <h1 class="font-bold">Location</h1>
                    <br>

                    <div class="overflow-auto ">
                        <div id="map" style="height: 380px; width: auto;" class="rounded-lg shadow z-0"></div>
                    </div>

                    {{-- <div>
                        @foreach ($listingDetail['data']['listing_attributes'] as $descriptions)
                        <p class="text-gray-700">
                            {{ $descriptions }}
                        </p>
                        @endforeach
                    </div> --}}
                </div>

                <div class="flex flex-col space-y-3 p-3">
                    <h1 class="font-bold">Facility</h1>
                    <br>
                    <div class="flex lg:flex-row flex-col gap-3 flex-wrap">
                        @foreach ($listingDetail['data']['facilities'] as $facility)
                            <div class="flex flex-row gap-1 items-center">
                                <img class="w-5 h-5" src="{{ $facility['facility_icon'] }}" alt="facilities-icons">
                                <p class="text-xs text-gray-700 font-semibold">{{ $facility['facility_name'] }}</p>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- SURROUNDING AREAS --}}
            <div class="lg:grid lg:grid-cols-3 lg:gap-5 p-6 rounded-lg bg-white mb-3">
                <div class="col-span-3">
                    <h1 class="font-bold">Surrounding Area</h1>
                    <br>
                    <div class="relative overflow-x-auto p-4 h-64">
                        <table class="w-full text-sm text-left text-gray-500 ">

                            <tbody>
                                @foreach ($listingDetail['data']['surrounding_areas'] as $surrounding)
                                    <tr class="bg-white border-b ">
                                        <td scope="row"
                                            class="flex gap-2 px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                            <img class="w-5 h-5" src="{{ $surrounding['surrounding_area_legend_icon'] }}"
                                                alt="areas-icons"> {{ $surrounding['surrounding_area_name'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $surrounding['surrounding_area_legend'] }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SPACE AVAILABLE --}}
            <div class="flex flex-col space-y-2 lg:gap-5 p-6 rounded-lg bg-white mb-3">
                <div>
                    <h1 class="font-bold">Space Available</h1>
                </div>

                <div class="flex justify-between items-center bg-purple-950 px-3 py-2 rounded-lg">
                    <div class="flex gap-3 items-center px-3">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24">
                                <path fill="#fff"
                                    d="M4 4a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2a2 2 0 0 1-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 1-2-2a2 2 0 0 1 2-2V6a2 2 0 0 0-2-2zm11.5 3L17 8.5L8.5 17L7 15.5zm-6.69.04c.98 0 1.77.79 1.77 1.77a1.77 1.77 0 0 1-1.77 1.77c-.98 0-1.77-.79-1.77-1.77a1.77 1.77 0 0 1 1.77-1.77m6.38 6.38c.98 0 1.77.79 1.77 1.77a1.77 1.77 0 0 1-1.77 1.77c-.98 0-1.77-.79-1.77-1.77a1.77 1.77 0 0 1 1.77-1.77" />
                            </svg></span>
                        <p class="text-white text-xs lg:text-sm">Log in now to use coupon code USAHAYUK and save up to
                            Rp1.000.000
                            with no
                            min. </p>
                    </div>
                    <div>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24">
                                <g fill="none">
                                    <path
                                        d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                    <path fill="#fff"
                                        d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m0 14a1 1 0 1 0 0 2a1 1 0 0 0 0-2m0-9.5a3.625 3.625 0 0 0-3.625 3.625a1 1 0 1 0 2 0a1.625 1.625 0 1 1 2.23 1.51c-.676.27-1.605.962-1.605 2.115V14a1 1 0 1 0 2 0c0-.244.05-.366.261-.47l.087-.04A3.626 3.626 0 0 0 12 6.5" />
                                </g>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="overflow-y-auto border h-screen rounded-xl ">
                    @if (collect($listingDetail['data']['spaces'])->contains('space_available', 1))
                        @php
                            $logoWA1 = 3;
                            $logoWA2 = 4;
                        @endphp
                        @foreach ($listingDetail['data']['spaces'] as $spaceList)
                            @if ($spaceList['space_available'] == 1)
                                <div class="p-4 rounded-lg">
                                    <div class="mb-3">
                                        <h1 class="font-bold text-custom-primary">{{ $spaceList['space_name'] }}</h1>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-3">
                                        <div>
                                            @if (!empty($spaceList['space_photos']) && count($spaceList['space_photos']) > 0)
                                                @foreach ($spaceList['space_photos'] as $photo)
                                                    <img class="w-[370px] h-48 rounded-l-lg object-cover"
                                                        src="{{ $photo ?? 'https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain' }}"
                                                        alt="Photo">
                                                @endforeach
                                            @else
                                                <img class="w-[370px] h-56 rounded-l-lg object-cover"
                                                    src="https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain"
                                                    alt="No Image Available">
                                            @endif
                                        </div>
                                        <div class="border">
                                            <div class="border p-2 bg-gray-100">
                                                <h1 class="text-center font-semibold">
                                                    ROOM OPTIONS
                                                </h1>
                                            </div>
                                            <div class="px-4 py-3">
                                                <p class="mb-2 font-semibold text-purple-950">
                                                    {{ $spaceList['space_name'] }}
                                                </p>
                                                <ol class="space-y-2 text-sm">
                                                    <li class="flex gap-3 items-center">
                                                        <span class="bg-green-600 rounded-full p-1 w-5 h-5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                viewBox="0 0 48 48">
                                                                <path fill="#fff" fill-rule="evenodd" stroke="#fff"
                                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                                    d="m4 24l5-5l10 10L39 9l5 5l-25 25z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        Size - {{ $spaceList['space_size_sqm'] }} / m2
                                                    </li>
                                                    <li class="flex gap-3 items-center">
                                                        <span class="bg-green-600 rounded-full p-1 w-5 h-5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                viewBox="0 0 48 48">
                                                                <path fill="#fff" fill-rule="evenodd" stroke="#fff"
                                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                                    d="m4 24l5-5l10 10L39 9l5 5l-25 25z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        Type - {{ $spaceList['space_type']['name'] }}
                                                    </li>
                                                    <li class="flex gap-3 items-center">
                                                        <span class="bg-green-600 rounded-full p-1 w-5 h-5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                viewBox="0 0 48 48">
                                                                <path fill="#fff" fill-rule="evenodd" stroke="#fff"
                                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                                    d="m4 24l5-5l10 10L39 9l5 5l-25 25z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        Type Price - {{ $spaceList['space_price_type'] }}
                                                    </li>
                                                    <li class="flex gap-3 items-center">
                                                        <span class="bg-green-600 rounded-full p-1 w-5 h-5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                viewBox="0 0 48 48">
                                                                <path fill="#fff" fill-rule="evenodd" stroke="#fff"
                                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                                    d="m4 24l5-5l10 10L39 9l5 5l-25 25z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                        Status -
                                                        {{ $spaceList['space_available'] == 1 ? 'Available' : 'Leased' }}
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                        <div class="border rounded-r-lg">
                                            <div class="border p-2 bg-gray-100">
                                                <h1 class="text-center font-semibold">
                                                    PRICE
                                                </h1>
                                            </div>
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="py-8 px-3 flex-col space-y-3 text-center">
                                                    <h1 class="text-xl text-purple-950 font-bold">
                                                        {!! html_entity_decode(str_replace('Bulan', 'Month', $spaceList['space_display_price'])) !!}
                                                    </h1>

                                                    <small class="text-gray-500">Taxes (11% VAT) are not included in the
                                                        price.</small>
                                                    <div class="flex flex-row justify-center gap-4">
                                                        <div x-data="bookingModal">

                                                            <button class="bg-purple-950 px-5 py-2 rounded-lg text-white"
                                                                @click="openModal">Booking</button>


                                                            <div x-show="isOpen"
                                                                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 py-12"
                                                                x-transition:enter="transition ease-out duration-300"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-100"
                                                                x-transition:leave="transition ease-in duration-300"
                                                                x-transition:leave-start="opacity-100"
                                                                x-transition:leave-end="opacity-0">
                                                                <div
                                                                    class="relative mx-auto border p-4 shadow rounded-xl bg-white flex justify-center items-center">
                                                                    <div class="overflow-auto relative"
                                                                        style="width: 80vw; max-height: 90vh;">
                                                                        <button
                                                                            class="absolute top-0 left-0 text-lg font-bold text-custom-primary"
                                                                            @click="closeModal">X</button>
                                                                        <div class="text-center flex flex-col ">
                                                                            <h1 class="font-bold lg:text-xl text-2xl mb-2">
                                                                                Booking Asets
                                                                            </h1>
                                                                        </div>

                                                                        <div class="flex flex-col justify-center items-center">
                                                                            <div
                                                                                class="w-full flex flex-col md:flex-row justify-around items-center gap-4">
                                                                                <div
                                                                                    class="flex flex-col justify-center items-center w-full">
                                                                                    <div class="flex flex-col justify-center items-center p-2  rounded mb-1"
                                                                                        style="width: fit-content">
                                                                                        <img src="{{ url('assets/img/asets_qr.jpg') }}"
                                                                                            alt="" style="width: 18rem">
                                                                                    </div>

                                                                                    @php
                                                                                        // Default values
                                                                                        $booking = 10000000;
                                                                                        $bookingLabel = 'Ten Million Rupiah';

                                                                                        // Check for space_attributes
                                                                                        if (!empty($spaceList['space_attributes'])) {
                                                                                            foreach ($spaceList['space_attributes'] as $attribute) {
                                                                                                if ($attribute['label'] === 'Booking' && !is_null($attribute['value'])) {
                                                                                                    $booking = (int) $attribute['value'];
                                                                                                }
                                                                                                if ($attribute['label'] === 'Booking Label' && !is_null($attribute['value'])) {
                                                                                                    $bookingLabel = $attribute['value'];
                                                                                                }
                                                                                            }
                                                                                        }

                                                                                        // Format as Indonesian currency
                                                                                        $formattedBooking = 'Rp. ' . number_format($booking, 0, ',', '.');
                                                                                    @endphp

                                                                                    <h1
                                                                                        class="font-bold text-2xl text-custom-primary mb-1">
                                                                                        {{ $formattedBooking }}
                                                                                    </h1>
                                                                                    <p class="text-gray-500 mb-2">({{ $bookingLabel }})
                                                                                    </p>

                                                                                    {{-- <h1
                                                                                        class="font-bold text-2xl text-custom-primary mb-1">
                                                                                        Rp.
                                                                                        10,000,000
                                                                                    </h1>
                                                                                    <p class="text-gray-500 mb-2">(Ten
                                                                                        Million Rupiah)
                                                                                    </p> --}}

                                                                                    @php
                                                                                        $invoiceNumber =
                                                                                            'INV-NF-' .
                                                                                            now()->format('dmY') .
                                                                                            '-' .
                                                                                            ($spaceList['space_name'] ??
                                                                                                'XXXX');
                                                                                        $month = now()->format('F');
                                                                                        $emailBody = rawurlencode(
                                                                                            "Dear ASETS Team,\n\nHello,\n\nFind attached $invoiceNumber payment confirmation. Thank you for your business.\n\nThanks,\n[Your Name]\n[Your Contact Information]\n---\n*Attachment:* [Scanned Confirmation - $invoiceNumber.html or PDF]\n---\n\nThis email and any files transmitted with it may contain confidential information and are intended solely for use by the individual to whom it is addressed. If you received this email in error, please notify the sender, do not disclose its contents to others, and delete it from your system.\n***************************************************************\nCommunication, including any attachments, may contain confidential information and is intended only for the individual or entity to whom it is addressed. Any review, dissemination, or copying of this communication by anyone other than the intended recipient is strictly prohibited. If you are not the intended recipient, please contact the sender by reply email and delete all copies of the original message.",
                                                                                        );
                                                                                    @endphp

                                                                                    <a id="pay-devices"
                                                                                        href="https://mail.google.com/mail/?view=cm&fs=1&to=finance.asets@gmail.com&su=Booking%20Order%20-%20Payment%20Confirmation&body={{ $emailBody }}"
                                                                                        target="_blank"
                                                                                        class="bg-purple-950 p-3 text-white rounded-lg w-full inline-block text-center font-semibold">
                                                                                        Payment Confirmation
                                                                                    </a>

                                                                                    <a id="pay-mobile"
                                                                                        href="mailto:finance.asets@gmail.com?subject=Booking%20Order%20-%20Payment%20Confirmation&body={{ $emailBody }}"
                                                                                        target="_blank"
                                                                                        class="bg-purple-950 p-3 text-white rounded-lg w-full inline-block text-center font-semibold">
                                                                                        Payment Confirmation
                                                                                    </a>
                                                                                </div>

                                                                                <div class="flex flex-col w-full">
                                                                                    <div class="flex items-center mb-2">
                                                                                        <hr class="flex-grow border-t border-gray-400">
                                                                                        <span
                                                                                            class="mx-4 font-bold text-custom-primary">How
                                                                                            To
                                                                                            Book</span>
                                                                                        <hr class="flex-grow border-t border-gray-400">
                                                                                    </div>

                                                                                    <ol class="text-justify text-xs md:text-sm space-y-2 ps-4"
                                                                                        style="list-style: decimal; padding-right: 10px;">
                                                                                        <li><strong>Choose Your
                                                                                                Location</strong> -
                                                                                            Select
                                                                                            the location that fits your
                                                                                            business needs
                                                                                        </li>
                                                                                        <li><strong>Make a Deposit</strong>
                                                                                            - After
                                                                                            choosing
                                                                                            your location, proceed to make
                                                                                            the required
                                                                                            deposit as instructed. </li>
                                                                                        <li><strong>Confirm Your
                                                                                                Payment</strong> -
                                                                                            Click
                                                                                            the “Payment Confirmation”
                                                                                            button and upload
                                                                                            your payment proof. </li>
                                                                                        <li><strong>Submit Your Application
                                                                                                Amount</strong>
                                                                                            - You will be
                                                                                            redirected to an email page.
                                                                                            Enter the
                                                                                            amount
                                                                                            you are applying for and make
                                                                                            sure to use an
                                                                                            active email, as it will become
                                                                                            your
                                                                                            “Business
                                                                                            ID Email” for the booking
                                                                                            process.</li>
                                                                                        <li><strong>Payment
                                                                                                Verification</strong> - Your
                                                                                            payment will be
                                                                                            verified within 2 business days
                                                                                            (maximum 48
                                                                                            hours). </li>
                                                                                        <li><strong>Receive Your Business
                                                                                                ID</strong> -
                                                                                            Once
                                                                                            your payment is
                                                                                            confirmed, you will receive an
                                                                                            email with
                                                                                            your
                                                                                            “Business ID” account details.
                                                                                        </li>
                                                                                        <li><strong>Congratulations!</strong>
                                                                                            - Your
                                                                                            location is now ready to use for
                                                                                            your
                                                                                            business.
                                                                                        </li>
                                                                                        <li><strong>First-Come,
                                                                                                First-Served</strong> - This
                                                                                            booking system operates on a
                                                                                            first-come, first-served basis.
                                                                                            Customers who complete their
                                                                                            payment earlier will be
                                                                                            prioritized in the process.
                                                                                        </li>
                                                                                    </ol>

                                                                                    <br>

                                                                                    <div class="flex flex-col items-center">
                                                                                        <p class=" text-gray-700">Having a
                                                                                            trouble? Contact our <br>

                                                                                            @php
                                                                                                $currentLogoWA1 = $logoWA1;
                                                                                                $currentLogoWA2 = $logoWA2;
                                                                                                $logoWA1 += 2;
                                                                                                $logoWA2 += 2;
                                                                                            @endphp

                                                                                            <a href="https://wa.me/62811102239?text={{ urlencode('Halo, saya ingin booking ruangan ' . $spaceList['space_name'] . ' pada lokasi SPBU ' . $listingDetail['data']['address']) }}"
                                                                                                target="_blank"
                                                                                                class="flex justify-center font-medium items-center gap-2 bg-[#03892B] rounded-full text-white py-2">
                                                                                                <span>
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                        width="25" height="25"
                                                                                                        viewBox="0 0 256 258">
                                                                                                        <defs>
                                                                                                            <linearGradient
                                                                                                                id="logosWhatsappIcon{{ $currentLogoWA1 }}"
                                                                                                                x1="50%" x2="50%"
                                                                                                                y1="100%" y2="0%">
                                                                                                                <stop offset="0%"
                                                                                                                    stop-color="#1faf38" />
                                                                                                                <stop offset="100%"
                                                                                                                    stop-color="#60d669" />
                                                                                                            </linearGradient>
                                                                                                            <linearGradient
                                                                                                                id="logosWhatsappIcon{{ $currentLogoWA2 }}"
                                                                                                                x1="50%" x2="50%"
                                                                                                                y1="100%" y2="0%">
                                                                                                                <stop offset="0%"
                                                                                                                    stop-color="#f9f9f9" />
                                                                                                                <stop offset="100%"
                                                                                                                    stop-color="#fff" />
                                                                                                            </linearGradient>
                                                                                                        </defs>
                                                                                                        <path
                                                                                                            fill="url(#logosWhatsappIcon{{ $currentLogoWA1 }})"
                                                                                                            d="M5.463 127.456c-.006 21.677 5.658 42.843 16.428 61.499L4.433 252.697l65.232-17.104a123 123 0 0 0 58.8 14.97h.054c67.815 0 123.018-55.183 123.047-123.01c.013-32.867-12.775-63.773-36.009-87.025c-23.23-23.25-54.125-36.061-87.043-36.076c-67.823 0-123.022 55.18-123.05 123.004" />
                                                                                                        <path
                                                                                                            fill="url(#logosWhatsappIcon{{ $currentLogoWA2 }})"
                                                                                                            d="M1.07 127.416c-.007 22.457 5.86 44.38 17.014 63.704L0 257.147l67.571-17.717c18.618 10.151 39.58 15.503 60.91 15.511h.055c70.248 0 127.434-57.168 127.464-127.423c.012-34.048-13.236-66.065-37.3-90.15C194.633 13.286 162.633.014 128.536 0C58.276 0 1.099 57.16 1.071 127.416m40.24 60.376l-2.523-4.005c-10.606-16.864-16.204-36.352-16.196-56.363C22.614 69.029 70.138 21.52 128.576 21.52c28.3.012 54.896 11.044 74.9 31.06c20.003 20.018 31.01 46.628 31.003 74.93c-.026 58.395-47.551 105.91-105.943 105.91h-.042c-19.013-.01-37.66-5.116-53.922-14.765l-3.87-2.295l-40.098 10.513z" />
                                                                                                        <path fill="#fff"
                                                                                                            d="M96.678 74.148c-2.386-5.303-4.897-5.41-7.166-5.503c-1.858-.08-3.982-.074-6.104-.074c-2.124 0-5.575.799-8.492 3.984c-2.92 3.188-11.148 10.892-11.148 26.561s11.413 30.813 13.004 32.94c1.593 2.123 22.033 35.307 54.405 48.073c26.904 10.609 32.379 8.499 38.218 7.967c5.84-.53 18.844-7.702 21.497-15.139c2.655-7.436 2.655-13.81 1.859-15.142c-.796-1.327-2.92-2.124-6.105-3.716s-18.844-9.298-21.763-10.361c-2.92-1.062-5.043-1.592-7.167 1.597c-2.124 3.184-8.223 10.356-10.082 12.48c-1.857 2.129-3.716 2.394-6.9.801c-3.187-1.598-13.444-4.957-25.613-15.806c-9.468-8.442-15.86-18.867-17.718-22.056c-1.858-3.184-.199-4.91 1.398-6.497c1.431-1.427 3.186-3.719 4.78-5.578c1.588-1.86 2.118-3.187 3.18-5.311c1.063-2.126.531-3.986-.264-5.579c-.798-1.593-6.987-17.343-9.819-23.64" />
                                                                                                    </svg></span> Customer
                                                                                                Care
                                                                                            </a>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <a href="https://wa.me/62811102239?text={{ urlencode('Permisi, saya ingin menyewa ruangan ' . $spaceList['space_name'] . ' pada lokasi SPBU ' . $listingDetail['data']['address']) }}"
                                                            target="_blank"
                                                            class="bg-purple-950 px-5 py-2 rounded-lg text-white">Contact</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <h3 class="font-bold text-center">Sorry. There are no spaces available.</h1>
                    @endif
                </div>
            </div>

            <!-- Add this section before the closing </section> tag -->
            <div class="lg:grid lg:grid-cols-1 lg:gap-5 p-6 rounded-lg bg-white mb-3">
                <div class="col-span-1">
                    <h1 class="font-bold mb-4">Other Available Spaces Listing</h1>

                    <div id="other-spaces-container" class="mt-4">
                        <div class="flex justify-center items-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-purple-950"></div>
                            <p class="ml-3 text-gray-600">Loading available spaces...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiper2", {
            spaceBetween: 10,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: swiper,
            },
        });

        var swiper2 = new Swiper(".mySwiper2", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",

            },
            thumbs: {
                swiper: swiper,
            },
        });
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingModal', () => ({
                isOpen: false, // Modal visibility state

                openModal() {
                    this.isOpen = true; // Buka modal
                },

                closeModal() {
                    this.isOpen = false; // Tutup modal
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {

            const address = @json($listingDetail['data']['address']);
            const coordinates = [@json($listingDetail['data']['lat'] ?? '0'),
                @json($listingDetail['data']['long'] ?? '0')
            ];
            console.log("Using coordinates:", coordinates);
            const map = L.map('map').setView(coordinates, 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker(coordinates).addTo(map);

            marker.bindPopup(`<b>Lokasi:</b><br>${address}`).openPopup();
        });

        // Other space listing implementation with fixed pagination
        $(document).ready(function () {
            // Store current page and limit as global variables
            let currentPage = 1;
            let currentLimit = 10;

            // Set initial minimum height
            $('#other-spaces-container').css('min-height', '400px');

            // Initial load
            loadSpaces(currentPage, currentLimit);

            // Main function to load spaces
            function loadSpaces(page, limit) {
                // Update the global variables
                currentPage = parseInt(page);
                currentLimit = parseInt(limit);

                // Show loading indicator with vertical centering
                $('#other-spaces-container').html(`
                                <div class="flex justify-center items-center py-8 h-[400px]">
                                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-purple-950"></div>
                                    <p class="ml-3 text-gray-600">Loading available spaces...</p>
                                </div>
                            `);

                // Fetch spaces from API
                $.ajax({
                    url: "https://pertare.asets.id/api/space-available",
                    method: "GET",
                    data: {
                        page: page,
                        limit: limit
                    },
                    success: function (response) {
                        // Add this before your AJAX call to confirm the endpoint
                        console.log("Fetching spaces from:",
                            "https://pertare.asets.id/api/space-available");
                        console.log("API Response:", response);
                        if (response && response.data && response.data.length > 0) {
                            renderOtherSpaces(response.data, response);
                        } else {
                            console.log("No spaces returned from API");
                            showNoSpacesMessage();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching spaces:", error);
                        console.log("Status:", status);
                        console.log("Response Text:", xhr.responseText);
                        console.log("Status Code:", xhr.status);
                        showErrorMessage();
                    }
                });
            }

            // Render the spaces - responsive card layout
            function renderOtherSpaces(spaces, response) {
                let html = `
                                <!-- Desktop view (table) -->
                                <div class="hidden md:block overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Jenis Ruangan</th>
                                                <th scope="col" class="px-6 py-3">Lokasi</th>
                                                <th scope="col" class="px-6 py-3">Harga</th>
                                                <th scope="col" class="px-6 py-3"></th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                spaces.forEach(function (space) {
                    html += `
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">${space.type ? space.type.name : '-'}</td>
                                        <td class="px-6 py-4">${space.listing && space.listing.listing_address ?
                            space.listing.listing_address.substring(0, 50) + (space.listing.listing_address.length > 50 ? '...' : '') : '-'}</td>
                                        <td class="px-6 py-4">${space.display_price || '-'}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="/detail-spbu/${space.listing ? space.listing.listing_id : ''}" class="text-white bg-purple-950 hover:bg-purple-800 font-medium text-xs px-4 py-2 rounded-md">
                                                Show Details
                                            </a>
                                        </td>
                                    </tr>`;
                });

                html += `
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile view (cards) -->
                                <div class="md:hidden grid grid-cols-1 gap-4">`;

                spaces.forEach(function (space) {
                    html += `
                                    <div class="bg-white p-4 rounded-lg border shadow-sm">
                                        <div class="mb-2">
                                            <h3 class="font-semibold text-purple-950">${space.type ? space.type.name : 'Not specified'}</h3>
                                        </div>
                                        <div class="mb-2 text-sm text-gray-600">
                                            <p class="mb-1"><span class="font-medium">Lokasi:</span> ${space.listing && space.listing.listing_address ?
                            space.listing.listing_address.substring(0, 100) + (space.listing.listing_address.length > 100 ? '...' : '') : '-'}</p>
                                            <p><span class="font-medium">Harga:</span> ${space.display_price || '-'}</p>
                                        </div>
                                        <div class="mt-3">
                                            <a href="/detail-spbu/${space.listing ? space.listing.listing_id : ''}" class="block w-full text-center text-white bg-purple-950 hover:bg-purple-800 font-medium text-sm px-4 py-2 rounded-md">
                                                Show Details
                                            </a>
                                        </div>
                                    </div>`;
                });

                html += `</div>`;

                // Set the HTML content
                $('#other-spaces-container').html(html);

                // Get pagination info from response
                let lastPage = 5; // Default fallback

                // Try to extract pagination info from different response formats
                if (response.meta && response.meta.pagination) {
                    lastPage = parseInt(response.meta.pagination.total_pages || response.meta.pagination
                        .last_page || 5);
                } else if (response.last_page) {
                    lastPage = parseInt(response.last_page);
                }

                // Add the pagination controls
                renderPagination(currentPage, lastPage, currentLimit);
            }

            // Create a separate function for rendering pagination
            function renderPagination(page, totalPages, limit) {
                page = parseInt(page);
                totalPages = parseInt(totalPages);
                limit = parseInt(limit);

                // Responsive pagination controls
                let paginationHtml = `
                                <div class="flex flex-col md:flex-row md:justify-between items-center mt-6 gap-4">
                                    <div class="text-sm text-gray-500 order-3 md:order-1">
                                        Showing page ${page} of ${totalPages}
                                    </div>

                                    <div class="flex justify-center order-1 md:order-2 w-full md:w-auto">
                                        <nav aria-label="Page navigation">
                                            <ul class="flex items-center -space-x-px h-8 text-sm">`;

                // Previous button
                const prevDisabled = page <= 1;
                paginationHtml += `
                                <li>
                                    <button type="button" data-page="${prevDisabled ? 1 : page - 1}"
                                    class="page-btn flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 ${prevDisabled ? 'opacity-50 cursor-not-allowed' : ''}"
                                    ${prevDisabled ? 'disabled' : ''}>
                                        <span class="sr-only">Previous</span>
                                        <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                        </svg>
                                    </button>
                                </li>`;

                // Page numbers - simplified for mobile
                const startPage = Math.max(1, page - 1);
                const endPage = Math.min(totalPages, startPage + 2); // Show fewer pages on mobile

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `
                                    <li>
                                        <button type="button" data-page="${i}"
                                            class="page-btn flex items-center justify-center px-3 h-8 leading-tight ${page === i ? 'text-white bg-purple-950 border border-purple-950' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'}">
                                            ${i}
                                        </button>
                                    </li>`;
                }

                // Next button
                const nextDisabled = page >= totalPages;
                paginationHtml += `
                                <li>
                                    <button type="button" data-page="${nextDisabled ? totalPages : page + 1}"
                                    class="page-btn flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 ${nextDisabled ? 'opacity-50 cursor-not-allowed' : ''}"
                                    ${nextDisabled ? 'disabled' : ''}>
                                        <span class="sr-only">Next</span>
                                        <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                        </svg>
                                    </button>
                                </li>`;

                paginationHtml += `
                                            </ul>
                                        </nav>
                                    </div>

                                    <div class="flex items-center order-2 md:order-3">
                                        <label for="itemsPerPage" class="mr-2 text-sm text-gray-500">Items:</label>
                                        <select id="itemsPerPage" class="bg-white border border-gray-300 text-gray-500 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500 block p-1">
                                            <option value="5" ${limit === 5 ? 'selected' : ''}>5</option>
                                            <option value="10" ${limit === 10 ? 'selected' : ''}>10</option>
                                            <option value="25" ${limit === 25 ? 'selected' : ''}>25</option>
                                            <option value="50" ${limit === 50 ? 'selected' : ''}>50</option>
                                        </select>
                                    </div>
                                </div>`;

                // Add at the end before appending to the container:
                paginationHtml += `
                                <!-- Extra spacing to maintain layout -->
                                <div class="py-4"></div>
                            `;

                // Append pagination to container
                $('#other-spaces-container').append(paginationHtml);

                // Attach event handlers
                // Page button clicks
                $('.page-btn').off('click').on('click', function () {
                    if ($(this).is('[disabled]')) return;

                    const pageNum = parseInt($(this).data('page'));
                    loadSpaces(pageNum, currentLimit);
                });

                // Items per page change
                $('#itemsPerPage').off('change').on('change', function () {
                    const newLimit = parseInt($(this).val());
                    loadSpaces(1, newLimit); // Reset to page 1 when changing limit
                });
            }

            // Update the error and empty states to maintain consistent height
            function showNoSpacesMessage() {
                $('#other-spaces-container').html(`
                                <div class="text-center py-8 min-h-[400px] flex flex-col justify-center">
                                    <p class="text-gray-500">No available spaces found.</p>
                                </div>
                            `);
            }

            function showErrorMessage() {
                $('#other-spaces-container').html(`
                                <div class="text-center py-8 min-h-[400px] flex flex-col justify-center">
                                    <p class="text-red-500">Error loading available spaces. Please try again later.</p>
                                </div>
                            `);
            }
        });
        window.onerror = function (message, source, lineno, colno, error) {
            console.error("JavaScript Error:", message);
            console.error("Source:", source);
            console.error("Line:", lineno);
            console.error("Column:", colno);
            console.error("Error object:", error);
            return false;
        };
        // Add this at the end of your script to check if everything loaded
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                console.log("==== PAGE LOAD CHECK ====");
                console.log("Map element exists:", document.getElementById('map') !== null);
                console.log("Other spaces container exists:", document.getElementById(
                    'other-spaces-container') !== null);

                // Check if Leaflet is properly initialized
                if (document.getElementById('map')) {
                    console.log("Map has leaflet class:", document.getElementById('map').classList.contains(
                        'leaflet-container'));
                }

                // Check network requests
                console.log("Check Network tab for any failed requests to:");
                console.log("- Map tiles (openstreetmap.org)");
                console.log("- API endpoint (pertare.asets.id)");
            }, 2000); // Check 2 seconds after page load
        });
    </script>


@endsection