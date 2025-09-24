<div class="rounded-lg">
    <div class="relative -bottom-9 left-3">
        <span class="bg-[#067409] text-xs rounded-lg shadow py-1 px-4 text-white">{{ $spaceAvailable }}</span>
        {{-- <span class="bg-[#900606] text-xs rounded-lg shadow py-1 px-4 text-white">Tidak Tersedia</span> --}}
    </div>
    <div>
        <div>
            <div>
                <a href="{{ $detailImage }}" target="__blank">
                    <img class="rounded-2xl w-full h-72 object-cover" src="{{ $imageUrl }}" alt="image-spbu" />
                </a>
            </div>
        </div>

    </div>

    <div class="">
        <div class="">
            <div class="rounded-2xl  bg-white flex flex-col shadow-lg p-3 m-2 relative -top-16 h-[280px]">
                <div class="mb-2">
                    <x-rating-star />
                </div>
                <div>
                    <a href="#">
                        <h5 class="font-bold lg:text-sm text-gray-900 ">{{ $spbuName }}</h5>
                    </a>
                    <p class="mb-3 lg:text-sm font-normal text-red-600">{{ $areaName }} - {{ $codeArea }}</p>
                </div>

                <div class="flex mb-2 item">
                    <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-4 text-[#03892B]">
                            <path fill-rule="evenodd"
                                d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                clip-rule="evenodd" />
                        </svg>

                    </p>
                    <p class="text-xs leading-relaxed ml-2">{{ $spbuAddress }}</p>
                </div>

                <div class="flex flex-col mt-auto text-xs py-1 ">

                    <div class="text-xs flex mb-1">
                        <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-4 text-[#03892B]">
                                <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                <path fill-rule="evenodd"
                                    d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z"
                                    clip-rule="evenodd" />
                                <path
                                    d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />
                            </svg>


                        </p>
                        <p class="leading-relaxed ml-2 font-semibold text-[#03892B]">Rp{{ $prices }}
                            <span class="text-xs">/month</span>
                        </p>
                    </div>
                    <hr>

                    <div class="flex  justify-between items-end">
                        <div class="flex">
                            <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-4 text-[#03892B]">
                                    <path fill-rule="evenodd"
                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </p>
                            <p class="leading-relaxed ml-2 text-gray-500">Indoor</p>
                        </div>
                        <div class="flex ">
                            <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-4 text-[#03892B]">
                                    <path
                                        d="M19.006 3.705a.75.75 0 1 0-.512-1.41L6 6.838V3a.75.75 0 0 0-.75-.75h-1.5A.75.75 0 0 0 3 3v4.93l-1.006.365a.75.75 0 0 0 .512 1.41l16.5-6Z" />
                                    <path fill-rule="evenodd"
                                        d="M3.019 11.114 18 5.667v3.421l4.006 1.457a.75.75 0 1 1-.512 1.41l-.494-.18v8.475h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3v-9.129l.019-.007ZM18 20.25v-9.566l1.5.546v9.02H18Zm-9-6a.75.75 0 0 0-.75.75v4.5c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75H9Z"
                                        clip-rule="evenodd" />
                                </svg>

                            </p>
                            <p class="leading-relaxed ml-2 text-gray-500">{{ $spaceTotal }} Ruangan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
