@extends('layouts.app')

@section('title', 'Asets Calculation')

@section('content')
    <div class="overflow-hidden">
        <div class="lg:py-24 py-14 px-6 lg:px-14">
            <div x-data="{ loading: false }">

                {{-- SEARCH SECTION --}}
                <div class="relative flex">
                    <x-home.aichat />
                </div>

                <h2 class="text-lg font-bold text-gray-500 mt-8 mb-4">Your space results</h2>

                {{-- RESULT --}}
                <div class="mb-16">
                    <div x-data="paginationComponentSpace()" x-init="fetchData()">

                        <div x-show="isLoading" class="text-center py-4">
                            <span class="flex gap-2 justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#610086" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2">
                                        <path stroke-dasharray="16" stroke-dashoffset="16" d="M12 3c4.97 0 9 4.03 9 9">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s"
                                                values="16;0" />
                                            <animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite"
                                                type="rotate" values="0 12 12;360 12 12" />
                                        </path>
                                        <path stroke-dasharray="64" stroke-dashoffset="64" stroke-opacity="0.3"
                                            d="M12 3c4.97 0 9 4.03 9 9c0 4.97 -4.03 9 -9 9c-4.97 0 -9 -4.03 -9 -9c0 -4.97 4.03 -9 9 -9Z">
                                            <animate fill="freeze" attributeName="stroke-dashoffset" dur="1.2s"
                                                values="64;0" />
                                        </path>
                                    </g>
                                </svg></span>
                            Loading please wait ...
                        </div>

                        <div x-show="!isLoading">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4  gap-4 auto-rows-fr ">
                                <template x-if="spaceData.length > 0">
                                    <template x-for="spaceList in spaceData">
                                        <div class="rounded-lg flex-col flex">
                                            <div class="relative px-4 flex justify-between top-9 left-1 z-10">
                                                <span
                                                    class="bg-custom-primary text-xs rounded shadow py-1 px-4 text-white">Available</span>

                                                @auth
                                                    <a href="#" class="love-button">
                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="gray" viewBox="0 0 24 24" stroke="white"
                                                            stroke-width="2">
                                                            <path
                                                                d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a href="{{ route('login-form') }}" class="love-button">
                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" fill="gray" viewBox="0 0 24 24" stroke="white"
                                                            stroke-width="2">
                                                            <path
                                                                d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                                                        </svg>
                                                    </a>
                                                @endauth
                                            </div>

                                            <!-- IMAGES -->
                                            <div class="relative">
                                                <div class="rounded-2xl overflow-hidden">
                                                    <img class="rounded-2xl w-full h-72 object-cover cursor-pointer"
                                                        :src="spaceList.image || 'https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain'"
                                                        alt="Thumbnail Image" />
                                                </div>
                                            </div>

                                            <div class="">
                                                <div
                                                    class="rounded-2xl bg-white flex flex-col shadow-lg p-3 m-2 -top-14 relative min-h-56">
                                                    <div class="flex flex-col text-sm py-1 space-y-3">
                                                        <div>
                                                            <a :href="`/detail-spbu/${spaceList.id}`">
                                                                <h5 class="font-bold lg:text-sm text-custom-primary line-clamp-2"
                                                                    x-text="spaceList.address">
                                                                </h5>
                                                            </a>
                                                            <p class="mb-3 mt-1 lg:text-xs font-normal text-gray-700 line-clamp-1"
                                                                x-text="(() => {
                                                                                        const parts = spaceList.address.split(',').map(part => part.trim());
                                                                                        return parts[parts.length - 1] === 'Indonesia'
                                                                                            ? parts[parts.length - 3]
                                                                                            : parts[parts.length - 2] || spaceList.city;
                                                                                    })()">
                                                            </p>
                                                        </div>

                                                        <div class="flex flex-col w-full h-full space-y-1">
                                                            <div class="flex">
                                                                <p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="currentColor"
                                                                        class="size-5 text-custom-primary">
                                                                        <path
                                                                            d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                                                        <path fill-rule="evenodd"
                                                                            d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z"
                                                                            clip-rule="evenodd" />
                                                                        <path
                                                                            d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />
                                                                    </svg>
                                                                </p>
                                                                <p class="leading-relaxed ml-2 font-semibold text-custom-primary"
                                                                    x-html="spaceList.price + (spaceList.priceType == 'sqm' ? ' / m<sup>2</sup> / Month' : ' / Month')">
                                                                </p>
                                                            </div>

                                                            <div class="flex">
                                                                <p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                        height="20" viewBox="0 0 24 24">
                                                                        <path fill="#551275"
                                                                            d="M21 19h2v2H1v-2h2V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v15h2V9h3a1 1 0 0 1 1 1zM7 11v2h4v-2zm0-4v2h4V7z" />
                                                                    </svg>
                                                                </p>
                                                                <p class="leading-relaxed ml-2 font-semibold text-custom-primary"
                                                                    x-text="spaceList.type">
                                                                </p>
                                                            </div>

                                                            <div class="flex ">
                                                                <p><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                        height="20" viewBox="0 0 15 15">
                                                                        <path fill="#3d2369" fill-rule="evenodd"
                                                                            d="M11.5 3.05a.45.45 0 0 1 .45.45v4a.45.45 0 0 1-.9 0V4.586L4.586 11.05H7.5a.45.45 0 0 1 0 .9h-4a.45.45 0 0 1-.45-.45v-4a.45.45 0 1 1 .9 0v2.914l6.464-6.464H7.5a.45.45 0 1 1 0-.9z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </p>
                                                                <p class="leading-relaxed ml-2 font-semibold text-custom-primary"
                                                                    x-text="`${spaceList.size} /m2`"></p>
                                                            </div>

                                                            <div class="flex mt-auto">
                                                                <a :href="spaceList.source === 'curated'
                                                                            ? 'https://wa.me/62811102239?text=' + encodeURIComponent('Halo, saya ingin bertanya mengenai lokasi: ' + (spaceList.address || 'Asets Curated Space'))
                                                                            : spaceList.source === 'pms'
                                                                                ? `/detail-spbu/${spaceList.id}`
                                                                                : spaceList.source === 'private_sector'
                                                                                    ? `/detail-listing/${spaceList.id}`
                                                                                    : '#'"
                                                                    class="w-full text-center text-white bg-purple-950 hover:bg-purple-800 focus:ring-4 font-medium rounded-lg text-xs px-3 py-2"
                                                                    x-text="spaceList.source === 'curated' ? 'Contact' : 'Detail'">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>

                            <template x-if="!isLoading && spaceData.length === 0">
                                <div class="text-center mb-10 text-gray-500 font-semibold">
                                    No spaces found
                                </div>
                            </template>

                            <div class="flex justify-center lg:space-x-2 overflow-auto">
                                <!-- First Page Button -->
                                <button data-tooltip-target="tooltip-first-page" @click="goToPage(1)"
                                    :disabled="currentPage === 1" class="px-3 py-1 text-xs lg:text-base rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="#610086"
                                            d="m4.836 12l6.207 6.207l1.414-1.414L7.664 12l4.793-4.793l-1.414-1.414zm5.65 0l6.207 6.207l1.414-1.414L13.314 12l4.793-4.793l-1.414-1.414z" />
                                    </svg>
                                </button>

                                <div id="tooltip-first-page" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-custom-primary rounded-lg shadow-sm opacity-0 tooltip">
                                    Go to first page
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <!-- Previous Page Button -->
                                <button @click="prevPage" :disabled="currentPage === 1"
                                    class="px-3 py-1 text-xs lg:text-base rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="none" stroke="#610086" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m14 7l-5 5m0 0l5 5" />
                                    </svg>
                                </button>

                                <!-- Page Numbers -->
                                <template x-for="page in totalPagesArray" :key="page">
                                    <button :class="{
                                                                    'bg-custom-primary text-white rounded-lg': page === currentPage,
                                                                    '': page !== currentPage
                                                                }" @click="goToPage(page)"
                                        class="px-3 py-1 text-xs lg:text-base rounded">
                                        <span x-text="page"></span>
                                    </button>
                                </template>

                                <!-- Next Page Button -->
                                <button @click="nextPage" :disabled="currentPage === totalPages"
                                    class="px-3 py-1 text-xs lg:text-base rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="20" viewBox="0 0 12 24">
                                        <path fill="#610086" fill-rule="evenodd"
                                            d="M10.157 12.711L4.5 18.368l-1.414-1.414l4.95-4.95l-4.95-4.95L4.5 5.64l5.657 5.657a1 1 0 0 1 0 1.414" />
                                    </svg>
                                </button>

                                <!-- Last Page Button -->
                                <button data-tooltip-target="tooltip-last-page" @click="goToPage(totalPages)"
                                    :disabled="currentPage === totalPages" class="px-3 py-1 text-xs lg:text-base rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="#610086"
                                            d="m19.164 12l-6.207-6.207l-1.414 1.414L16.336 12l-4.793 4.793l1.414 1.414zm-5.65 0L7.307 5.793L5.893 7.207L10.686 12l-4.793 4.793l1.414 1.414z" />
                                    </svg>
                                </button>
                                <div id="tooltip-last-page" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-custom-primary rounded-lg shadow-sm opacity-0 tooltip">
                                    Go to last page
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function paginationComponentSpace() {
            return {
                spaceData: [],
                currentPage: 1,
                perPage: 8,
                totalPages: 1,
                totalPagesArray: [],
                city: `{{ urlencode($city) }}`,
                type: `{{ urldecode($type) }}`,
                baseUrl: `https://service.asets.id/api/space-available?address={{ urlencode($city) }}&type={{ urlencode($type) }}`,
                isLoading: false,

                formatPriceToRupiah(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value || 0);
                },

                async fetchData() {
                    this.isLoading = true;

                    try {
                        // First, get the first page to extract max_page
                        const firstResponse = await fetch(`${this.baseUrl}&page=1&limit=50`);
                        const firstJson = await firstResponse.json();
                        const maxPage = firstJson?.requested?.max_page || 1;

                        let allApiData = firstJson?.data || [];

                        // Fetch remaining pages (if any)
                        const fetchPromises = [];
                        for (let page = 2; page <= maxPage; page++) {
                            const url = `${this.baseUrl}&page=${page}&limit=50`;
                            fetchPromises.push(fetch(url).then(res => res.json()));
                        }

                        const results = await Promise.all(fetchPromises);
                        results.forEach(json => {
                            if (json?.data?.length > 0) {
                                allApiData.push(...json.data);
                            }
                        });

                        const apiSpace = allApiData.map(item => ({
                            id: item.listing.id,
                            code: item.space.code,
                            name: item.space.name,
                            type: item.space.type,
                            price: this.formatPriceToRupiah(item.space.price),
                            priceType: item.space.price_type,
                            size: item.space.size_sqm,
                            minPeriod: item.space.min_period,
                            address: item.listing.address,
                            city: item.listing.city,
                            image: item.media.length > 0 ? item.media[0].url : '',
                            source: item.source
                        }));

                        // Fetch local data
                        const localRes = await fetch('/assets/files/search-non-pms.json');
                        const localData = await localRes.json();

                        const localSpace = localData
                            .filter(item => {
                                const address = item.listing.address?.toLowerCase() || '';
                                const spaceType = item.space.type?.toLowerCase() || '';
                                const matchesCity = !this.city || address.includes(this.city.toLowerCase());
                                const matchesType =
                                    spaceType === this.type || this.type === '';
                                return matchesCity && matchesType;
                            })
                            .map(item => ({
                                id: item.listing.id,
                                code: item.space.code,
                                name: item.space.name,
                                type: item.space.type,
                                price: this.formatPriceToRupiah(item.space.price),
                                priceType: item.space.price_type,
                                size: item.space.size_sqm,
                                minPeriod: item.space.min_period,
                                address: item.listing.address,
                                city: item.listing.city,
                                image: item.media.length > 0 ? item.media[0].url : '',
                                source: 'curated'
                            }));

                        // Combine all sources and apply client-side pagination
                        const combined = [...apiSpace, ...localSpace];
                        const start = (this.currentPage - 1) * this.perPage;
                        const end = start + this.perPage;

                        this.spaceData = combined.slice(start, end);
                        this.totalPages = Math.ceil(combined.length / this.perPage);
                        this.totalPagesArray = this.generatePageNumbers();
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                generatePageNumbers() {
                    const totalPages = this.totalPages;
                    const currentPage = this.currentPage;
                    const pages = [];
                    const pageWindow = 4;

                    if (totalPages <= pageWindow) {
                        return Array.from({ length: totalPages }, (_, i) => i + 1);
                    }

                    let startPage = Math.max(1, currentPage - Math.floor(pageWindow / 2));
                    let endPage = startPage + pageWindow - 1;

                    if (endPage > totalPages) {
                        endPage = totalPages;
                        startPage = Math.max(1, totalPages - pageWindow + 1);
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }

                    return pages;
                },

                goToPage(page) {
                    if (page !== this.currentPage && page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                        this.fetchData();
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.goToPage(this.currentPage - 1);
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.goToPage(this.currentPage + 1);
                    }
                },

                goToFirstPage() {
                    this.goToPage(1);
                },

                goToLastPage() {
                    this.goToPage(this.totalPages);
                },

                init() {
                    this.fetchData();
                }
            };
        }
    </script>
@endsection