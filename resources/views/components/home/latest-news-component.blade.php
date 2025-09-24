{{-- resources/views/components/latest-news-component.blade.php --}}
<div class="">
    <div class="text-center flex flex-col space-y-2">
        <x-heading-title title="Latest Articles And News" />
        <p class="text-gray-600">Stay informed with the latest updates, trends, and insights from our industry
            and services</p>
    </div>
    <div class="flex justify-center mt-4 mb-6">
        <a href="{{ route('news.index') }}"
            class="inline-block rounded-xl text-sm bg-purple-950 px-6 py-3 text-white font-medium hover:bg-purple-800 transition">
            View All News
        </a>
    </div>
    <br>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6 mb-24">
        @foreach ($articles as $article)
            <div
                class="article-card bg-white rounded-lg overflow-hidden shadow flex flex-col hover:shadow-lg transition-all duration-300 hover:transform hover:translate-y-[-5px]">
                <div class="relative">
                    <a href="{{ route('news.show', $article['slug']) }}" class="block">
                        <img class="h-52 w-full object-cover"
                            src="{{ Str::startsWith($article['image'] ?? '', 'http') ? $article['image'] : 'https://cms.asets.id/storage/' . ($article['image'] ?? 'default-news-image.jpg') }}"
                            alt="{{ $article['title'] ?? 'Article image' }}" />
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/60 to-transparent h-24">
                        </div>
                    </a>
                </div>

                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex items-center justify-between text-sm mb-3">
                        <div class="flex items-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($article['published_date'] ?? now())->format('F j, Y') }}</span>
                        </div>
                        <span
                            class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-medium">
                            {{ $article['category'] ?? 'Asets' }}
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-custom-primary hover:text-purple-800 mb-3 line-clamp-2">
                        <a href="{{ route('news.show', $article['slug']) }}">
                            {{ $article['title'] ?? 'No Title' }}
                        </a>
                    </h2>

                    <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">
                        {{ Str::limit(strip_tags($article['content'] ?? 'No Description'), 150) }}
                    </p>

                    <div class="pt-2 mt-auto">
                        <a href="{{ route('news.show', $article['slug']) }}"
                            class="inline-flex items-center text-sm font-medium text-custom-primary hover:text-custom-secondary transition-colors">
                            Read Full Article
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if ('IntersectionObserver' in window) {
                const newsArticles = document.querySelectorAll('.article-card');
                const articleObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('article-visible');
                            articleObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1
                });
                newsArticles.forEach(article => {
                    articleObserver.observe(article);
                });
            }
        });
    </script>
@endpush
