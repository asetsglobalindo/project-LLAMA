@extends('layouts.app')

@section('title', 'Asets Calculation')

@section('content')
    <div class="overflow-hidden">
        <div class="lg:py-24 py-14 px-6 lg:px-14">
            <div x-data="{ loading: false }">

            {{-- FILTERED SECTION AMOUNT --}}
            <div class="relative flex">
                <x-filter-calculator />
            </div>

                {{-- <form method="GET" action="{{ route('filter-space') }}" class="flex flex-col lg:flex-row gap-4"
                    @submit="loading = true">
                    <div>
                        <label for="min_amount" class="block">Min Amount</label>
                        <div class="flex items-center">
                            <span class="bg-gray-200 text-gray-400 px-3 py-2 border border-gray-300 rounded-l-lg">Rp</span>
                            <input type="text" name="min_amount" id="min_amount"
                                class="border border-gray-300 p-2 flex-1 rounded-r-lg" placeholder="e.g 210000"
                                value="{{ request('min_amount') }}">
                        </div>
                    </div>


                    <div>
                        <label for="max_amount" class="block text-gray-600">Max Amount</label>
                        <div class="flex items-center">
                            <span class="bg-gray-200 px-3 text-gray-400 py-2 border border-gray-300 rounded-l-lg">Rp</span>
                            <input type="text" name="max_amount" id="max_amount"
                                class="border border-gray-300 rounded-r-lg p-2 flex-1" placeholder="e.g 3000000"
                                value="{{ request('max_amount') }}">
                        </div>
                    </div>


                    <div>
                        <label for="area" class="block ">Area</label>
                        <input type="text" name="area" id="area"
                            class="border p-2 w-full border-gray-300 rounded-lg" placeholder="eg. jakarta pusat"
                            value="{{ request('area') }}">
                    </div>

                    <div>
                        <label for="min_luas" class="block">Min Size</label>
                        <input type="text" name="min_luas" id="min_luas" class="border p-2 rounded-lg border-gray-300 w-full"
                            placeholder="... m2" value="{{ request('min_luas') }}">
                    </div>
            
                    <div>
                        <label for="max_luas" class="block">Max Size</label>
                        <input type="text" name="max_luas" id="max_luas" class="border p-2 rounded-lg border-gray-300 w-full"
                            placeholder="... m2" value="{{ request('max_luas') }}">
                    </div>

                    <div class="flex text-center lg:items-end">
                        <button type="submit"
                            class="bg-custom-primary flex justify-center text-white px-4 py-2 rounded-lg w-full"
                            :disabled="loading">
                            <template x-if="!loading">
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5l-1.5 1.5l-5-5v-.79l-.27-.27A6.52 6.52 0 0 1 9.5 16A6.5 6.5 0 0 1 3 9.5A6.5 6.5 0 0 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14S14 12 14 9.5S12 5 9.5 5" />
                                </svg>
                            </template>
                            <template x-if="loading">
                                <div class="animate-spin rounded-full h-5 w-5 border-t-2 border-white border-opacity-50">
                                </div>
                            </template>
                        </button>
                    </div>
                </form> --}}


                <h2 class="text-lg font-bold text-gray-500 mt-8 mb-4">Your space results</h2>
                @if ($assets->isEmpty())
                    <p class="text-gray-500">No space founded.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-cols-fr">
                        @foreach ($assets as $asset)
                            <div class="text-sm p-3 flex flex-col w-full h-full space-y-2 border rounded-lg shadow">
                                @if (!empty($asset['photos']))
                                <a href="/detail-spbu/{{ $asset['listing']['listing_id'] }}">
                                    <img src="{{ $asset['photos'][0] }}" alt="photos-spaces"
                                        class="mb-4 w-full h-52 object-cover rounded-lg">
                                </a>
                                @else
                                    <img src="https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain"
                                        alt="default-photo" class="mb-4 w-full h-52 object-cover rounded">
                                @endif
                                <div class="flex items-center justify-between">
                                    <a href="/detail-spbu/{{ $asset['listing']['listing_id'] }}"
                                        class="text-custom-primary hover:text-purple-700 hover:underline"><strong>{{ $asset['type']['name'] }}</strong></a>

                                    <p class="text-xs text-white bg-custom-primary px-3 py-1 rounded-lg ">
                                        {{ $asset['available'] == 1 ? 'Available' : 'Leased' }}</p>
                                </div>

                                <p><strong>{!! html_entity_decode($asset['display_price']) !!}</strong></p>
                                <p class="line-clamp-1">{{ $asset['size_sqm'] }}m² |     @php
                                    $addressParts = explode(',', $asset['listing']['listing_address']);
                                    $addressParts = array_map('trim', $addressParts);
                                    $cityName = end($addressParts) === 'Indonesia' 
                                        ? $addressParts[count($addressParts) - 2] 
                                        : end($addressParts);
                                @endphp
                                {{ $cityName ?? 'No city data' }}</p>
                                <p class="text-gray-500 text-sm line-clamp-2">
                                    {{ $asset['listing']['listing_address'] }}
                                </p>
                                <br>
                                <div class="flex text-center mt-auto">
                                    <a href="https://wa.me/62811102239?text={{ urlencode('Permisi, saya ingin menyewa ruangan ' . $asset['name'] . ' pada lokasi SPBU ' . $asset['listing']['listing_address']) }}"
                                        class="bg-custom-primary text-white px-4 w-full py-2 rounded hover:bg-purple-800"
                                        target="_blank">Contact</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>

    @endsection
