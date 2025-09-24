{{-- resources/views/components/asets-curated-space-component.blade.php --}}

<div class="mb-8 md:mb-16 container mx-auto px-4">
    <div class="text-center mb-6 md:mb-8">
        <x-heading-title title="Asets Curated Space" />
    </div>

    <div x-data="paginationComponentNonPMS({!! htmlspecialchars(json_encode($dataNonPMS), ENT_QUOTES, 'UTF-8') !!})">
        <!-- Skeleton Loading State - Modernized -->
        <div x-show="isLoading" class="py-4 md:py-6">
            <!-- Skeleton for filter dropdown (if needed) -->
            <div class="mb-4 md:mb-6 px-2 sm:px-0" x-show="uniqueAreas.length > 0">
                <div class="relative w-full sm:w-64">
                    <div class="animate-pulse h-10 bg-gray-200 rounded-lg"></div>
                </div>
            </div>

            <!-- Skeleton Card Grid - Improved Version -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6 lg:gap-7">
                <template x-for="i in 3">
                    <div class="animate-pulse rounded-2xl bg-white shadow-sm overflow-hidden">
                        <!-- Skeleton Badge -->
                        <div class="absolute top-3 md:top-4 left-3 md:left-4 z-10">
                            <div class="h-6 w-20 bg-gray-200 rounded-full"></div>
                        </div>

                        <!-- Skeleton Favorite Button -->
                        <div class="absolute top-3 md:top-4 right-3 md:right-4 z-10">
                            <div class="h-6 w-6 bg-gray-200 rounded-full"></div>
                        </div>

                        <!-- Skeleton Image with Better Animation -->
                        <div class="bg-gray-200 w-full aspect-[4/3] relative overflow-hidden">
                            <!-- Shimmer effect -->
                            <div
                                class="absolute inset-0 -translate-x-full animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200">
                            </div>
                        </div>

                        <!-- Skeleton Content -->
                        <div class="p-4 md:p-5">
                            <!-- Title Skeleton -->
                            <div class="h-5 bg-gray-200 rounded-md w-3/4 mb-2"></div>

                            <!-- Subtitle Skeleton -->
                            <div class="h-3 bg-gray-200 rounded w-1/2 mt-2"></div>

                            <!-- Details Skeleton -->
                            <div class="space-y-3 mt-4">
                                <div class="flex items-center">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 mr-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 mr-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 mr-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                                </div>
                            </div>

                            <!-- Button Skeleton -->
                            <div class="h-9 bg-gray-200 rounded-lg mt-4"></div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Skeleton Pagination - Improved -->
            <div class="flex flex-wrap items-center justify-center space-x-1 pt-6 md:pt-8 mt-4">
                <div class="animate-pulse flex items-center space-x-2">
                    <!-- Prev Button -->
                    <div class="h-9 w-9 bg-gray-200 rounded-full"></div>

                    <!-- Page Numbers (Desktop) -->
                    <div class="hidden sm:flex space-x-2">
                        <div class="h-9 w-9 bg-gray-300 rounded-full"></div>
                        <div class="h-9 w-9 bg-gray-200 rounded-full"></div>
                        <div class="h-9 w-9 bg-gray-200 rounded-full"></div>
                    </div>

                    <!-- Mobile Indicator -->
                    <div class="flex sm:hidden items-center px-3">
                        <div class="h-4 w-20 bg-gray-200 rounded"></div>
                    </div>

                    <!-- Next Button -->
                    <div class="h-9 w-9 bg-gray-200 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Error State - Modern Styling -->
        <div x-show="hasError && !isLoading"
            class="bg-red-50 border border-red-200 rounded-lg p-4 md:p-6 text-center max-w-lg mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-400 mb-3" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-red-800 mb-2">Unable to Load Spaces</h3>
            <p class="text-red-600 mb-4">We encountered an issue while loading curated spaces. Please try again later.
            </p>
            <button @click="retryFetch()"
                class="px-4 py-2 bg-custom-primary hover:bg-custom-primary/90 text-white rounded-lg transition-colors">
                Try Again
            </button>
        </div>

        <!-- Empty State - Modern Styling -->
        <div x-show="!hasError && !isLoading && spaceData.length === 0"
            class="bg-gray-50 border border-gray-200 rounded-lg p-4 md:p-6 text-center max-w-lg mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="text-lg font-medium text-gray-800 mb-2">No Spaces Available</h3>
            <p class="text-gray-600">We're currently updating our curated space collection. Please check back soon!</p>
        </div>

        <!-- Filter by area (if needed) -->
        <div x-show="!isLoading && !hasError && spaceData.length > 0 && uniqueAreas && uniqueAreas.length > 0"
            class="mb-4 md:mb-6 px-2 sm:px-0">
            <div class="relative w-full sm:w-64">
                <select @change="filterByArea($event.target.value)"
                    class="block appearance-none w-full bg-white border border-gray-100 hover:border-purple-500 px-4 py-2.5 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-300 transition-all duration-200">
                    <option value="">All Areas</option>
                    <template x-for="area in uniqueAreas" :key="area">
                        <option :value="area" x-text="area"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Modern Card Grid -->
        <div x-show="!isLoading && !hasError && spaceData.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6 lg:gap-7">
            <template x-for="item in spaceData" :key="item.id">
                <div @click="window.location.href = 'https://wa.me/62811102239?text=' + encodeURIComponent('Halo, saya ingin bertanya mengenai lokasi: ' + (item.listing?.listing_name || 'Asets Curated Space'))"
                    class="group relative overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer w-full transform hover:-translate-y-1">
                    <!-- Status Badge - Modern Style -->
                    <div class="absolute top-3 md:top-4 left-3 md:left-4 z-10">
                        <span
                            class="text-xs font-medium rounded-full py-1 px-2.5 md:px-3.5 text-white shadow-md bg-custom-primary"
                            x-text="item.available ? 'Available' : 'Not Available'"></span>
                    </div>

                    <!-- Favorite Button - Modern Style -->
                    @auth
                        <button @click.stop="toggleFavorite($event)"
                            class="absolute top-3 md:top-4 right-3 md:right-4 z-20 transition-transform duration-200 hover:scale-110 focus:outline-none">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="rgba(255, 255, 255, 0.3)" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                                class="h-5 w-5 md:h-6 md:w-6 text-white transition-colors duration-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]">
                                <path
                                    d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('login-form') }}" @click.stop
                            class="absolute top-3 md:top-4 right-3 md:right-4 z-10 transition-transform duration-200 hover:scale-110">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="rgba(0, 0, 0, 0.5)" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                                class="h-5 w-5 md:h-6 md:w-6 text-white transition-colors duration-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]">
                                <path
                                    d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                            </svg>
                        </a>
                    @endauth

                    <!-- Modern Image Container with Slider -->
                    <div x-data="{ activeSlide: 1 }" class="relative w-full aspect-[4/3]" @mouseenter="hover = true"
                        @touchstart="hover = true" @mouseleave="hover = false"
                        @touchend="setTimeout(() => hover = false, 3000)">

                        <!-- Images Container with Gradient Overlay -->
                        <div class="h-full w-full overflow-hidden bg-gray-100">
                            <template x-if="item.photos && item.photos.length > 0">
                                <div class="flex h-full transition-transform duration-300 ease-in-out"
                                    :style="`transform: translateX(-${(activeSlide - 1) * 100}%); width: ${item.photos.length * 100}%`">
                                    <template x-for="(photo, index) in item.photos" :key="index">
                                        <div class="h-full flex-shrink-0 relative"
                                            :style="`width: ${100 / item.photos.length}%`">
                                            <img class="h-full w-full object-cover" loading="lazy"
                                                :src="photo ||
                                                    'https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain'"
                                                alt="space image" />
                                            <!-- Subtle Image Gradient Overlay -->
                                            <div
                                                class="absolute inset-0 bg-gradient-to-b from-black/5 to-black/20 pointer-events-none">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Fallback for no images -->
                            <template x-if="!item.photos || item.photos.length === 0">
                                <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </template>
                        </div>

                        <!-- Navigation buttons -->
                        <template x-if="item.photos && item.photos.length > 1">
                            <div>
                                <!-- Previous button -->
                                <button
                                    @click.stop="activeSlide = activeSlide > 1 ? activeSlide - 1 : item.photos.length"
                                    class="absolute inset-y-0 left-0 flex items-center bg-black/30 hover:bg-black/50 text-white rounded-r-lg p-1.5 mx-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>

                                <!-- Next button -->
                                <button
                                    @click.stop="activeSlide = activeSlide < item.photos.length ? activeSlide + 1 : 1"
                                    class="absolute inset-y-0 right-0 flex items-center bg-black/30 hover:bg-black/50 text-white rounded-l-lg p-1.5 mx-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>

                                <!-- Indicators -->
                                <div class="absolute -bottom-0.5 left-0 right-0 flex justify-center gap-1.5 pb-3">
                                    <template x-for="(_, index) in item.photos" :key="index">
                                        <button @click.stop="activeSlide = index + 1"
                                            :class="activeSlide === index + 1 ? 'w-6 bg-white' : 'w-2 bg-white/60'"
                                            class="h-1 rounded-full transition-all duration-300 hover:bg-white"></button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Card Content - Modern Style -->
                    <div class="p-4 md:p-5">
                        <!-- Title and location -->
                        <div class="mb-3 md:mb-4">
                            <h3 class="font-bold text-base md:text-lg text-gray-900 line-clamp-1 group-hover:text-custom-primary transition-colors duration-300"
                                x-text="item.listing?.listing_address || 'Address Not Available'"></h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1"
                                x-text="item.listing?.listing_name || 'No Listing Name'"></p>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-100 my-3"></div>

                        <!-- Property Details -->
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-5 text-custom-primary flex-shrink-0">
                                    <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                    <path fill-rule="evenodd"
                                        d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />
                                </svg>
                                <p class="leading-relaxed ml-2 font-semibold text-custom-primary"
                                    x-text="item.price == 0 ? 'Contact for the price' : new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.price)">
                                </p>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" class="flex-shrink-0">
                                    <path fill="#551275"
                                        d="M21 19h2v2H1v-2h2V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v15h2V9h3a1 1 0 0 1 1 1zM7 11v2h4v-2zm0-4v2h4V7z" />
                                </svg>
                                <p class="leading-relaxed ml-2 font-semibold text-custom-primary">
                                    Month
                                </p>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 15 15" class="flex-shrink-0">
                                    <path fill="#3d2369" fill-rule="evenodd"
                                        d="M11.5 3.05a.45.45 0 0 1 .45.45v4a.45.45 0 0 1-.9 0V4.586L4.586 11.05H7.5a.45.45 0 0 1 0 .9h-4a.45.45 0 0 1-.45-.45v-4a.45.45 0 1 1 .9 0v2.914l6.464-6.464H7.5a.45.45 0 1 1 0-.9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p x-text="item.size_sqm + ' / m²'"
                                    class="leading-relaxed font-semibold ml-2 text-custom-primary">
                                </p>
                            </div>
                        </div>

                        <!-- Clear separation with divider -->
                        <div class="border-t border-gray-100 my-3 md:my-4"></div>

                        <!-- Contact link with arrow and animation, styled consistently -->
                        <div class="mt-2">
                            <div
                                class="group inline-flex items-center text-custom-primary font-medium text-sm hover:text-custom-primary/80 transition-all duration-300">
                                <span>Contact</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor"
                                    class="w-4 h-4 ml-1 text-custom-primary transform transition-transform duration-300 group-hover:translate-x-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modern Pagination Controls -->
        <div x-show="totalPages > 1" class="flex flex-wrap items-center justify-center space-x-1 pt-6 md:pt-8 mt-4">
            <!-- First Page Button -->
            <button @click="goToPage(1)" :disabled="currentPage === 1"
                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-purple-50'"
                class="p-2 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    class="text-custom-primary">
                    <path fill="currentColor"
                        d="m4.836 12l6.207 6.207l1.414-1.414L7.664 12l4.793-4.793l-1.414-1.414zm5.65 0l6.207 6.207l1.414-1.414L13.314 12l4.793-4.793l-1.414-1.414z" />
                </svg>
            </button>

            <!-- Previous Page Button -->
            <button @click="prevPage()" :disabled="currentPage === 1"
                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-purple-50'"
                class="p-2 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-custom-primary" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Desktop pagination numbers -->
            <div class="hidden sm:flex items-center space-x-1.5">
                <!-- First Page (if not in view) -->
                <button x-show="!totalPagesArray.includes(1) && currentPage > 3" @click="goToPage(1)"
                    class="h-9 w-9 rounded-full hover:bg-purple-50 flex items-center justify-center transition-colors text-sm">
                    <span>1</span>
                </button>

                <!-- Ellipsis (if needed) -->
                <span x-show="!totalPagesArray.includes(1) && currentPage > 3" class="text-gray-500">...</span>

                <!-- Page Numbers -->
                <template x-for="page in totalPagesArray" :key="page">
                    <button @click="goToPage(page)"
                        :class="page === currentPage ?
                            'bg-custom-primary text-white hover:bg-custom-primary/90' :
                            'text-gray-700 hover:bg-purple-50'"
                        class="h-9 w-9 rounded-full flex items-center justify-center text-sm transition-colors">
                        <span x-text="page"></span>
                    </button>
                </template>

                <!-- Ellipsis (if needed) -->
                <span x-show="!totalPagesArray.includes(totalPages) && currentPage < totalPages - 2"
                    class="text-gray-500">...</span>

                <!-- Last Page (if not in view) -->
                <button x-show="!totalPagesArray.includes(totalPages) && currentPage < totalPages - 2"
                    @click="goToPage(totalPages)"
                    class="h-9 w-9 rounded-full hover:bg-purple-50 flex items-center justify-center transition-colors text-sm">
                    <span x-text="totalPages"></span>
                </button>
            </div>

            <!-- Mobile page indicator -->
            <div class="flex sm:hidden items-center px-3">
                <span class="text-sm text-gray-600">
                    Page <span class="font-medium text-custom-primary" x-text="currentPage"></span> of <span
                        x-text="totalPages"></span>
                </span>
            </div>

            <!-- Next Page Button -->
            <button @click="nextPage()" :disabled="currentPage === totalPages"
                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-purple-50'"
                class="p-2 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-custom-primary" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Last Page Button -->
            <button @click="goToPage(totalPages)" :disabled="currentPage === totalPages"
                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-purple-50'"
                class="p-2 rounded-full transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    class="text-custom-primary">
                    <path fill="currentColor"
                        d="m19.164 12l-6.207-6.207l-1.414 1.414L16.336 12l-4.793 4.793l1.414 1.414zm-5.65 0L7.307 5.793L5.893 7.207L10.686 12l-4.793 4.793l1.414 1.414z" />
                </svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // CURATED SPACE SCRIPT - With fixed pagination scroll
        function paginationComponentNonPMS(data) {
            return {
                spaceData: data || [],
                allPagesData: [],
                currentPage: 1,
                perPage: 3, // You can adjust this if needed
                totalPages: 1,
                totalPagesArray: [],
                uniqueAreas: [],
                selectedArea: '',
                isLoading: true,
                hasError: false,

                init() {
                    this.allPagesData = this.spaceData;

                    // Simulate loading briefly for better UX
                    setTimeout(() => {
                        this.isLoading = false;
                        try {
                            this.updatePageData();
                            this.generateUniqueAreas();
                        } catch (error) {
                            console.error('Error initializing component:', error);
                            this.hasError = true;
                        }
                    }, 500);
                },

                retryFetch() {
                    this.hasError = false;
                    this.isLoading = true;

                    setTimeout(() => {
                        this.isLoading = false;
                        this.updatePageData();
                    }, 500);
                },

                generateUniqueAreas() {
                    this.uniqueAreas = [...new Set(this.allPagesData.map(item => item.area_name))].filter(Boolean);
                },

                updatePageData() {
                    let filteredData = this.selectedArea ?
                        this.allPagesData.filter(item => item.area_name === this.selectedArea) :
                        this.allPagesData;

                    this.totalPages = Math.ceil(filteredData.length / this.perPage);

                    const start = (this.currentPage - 1) * this.perPage;
                    this.spaceData = filteredData.slice(start, start + this.perPage);

                    this.totalPagesArray = this.generatePageNumbers();

                    // No scrolling behavior - we've removed it as requested
                },

                generatePageNumbers() {
                    // Improved pagination display logic
                    const totalPages = this.totalPages;
                    const currentPage = this.currentPage;
                    const maxVisible = window.innerWidth < 640 ? 3 : 5; // Fewer pages on mobile

                    if (totalPages <= maxVisible) {
                        // Show all pages if total is less than max visible
                        return Array.from({
                            length: totalPages
                        }, (_, i) => i + 1);
                    }

                    // Calculate the range of pages to show
                    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                    let endPage = Math.min(totalPages, startPage + maxVisible - 1);

                    // Adjust if at the end
                    if (endPage === totalPages) {
                        startPage = Math.max(1, endPage - maxVisible + 1);
                    }

                    // Create array with page numbers
                    const result = [];
                    for (let i = startPage; i <= endPage; i++) {
                        result.push(i);
                    }
                    return result;
                },

                filterByArea(area = '') {
                    this.selectedArea = area;
                    this.currentPage = 1;
                    this.updatePageData();
                },

                goToPage(page) {
                    if (page === '...' || page === this.currentPage) return;
                    this.currentPage = page;
                    this.updatePageData();
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.updatePageData();
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        this.updatePageData();
                    }
                },

                goToFirstPage() {
                    this.currentPage = 1;
                    this.updatePageData();
                },

                goToLastPage() {
                    this.currentPage = this.totalPages;
                    this.updatePageData();
                },

                // Function to handle favorite toggle
                toggleFavorite(event) {
                    event.stopPropagation(); // Prevent card click when clicking favorite
                    // Implement favorite toggle logic here
                    console.log('Toggle favorite');
                }
            };
        }
    </script>
@endpush
