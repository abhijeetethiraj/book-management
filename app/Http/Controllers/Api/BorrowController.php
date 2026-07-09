<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BorrowService;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    protected BorrowService $borrowservice;

    public function __construct(BorrowService $borrowservice)
    {
        $this->borrowservice = $borrowservice;

    }

    public function borrow(Request $request)
    {
        $data = $request->validate([
            'book_id'=> 'required|integer',
            'borrower_name'=> 'required|string|max:255',
        ]);

        $borrow = $this->borrowservice->borrow($data);
        return response()->json($borrow,201);
    }

    public function returnBook($id)
    {
        $book = $this->borrowservice->returnBook($id);
        return response()->json($book);
    }

    public function borrowedBooks()
{
    return response()->json(
        $this->borrowservice->borrowedBooks()
    );
}
   
}
