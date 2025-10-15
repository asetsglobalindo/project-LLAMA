@extends('layouts.app')

@section('title', 'The Big Bang Of Commercial Universe Is Coming')

@section('content')


    <div class="overflow-hidden"
        style="background-image: url('https://images.pexels.com/photos/936722/pexels-photo-936722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'); background-size: cover; background-position: center; background-repeat: no-repeat">
        <div class=" bg-black bg-opacity-70 w-full min-h-screen ">
            <div
                class="flex relative top-0 flex-col w-full py-16 lg:flex-row md:justify-between px-6 gap-9 items-center lg:pt-32 lg:pb-8 lg:px-14">
                <div>
                    <div class="hidden lg:flex items-center flex-col justify-center text-white ">
    <div class="flex items-center flex-col relative">
        <div class="w-[35px] h-[35px]  backdrop-blur-sm  shrink-0 mx-[-1px]  p-1.5 flex items-center justify-center rounded-full bg-white text-gray-800">
            1
        </div>
        <div class="w-0.5 h-[56px] bg-white/10"></div>
    </div>
    <div class="flex items-center flex-col relative">
        <div class="w-[35px] h-[35px] backdrop-blur-sm  shrink-0 mx-[-1px]  p-1.5 flex items-center justify-center rounded-full bg-white/20">
            2
        </div>
        <div class="w-0.5 h-[56px] bg-white/10"></div>
    </div>
    <div class="flex items-center flex-col relative">
        <div class="w-[35px] h-[35px] backdrop-blur-sm  shrink-0 mx-[-1px]  p-1.5 flex items-center justify-center rounded-full bg-white/20">
            3
        </div>
        <div class="w-0.5 h-[56px] bg-white/10"></div>
    </div>
    <div class="flex items-center flex-col relative">
        <div class="w-[35px] h-[35px] backdrop-blur-sm  shrink-0 mx-[-1px]  p-1.5 flex items-center justify-center rounded-full bg-white/20">
            4
        </div>
        <div class="w-0.5 h-[56px]  bg-white/10"></div>
    </div>
    <div class="flex items-center flex-col relative">
        <div class="w-[35px] h-[35px] backdrop-blur-sm  shrink-0 mx-[-1px] p-1.5 flex items-center justify-center rounded-full bg-white/20">
            5
        </div>
        <div class="w-0.5 h-[56px]  bg-white/10"></div>
    </div>
    <div class="flex items-center flex-col relative">
        <div class="w-[35px] h-[35px] backdrop-blur-sm  shrink-0 mx-[-1px] p-1.5 flex items-center justify-center rounded-full bg-white/20">
            6
        </div>
    </div>

</div>
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
                    
                    <div class="mt-5 flex gap-4 flex-col justify-center lg:justify-start md:flex-row">
                        <div class="">
                            <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20lebih%20lanjut" target="_blank">
                                <button type="button" class=" bg-white/20 hover:bg-white/30 w-full backdrop-blur-sm leading-relaxed text-white text-sm flex px-6 py-4 items-center justify-center gap-2 shadow rounded-xl" fdprocessedid="9zc78r">
    Site Survey <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"></path>
                                    </svg>
</button>
                            </a>
                        </div>

                        <div class="">
                            <a href="https://www.instagram.com/asets.co/" target="_blank" rel="noopener noreferrer">
                                <button type="button" class=" bg-white/20 hover:bg-white/30 w-full backdrop-blur-sm leading-relaxed text-white text-sm flex px-6 py-4 items-center justify-center gap-2 shadow rounded-xl" fdprocessedid="3d6ipi">
    Explore Insight <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                    </svg>
</button>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="relative w-full lg:w-1/3  lg:flex flex-col ">
                    <div class="swiper-container swiper-initialized swiper-horizontal swiper-backface-hidden" id="slider">
                        <div class="swiper-wrapper" id="swiper-wrapper-6dae1d74ab95b6ee" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-796px, 0px, 0px); transition-delay: 0ms;">
                            

                            

                            
                        <div class="swiper-slide swiper-slide-next" style="width: 389px; margin-right: 9px;" role="group" aria-label="1 / 3" data-swiper-slide-index="0">
                                <div class="lg:min-w-[280px] min-w-full h-fit sm:h-80 bg-white rounded-2xl">
    <div class="">

        <img src="https://images.pexels.com/photos/10546983/pexels-photo-10546983.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=1260&amp;h=750&amp;dpr=2" alt="img-card" class="w-full h-36 object-cover rounded-t-2xl">

        <div class=" flex flex-col items-start h-full py-2 px-4 gap-2">
            <div class="">
                <h2 class="text-sm font-semibold text-purple-950">Commercial Assets Finder</h2>
                <p class=" mt-2 mb-3 text-xs leading-relaxed text-gray-600 text-justify">This service assists businesses and investors in locating potential and strategic commercial assets, such as office spaces, retail locations, or industrial zones...</p>
            </div>

            <div class="flex justify-between items-end mt-auto w-full">
                                    <div>
                        <a href="https://www.instagram.com/asets.co/" target="_blank" rel="noopener noreferrer" class="flex text-sm gap-2 items-center text-gray-400 hover:text-gray-500">
                            Read More
                            <i class="bg-white/15 hover:bg-white/30  p-2 rounded-full w-8 h-8 flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                                </svg>
                            </i>
                        </a>
                    </div>
                
                                    <div>
                        <img src="/assets/img/qr-instagram.png" alt="Qr-codes" class="w-9 h-9 mt-4 rounded">
                    </div>
                            </div>
        </div>
    </div>
</div>
                            </div><div class="swiper-slide swiper-slide-prev" style="width: 389px; margin-right: 9px;" role="group" aria-label="2 / 3" data-swiper-slide-index="1">
                                <div class="lg:min-w-[280px] min-w-full h-fit sm:h-80 bg-white rounded-2xl">
    <div class="">

        <img src="https://images.pexels.com/photos/273244/pexels-photo-273244.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=1260&amp;h=750&amp;dpr=2" alt="img-card" class="w-full h-36 object-cover rounded-t-2xl">

        <div class=" flex flex-col items-start h-full py-2 px-4 gap-2">
            <div class="">
                <h2 class="text-sm font-semibold text-purple-950">Assets Property Management</h2>
                <p class=" mt-2 mb-3 text-xs leading-relaxed text-gray-600 text-justify">This service provides comprehensive management of commercial property assets, covering administration and day-to-day operations...</p>
            </div>

            <div class="flex justify-between items-end mt-auto w-full">
                                    <div>
                        <a href="https://www.instagram.com/asets.co/" target="_blank" rel="noopener noreferrer" class="flex text-sm gap-2 items-center text-gray-400 hover:text-gray-500">
                            Read More
                            <i class="bg-white/15 hover:bg-white/30  p-2 rounded-full w-8 h-8 flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                                </svg>
                            </i>
                        </a>
                    </div>
                
                                    <div>
                        <img src="/assets/img/qr-instagram.png" alt="Qr-codes" class="w-9 h-9 mt-4 rounded">
                    </div>
                            </div>
        </div>
    </div>
</div>
                            </div><div class="swiper-slide swiper-slide-active" style="width: 389px; margin-right: 9px;" role="group" aria-label="3 / 3" data-swiper-slide-index="2">
                                <div class="lg:min-w-[280px] min-w-full h-fit sm:h-80 bg-white rounded-2xl">
    <div class="">

        <img src="https://images.pexels.com/photos/4186017/pexels-photo-4186017.jpeg?auto=compress&amp;cs=tinysrgb&amp;w=1260&amp;h=750&amp;dpr=2" alt="img-card" class="w-full h-36 object-cover rounded-t-2xl">

        <div class=" flex flex-col items-start h-full py-2 px-4 gap-2">
            <div class="">
                <h2 class="text-sm font-semibold text-purple-950">Assets Capital Investment</h2>
                <p class=" mt-2 mb-3 text-xs leading-relaxed text-gray-600 text-justify">This service focuses on offering capital investment opportunities for business and landowners to enhance their business potential...</p>
            </div>

            <div class="flex justify-between items-end mt-auto w-full">
                                    <div>
                        <a href="https://www.instagram.com/asets.co/" target="_blank" rel="noopener noreferrer" class="flex text-sm gap-2 items-center text-gray-400 hover:text-gray-500">
                            Read More
                            <i class="bg-white/15 hover:bg-white/30  p-2 rounded-full w-8 h-8 flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                                </svg>
                            </i>
                        </a>
                    </div>
                
                                    <div>
                        <img src="/assets/img/qr-instagram.png" alt="Qr-codes" class="w-9 h-9 mt-4 rounded">
                    </div>
                            </div>
        </div>
    </div>
</div>
                            </div></div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>

                    <div class="flex lg:justify-start justify-around gap-5 mt-4">
                        <button id="prev" class="backdrop-blur-md bg-white/20 hover:bg-white/30 border-0 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center" fdprocessedid="dkxicm">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-11">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                            </svg>
</button>
                        <button id="next" class="backdrop-blur-md bg-white/20 hover:bg-white/30 border-0 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center" fdprocessedid="0b8q7">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-11">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                            </svg>
</button>
                    </div>
                </div>
            </div>

            {{-- AI Chat Widget - Di dalam container hero section --}}
            <div class="w-full px-6 lg:px-14 pt-0 pb-8">
                <x-home.aichat />
            </div>
        </div>

    </div>

    <br>

    <section class="bg-white px-6 lg:px-14">
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