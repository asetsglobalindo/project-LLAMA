{{-- SPBU CARD LOCATION SECTION WITH SWIPER.JS INTEGRATION --}}
<div class="mb-8 md:mb-16 container mx-auto px-4">
    <div class="text-center mb-6 md:mb-8">
        <x-heading-title title="Asets Commercial Location Listing" />
    </div>

    <div x-data="locationListingComponent()" x-init="initialize()">
        <!-- Skeleton Loading State -->
        <div x-show="isLoading" class="py-4 md:py-6">
            <!-- Skeleton for filter dropdown -->
            <div class="mb-4 md:mb-6 px-2 sm:px-0">
                <div class="relative w-full sm:w-64">
                    <div class="animate-pulse h-10 bg-gray-200 rounded-lg"></div>
                </div>
            </div>

            <!-- Skeleton Card Grid - Improved Version -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-6 lg:gap-7">
                <!-- Menggunakan skeletonCount yang sama dengan perPage -->
                <template x-for="i in skeletonCount">
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

                            <!-- City Skeleton -->
                            <div class="h-3 bg-gray-200 rounded w-1/2 mt-2 flex items-center">
                                <div class="h-3 w-3 bg-gray-300 rounded-full mr-1"></div>
                                <div class="h-3 w-20 bg-gray-200 rounded"></div>
                            </div>

                            <!-- Address Skeleton -->
                            <div class="h-3 bg-gray-200 rounded w-full mt-3 flex">
                                <div class="h-3 w-3 bg-gray-300 rounded-full mr-1 flex-shrink-0"></div>
                                <div class="h-3 w-full bg-gray-200 rounded"></div>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-100 my-3"></div>

                            <!-- Footer Skeleton -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="h-3.5 w-3.5 bg-gray-300 rounded-full mr-1.5 flex-shrink-0"></div>
                                    <div class="h-4 bg-gray-200 rounded w-16"></div>
                                </div>
                                <div class="h-4 bg-gray-200 rounded w-14"></div>
                            </div>
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

        <div x-show="!isLoading" class="space-y-4 md:space-y-6">
            <!-- Error State -->
            <div x-show="hasError" class="bg-red-50 border border-red-200 rounded-lg p-4 md:p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-400 mb-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-red-800 mb-2">Unable to Load Data</h3>
                <p class="text-red-600 mb-4">We encountered an issue while fetching listings. Please try again later.
                </p>
                <button @click="retryFetch()"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Try Again
                </button>
            </div>

            <!-- Empty State - No Results -->
            <div x-show="!hasError && listingData.length === 0"
                class="bg-gray-50 border border-gray-200 rounded-lg p-4 md:p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-800 mb-2">No Listings Found</h3>
                <p class="text-gray-600 mb-4"
                    x-text="selectedArea ? `No listings available in '${selectedArea}'. Try selecting a different area.` : 'No listings are currently available.'">
                </p>
                <button x-show="selectedArea" @click="filterByArea('')"
                    class="px-4 py-2 bg-custom-primary hover:bg-custom-primary/90 text-white rounded-lg transition-colors">
                    Show All Areas
                </button>
            </div>

            <!-- Content when data exists -->
            <div x-show="!hasError && listingData.length > 0">
                <!-- Filter by area - Modern Styling -->
                {{-- <div x-show="!hasError && listingData.length > 0 && uniqueAreas.length > 0"
                    class="mb-4 md:mb-6 px-2 sm:px-0">
                    <div class="relative w-full sm:w-64">
                        <select @change="filterByArea($event.target.value)"
                            class="block appearance-none w-full bg-white border border-gray-100 hover:border-purple-500 px-4 py-2.5 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-300 transition-all duration-200">
                            <option value="">All Areas</option>
                            <template x-for="area in uniqueAreas" :key="area">
                                <option :value="area" x-text="area"></option>
                            </template>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div> --}}

                <!-- Modern Card Grid Layout -->
                <div id="listing-container"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-6 lg:gap-7">
                    <template x-for="listing in listingData" :key="listing.listing_id">
                        <div @click="navigateToDetail(listing.listing_id, listing.source)"
                            class="group relative overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer w-full transform hover:-translate-y-1">

                            <!-- Status Badge - Modern -->
                            <div class="absolute top-3 md:top-4 left-3 md:left-4 z-10">
                                <span :class="listing.available == 1 ? 'bg-custom-primary' : 'bg-zinc-700'"
                                    class="text-xs font-medium rounded-full py-1 px-2.5 md:px-3.5 text-white shadow-md"
                                    x-text="getAvailabilityText(listing.available)"></span>
                            </div>

                            <!-- Favorite Button - Modern -->
                            <template x-if="isAuthenticated">
                                <button x-data="{ liked: false }" @click.stop="toggleFavorite($event, liked)"
                                    class="absolute top-3 md:top-4 right-3 md:right-4 z-20 transition-transform duration-200 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        :class="liked ? 'text-red-500' : 'text-white hover:text-red-200'"
                                        class="h-5 w-5 md:h-6 md:w-6 transition-colors duration-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                            :fill="liked ? 'currentColor' : 'rgba(255,255,255,0.3)'"
                                            stroke="currentColor" stroke-width="1.5" />
                                    </svg>
                                </button>
                            </template>
                            <template x-if="!isAuthenticated">
                                <a href="{{ route('login-form') }}" @click.stop
                                    class="absolute top-3 md:top-4 right-3 md:right-4 z-10 transition-transform duration-200 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="h-5 w-5 md:h-6 md:w-6 text-white transition-colors duration-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                            fill="rgba(0, 0, 0, 0.5)" stroke="currentColor" stroke-width="1.5" />
                                    </svg>
                                </a>
                            </template>

                            <!-- Slider Component dengan Error yang Diperbaiki -->
                            <div x-data="swiperSlider(listing.photos || [])" x-init="initSwiper"
                                class="relative w-full aspect-[4/3]">
                                <!-- Swiper Container -->
                                <div class="swiper-container h-full w-full overflow-hidden bg-gray-100">
                                    <template x-if="slides.length > 0">
                                        <div class="flex h-full transition-transform duration-300 ease-in-out"
                                            :style="`transform: translateX(-${(activeSlide - 1) * 100 / slides.length}%); width: ${slides.length * 100}%`">
                                            <template x-for="(slide, index) in slides" :key="index">
                                                <div class="h-full flex-shrink-0 relative"
                                                    :style="`width: ${100 / slides.length}%`">
                                                    <img class="h-full w-full object-cover" loading="lazy"
                                                        :src="slide.image_url ||
                                                            'https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain'" :alt="listing.name" />
                                                    <!-- Subtle Image Gradient Overlay -->
                                                    <div
                                                        class="absolute inset-0 bg-gradient-to-b from-black/5 to-black/20 pointer-events-none">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Fallback for no images -->
                                    <template x-if="slides.length === 0">
                                        <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- Navigation buttons (desktop only) -->
                                <div class="absolute inset-y-0 left-0 flex items-center z-20">
                                    <button @click.stop="prevSlide()"
                                        class="bg-black/30 hover:bg-black/50 text-white rounded-r-lg p-1.5 mx-1.5 focus:outline-none transform transition-all duration-300 hover:scale-105 opacity-0 group-hover:opacity-100 hidden sm:block"
                                        x-show="slides.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 19.5L8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="absolute inset-y-0 right-0 flex items-center z-20">
                                    <button @click.stop="nextSlide()"
                                        class="bg-black/30 hover:bg-black/50 text-white rounded-l-lg p-1.5 mx-1.5 focus:outline-none transform transition-all duration-300 hover:scale-105 opacity-0 group-hover:opacity-100 hidden sm:block"
                                        x-show="slides.length > 1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Slide indicators -->
                                <div class="absolute bottom-0 left-0 right-0 flex justify-center gap-1.5 pb-3 z-20"
                                    x-show="slides.length > 1">
                                    <template x-for="(slide, index) in slides" :key="index">
                                        <button @click.stop="activeSlide = index + 1; updateSlidePosition()"
                                            :class="activeSlide === index + 1 ? 'w-6 bg-white' : 'w-2 bg-white/60'"
                                            class="h-1 rounded-full transition-all duration-300 hover:bg-white"></button>
                                    </template>
                                </div>

                                <!-- Mobile instruction (shows briefly) -->
                                <div x-data="{ show: true }" x-show="show && slides.length > 1"
                                    x-init="setTimeout(() => show = false, 3000)"
                                    class="absolute inset-0 bg-black/30 flex items-center justify-center sm:hidden z-40">
                                    <div class="text-white text-center px-4 py-3 rounded-lg bg-black/50">
                                        <p class="text-sm font-medium">Tap sisi kiri/kanan untuk melihat gambar lainnya
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modern Card Content -->
                            <div class="p-4 md:p-5">
                                <!-- Title and location -->
                                <div class="mb-3 md:mb-4">
                                    <!-- Name -->
                                    <h3 class="font-bold text-base md:text-lg text-gray-900 line-clamp-1 group-hover:text-custom-primary transition-colors duration-300"
                                        x-text="listing.name"></h3>

                                    <!-- City/Region -->
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-3 w-3 mr-1 text-custom-primary">
                                            <path fill="currentColor"
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                                        </svg>
                                        <span x-text="extractCity(listing.address)" class="font-medium"></span>
                                    </p>

                                    <!-- Full address with icon -->
                                    <p class="text-xs text-gray-500 mt-1.5 flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-3 w-3 mr-1 text-custom-primary mt-0.5 flex-shrink-0">
                                            <path fill="currentColor"
                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                        </svg>
                                        <span class="line-clamp-2" x-text="listing.address"></span>
                                    </p>
                                </div>

                                <!-- Divider -->
                                <div class="border-t border-gray-100 my-3"></div>

                                <!-- Key details - Modern style -->
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center text-xs md:text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            class="h-3.5 w-3.5 text-custom-primary mr-1.5 flex-shrink-0">
                                            <path fill="currentColor"
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                        </svg>
                                        <p class="font-medium text-gray-800"
                                            x-text="formatSpacesAvailable(listing.total_space_available)">
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-end text-xs md:text-sm">
                                        <p class="font-semibold text-custom-primary rounded-lg py-1"
                                            x-text="formatPrice(listing.starting_display_price)">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modern Pagination -->
                <div id="pagination-container" x-show="totalPages > 1"
                    class="flex flex-wrap items-center justify-center space-x-1 pt-6 md:pt-8 mt-4">
                    <!-- Previous Button -->
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
                        <!-- First Page -->
                        <button x-show="!totalPagesArray.includes(1) && currentPage > 3" @click="goToPage(1)"
                            class="h-9 w-9 rounded-full hover:bg-purple-50 flex items-center justify-center transition-colors text-sm">
                            <span>1</span>
                        </button>

                        <!-- Ellipsis (if needed) -->
                        <span x-show="!totalPagesArray.includes(1) && currentPage > 3" class="text-gray-500">...</span>

                        <!-- Page Numbers -->
                        <template x-for="page in totalPagesArray" :key="page">
                            <button @click="goToPage(page)" :class="page === currentPage ?
                                    'bg-custom-primary text-white hover:bg-custom-primary/90' :
                                    'text-gray-700 hover:bg-purple-50'"
                                class="h-9 w-9 rounded-full flex items-center justify-center text-sm transition-colors">
                                <span x-text="page"></span>
                            </button>
                        </template>

                        <!-- Ellipsis (if needed) -->
                        <span x-show="!totalPagesArray.includes(totalPages) && currentPage < totalPages - 2"
                            class="text-gray-500">...</span>

                        <!-- Last Page -->
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

                    <!-- Next Button -->
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
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Add Swiper.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Add Swiper.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Swiper.js implementation for image slider
        function swiperSlider(slides = []) {
            return {
                slides: slides || [],
                activeSlide: 1,
                swiperInstance: null,
                touchStartX: 0,
                touchEndX: 0,
                isSwiping: false,
                sliderId: null,

                initSwiper() {
                    // Make sure we have slides to work with
                    if (!this.slides || this.slides.length === 0) {
                        console.log('No slides available to initialize');
                        return;
                    }

                    // Generate a unique ID for this slider instance
                    this.sliderId = 'swiper-' + Math.random().toString(36).substr(2, 9);

                    // Set up touch events for mobile swipe
                    const container = this.$el.querySelector('.swiper-container');

                    // Add touch event listeners for manual swipe
                    container.addEventListener('touchstart', (e) => this.handleTouchStart(e), {
                        passive: true
                    });
                    container.addEventListener('touchmove', (e) => this.handleTouchMove(e), {
                        passive: true
                    });
                    container.addEventListener('touchend', (e) => this.handleTouchEnd(e), {
                        passive: true
                    });

                    // Add mouse events for desktop testing/support
                    container.addEventListener('mousedown', (e) => this.handleMouseDown(e));
                    container.addEventListener('mousemove', (e) => this.handleMouseMove(e));
                    container.addEventListener('mouseup', (e) => this.handleMouseUp(e));
                    container.addEventListener('mouseleave', (e) => this.handleMouseUp(e));

                    console.log(`Swiper initialized with ${this.slides.length} slides`);
                },

                // Touch handlers
                handleTouchStart(e) {
                    this.touchStartX = e.touches[0].clientX;
                    this.isSwiping = true;
                    this.markSwiping(true);
                },

                handleTouchMove(e) {
                    if (!this.isSwiping) return;
                    this.touchEndX = e.touches[0].clientX;

                    // Calculate the distance moved
                    const diff = this.touchStartX - this.touchEndX;
                    const container = this.$el.querySelector('.flex');

                    // Apply a temporary transform during the swipe for visual feedback
                    if (container && this.slides.length > 1) {
                        const currentTransform = -((this.activeSlide - 1) * 100 / this.slides.length);
                        const newTransform = currentTransform - (diff / container.offsetWidth *
                            50); // Limit the drag effect

                        // Clamp the transform to prevent overscrolling
                        const maxTransform = 0;
                        const minTransform = -((this.slides.length - 1) * 100 / this.slides.length);

                        const clampedTransform = Math.min(maxTransform, Math.max(minTransform, newTransform));
                        container.style.transform = `translateX(${clampedTransform}%)`;
                    }
                },

                handleTouchEnd(e) {
                    if (!this.isSwiping) return;
                    this.isSwiping = false;

                    const diff = this.touchStartX - this.touchEndX;

                    // Determine if swipe was significant
                    if (Math.abs(diff) > 30 && this.slides.length > 1) {
                        if (diff > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    } else {
                        // Reset to current slide if swipe wasn't significant
                        this.updateSlidePosition();
                    }

                    // Set a timeout before allowing navigation to prevent accidental clicks
                    setTimeout(() => {
                        this.markSwiping(false);
                    }, 100);
                },

                // Mouse handlers (desktop)
                handleMouseDown(e) {
                    // Only handle left mouse button
                    if (e.button !== 0) return;

                    this.touchStartX = e.clientX;
                    this.isSwiping = true;
                    this.markSwiping(true);

                    // Prevent default to avoid text selection during swipe
                    e.preventDefault();
                },

                handleMouseMove(e) {
                    if (!this.isSwiping) return;
                    this.touchEndX = e.clientX;

                    // Same logic as touch move
                    const diff = this.touchStartX - this.touchEndX;
                    const container = this.$el.querySelector('.flex');

                    if (container && this.slides.length > 1) {
                        const currentTransform = -((this.activeSlide - 1) * 100 / this.slides.length);
                        const newTransform = currentTransform - (diff / container.offsetWidth * 50);

                        const maxTransform = 0;
                        const minTransform = -((this.slides.length - 1) * 100 / this.slides.length);

                        const clampedTransform = Math.min(maxTransform, Math.max(minTransform, newTransform));
                        container.style.transform = `translateX(${clampedTransform}%)`;
                    }
                },

                handleMouseUp(e) {
                    if (!this.isSwiping) return;
                    this.isSwiping = false;

                    const diff = this.touchStartX - this.touchEndX;

                    if (Math.abs(diff) > 30 && this.slides.length > 1) {
                        if (diff > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    } else {
                        this.updateSlidePosition();
                    }

                    setTimeout(() => {
                        this.markSwiping(false);
                    }, 100);
                },

                // Helper to mark the card as currently being swiped
                markSwiping(value) {
                    const parentCard = this.$el.closest('[x-data]');
                    if (parentCard) {
                        parentCard.setAttribute('data-swiping', value ? 'true' : 'false');
                    }
                },

                // Update slide position after transition
                updateSlidePosition() {
                    const container = this.$el.querySelector('.flex');
                    if (container) {
                        container.style.transform = `translateX(-${(this.activeSlide - 1) * 100 / this.slides.length}%)`;
                    }
                },

                // Navigation methods
                nextSlide() {
                    if (this.slides.length <= 1) return;

                    this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1;
                    this.updateSlidePosition();
                },

                prevSlide() {
                    if (this.slides.length <= 1) return;

                    this.activeSlide = this.activeSlide === 1 ? this.slides.length : this.activeSlide - 1;
                    this.updateSlidePosition();
                }
            };
        }

        function locationListingComponent() {
            return {
                // Location listing component code
                listingData: [],
                currentPage: 1,
                perPage: 8,
                totalPages: 1,
                totalData: 0,
                totalPagesArray: [],
                uniqueAreas: [],
                selectedArea: '',
                baseUrl: 'https://api.asets.id/api/listings',
                isLoading: false,
                hasError: false,
                skeletonCount: 8,
                cachedData: {},
                isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},

                initialize() {
                    this.fetchData();
                    this.setupEventListeners();
                    this.adjustPerPage();
                    this.skeletonCount = this.perPage;
                },

                setupEventListeners() {
                    let resizeTimer;
                    window.addEventListener('resize', () => {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(() => {
                            const oldPerPage = this.perPage;
                            this.adjustPerPage();

                            // Only refetch if perPage changed
                            if (oldPerPage !== this.perPage) {
                                this.fetchData();
                            }
                        }, 250);
                    });

                    // Improved event delegation for swipe prevention
                    document.addEventListener('click', (e) => {
                        // Find the nearest card ancestor that might be being swiped
                        const card = e.target.closest('[data-swiping="true"]');
                        if (card) {
                            // If we're in the middle of a swipe, prevent the navigation
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Card navigation prevented during swipe');
                            return false;
                        }
                    }, true); // Use capture phase to catch events early
                },

                // Simplified pagination methods
                goToPage(page) {
                    if (page === '...' || page === this.currentPage) return;

                    const pageNum = parseInt(page);
                    if (isNaN(pageNum)) return;

                    // Store the scroll position before changing page
                    const scrollPosition = window.scrollY;
                    const listingContainer = document.getElementById('pagination-container');

                    // Fetch new data
                    this.fetchData(pageNum, () => {
                        // After data is loaded, restore the scroll position
                        if (listingContainer) {
                            window.scrollTo({
                                top: scrollPosition,
                                behavior: 'auto'
                            });
                        }
                    });
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

                adjustPerPage() {
                    // Set fixed items per page based on screen size
                    const width = window.innerWidth;

                    if (width < 640) { // Mobile
                        this.perPage = 4;
                    } else if (width < 1024) { // Tablet
                        this.perPage = 6;
                    } else { // Desktop (both small and large desktop)
                        this.perPage = 8;
                    }

                    // Update skeleton count to match perPage
                    this.skeletonCount = this.perPage;
                },

                fetchData(page = 1, callback = null) {
                    this.isLoading = true;
                    this.currentPage = page;
                    this.hasError = false;

                    // Construct URL with filters
                    let url = `${this.baseUrl}?limit=${this.perPage}&page=${this.currentPage}`;

                    // Add area filter if selected
                    if (this.selectedArea) {
                        url += `&area=${encodeURIComponent(this.selectedArea)}`;
                    }

                    // Generate cache key
                    const cacheKey = `${url}_${this.perPage}`;

                    // Check if we have cached data
                    if (this.cachedData[cacheKey]) {
                        console.log('Using cached data for:', cacheKey);

                        // Use cached data
                        const cachedResult = this.cachedData[cacheKey];
                        this.listingData = cachedResult.listingData;
                        this.totalPages = cachedResult.totalPages;
                        this.totalData = cachedResult.totalData;
                        this.currentPage = cachedResult.currentPage;
                        this.totalPagesArray = this.generatePageNumbers();

                        this.isLoading = false;

                        // Execute callback if provided
                        if (typeof callback === 'function') {
                            setTimeout(callback, 100);
                        }

                        return;
                    }

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Network response was not ok: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.data) {
                                // Map the API response to the format expected by the template
                                this.listingData = data.data.map(item => ({
                                    listing_id: item.listing?.id || '',
                                    name: item.listing?.name || 'Unnamed Location',
                                    address: item.listing?.address || 'Address unavailable',
                                    available: item.listing?.available ?? 1,
                                    photos: Array.isArray(item.media) ? item.media.map(media => ({
                                        image_url: media.url || ''
                                    })) : [],
                                    total_space_available: item.listing?.spaces || 0,
                                    starting_display_price: item.listing?.starting_display_price ||
                                        "Contact for price",
                                    area_name: item.area?.name || 'Unknown Area',
                                    city: item.city?.name || 'Unknown City',
                                    source: item.source || 'Unknown Source'
                                }));

                                // Update pagination information directly from API response
                                if (data.requested) {
                                    this.totalPages = parseInt(data.requested.max_page) || 1;
                                    this.totalData = parseInt(data.requested.total_data) || 0;
                                    this.currentPage = parseInt(data.requested.page) || 1;
                                } else {
                                    this.totalPages = 1;
                                    this.totalData = this.listingData.length;
                                }

                                this.totalPagesArray = this.generatePageNumbers();
                                this.extractUniqueAreas();

                                // Cache the result
                                this.cachedData[cacheKey] = {
                                    listingData: [...this.listingData],
                                    totalPages: this.totalPages,
                                    totalData: this.totalData,
                                    currentPage: this.currentPage
                                };

                                // Limit cache size to prevent memory issues
                                const cacheKeys = Object.keys(this.cachedData);
                                if (cacheKeys.length > 10) {
                                    // Remove oldest cache entry
                                    delete this.cachedData[cacheKeys[0]];
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            this.hasError = true;
                            this.listingData = [];
                            this.totalPages = 1;
                            this.totalPagesArray = [1];
                        })
                        .finally(() => {
                            // Set isLoading to false
                            this.isLoading = false;

                            // Execute callback if provided (for scroll position restoration)
                            if (typeof callback === 'function') {
                                setTimeout(() => {
                                    callback();
                                }, 100);
                            }
                        });
                },

                // Retry function for error state
                retryFetch() {
                    this.fetchData(this.currentPage);
                },

                extractUniqueAreas() {
                    if (!this.listingData.length) {
                        this.uniqueAreas = [];
                        return;
                    }

                    // We should fetch unique areas from a separate API endpoint ideally
                    // For now, extract from current data
                    const areas = this.listingData
                        .map(item => item.area_name)
                        .filter(area => area && area.trim() !== '');

                    // Add to existing unique areas without duplicates
                    const newAreas = [...new Set(areas)];
                    this.uniqueAreas = [...new Set([...this.uniqueAreas, ...newAreas])];
                },

                generatePageNumbers() {
                    // Safety check
                    if (!this.totalPages || this.totalPages < 1) {
                        return [1];
                    }

                    const totalPages = parseInt(this.totalPages);
                    const currentPage = parseInt(this.currentPage);
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
                    this.fetchData(1);
                },

                // Helper functions
                formatPrice(displayPrice) {
                    if (!displayPrice) return 'Contact for price';
                    return displayPrice.replace(/m&sup2;/g, 'm²').replace('Bulan', 'Month');
                },

                formatSpacesAvailable(spaces) {
                    const count = parseInt(spaces) || 0;
                    return `${count} Space${count !== 1 ? 's' : ''} Available`;
                },

                extractCity(address) {
                    if (!address) return '';
                    const parts = address.split(',').map(part => part.trim());
                    return parts[parts.length - 1] === 'Indonesia' ?
                        parts[parts.length - 2] :
                        parts[parts.length - 1];
                },

                getAvailabilityText(available) {
                    return available == 1 ? 'Available' : 'Leased';
                },

                navigateToDetail(listingId, source) {
                    // Improved check to prevent navigation during swipes
                    if (this.checkIfSwiping()) {
                        console.log('Navigation prevented during swipe');
                        return;
                    }

                    if (source === 'pms') {
                        window.location.href = `/detail-spbu/${listingId}`;
                    } else {
                        window.location.href = `/detail-listing/${listingId}`;
                    }

                },

                // Helper method to check if any element is currently swiping
                checkIfSwiping() {
                    const swipingElements = document.querySelectorAll('[data-swiping="true"]');
                    return swipingElements.length > 0;
                },

                toggleFavorite(event, liked) {
                    event.stopPropagation();
                    // Implement the favorite toggle functionality
                    // This is just a placeholder for the actual implementation
                    console.log('Toggle favorite', liked);
                }
            };
        }
        // Debugging untuk memastikan script berjalan
        console.log('Script image slider dengan gestur sentuh dimuat');
        window.addEventListener('DOMContentLoaded', () => {
            console.log('DOM sepenuhnya dimuat');
        });
    </script>
@endpush