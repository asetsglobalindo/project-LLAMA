@extends('layouts.app')

@section('title', 'Partnership')

@section('content')

    <div id="product" class="overflow-hidden"
        style="background-image: url('https://images.pexels.com/photos/936722/pexels-photo-936722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'); background-size: cover; background-position: center; background-repeat: no-repeat">
        <div class=" bg-black bg-opacity-70 w-full min-h-screen ">
            <div
                class="flex relative top-0 flex-col w-full py-16 lg:flex-row md:justify-between px-6 gap-9 items-center lg:py-32 lg:px-14">
                <div class="">
                    <x-steppers />
                </div>
                <div class="text-white lg:text-start text-center  flex flex-col justify-center ">
                    <h1 class="text-5xl leading-relaxed font-bold">Product and Solutions</h1>
                    <p class="leading-relaxed font-light text-justify">Unlock strategic growth opportunities by partnering
                        with Asets
                        premier property investment
                        infrastructure management provider.
                        Whether you’re a business owner, a landowner looking to optimize your property value, or an investor
                        seeking
                        strategic opportunities, Asets offers tailored solutions that align with your goals.
                        Our robust ecosystem empowers businesses by offering direct access to prime commercial assets,
                        innovative
                        financing options, and tailored market insights to ensure every venture reaches its highest
                        potential.
                    </p>
                </div>

                <div class="relative w-full lg:w-1/3  lg:flex flex-col ">
                    <div class="swiper-container" id="slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <x-card-sliders-partner
                                    image="https://images.pexels.com/photos/15636156/pexels-photo-15636156/free-photo-of-kota-jalan-bangunan-gedung.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Asets Commercial Space"
                                    description="Offer 188 strategic locations across Indonesia, Singapore, Qatar and the US. Our ecosystem provides comprehensive solutions to help your business grow & succeed."
                                    showButton="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders-partner
                                    image="https://images.pexels.com/photos/29175971/pexels-photo-29175971/free-photo-of-pemandangan-udara-cakrawala-dan-jalan-raya-chicago.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="ASETS Commercial Design"
                                    description="Empower your business with comprehensive design and consulting services. From branding to go-tomarket strategies, we'll elevate your brand and drive growth."
                                    showButton="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders-partner
                                    image="https://images.pexels.com/photos/273244/pexels-photo-273244.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="ASETS Property & Inventory Management Platform"
                                    description="Since 2018, ASETS has streamlined property and inventory management. Our innovative platform provides real-time visibility, reducing costs and improving efficiency."
                                    showButton="true" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders-partner
                                    image="https://images.pexels.com/photos/10546983/pexels-photo-10546983.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="ASETS Capital and Strategic Investment"
                                    description="ASETS drives sustainable growth through strategic investments. We acquire land, develop infrastructure, and form partnerships to optimize assets."
                                    showButton="true" />
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

    <section class="bg-white px-6 lg:px-14">

        <div id="platform" class="mb-16 mt-16">
            <div class="flex justify-center" style="margin: .5rem 0">
                <div
                    style="width: 100%; height: 30rem; border-radius: 25px; background-image: url('{{ asset('assets/img/partnership1.png') }}'); background-size: cover; background-position: center;">
                </div>
            </div>
            <div class="text-center m-16">
                <x-heading-title title="Property & Inventory Management Platform" />
            </div>

            <div class="flex flex-col mb-16">

                <div class="flex justify-center" style="margin: .5rem 0">
                    <div
                        style="width: 100%; height: 30rem; border-radius: 25px; background-image: url('{{ asset('assets/img/partnership2.png') }}'); background-size: cover; background-position: center;">
                    </div>
                </div>
            </div>

            <!-- Premium Asets Site-Intelligence section -->
            <div class="w-full mb-16">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-purple-900">Premium Asets Site-Intelligence</h3>
                    <p class="text-center mt-2 mx-auto max-w-2xl">
                        A premium site with e-catalogue customized design based on marketing research
                        with pro- features; virtual tour, ai based preferences, etc.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-6 justify-center">
                    <!-- Left price card -->
                    <div class="bg-purple-900 rounded-lg p-6 text-white flex-1 max-w-sm">
                        <h4 class="font-bold text-lg mb-4">Start From</h4>
                        <p class="font-bold text-xl mb-4">IDR 13,000,000/Location</p>
                        <p class="text-sm mb-6">360 Photoshoot</p>

                        <div class="space-y-4">
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Include Transportation</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Include Aerial Photo</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Max. 3 Hours Photo Session</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>In Indonesia Barat, Jawa, and Region, photo sessions must be organized with a minimum
                                    of <strong>3 locations</strong> concurrently.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>In Indonesia Tengah and Indonesia Timur Region, photo sessions must be organized with
                                    a minimum of <strong>5 locations</strong> concurrently.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Activity Tracker</span>
                            </div>
                        </div>

                        <button
                            class="mt-8 w-full text-black bg-white rounded-lg py-2 px-4 font-semibold flex items-center justify-center gap-2 border-2 border-purple-900">
                            <img src="{{ asset('assets/img/whatsapp.png') }}" alt="WhatsApp" class="h-5 w-5">
                            Get Started
                        </button>
                    </div>

                    <!-- Right price card -->
                    <div class="border-2 border-gray-300 rounded-lg p-6 flex-1 max-w-sm">
                        <h4 class="font-bold text-lg mb-4">Start From</h4>
                        <p class="font-bold text-xl mb-4">IDR 10,000,000/Year/Location</p>
                        <p class="text-sm mb-6">Yearly E-Catalog Platform on your dedicated web-based platform. Rental
                            Management System Subscription Fee:</p>

                        <div class="space-y-4">
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Content Management System</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Space Price Management System</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Inquiry Management</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Nearby Place Locator</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Availability Management</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Activity Tracker</span>
                            </div>
                        </div>

                        <button
                            class="mt-8 w-full text-black bg-white rounded-lg py-2 px-4 font-semibold flex items-center justify-center gap-2 border-2 border-purple-900">
                            <img src="{{ asset('assets/img/whatsapp.png') }}" alt="WhatsApp" class="h-5 w-5">
                            Get Started
                        </button>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 8rem">
                <div class="flex flex-col">

                    <div class="flex justify-center" style="margin: .5rem 0">
                        <div
                            style="width: 100%; height: 30rem; border-radius: 25px; background-image: url('{{ asset('assets/img/partnership3.png') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>

                </div>
            </div>
            <!-- Asets Management Service-Intelligence section -->
            <div class="w-full mb-16">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-purple-900">Asets Management Service-Intelligence</h3>
                    <p class="text-center mt-2 mx-auto max-w-2xl">
                        Asets Management Service is an webbased tools that helps property manager
                        automate back-office, financial, and administrative processes.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-6 justify-center">
                    <!-- Service Intelligence pricing card -->
                    <div class="bg-purple-900 rounded-lg p-6 text-white flex-1 max-w-sm">
                        <h4 class="font-bold text-lg mb-4">Start from</h4>
                        <p class="font-bold text-xl mb-4">IDR 13,000,000/Location</p>
                        <p class="text-sm mb-2">Add On-Use Admin to operate CMS and reporting
                            (Biweekly, Monthly, Quarterly, Annually)</p>

                        <div class="space-y-4 mt-6">
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Include Transportation</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Include Aerial Photo</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Max. 3 Hours Photo Session</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>In Indonesia Barat, Jawa, and Region, photo
                                    sessions must be organized with a
                                    minimum of <strong>3 locations</strong> concurrently.</span>
                            </div>
                        </div>

                        <button
                            class="mt-8 w-full text-black bg-white rounded-lg py-2 px-4 font-semibold flex items-center justify-center gap-2">
                            <img src="{{ asset('assets/img/whatsapp.png') }}" alt="WhatsApp" class="h-5 w-5">
                            Get Started
                        </button>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 8rem">
                <div class="flex flex-col">
                    <div class="flex justify-center" style="margin: .5rem 0">
                        <div
                            style="width: 100%; height: 30rem; border-radius: 25px; background-image: url('{{ asset('assets/img/partnership4.png') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Asets Management Service-Intelligence section -->
            <div class="w-full mb-16">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-purple-900">Asets Management Service-Intelligence</h3>
                    <p class="text-center mt-2 mx-auto max-w-2xl">
                        Asets Management Service is an webbased tools that helps property manager automate back-office,
                        financial, and administrative processes.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-6 justify-center">
                    <!-- Service Intelligence pricing card -->
                    <div class="bg-purple-900 rounded-lg p-6 text-white flex-1 max-w-sm">
                        <h4 class="font-bold text-lg mb-4">Start from</h4>
                        <p class="font-bold text-xl mb-4">IDR 13,000,000/Location</p>
                        <p class="text-sm mb-2">Add On-Use Admin to operate CMS and reporting (Biweekly, Monthly,
                            Quarterly, Annually)</p>

                        <div class="space-y-4 mt-6">
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Include Transportation</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Include Aerial Photo</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>Max. 3 Hours Photo Session</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <img src="{{ asset('assets/img/checkbox.png') }}" alt="Checkbox"
                                    class="flex-shrink-0 h-5 w-5 mt-0.5">
                                <span>In Indonesia Barat, Jawa, and Region, photo sessions must be organized with a minimum
                                    of <strong>3 locations</strong> concurrently.</span>
                            </div>
                        </div>

                        <button
                            class="mt-8 w-full text-black bg-white rounded-lg py-2 px-4 font-semibold flex items-center justify-center gap-2">
                            <img src="{{ asset('assets/img/whatsapp.png') }}" alt="WhatsApp" class="h-5 w-5">
                            Get Started
                        </button>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 8rem">
                <div class="flex flex-col">
                    <div class="flex justify-center" style="margin: .5rem 0">
                        <div
                            style="width: 100%; height: 30rem; border-radius: 25px; background-image: url('{{ asset('assets/img/partnership4.png') }}'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                </div>
            </div>
    </section>

@endsection
