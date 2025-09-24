@extends('layouts.app')

@section('title', 'Our Location')

@section('content')
    {{-- <div class="flex flex-col  lg:flex-row lg:justify-between lg:px-0 gap-12 items-center ">
        <div>
            <x-steppers />
        </div>
        <div class="text-white text-center lg:text-start flex flex-col justify-center ">
            <h1 class="text-5xl mb-3 leading-normal font-semibold">Our Global Presence</h1>
            <p class="leading-relaxed">Our assets operate across strategic locations worldwide. From Indonesia to the United
                States, our reach continues to expand, allowing us to provide global expertise along with local knowledge.
            </p>

            <div class="mt-6 lg:w-1/3">
                <a href="https://maps.app.goo.gl/aFqTPsxi5jZ8ZCfS8" target="_blank">
                    <x-button-primary>Explore Us </x-button-primary>
                </a>
            </div>
        </div>

        <div class="relative w-full overflow-hidden ">
            <div class="h-full">
                <div class="">
                    <img src="/assets/img/world-map-bigs.png" alt="world-map" class="rounded-2xl bg-center ">
                </div>
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
                    <h1 class="text-5xl leading-relaxed font-bold">Our Global Presence</h1>
                    <p class="leading-relaxed font-light">Our assets operate across strategic locations worldwide. From
                        Indonesia to the United States, our reach continues to expand, allowing us to provide global
                        expertise along with local knowledge.
                    </p>
                    {{-- CTA Button --}}
                    <div class="mt-5 flex gap-4 flex-col justify-center lg:justify-start md:flex-row">
                        <div class="">
                            <a href="https://maps.app.goo.gl/aFqTPsxi5jZ8ZCfS8" target="_blank">
                                <x-button-primary>Explore Us</x-button-primary>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="relative w-full overflow-hidden ">
                    <div class="h-full">
                        <div class="">
                            <img src="/assets/img/world-map-bigs.png" alt="world-map" class="rounded-2xl object-cover ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
