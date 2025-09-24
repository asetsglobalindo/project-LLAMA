<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsApiService
{
    protected $baseUrl;
    protected $category;

    public function __construct($category = 'Asets')
    {
        $this->baseUrl = config('services.external_apis.news_base_url');
        $this->category = $category;
    }

    public function getAllNews($search = null)
    {
        try {
            $response = Http::get($this->baseUrl);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['status']) && $responseData['status'] === true && isset($responseData['data'])) {
                    // Filter news by category
                    $filteredNews = array_filter($responseData['data'], function ($item) use ($search) {
                        $categoryMatch = isset($item['category']) && $item['category'] === $this->category;

                        // If search term is provided, filter by title and content too
                        if ($search) {
                            $titleMatch = isset($item['title']) &&
                                Str::contains(strtolower($item['title']), strtolower($search));

                            $contentMatch = isset($item['content']) &&
                                Str::contains(strtolower(strip_tags($item['content'])), strtolower($search));

                            $tagsMatch = isset($item['tags']) &&
                                Str::contains(strtolower($item['tags']), strtolower($search));

                            return $categoryMatch && ($titleMatch || $contentMatch || $tagsMatch);
                        }

                        return $categoryMatch;
                    });

                    // Re-index the array
                    return ['status' => true, 'data' => array_values($filteredNews)];
                }
            }

            return ['status' => false, 'message' => 'Failed to fetch news', 'data' => []];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'data' => []];
        }
    }

    public function getNewsBySlug($slug)
    {
        try {
            // Get all news and filter by category and slug
            $response = Http::get($this->baseUrl);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['status']) && $responseData['status'] === true && isset($responseData['data'])) {
                    $allNews = $responseData['data'];

                    // Filter news by category and slug
                    $filteredNews = array_filter($allNews, function ($item) use ($slug) {
                        return isset($item['category']) &&
                            $item['category'] === $this->category &&
                            isset($item['slug']) &&
                            $item['slug'] === $slug;
                    });

                    if (!empty($filteredNews)) {
                        // Get the first news item that matches the slug
                        return ['status' => true, 'data' => reset($filteredNews)];
                    }
                }
            }

            // Try using a specific endpoint for the slug if available
            $slugResponse = Http::get("{$this->baseUrl}/{$slug}");

            if ($slugResponse->successful()) {
                $slugResponseData = $slugResponse->json();

                if (
                    isset($slugResponseData['status']) &&
                    $slugResponseData['status'] === true &&
                    isset($slugResponseData['data'])
                ) {

                    // Check if the news from the slug endpoint belongs to the desired category
                    if (
                        isset($slugResponseData['data']['category']) &&
                        $slugResponseData['data']['category'] === $this->category
                    ) {
                        return ['status' => true, 'data' => $slugResponseData['data']];
                    }
                }
            }

            return ['status' => false, 'message' => 'News not found', 'data' => null];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'data' => null];
        }
    }

    public function getRelatedNews($slug, $limit = 3)
    {
        try {
            // Get current article first to identify related tags/content
            $currentArticle = $this->getNewsBySlug($slug);

            if (!$currentArticle['status'] || !isset($currentArticle['data'])) {
                return ['status' => false, 'message' => 'Article not found', 'data' => []];
            }

            // Get all news
            $allNews = $this->getAllNews();

            if (!$allNews['status']) {
                return ['status' => false, 'message' => 'Failed to fetch news', 'data' => []];
            }

            $currentArticleData = $currentArticle['data'];
            $allNewsData = $allNews['data'];

            // Remove current article from the list
            $otherArticles = array_filter($allNewsData, function ($item) use ($slug) {
                return !isset($item['slug']) || $item['slug'] !== $slug;
            });

            // Calculate relevance scores based on tags and content similarity
            $scoredArticles = [];
            foreach ($otherArticles as $article) {
                $score = 0;

                // Check tags similarity
                if (isset($currentArticleData['tags']) && isset($article['tags'])) {
                    $currentTags = explode(' ', str_replace('#', '', $currentArticleData['tags']));
                    $articleTags = explode(' ', str_replace('#', '', $article['tags']));

                    // Count matching tags
                    $matchingTags = array_intersect($currentTags, $articleTags);
                    $score += count($matchingTags) * 10; // Weight tags heavily
                }

                // Add to scored articles
                $scoredArticles[] = [
                    'article' => $article,
                    'score' => $score
                ];
            }

            // Sort by score (highest first)
            usort($scoredArticles, function ($a, $b) {
                return $b['score'] - $a['score'];
            });

            // If we don't have enough related articles by tags, supplement with most recent
            if (count($scoredArticles) < $limit) {
                usort($otherArticles, function ($a, $b) {
                    $dateA = isset($a['published_date']) ? strtotime($a['published_date']) : 0;
                    $dateB = isset($b['published_date']) ? strtotime($b['published_date']) : 0;
                    return $dateB - $dateA;
                });

                // Add recent articles that aren't already in the scored list
                foreach ($otherArticles as $article) {
                    $alreadyIncluded = false;
                    foreach ($scoredArticles as $scoredArticle) {
                        if ($scoredArticle['article']['id'] === $article['id']) {
                            $alreadyIncluded = true;
                            break;
                        }
                    }

                    if (!$alreadyIncluded) {
                        $scoredArticles[] = [
                            'article' => $article,
                            'score' => 0
                        ];

                        if (count($scoredArticles) >= $limit) {
                            break;
                        }
                    }
                }
            }

            // Extract just the articles from the scored list
            $relatedArticles = array_slice(array_column($scoredArticles, 'article'), 0, $limit);

            return ['status' => true, 'data' => $relatedArticles];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'data' => []];
        }
    }

    // Method to set a different category if needed
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
}
