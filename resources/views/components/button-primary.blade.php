<button type="{{ $type ?? 'button' }}"
    class=" bg-white/20 hover:bg-white/30 w-full backdrop-blur-sm leading-relaxed text-white text-sm flex px-6 py-4 items-center justify-center gap-2 shadow rounded-xl">
    {{ $slot }}
</button>
