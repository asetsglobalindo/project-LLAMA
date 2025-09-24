<article class="rounded-lg bg-white flex flex-col p-1 hover:bg-gray-100 hover:rounded">
    <div class="relative overflow-hidden bg-cover bg-no-repeat" data-twe-ripple-init data-twe-ripple-color="light">
        <img class="rounded-2xl h-64 w-full object-cover"
            src="{{ Str::startsWith($imageArticle, 'http') ? $imageArticle : 'https://cms.asets.id/storage/' . $imageArticle }}"
            alt="Article image for {{ $titleArticle }}" />
        <a href="{{ $linkArticle }}">
            <div
                class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-[hsla(0,0%,98%,0.15)] bg-fixed ">
            </div>
        </a>
    </div>
    <div class="py-4 px-2">
        <h5 class="mb-2 text-xl font-bold leading-tight text-purple-950 line-clamp-2">{{ $titleArticle }}</h5>
        <small class="mb-2 block font-semibold text-gray-600">{{ $dateArticle }}</small>
        <p class="mb-4 text-base line-clamp-5 text-gray-600 text-justify">
            {{ $descriptionArticle }}
        </p>
    </div>
    <br>
    <div class="flex mt-auto px-2">
        <a href="{{ $linkArticle }}"
            class="inline-block rounded-xl text-sm bg-purple-950 px-4 py-2 text-white leading-normal ">
            Read More
        </a>
    </div>
</article>
