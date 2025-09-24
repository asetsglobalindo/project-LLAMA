@extends('layouts.app')
@section('title', 'News & Articles | Asets')

@php
    // Set the $hideNavbar variable to true for this view
    $hideNavbar = true;
@endphp

@push('styles')
    <style>
        /* Styles for this page only */
        .news-page {
            padding-top: 0;
        }

        .page-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 2.5rem 0;
        }

        .news-grid {
            padding-top: 2rem;
            padding-bottom: 4rem;
        }

        .top-nav {
            padding: 1.5rem 0 0.5rem 0;
        }

        .article-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            height: 100%; /* Ensure all cards have the same height */
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-top: 3rem;
        }

        .pagination li {
            margin: 0 0.25rem;
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            color: #6b7280;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination li.active span {
            background-color: var(--custom-primary, #4f46e5);
            color: #ffffff;
            border-color: var(--custom-primary, #4f46e5);
        }

        .pagination li a:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }

        .pagination li.disabled span {
            color: #d1d5db;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen news-page bg-gray-50">
        <!-- Top Navigation -->
        <div class="bg-white shadow-sm">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="top-nav">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center text-custom-primary hover:text-custom-secondary transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-3xl sm:text-4xl font-bold text-custom-primary mb-4">News & Articles</h1>
                    <p class="text-gray-600 text-lg">Stay informed with the latest updates, trends, and insights from
                        our industry and services</p>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <div class="news-grid">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Search Bar -->
                <div class="mb-8 max-w-md mx-auto">
                    <form action="{{ route('news.index') }}" method="GET" class="relative">
                        <div class="flex items-center">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search articles..."
                                class="w-full border border-gray-300 rounded-l-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-custom-primary">
                            <button type="submit"
                                class="bg-custom-primary text-white px-5 py-3 rounded-r-lg hover:bg-custom-secondary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                        @if (request('search'))
                            <div class="absolute right-16 top-1/2 transform -translate-y-1/2">
                                <a href="{{ route('news.index') }}" class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                @if (count($news) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($news as $article)
                            <div class="article-card bg-white rounded-lg overflow-hidden shadow flex flex-col">
                                <div class="relative">
                                    <a href="{{ route('news.show', $article['slug'] ?? 'article-1') }}" class="block">
                                        <img class="h-52 w-full object-cover"
                                            src="{{ Str::startsWith($article['image'] ?? '', 'http') ? $article['image'] : 'https://cms.asets.id/storage/' . ($article['image'] ?? 'default-news-image.jpg') }}"
                                            alt="{{ $article['title'] ?? 'Article image' }}" />
                                        <div
                                            class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/60 to-transparent h-24">
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

                                    <h2
                                        class="text-xl font-bold text-custom-primary hover:text-purple-800 mb-3 line-clamp-2">
                                        <a href="{{ route('news.show', $article['slug'] ?? 'article-1') }}">
                                            {{ $article['title'] ?? 'No Title' }}
                                        </a>
                                    </h2>

                                    <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">
                                        {{ Str::limit(strip_tags($article['content'] ?? 'No Description'), 150) }}
                                    </p>

                                    <div class="pt-2 mt-auto">
                                        <a href="{{ route('news.show', $article['slug'] ?? 'article-1') }}"
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

                    <!-- Pagination -->
                    <div class="mt-10">
                        {{ $paginator->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-lg shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-600 mb-3">No Articles Found</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">
                            @if (request('search'))
                                No results for "<span class="font-medium">{{ request('search') }}</span>". Try different keywords.
                            @else
                                Check back later for new articles and updates.
                            @endif
                        </p>

                        @if (request('search'))
                            <a href="{{ route('news.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-custom-primary text-white rounded-md hover:bg-custom-secondary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                View All Articles
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
