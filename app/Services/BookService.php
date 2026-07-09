<?php

namespace App\Services;
use Illuminate\Support\Facades\Cache;

use App\Repositories\BookRepository;

class BookService
{
    protected BookRepository $bookRepository;
    protected GutendexService $gutendexService;

    public function __construct(
        BookRepository $bookRepository,
        GutendexService $gutendexService
    ) {
        $this->bookRepository = $bookRepository;
        $this->gutendexService = $gutendexService;
    }

    public function store($gutendexId)
    {
        $book = $this->bookRepository->findByGutendexId($gutendexId);
        if ($book) {
            return $book;
        }
        $gutendexBook = $this->gutendexService->getBookByGutendexId($gutendexId);

        if (isset($gutendexBook['error'])) {
            return $gutendexBook;
        }

        $book= $this->bookRepository->store([
            'gutendex_id' => $gutendexBook['id'],
            'title' => $gutendexBook['title'],
            'author' => $gutendexBook['author'],
            'language' => $gutendexBook['language'],
            'cover' => $gutendexBook['cover'],
        ]);
        Cache::forget('books');
        return $book;
    }
}
