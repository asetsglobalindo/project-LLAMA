<?php

namespace App\Http\Controllers;

use App\Services\NewsApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService)
    {
        $this->newsApiService = $newsApiService;
    }

    public function index(Request $request)
    {
        // Get search query if any
        $search = $request->input('search');

        // Get all news with search filter if provided
        $response = $this->newsApiService->getAllNews($search);
        $allNews = $response['status'] ? $response['data'] : [];

        // Set items per page
        $perPage = 9;

        // Get current page from the request
        $currentPage = $request->input('page', 1);

        // Create a collection from the array
        $newsCollection = collect($allNews);

        // Sort by published_date (latest first)
        $newsCollection = $newsCollection->sortByDesc('published_date');

        // Paginate the collection
        $paginator = new LengthAwarePaginator(
            $newsCollection->forPage($currentPage, $perPage),
            $newsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pages.news.index', [
            'news' => $paginator->items(),
            'paginator' => $paginator,
            'search' => $search
        ]);
    }

    public function show($slug)
    {
        // Get the specific news by slug
        $response = $this->newsApiService->getNewsBySlug($slug);

        if (!$response['status'] || !isset($response['data'])) {
            abort(404, 'Article not found');
        }

        $article = $response['data'];

        // Get related articles
        $relatedResponse = $this->newsApiService->getRelatedNews($slug, 3);
        $relatedArticles = $relatedResponse['status'] ? $relatedResponse['data'] : [];

        return view('pages.news.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles
        ]);
    }
}
