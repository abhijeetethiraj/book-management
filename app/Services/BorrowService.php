<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Repositories\BorrowRepository;

class BorrowService
{
    protected BorrowRepository $borrowRepository;

    public function __construct(BorrowRepository $borrowRepository)
    {
        $this->borrowRepository = $borrowRepository;
    }

    public function borrow(array $data)
    {
        $lock = Cache::lock('borrow-lock-' . $data['book_id'], 10);

        if (! $lock->get()) {
            return [
                'error' => 'This book is currently being borrowed. Please try again.',
            ];
        }

        try {
            sleep(10);
            $book = $this->borrowRepository->findBook($data['book_id']);

            if (! $book) {
                return [
                    'error' => 'Book not found',
                ];
            }

            if ($this->borrowRepository->isBookBorrowed($data['book_id'])) {
                return [
                    'error' => 'Book is already borrowed.',
                ];
            }

            $data['borrowed_at'] = now();
            $data['status'] = 'borrowed';

            return $this->borrowRepository->borrowBook($data);
        } finally {

            // $lock->release();
        }
    }

    public function returnBook($id)
    {
        $borrowRecord = $this->borrowRepository->findBorrowRecord($id);
        if (!$borrowRecord) {
            return [
                'error' => 'Borrow record not found',
            ];
        }

        if ($borrowRecord->status == 'returned') {
            return [
                'error' => 'Book already returned',
            ];
        }

        return $this->borrowRepository->update(
            $borrowRecord,
            [
                'status' => 'returned',
                'returned_at' => now(),
            ]
        );
    }

    public function borrowedBooks()
    {
        return $this->borrowRepository->getAllBorrowedBooks();
    }
}
