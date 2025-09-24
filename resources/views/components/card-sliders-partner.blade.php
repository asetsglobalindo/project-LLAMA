<div class="lg:min-w-[280px] min-w-full h-fit sm:h-80 bg-white rounded-2xl">
    <div class="">

        <img src="{{ $image }}" alt="img-card" class="w-full h-36 object-cover rounded-t-2xl">

        <div class=" flex flex-col items-start h-full py-2 px-4 gap-2">
            <div class="">
                <h2 class="text-sm font-semibold text-purple-950">{{ $title }}</h2>
                <p class="mt-2 mb-3 text-xs leading-relaxed text-gray-600 text-justify">{!! $description !!}</p>
            </div>

            <div class="flex justify-between items-end mt-auto w-full">
                @if ($showButton ?? false)
                    <div>
                        <a href="https://wa.me/62811102239/" target="_blank" rel="noopener noreferrer"
                            class="flex text-sm gap-2 items-center text-gray-400 hover:text-gray-500">
                            Contact Us
                            <i class="bg-white/15 hover:bg-white/30  p-2 rounded-full w-8 h-8 flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </i>
                        </a>
                    </div>
                @endif

                @if ($showQR ?? false)
                    <div>
                        <img src="{{ $qr }}" alt="Qr-codes" class="w-9 h-9 mt-4 rounded">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>