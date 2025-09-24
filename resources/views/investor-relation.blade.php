@extends('layouts.app')

@section('title', 'Investor Relations')

@section('content')
    <div class="overflow-hidden"
        style="background-image: url('https://images.pexels.com/photos/936722/pexels-photo-936722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'); background-size: cover; background-position: center; background-repeat: no-repeat">
        <div class=" bg-black bg-opacity-70 w-full min-h-screen ">
            <div
                class="flex relative top-0 flex-col w-full py-16 lg:flex-row md:justify-between px-6 gap-9 items-center lg:py-32 lg:px-14">
                <div>
                    <x-steppers />
                </div>
                <div class="text-white lg:text-start text-center  flex flex-col justify-center">
                    <h1 class="text-6xl leading-relaxed font-bold">Investor Relations</h1>
                    <p class="leading-relaxed font-light">Stay informed about the latest developments from Asets. Our
                        investor relations section offers comprehensive insights into our business, financial performance,
                        and future growth opportunities.
                    </p>
                    {{-- CTA Button --}}
                    <div class="mt-5 flex gap-4 flex-col justify-center lg:justify-start md:flex-row">
                        <div class="">
                            <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20lebih%20lanjut"
                                target="_blank">
                                <x-button-primary>Explore More <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>

                                </x-button-primary>
                            </a>
                        </div>

                        <div class="">
                            <a href="/assets/files/company-profile.pdf" download="Company Profile Asets">
                                <x-button-primary>Compro <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
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
                                <x-card-sliders image="/assets/img/bg-bsi.jpeg" title="Partnership Announcement"
                                    description="We are thrilled to announce a strategic partnership between Asets and Bank Syariah Indonesia (BSI), creating new opportunities for entrepreneurs eager to scale their businesses..."
                                    qr="" showButton="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://img.freepik.com/free-photo/helix-bridge-with-marina-bay-building_1203-7094.jpg?t=st=1731114792~exp=1731118392~hmac=0f3d917e05cabbdba4a4249b8a77ac5ba14ac47f2c1113a0e22ad78ae0952267&w=900"
                                    title="Digital Transformation in Property Management: How Asets is Leading the Way"
                                    description="This service provides comprehensive management of commercial property assets, covering administration and day-to-day operations..."
                                    qr="" showButton="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://images.pexels.com/photos/4186017/pexels-photo-4186017.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Innovation for the Future of Indonesia's Economy"
                                    description="This partnership embodies the shared vision of Asets and BSI to build a stronger, more sustainable future for Indonesia's economy..."
                                    qr="" showButton="true" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
