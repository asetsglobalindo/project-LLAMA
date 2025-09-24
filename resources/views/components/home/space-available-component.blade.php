{{-- resources/views/components/space-available-component.blade.php --}}

<div class="mb-8 md:mb-16 container mx-auto px-4">
<div class="flex flex-col bg-white p-4 lg:p-5 shadow-md gap-2 lg:gap-5 w-full border-1 rounded-2xl">
<form id="form-search" method="GET" action="{{ route('filter-space') }}"
        class="flex flex-col md:flex-row justify-center items-start md:items-center gap-4">
        {{-- Title --}}
        <div class="w-full md:w-1/2">
            <h1 class="font-bold text-custom-primary">Commercial Asets Finder</h1>
            <p>Find information in just a few clicks!</p>
        </div>

        <!-- Search -->
        <div class="w-full md:w-1/2">
            <label for="area" class="block font-semibold">Search</label>
            <input type="text" name="city" id="area" class="border p-2 w-full border-gray-300 rounded-lg"
                placeholder="eg. jakarta pusat" value="{{ request('city') }}">
        </div>

        <!-- Type -->
        <div class="w-full md:w-1/2">
            <label for="size" class="block font-semibold">Type</label>
            <select name="type" id="type" class="w-full border p-2 border-gray-300 rounded-lg">
                <option value="" selected>All Type</option>
                <option value="ruangan" {{ request('type') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                <option value="lot indoor" {{ request('type') == 'lot indoor' ? 'selected' : '' }}>Lot Indoor</option>
                <option value="lot outdoor" {{ request('type') == 'lot outdoor' ? 'selected' : '' }}>Lot Outdoor</option>
                <option value="atm" {{ request('type') == 'atm' ? 'selected' : '' }}>ATM</option>
                <option value="lahan parkir" {{ request('type') == 'lahan parkir' ? 'selected' : '' }}>Lahan Parkir
                </option>
                <option value="selasar indoor" {{ request('type') == 'selasar indoor' ? 'selected' : '' }}>Selasar Indoor
                </option>
                <option value="selasar outdoor" {{ request('type') == 'selasar outdoor' ? 'selected' : '' }}>Selasar
                    Outdoor</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="w-full md:w-[70px] flex text-center lg:items-end">
            <button type="submit" class="bg-custom-primary flex justify-center text-white px-4 py-2 rounded-lg w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5l-1.5 1.5l-5-5v-.79l-.27-.27A6.52 6.52 0 0 1 9.5 16A6.5 6.5 0 0 1 3 9.5A6.5 6.5 0 0 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14S14 12 14 9.5S12 5 9.5 5" />
                </svg>
            </button>
        </div>
    </form>
</div>

    <div class="text-center mb-8 md:mb-10 mt-20">
        <x-heading-title title="Space Available Commercial" />
    </div>

    <div x-data="spaceAvailableComponent()" x-init="initialize()">
        <!-- Skeleton Loading State -->
        <div x-show="isLoading" class="py-4 md:py-6">
            <!-- Skeleton Card Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                <template x-for="i in skeletonCount">
                    <div class="animate-pulse rounded-2xl bg-white shadow-sm overflow-hidden">
                        <!-- Skeleton Badge -->
                        <div class="absolute top-3 md:top-4 left-3 md:left-4 z-10">
                            <div class="h-5 w-16 bg-gray-200 rounded-full"></div>
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

                            <!-- Size and price skeletons -->
                            <div class="h-3 bg-gray-200 rounded w-full mt-3 flex">
                                <div class="h-3 w-3 bg-gray-300 rounded-full mr-1 flex-shrink-0"></div>
                                <div class="h-3 w-full bg-gray-200 rounded"></div>
                            </div>

                            <div class="h-3 bg-gray-200 rounded w-full mt-3 flex">
                                <div class="h-3 w-3 bg-gray-300 rounded-full mr-1 flex-shrink-0"></div>
                                <div class="h-3 w-full bg-gray-200 rounded"></div>
                            </div>

                            <!-- Button Skeleton -->
                            <div class="h-8 bg-gray-200 rounded-lg w-full mt-4"></div>
                        </div>
                    </div>
                </template>
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
                <p class="text-red-600 mb-4">We encountered an issue while fetching available spaces. Please try again
                    later.</p>
                <button @click="retryFetch()"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Try Again
                </button>
            </div>

            <!-- Filter by major city - Modern Styling -->
            <div x-show="!hasError && majorCities.length > 0" class="mb-4 md:mb-6 px-2 sm:px-0">
                <div class="relative w-full sm:w-64">
                    <select @change="filterByCity($event.target.value)"
                        class="block appearance-none w-full bg-white border border-gray-100 hover:border-purple-500 px-4 py-2.5 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-300 transition-all duration-200">
                        <option value="">All Cities</option>
                        <template x-for="city in majorCities" :key="city">
                            <option :value="city" x-text="city"></option>
                        </template>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Empty State - No Results -->
            <div x-show="!hasError && spaceData.length === 0"
                class="bg-gray-50 border border-gray-200 rounded-lg p-4 md:p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-800 mb-2">No Spaces Found</h3>
                <p class="text-gray-600 mb-4"
                    x-text="selectedArea ? `No spaces available in '${selectedArea}'. Try selecting a different area.` : 'No spaces are currently available.'">
                </p>
                <button x-show="selectedArea" @click="filterByArea('')"
                    class="px-4 py-2 bg-custom-primary hover:bg-custom-primary/90 text-white rounded-lg transition-colors">
                    Show All Areas
                </button>
            </div>

            <!-- Modern Card Grid Layout -->
            <div x-show="!hasError && spaceData.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                <template x-for="space in spaceData" :key="space.space.id">
                    <div @click="navigateToDetail(space.listing.id, space.source)"
                        class="group relative overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer w-full transform hover:-translate-y-1">

                        <!-- Status Badge - Modern -->
                        <div class="absolute top-3 md:top-4 left-3 md:left-4 z-10">
                            <span
                                class="bg-custom-primary text-xs font-semibold rounded-full py-1 px-2.5 md:px-3.5 text-white shadow-md">Available</span>
                        </div>

                        <!-- Favorite Button - Modern -->
                        <template x-if="isAuthenticated">
                            <button @click.stop="toggleFavorite($event)"
                                class="absolute top-3 md:top-4 right-3 md:right-4 z-20 transition-transform duration-200 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    class="h-5 w-5 text-white transition-colors duration-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]"
                                    fill="rgba(0, 0, 0, 0.5)" stroke="currentColor" stroke-width="1.5">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                            </button>
                        </template>
                        <template x-if="!isAuthenticated">
                            <a href="{{ route('login-form') }}" @click.stop
                                class="absolute top-3 md:top-4 right-3 md:right-4 z-10 transition-transform duration-200 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    class="h-5 w-5 text-white transition-colors duration-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.3)]">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                        fill="rgba(0, 0, 0, 0.5)" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                        </template>

                        <!-- Modern Image Slider Component -->
                        <div x-data="imageSlider(space.media || [])" class="relative w-full aspect-[4/3]"
                            @mouseenter="hover = true" @touchstart="hover = true" @mouseleave="hover = false"
                            @touchend="setTimeout(() => hover = false, 3000)">

                            <!-- Images Container with Mask Overlay -->
                            <div class="h-full w-full overflow-hidden bg-gray-100">
                                <template x-if="slides.length > 0">
                                    <div class="flex h-full transition-transform duration-300 ease-in-out"
                                        :style="`transform: translateX(-${(activeSlide - 1) * 100 / slides.length}%); width: ${slides.length * 100}%`">
                                        <template x-for="(slide, index) in slides" :key="index">
                                            <div class="h-full flex-shrink-0 relative"
                                                :style="`width: ${100 / slides.length}%`">
                                                <img class="h-full w-full object-cover" loading="lazy"
                                                    :src="slide.url ||
                                                        'https://th.bing.com/th/id/OIP.H1gHhKVbteqm1U5SrwpPgwAAAA?rs=1&pid=ImgDetMain'" :alt="space.listing.address" />
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

                            <!-- Navigation buttons -->
                            <div class="absolute inset-y-0 left-0 flex items-center">
                                <button @click.stop="prevSlide()"
                                    class="bg-black/30 hover:bg-black/50 text-white rounded-r-lg p-1.5 mx-1.5 focus:outline-none transform transition-all duration-300 hover:scale-105 opacity-0 group-hover:opacity-100"
                                    x-show="slides.length > 1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>
                            </div>

                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <button @click.stop="nextSlide()"
                                    class="bg-black/30 hover:bg-black/50 text-white rounded-l-lg p-1.5 mx-1.5 focus:outline-none transform transition-all duration-300 hover:scale-105 opacity-0 group-hover:opacity-100"
                                    x-show="slides.length > 1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Slide indicators -->
                            <div class="absolute -bottom-0.5 left-0 right-0 flex justify-center gap-1.5 pb-3"
                                x-show="slides.length > 1">
                                <template x-for="(slide, index) in slides" :key="index">
                                    <button @click.stop="activeSlide = index + 1"
                                        :class="activeSlide === index + 1 ? 'w-5 bg-white' : 'w-1.5 bg-white/60'"
                                        class="h-1 rounded-full transition-all duration-300 hover:bg-white"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-4 md:p-5">
                            <!-- Title and location -->
                            <div class="mb-2 md:mb-3">
                                <!-- Address -->
                                <h3 class="font-bold text-sm md:text-base text-gray-900 line-clamp-2 group-hover:text-custom-primary transition-colors duration-300"
                                    x-text="space.listing.address"></h3>

                                <!-- City/Region -->
                                <p class="text-xs text-gray-600 mt-1.5 line-clamp-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="h-3 w-3 mr-1.5 text-custom-primary">
                                        <path fill="currentColor"
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                                    </svg>
                                    <span x-text="extractCity(space.listing.address)" class="font-medium"></span>
                                </p>
                            </div>

                            <!-- Property details -->
                            <div class="mb-3">
                                <!-- Price -->
                                <div class="flex items-center text-xs md:text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="h-3.5 w-3.5 text-custom-primary mr-1.5">
                                        <path fill="currentColor"
                                            d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                                    </svg>
                                    <span class="font-semibold text-custom-primary"
                                        x-text="formatPriceDisplay(space.space.price, space.space.price_type)"></span>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-100 my-3"></div>

                            <!-- Key details in footer -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="h-3.5 w-3.5 text-custom-primary mr-1.5 flex-shrink-0">
                                        <path fill="currentColor"
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                    </svg>
                                    <p class="font-medium text-gray-700" x-text="space.space.type"></p>
                                </div>

                                <div class="flex items-center justify-end text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="h-3.5 w-3.5 text-custom-primary mr-1.5 flex-shrink-0">
                                        <path fill="currentColor"
                                            d="M17 15h2V7c0-1.1-.9-2-2-2H9v2h8v8zM7 17V1H5v4H1v2h4v10c0 1.1.9 2 2 2h10v4h2v-4h4v-2H7z" />
                                    </svg>
                                    <p class="font-medium text-gray-700" x-text="`${space.space.size_sqm} m²`"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modern Pagination -->
            <div x-show="!hasError && spaceData.length > 0 && totalPages > 1"
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
                        <button @click="goToPage(page)" :class="page === currentPage ? 'bg-custom-primary text-white hover:bg-custom-primary/90' :
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

@push('scripts')
    <script>
        function spaceAvailableComponent() {
            return {
                spaceData: [],
                currentPage: 1,
                perPage: 9,
                totalPages: 1,
                totalData: 0,
                totalPagesArray: [],
                // Define a list of major Indonesian cities for the filter
                majorCities: ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Yogyakarta', 'Denpasar',
                    'Palembang', 'Balikpapan'
                ],
                selectedCity: '',
                baseUrl: 'https://service.asets.id/api/space-available',
                isLoading: false,
                hasError: false,
                skeletonCount: 9,
                isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},

                initialize() {
                    this.fetchData();
                    this.setupEventListeners();
                    this.adjustPerPage();
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
                },

                adjustPerPage() {
                    // Set fixed items per page based on screen size
                    const width = window.innerWidth;

                    if (width < 640) { // Mobile
                        this.perPage = 4;
                    } else if (width < 1024) { // Tablet
                        this.perPage = 6;
                    } else { // Desktop (both small and large desktop)
                        this.perPage = 9;
                    }

                    // Update skeleton count to match perPage
                    this.skeletonCount = this.perPage;
                },

                fetchData(page = 1) {
                    this.isLoading = true;
                    this.currentPage = page;
                    this.hasError = false;

                    // Construct URL with filters
                    let url = `${this.baseUrl}?limit=${this.perPage}&page=${this.currentPage}`;

                    // Add city filter if selected
                    if (this.selectedCity) {
                        url += `&address=${encodeURIComponent(this.selectedCity)}`;
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
                                this.spaceData = data.data;

                                // Update pagination information directly from API response
                                if (data.requested) {
                                    this.totalPages = parseInt(data.requested.max_page) || 1;
                                    this.totalData = parseInt(data.requested.total_data) || 0;
                                    this.currentPage = parseInt(data.requested.page) || 1;
                                } else {
                                    this.totalPages = 1;
                                    this.totalData = this.spaceData.length;
                                }

                                this.totalPagesArray = this.generatePageNumbers();
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            this.hasError = true;
                            this.spaceData = [];
                            this.totalPages = 1;
                            this.totalPagesArray = [1];
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },

                // Retry function for error state
                retryFetch() {
                    this.fetchData(this.currentPage);
                },

                // Filter by city
                filterByCity(city = '') {
                    this.selectedCity = city;
                    this.currentPage = 1;
                    this.fetchData(1);
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

                // This function is no longer needed as we use predefined major cities
                // It's kept here for reference but not used
                extractUniqueAreas() {
                    // Not used anymore - we use predefined major cities
                },

                // Maintain backward compatibility
                filterByArea(area = '') {
                    // Redirect to the new filterByCity function
                    this.filterByCity(area);
                },

                goToPage(page) {
                    if (page === '...' || page === this.currentPage) return;
                    this.fetchData(parseInt(page));
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.fetchData(this.currentPage - 1);
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.fetchData(this.currentPage + 1);
                    }
                },

                // Helper functions
                formatPriceDisplay(price, priceType) {
                    if (!price) return 'Contact for price';
                    const formattedPrice = Number(price).toLocaleString();
                    if (priceType === 'sqm') {
                        return `IDR ${formattedPrice} / m² / Month`;
                    } else {
                        return `IDR ${formattedPrice} / Month`;
                    }
                },

                extractCity(address) {
                    if (!address) return '';
                    const parts = address.split(',').map(part => part.trim());
                    return parts[parts.length - 1] === 'Indonesia' ?
                        parts[parts.length - 2] :
                        parts[parts.length - 1];
                },

                navigateToDetail(listingId, source) {
                    if (source == "pms") {
                        window.location.href = `/detail-spbu/${listingId}`;
                    } else {
                        window.location.href = `/detail-listing/${listingId}`;
                    }

                },

                toggleFavorite(event) {
                    event.stopPropagation();
                    // Implement favorite toggle functionality
                    console.log('Toggle favorite clicked');
                }
            };
        }


        function imageSlider(slides = []) {
            return {
                slides,
                activeSlide: 1,
                hover: false,

                prevSlide() {
                    this.activeSlide = this.activeSlide > 1 ? this.activeSlide - 1 : this.slides.length;
                },

                nextSlide() {
                    this.activeSlide = this.activeSlide < this.slides.length ? this.activeSlide + 1 : 1;
                }
            };
        }
    </script>
@endpush