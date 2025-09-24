@extends('layouts.app')
@section('title', $article['title'] ?? 'Article Detail | Asets')

@php
    // Set the $hideNavbar variable to true for this view
    $hideNavbar = true;
@endphp

@push('styles')
    <style>
        /* Custom styles for article page */
        .article-container {
            background-color: #f9fafb;
        }

        .article-header {
            position: relative;
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .article-title {
            font-size: 1.875rem;
            line-height: 1.3;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
        }

        @media (min-width: 768px) {
            .article-title {
                font-size: 2.25rem;
            }
        }

        .article-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .article-date {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            background-color: #f3e8ff;
            color: #7e22ce;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Full width banner image with rounded corners */
        .banner-image-container {
            width: 100%;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .banner-image {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            display: block;
            border-radius: 0.75rem;
            /* Added rounded corners */
        }

        .article-content-wrapper {
            background-color: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .article-content {
            line-height: 1.8;
            color: #374151;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #4c1d95;
        }

        .article-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #6d28d9;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }

        .article-content a {
            color: #4f46e5;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .article-content a:hover {
            color: #4338ca;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .article-content ul li,
        .article-content ol li {
            margin-bottom: 0.5rem;
        }

        /* Tags section */
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            background-color: #f3f4f6;
            color: #4b5563;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .tag:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        /* Top navigation area */
        .top-nav {
            padding: 1.5rem 0;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            color: #4f46e5;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-button:hover {
            color: #4338ca;
        }

        .back-button svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }

        /* Share and actions section */
        .article-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background-color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        @media (min-width: 640px) {
            .article-actions {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .share-container {
            display: flex;
            align-items: center;
        }

        .share-label {
            margin-right: 0.75rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .share-buttons {
            display: flex;
            gap: 0.5rem;
        }

        /* Share buttons */
        .share-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            transition: all 0.2s ease;
        }

        .share-button:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .share-facebook {
            background-color: #1877f2;
        }

        .share-twitter {
            background-color: #1da1f2;
        }

        .share-linkedin {
            background-color: #0a66c2;
        }

        .share-whatsapp {
            background-color: #25d366;
        }

        /* Related articles */
        .related-section {
            margin-bottom: 3rem;
        }

        .related-heading {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .related-card {
            background-color: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            height: 100%;
        }

        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .related-content {
            padding: 1rem;
        }

        .related-date {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .related-title {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
            color: #1f2937;
            margin-bottom: 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-link {
            display: inline-flex;
            align-items: center;
            color: #4f46e5;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .related-link:hover {
            color: #4338ca;
        }

        .related-link svg {
            width: 0.875rem;
            height: 0.875rem;
            margin-left: 0.25rem;
        }

        /* Rectangular image container for related articles */
        .rect-img-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            overflow: hidden;
            background-color: #f3f4f6;
        }

        .rect-img-container img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
@endpush

@section('content')
    <div class="article-container min-h-screen pb-16">
        <!-- Top Navigation -->
        <div class="bg-white shadow-sm">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="top-nav">
                    <a href="{{ route('news.index') }}" class="back-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Back to All Articles
                    </a>
                </div>
            </div>
        </div>

        <!-- Container for title and content -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-10">
            <!-- Article Header -->
            <div class="article-header p-6 md:p-8">
                <div class="article-meta">
                    @if (isset($article['category']))
                        <span class="category-badge">
                            {{ $article['category'] }}
                        </span>
                    @endif
                    <div class="article-date">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($article['published_date'] ?? now())->format('F j, Y') }}</span>
                    </div>
                </div>

                <h1 class="article-title">
                    {{ $article['title'] ?? 'No Title' }}
                </h1>
            </div>

            <!-- Full-width Banner Image with Rounded Corners -->
            @if (isset($article['image']) && $article['image'])
                <div class="banner-image-container">
                    <img src="{{ Str::startsWith($article['image'], 'http') ? $article['image'] : 'https://cms.asets.id/storage/' . $article['image'] }}"
                        alt="{{ $article['title'] ?? 'Article image' }}" class="banner-image">
                </div>
            @endif

            <!-- Article Content -->
            <div class="article-content-wrapper">
                <div class="article-content prose prose-lg max-w-none">
                    {!! $article['content'] ?? 'No content available' !!}
                </div>

                <!-- Tags if available -->
                @if (isset($article['tags']) && !empty($article['tags']))
                    <div class="tags-container">
                        @foreach (explode(' ', str_replace('#', '', $article['tags'])) as $tag)
                            @if (!empty($tag))
                                <a href="{{ route('news.index', ['search' => $tag]) }}" class="tag">
                                    #{{ $tag }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Share & Navigation Buttons -->
            <div class="article-actions">
                <a href="{{ route('news.index') }}" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to All Articles
                </a>

                <!-- Social Share buttons -->
                <div class="share-container">
                    <span class="share-label">Share:</span>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener noreferrer" class="share-button share-facebook text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-facebook" viewBox="0 0 16 16">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article['title'] ?? 'Check out this article') }}"
                            target="_blank" rel="noopener noreferrer" class="share-button share-twitter text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-twitter" viewBox="0 0 16 16">
                                <path
                                    d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($article['title'] ?? 'Check out this article') }}"
                            target="_blank" rel="noopener noreferrer" class="share-button share-linkedin text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-linkedin" viewBox="0 0 16 16">
                                <path
                                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
                            </svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article['title'] ?? 'Check out this article') }}%20{{ urlencode(request()->url()) }}"
                            target="_blank" rel="noopener noreferrer" class="share-button share-whatsapp text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path
                                    d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Articles if available -->
            @if (isset($relatedArticles) && count($relatedArticles) > 0)
                <div class="related-section">
                    <h3 class="related-heading">Related Articles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedArticles as $relatedArticle)
                            <div class="related-card">
                                <a href="{{ route('news.show', $relatedArticle['slug'] ?? 'article-1') }}" class="block">
                                    <div class="rect-img-container">
                                        <img src="{{ Str::startsWith($relatedArticle['image'] ?? '', 'http') ? $relatedArticle['image'] : 'https://cms.asets.id/storage/' . ($relatedArticle['image'] ?? 'default-news-image.jpg') }}"
                                            alt="{{ $relatedArticle['title'] ?? 'Related article' }}">
                                    </div>
                                </a>
                                <div class="related-content">
                                    <div class="related-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($relatedArticle['published_date'] ?? now())->format('F j, Y') }}</span>
                                    </div>
                                    <h4 class="related-title">
                                        <a href="{{ route('news.show', $relatedArticle['slug'] ?? 'article-1') }}">
                                            {{ $relatedArticle['title'] ?? 'No Title' }}
                                        </a>
                                    </h4>
                                    <a href="{{ route('news.show', $relatedArticle['slug'] ?? 'article-1') }}"
                                        class="related-link">
                                        Read Article
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Social Sharing Tracking (Optional) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all social share buttons
            const shareButtons = document.querySelectorAll('.share-button');

            // Add click event listener to each button
            shareButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Get the platform name from the class
                    const platform = this.classList.contains('share-facebook') ? 'Facebook' :
                        this.classList.contains('share-twitter') ? 'Twitter' :
                        this.classList.contains('share-linkedin') ? 'LinkedIn' :
                        this.classList.contains('share-whatsapp') ? 'WhatsApp' : 'Unknown';

                    console.log(`Article shared on ${platform}`);

                    // If you have analytics, you can track the share event here
                    // Example: Google Analytics tracking code
                });
            });
        });
    </script>
@endsection
