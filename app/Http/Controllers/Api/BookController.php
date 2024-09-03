<?php
namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Http\Requests\BookRequest\StoreBookRequest;
use App\Http\Requests\BookRequest\UpdateBookRequest;

/**
 * BookController handles CRUD operations for books.
 */
class BookController extends Controller
{
    /**
     * @var BookService
     */
    protected $bookService;

    /**
 * BookController constructor.
 *
 * @param BookService $bookService
 *
 * Applies the 'auth' middleware to all methods,
 * and the 'admin' middleware to all methods except 'index' and 'show'.
 */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of books.
      * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // return $this->bookService->getAllBooks($request);
        return $this->bookService->getAllBooks($request);

    }

    /**
     * Store a newly created book in storage.
     *
     * @param StoreBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookRequest $request)
    {
        return $this->bookService->createBook($request->validated());
    }

    public function show($id)
{
    $book = $this->bookService->getBookById($id);


    return ApiResponseService::success($book);
}

    /**
     * Update the specified book in storage.
     *
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
                return ApiResponseService::error('Book not found', 404);
            }

        $updatedBook = $this->bookService->updateBook($book, $request->validated());

        return ApiResponseService::success($updatedBook, 'Book updated successfully');
    }



    /**
     * Remove the specified book from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = $this->bookService->getBookById($id);

        // if (!$book) {
        //     return ApiResponseService::error('Book not found', 404);
        // }

        return $this->bookService->deleteBook($book);
    }
}
