<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        $response = Http::get($this->baseUrl);

        if (! $response->successful()) {
            return [
                'error' => 'Failed to fetch books',
            ];
        }

        $books = collect($response->json()['results'])->map(fn($book) => $this->formatbook($book));

        return [
            'count' => $response->json()['count'],
            'books' => $books,
        ];
    }

    public function show($id)
    {
        $response = Http::get($this->baseUrl);
        if (! $response->successful()) {
            return [
                'error' => 'Failed to fetch books',
            ];
        }
        $book = collect($response->json()['results'])
            ->firstWhere('id', (int) $id);

        if (! $book) {
            return [
                'error' => 'Book not found',
            ];
        }

        return $this->formatbook($book);
    }

    public function search($query)
    {
        $response = Http::get($this->baseUrl, [
            'search' => $query,
        ]);
        if (! $response->successful()) {
            return [
                'error' => 'Failed to fetch books',
            ];
        }
        $books = collect($response->json()['results'])
            ->map(fn($book) => $this->formatBook($book));

        return [
            'count' => $response->json()['count'],
            'books' => $books,
        ];
    }
}
