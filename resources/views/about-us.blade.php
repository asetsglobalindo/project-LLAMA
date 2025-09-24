@extends('layouts.app')

@section('title', 'About Us')

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
                    <h1 class="text-6xl leading-relaxed font-bold">About Us</h1>
                    </h5>
                    <p class="leading-relaxed font-light text-justify">We specialize in creating innovative landscapes,
                        transforming
                        properties by offering a dependable investment and intuitive technology infrastructure platform. Our
                        solutions boost efficiency and streamline the complex process into a seamless ecosystem. Beyond
                        innovation, we cultivate extraordinary value, fuel global economic impact, and empower all brands to
                        connect with the markets they were destined to lead though end-to-end insights that empower your
                        business and beyond.
                    </p>
                </div>

                <div class="relative w-full lg:w-1/3  lg:flex flex-col ">
                    <div class="swiper-container" id="slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://images.pexels.com/photos/15636156/pexels-photo-15636156/free-photo-of-kota-jalan-bangunan-gedung.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Vision"
                                    description="To be the global leader in innovative and sustainable property investment infrastructure, empowering businesses and communities with seamless solutions that redefine growth and elevate economic and social prosperity worldwide."
                                    qr="" />
                            </div>

                            <div class="swiper-slide">
                                <x-card-sliders
                                    image="https://images.pexels.com/photos/29175971/pexels-photo-29175971/free-photo-of-pemandangan-udara-cakrawala-dan-jalan-raya-chicago.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    title="Mission"
                                    description="<ul class='list-disc text-xs px-3 text-gray-500 overflow-y-auto h-32'>
                                                    <li>Revolutionize Investment to Deliver cutting-edge, innovation and unlock unparalleled value.</li>
                                                    <li>Drive Global Growth, thru Connect businesses to strategic opportunities.</li>
                                                    <li>Empower Excellence by Provide precise insights and advanced technology and long-term impact.</li>
                                                    <li>Redefine Standards with making new hype in property innovation, blending environmental responsibility.</li>
                                                    <li>Create Global Benefits by Build meaningful value for businesses, society, and the environment, ensuring equitable growth and a positive global impact for all.</li>
                                                    </ul>"
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
            </div>
        </div>
    </div>

@endsection
