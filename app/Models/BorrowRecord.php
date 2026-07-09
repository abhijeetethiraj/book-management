<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    protected $fillable = [
        'book_id',
        'borrower_name',
        'borrowed_at',
        'returned_at',
        'status'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
