<?php
namespace App\Http\Controllers\Api;

use App\Models\Book;
use Carbon\Carbon;

use Exception;

use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Services\BorrowRecordService;
use App\Http\Requests\BorrowRecordRequest\ReturnBookRequest;
use App\Http\Requests\BorrowRecordRequest\StoreBorrowRecordRequest;

class BorrowRecordController extends Controller
{
    /**
    * @var BorrowRecordService
    */
    protected $borrowRecordService;

    /**
     * BorrowRecordController constructor.
     *
     * @param BorrowRecordService $borrowRecordService
     */
    public function __construct(BorrowRecordService $borrowRecordService)
    {
        $this->borrowRecordService = $borrowRecordService;
    }


     /**
     * Store a new borrow record.
     *
     * @param StoreBorrowRecordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBorrowRecordRequest $request)
{

    // $borrowedAt = $request->input('borrowed_at', now());

    $borrowRecord = $this->borrowRecordService->createBorrowRecord($request->book_id);

    return ApiResponseService::success($borrowRecord, 'Book borrowed successfully', 201);
}

    /**
     * Return a borrowed book.
     *
     * @param ReturnBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function returnBook(ReturnBookRequest $request)
    {
        $borrowRecord = $this->borrowRecordService->getBorrowRecordById($request->borrow_record_id);

        if (!$borrowRecord) {
            return ApiResponseService::error('Borrow record not found', 404);
        }

        $result = $this->borrowRecordService->returnBook($borrowRecord);

        return ApiResponseService::success($result['borrowRecord'], $result['message'], 200);
    }



    /**
     * Delete all borrow records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll()
    {
        $this->borrowRecordService->deleteAllBorrowRecords();
        return ApiResponseService::success(null, 'All borrow records deleted successfully', 200);
    }

     /**
     * Delete a specific borrow record.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $borrowRecord = $this->borrowRecordService->getBorrowRecordById($id);

        if (!$borrowRecord) {
            return ApiResponseService::error('Borrow record not found', 404);
        }

        $this->borrowRecordService->deleteBorrowRecord($id);

        return ApiResponseService::success(null, 'Borrow record deleted successfully', 200);
    }

    /**
     * Get all borrow records of a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserBorrowRecords($userId)
    {
        $borrowRecords = $this->borrowRecordService->getBorrowRecordsByUserId($userId);

        if (count($borrowRecords) === 0) {
            return ApiResponseService::error('No borrow records found for this user', 404);
        }

        return ApiResponseService::success($borrowRecords, 'Borrow records retrieved successfully', 200);
    }
}

