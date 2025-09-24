@extends('layouts.app')

@section('title', 'The Big Bang Of Commercial Universe Is Coming')

@section('content')

    <div class="overflow-hidden"
        style="background-image: url('https://images.pexels.com/photos/936722/pexels-photo-936722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'); background-size: cover; background-position: center; background-repeat: no-repeat">
        <div class=" bg-black bg-opacity-70 w-full min-h-screen ">
            <div
                class="flex relative top-0 flex-col w-full py-16 lg:flex-row md:justify-between px-6 gap-9 items-center lg:py-32 lg:px-14">
                <div>
                    <x-steppers />
                </div>
                <div class="text-white lg:text-start flex flex-col justify-center">
                    <h5 class="mb-1 leading-relaxed font-semibold">Welcome To</h5>
                    <h1 class="text-6xl leading-relaxed font-bold">Asets</h1>
                    <h5 class="mb-2">Asets - <span class="italic font-semibold">Empowering Your Business and Beyond</span>
                    </h5>
                    <p class="lg:leading-relaxed font-light text-justify" x-text="translations.description">Asets, a global
                        leader in
                        Property Investment Management,
                        introduces
                        Indonesia's first
                        AI-powered e-commerce platform to support businesses at every level. Through the Asets Virtual
                        Intelligence
                        System (AVIS), we connect millions of MSMEs to a network of strategic assets and financial
                        institutions,
                        providing essential tools to drive growth and online success. With Asets, unlock new opportunities
                        to
                        elevate your business.
                    </p>
                    {{-- CTA Button --}}
                    <div class="mt-5 flex gap-4 flex-col justify-center lg:justify-start md:flex-row">
                        <div class="">
                            <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20lebih%20lanjut"
                                target="_blank">
                                <x-button-primary>Site Survey <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                    </svg>
                                </x-button-primary>
                            </a>
                        </div>

                        <div class="">
                            <a href="https://www.instagram.com/asets.co/" target="_blank" rel="noopener noreferrer">
                                <x-button-primary>Explore Insight <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </x-button-primary>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="relative w-full lg:w-1/3  lg:flex flex-col ">
                    <div class="swiper-container" id="slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://images.pexels.com/photos/10546983/pexels-photo-10546983.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Commercial Assets Finder"
                                    description="This service assists businesses and investors in locating potential and strategic commercial assets, such as office spaces, retail locations, or industrial zones..."
                                    qr="/assets/img/qr-instagram.png" showButton="true" showQR="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://images.pexels.com/photos/273244/pexels-photo-273244.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Assets Property Management"
                                    description="This service provides comprehensive management of commercial property assets, covering administration and day-to-day operations..."
                                    qr="/assets/img/qr-instagram.png" showButton="true" showQR="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://images.pexels.com/photos/4186017/pexels-photo-4186017.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Assets Capital Investment"
                                    description="This service focuses on offering capital investment opportunities for business and landowners to enhance their business potential..."
                                    qr="/assets/img/qr-instagram.png" showButton="true" showQR="true" />
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
            </div>
        </div>
    </div>

    <br>

    <section class="bg-white px-6 lg:px-14">

        {{-- FILTERED SECTION AMOUNT --}}
        <div class="relative flex -top-20">
            <x-home.aichat />
        </div>
        {{-- GALERY IMAGE --}}
        <div class="mb-24">
            <x-home.galery-image />
        </div>
        {{-- HAKITA SECTION --}}
        <div class="my-24">
            <x-home.hakita />
        </div>
        {{-- SPBU CARD LOCATION SECTION --}}
        <div class="my-24">
            <x-home.spbu-location-component />
        </div>
        {{-- SPACE AVAILABLE SECTION --}}
        <div class="my-24">
            <x-home.space-available-component :commercialLocations="$commercialLocations" />
        </div>
        {{-- ASETS ESG --}}
        <div class="my-24">
            <x-home.asets-esg-component />
        </div>
        {{-- ASETS CURATED SECTIONS --}}
        <div class="my-24">
            <x-home.asets-curated-space-component :dataNonPMS="$dataNonPMS" />
        </div>

        {{-- ARTICLE SECTIONS --}}
        <div class="">
            <x-home.latest-news-component :articles="$articles" />
        </div>

    </section>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('imageModal', () => ({
                    isOpen: false, // Modal visibility state

                    openModal() {
                        this.isOpen = true; // Buka modal
                    },

                    closeModal() {
                        this.isOpen = false; // Tutup modal
                    }
                }));
            });
        </script>
    @endpush

@endsection