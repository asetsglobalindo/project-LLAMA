<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm ">
    <div class="h-56 w-full">
        <a href="#">
            <img class="mx-auto h-full " src="{{ $imageSpace }}" alt="image-spaces-spbu" />
        </a>
    </div>

    <div class="pt-6">

        <a href="#"
            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $nameSpace }}</a>

        <ul class="mt-2 flex items-center gap-4">
            <li class="flex items-center gap-2">
                <p class="text-xs  text-gray-500">Space Size : {{ $spaceSize }}</p>
            </li>

            <li class="flex items-center gap-2">
                <p class="text-xs  text-gray-500">Space Type : {{ $spaceTypes }}</p>
            </li>
        </ul>

        <div class="mt-4 flex flex-col gap-4">
            <p class="text-xl font-extrabold leading-tight text-gray-900 ">{{ $priceSpaces }}</p>

            <button type="button" class="flex justify-center rounded-lg px-5 py-2.5 text-sm  text-white bg-purple-950">
                <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                </svg>
                Detail Space
            </button>
        </div>
    </div>
</div>
