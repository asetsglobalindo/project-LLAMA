{{-- HAKITA SECTION dengan Animasi Modern --}}
<div class="mb-16">
    <div class="text-center" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
        :class="{ 'opacity-0 translate-y-4': !show, 'opacity-100 translate-y-0': show }"
        class="transition-all duration-700 ease-out">
        <x-heading-title title="Intellectual Property Services" />
        <p class="text-gray-600 mt-2">Protect your brand and intellectual property with our comprehensive services</p>
    </div>

    <!-- Mobile: Grid layout (hidden on md and above) -->
    <div class="md:hidden mt-8">
        <div class="grid grid-cols-2 gap-6">
            @php
                $services = [
                    ['name' => 'Pengecekan Merek', 'image' => 'hakita1.png'],
                    ['name' => 'Pendaftaran Hak Paten', 'image' => 'hakita2.png'],
                    ['name' => 'Perpanjangan Merek', 'image' => 'hakita3.png'],
                    ['name' => 'Pengalihan Merek', 'image' => 'hakita4.png'],
                    ['name' => 'Pendaftaran Desain Industri', 'image' => 'hakita5.png'],
                    ['name' => 'Hak Cipta Program Komputer', 'image' => 'hakita6.png'],
                    ['name' => 'Pendaftaran Rahasia Dagang', 'image' => 'hakita7.png'],
                    ['name' => 'Pendaftaran Merek', 'image' => 'hakita8.png'],
                    ['name' => 'Pendaftaran Hak Cipta', 'image' => 'hakita9.png', 'full_width' => true],
                ];
            @endphp

            @foreach ($services as $index => $service)
                <div class="text-center {{ isset($service['full_width']) && $service['full_width'] ? 'col-span-2 flex justify-center' : '' }}"
                    x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ 100 + $index * 100 }})"
                    :class="{ 'opacity-0 translate-y-4': !show, 'opacity-100 translate-y-0': show }"
                    class="transition-all duration-500 ease-out animate-on-scroll"
                    style="transition-delay: {{ $index * 50 }}ms">
                    <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20tentang%20layanan%20{{ urlencode($service['name']) }}"
                        class="flex flex-col items-center group">
                        <div
                            class="rounded-full mb-2 w-16 h-16 flex items-center justify-center overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 transform group-hover:scale-105 bg-white">
                            <img src="{{ url('assets/img/' . $service['image']) }}" alt="{{ $service['name'] }}"
                                class="w-full h-full object-cover">
                        </div>
                        <span
                            class="text-xs text-center font-medium text-gray-700 transition-all duration-300">{{ $service['name'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Desktop: Flex layout (hidden on small screens) -->
    <div class="hidden md:block mt-8 mb-8">
        <div class="flex flex-col justify-center gap-8">
            <!-- Row 1 - First 5 services -->
            <div class="flex flex-wrap justify-center gap-8 lg:gap-16">
                @php
                    $topServices = array_slice($services, 0, 5);
                @endphp

                @foreach ($topServices as $index => $service)
                    <div class="text-center w-24" x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ 100 + $index * 100 }})"
                        :class="{ 'opacity-0 translate-y-4': !show, 'opacity-100 translate-y-0': show }"
                        class="transition-all duration-500 ease-out animate-on-scroll"
                        style="transition-delay: {{ $index * 50 }}ms">
                        <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20tentang%20layanan%20{{ urlencode($service['name']) }}"
                            class="flex flex-col items-center group">
                            <div
                                class="rounded-full mb-2 w-16 h-16 flex items-center justify-center overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 transform group-hover:scale-105 bg-white">
                                <img src="{{ url('assets/img/' . $service['image']) }}" alt="{{ $service['name'] }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <span
                                class="text-xs text-center font-medium text-gray-700 transition-all duration-300">{{ $service['name'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Row 2 - Remaining 4 services -->
            <div class="flex flex-wrap justify-center gap-8 lg:gap-16">
                @php
                    $bottomServices = array_slice($services, 5, 4);
                @endphp

                @foreach ($bottomServices as $index => $service)
                    <div class="text-center w-24" x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ 600 + $index * 100 }})"
                        :class="{ 'opacity-0 translate-y-4': !show, 'opacity-100 translate-y-0': show }"
                        class="transition-all duration-500 ease-out animate-on-scroll"
                        style="transition-delay: {{ $index * 50 + 300 }}ms">
                        <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20tentang%20layanan%20{{ urlencode($service['name']) }}"
                            class="flex flex-col items-center group">
                            <div
                                class="rounded-full mb-2 w-16 h-16 flex items-center justify-center overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 transform group-hover:scale-105 bg-white">
                                <img src="{{ url('assets/img/' . $service['image']) }}" alt="{{ $service['name'] }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <span
                                class="text-xs text-center font-medium text-gray-700 transition-all duration-300">{{ $service['name'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Whatsapp Floating Button -->
    <div class="fixed bottom-6 right-6 z-50" x-data="{ show: false }" x-init="setTimeout(() => show = true, 1000)"
        :class="{ 'opacity-0 scale-90': !show, 'opacity-100 scale-100': show }"
        class="transition-all duration-500 ease-out">
        <a href="https://wa.me/62811102239?text=Halo%20saya%20ingin%20bertanya%20tentang%20layanan%20IP"
            class="flex items-center justify-center bg-green-500 hover:bg-green-600 rounded-full w-14 h-14 shadow-lg transition-all duration-300 transform hover:scale-105 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
            </svg>
            <span
                class="absolute right-16 bg-white text-gray-700 px-3 py-1 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap text-sm">Hubungi
                kami</span>
        </a>
    </div>
</div>

@push('scripts')
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Animasi minimal dengan intersection observer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('appear');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.animate-on-scroll').forEach(item => {
                observer.observe(item);
            });
        });
    </script>

    <style>
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .animate-on-scroll.appear {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animasi halus untuk semua elemen interaktif */
        a,
        button {
            transition: all 0.3s ease;
        }
    </style>
@endpush
