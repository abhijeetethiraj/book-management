<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GutendexService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.gutendex.base_url');
    }

    public function formatbook(array $book): array
    {
        return [
            'id' => $book['id'],
            'title' => $book['title'],
            'author' => $book['authors'][0]['name'] ?? 'Unknown',
            'language' => $book['languages'][0] ?? 'N/A',
            'cover' => $book['formats']['image/jpeg'] ?? null,
            'download_count' => $book['download_count'],
        ];
    }

    public function getBooks()
    {
        return Cache::remember('books', now()->addMinutes(10), function () {

            $response = Http::get($this->baseUrl);

            if (! $response->successful()) {
                return [
                    'error' => 'Failed to fetch books',
                ];
            }

            $books = collect($response->json()['results'])
                ->map(fn($book) => $this->formatbook($book))
                ->values()
                ->toArray();

            return [
                'count' => $response->json()['count'],
                'books' => $books,
            ];
        });
    }

    public function getBookByGutendexId($gutendexId)
    {
        return Cache::remember('book:'.$gutendexId, now()->addMinute(10),function() use($gutendexId){

            $response = Http::get($this->baseUrl);
            if (! $response->successful()) {
                return [
                    'error' => 'Failed to fetch books',
                ];
            }
            $book = collect($response->json()['results'])
                ->firstWhere('id', (int) $gutendexId);
    
            if (! $book) {
                return [
                    'error' => 'Book not found',
                ];
            }
    
            return $this->formatbook($book);
        });
    }

    public function search($query)
    {

        return Cache::remember('search:' . $query, now()->addMinute(10), function () use ($query) {

            $response = Http::get($this->baseUrl, [
                'search' => $query,
            ]);
            if (! $response->successful()) {
                return [
                    'error' => 'Failed to fetch books',
                ];
            }
            $books = collect($response->json()['results'])
                ->map(fn($book) => $this->formatbook($book))
                ->values()
                ->toArray();

            return [
                'count' => $response->json()['count'],
                'books' => $books,
            ];
        });
    }
}
