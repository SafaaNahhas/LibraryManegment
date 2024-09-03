<?php
namespace App\Services;

use Exception;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * BookService handles business logic for book operations.
 */
class BookService
{
    /**
     * Create a new book.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function createBook(array $data)
    {
        try {
            return Book::create($data);
        } catch (Exception $e) {
            Log::error('Error creating book: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error creating book'));
        }
    }

    /**
     * Update an existing book.
     *
     * @param Book $book
     * @param array $data
     * @return mixed
     * @throws Exception
     */

    public function updateBook(Book $book, array $data)
    {
        try {
            // Check if the book exists
        // if (!$book) {
        //     throw new Exception(ApiResponseService::error('Book not found', 404));
        // }
            $book->update($data);
            return $book;
        } catch (Exception $e) {
            Log::error('Error updating book: ' . $e->getMessage());
            throw new Exception('Failed to update book: Specific detail about the error');
        }
    }


    /**
     * Delete a book.
     *
     * @param Book $book
     * @return mixed
     * @throws Exception
     */
    public function deleteBook(Book $book)
    {
        try {
        // Check if the book exists
        // if (!$book) {
        //     throw new Exception(ApiResponseService::error('Book not found', 404));
        // }

            // return $book->delete();
            $book->delete();
        } catch (Exception $e) {
            Log::error('Error deleting book: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error deleting book'));
        }
    }

    /**
     * Retrieve a book by its ID.
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */

public function getBookById($id)
{
    try {
        $book = Book::with([
            'ratings' => function ($query) {
                $query->select('book_id', 'rating');
            },
            'borrowRecords' => function ($query) {
                $query->select('book_id', 'due_date')->latest('due_date');
            }
        ])->find($id);

        if ($book) {
            $book->average_rating = $book->ratings->avg('rating');
            unset($book->ratings);

            if ($book->status == 'borrowed' && $book->borrowRecords->isNotEmpty()) {
                $book->expected_return_date = $book->borrowRecords->first()->due_date;
            } else {
                $book->expected_return_date = null;
            }

            unset($book->borrowRecords);

            return $book;
        } else {
            return 'Book not found';
        }
    } catch (Exception $e) {
        Log::error('Error retrieving book with ID ' . $id . ': ' . $e->getMessage());
        throw new Exception(ApiResponseService::error('Error retrieving book'));
    }
}


    /**
     * Retrieve all books.
     *
     * @return mixed
     * @throws Exception
     */

    public function getAllBooks($request)
    {
        try {
            $query = Book::query();

            // Apply filters based on request parameters
            if ($request->has('author')) {
                $query->where('author', $request->author);
            }
            if ($request->has('classification')) {
                $query->where('classification', $request->classification);
            }
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Fetch books along with borrowRecords and ratings
            $books = $query->with([
                'borrowRecords' => function ($query) {
                    $query->select('book_id', 'due_date')->latest('due_date');
                },
                // 'ratings' // Fetch all related ratings
            ])->get();

            // Adding expected return date and average rating to books
            foreach ($books as $book) {
                // Calculate average rating
                $book->average_rating = $book->ratings->isNotEmpty() ? $book->ratings->avg('rating') : null;

                // If the book is borrowed, add the expected return date
                if ($book->status == 'borrowed') {
                    if ($book->borrowRecords->isNotEmpty()) {
                        // Access the latest borrow record
                        $book->expected_return_date = $book->borrowRecords->first()->due_date;
                    } else {
                        $book->expected_return_date = null;
                    }
                } else {
                    // If the book is available, do not include borrowRecords
                    $book->expected_return_date = null;
                }

                // Remove borrowRecords from the output
                unset($book->borrowRecords);
                unset($book->ratings);

            }

            return $books;
        } catch (Exception $e) {
            Log::error('Error retrieving all books: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error retrieving books'));
        }
    }



}


