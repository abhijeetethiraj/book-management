<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\BorrowRecord;

class BorrowRepository
{
    public function findbook($id)
    {
        return Book::find($id);
    }

    public function borrowBook(array $data)
    {
        return BorrowRecord::create($data);
    }

    public function findBorrowRecord($id)
    {
        return BorrowRecord::find($id);
    }

    public function update(BorrowRecord $borrowRecord, array $data)
    {
        $borrowRecord->update($data);
        return $borrowRecord;
    }
    public function getAllBorrowedBooks()
    {
        return BorrowRecord::with('book')->get();
    }
    public function isBookBorrowed($bookId)
    {
        return BorrowRecord::where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->exists();
    }
}
