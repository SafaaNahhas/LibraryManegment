<?php
// namespace App\Services;

// use Exception;
// use Carbon\Carbon;
// use App\Models\BorrowRecord;
// use Illuminate\Support\Facades\Log;

// class BorrowRecordService
// {
//     public function createBorrowRecord($bookId)
//     {
//         try {
//             return BorrowRecord::create([
//                 'book_id' => $bookId,
//                 'borrowed_at' => Carbon::now(),
//                 'due_date' => Carbon::now()->addDays(14),
//             ]);
//         } catch (Exception $e) {
//             Log::error('Error creating borrow record: ' . $e->getMessage());
//             throw $e;         }
//     }

//     public function getBorrowRecordById($id)
//     {
//         try {
//             return BorrowRecord::find($id);
//         } catch (Exception $e) {
//             Log::error('Error finding borrow record with ID ' . $id . ': ' . $e->getMessage());
//             throw $e;
//         }
//     }

//     public function returnBook(BorrowRecord $borrowRecord)
//     {
//         try {
//             $borrowRecord->update(['returned_at' => Carbon::now()]);
//             return $borrowRecord;
//         } catch (Exception $e) {
//             Log::error('Error returning book with ID ' . $borrowRecord->book_id . ': ' . $e->getMessage());
//             throw $e;
//         }
//     }

//     public function isBookAlreadyBorrowed($bookId)
//     {
//         try {
//             return BorrowRecord::where('book_id', $bookId)
//                                 ->whereNull('returned_at')
//                                 ->exists();
//         } catch (Exception $e) {
//             Log::error('Error checking if book with ID ' . $bookId . ' is already borrowed: ' . $e->getMessage());
//             throw $e;
//         }
//     }
// }


namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Log;

class BorrowRecordService
{
    // public function createBorrowRecord($bookId)
    // {
    //     try {
    //         // تحديث حالة الكتاب إلى "borrowed"
    //         $book = Book::findOrFail($bookId);
    //         $book->status = 'borrowed';
    //         $book->save();

    //         return BorrowRecord::create([
    //             'book_id' => $bookId,
    //             'borrowed_at' => Carbon::now(),
    //             'due_date' => Carbon::now()->addDays(14),
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error('Error creating borrow record: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    // public function getBorrowRecordById($id)
    // {
    //     try {
    //         return BorrowRecord::find($id);
    //     } catch (Exception $e) {
    //         Log::error('Error finding borrow record with ID ' . $id . ': ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    // public function returnBook(BorrowRecord $borrowRecord)
    // {
    //     try {
    //         // تحديث حالة الكتاب إلى "available"
    //         $book = Book::findOrFail($borrowRecord->book_id);
    //         $book->status = 'available';
    //         $book->save();

    //         $borrowRecord->update(['returned_at' => Carbon::now()]);
    //         return $borrowRecord;
    //     } catch (Exception $e) {
    //         Log::error('Error returning book with ID ' . $borrowRecord->book_id . ': ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    // public function createBorrowRecord($bookId, $borrowedAt)
    // {
    //     try {
    //         // التأكد من أن الكتاب موجود
    //         $book = Book::findOrFail($bookId);

    //         // التأكد من أن حالة الكتاب هي "available"
    //         if ($book->status !== 'available') {
    //             throw new Exception('Book is not available for borrowing.');
    //         }

    //         // التأكد من أن تاريخ الاستعارة ليس قبل تاريخ اليوم
    //         if (Carbon::parse($borrowedAt)->isBefore(Carbon::today())) {
    //             throw new Exception('Borrowing date cannot be before today.');
    //         }

    //         // تحديث حالة الكتاب إلى "borrowed"
    //         $book->status = 'borrowed';
    //         $book->save();

    //         // إنشاء سجل الاستعارة مع تعيين تاريخ الإرجاع بعد 14 يومًا من تاريخ الاستعارة
    //         return BorrowRecord::create([
    //             'book_id' => $bookId,
    //             'borrowed_at' => $borrowedAt,
    //             'due_date' => Carbon::parse($borrowedAt)->addDays(14),
    //         ]);
    //     } catch (Exception $e) {
    //         Log::error('Error creating borrow record: ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error creating borrow record '));

    //     }
    // }

    // public function returnBook(BorrowRecord $borrowRecord)
    // {
    //     try {
    //         // التأكد من أن الكتاب موجود
    //         $book = Book::findOrFail($borrowRecord->book_id);

    //         // التأكد من أن حالة الكتاب هي "borrowed"
    //         if ($book->status !== 'borrowed') {
    //             throw new Exception('Book is not currently borrowed.');
    //         }

    //         // التأكد من أن المستخدم الذي استعار الكتاب هو نفسه الذي يريد إرجاعه
    //         if ($borrowRecord->user_id !== auth()->id()) {
    //             throw new Exception('Unauthorized to return this book.');
    //         }

    //         // تسجيل وقت الإرجاع الحالي
    //         $currentDateTime = Carbon::now();

    //         // التحقق من حالة الإرجاع بناءً على تاريخ الإرجاع المتوقع
    //         if ($currentDateTime->isBefore($borrowRecord->borrowed_at)) {
    //             throw new Exception('Cannot return book before the borrowed date.');
    //         } elseif ($currentDateTime->isAfter($borrowRecord->due_date)) {
    //             $message = 'Book returned late. Please be mindful of return dates.';
    //         } else {
    //             $message = 'Thank you for returning the book on time.';
    //         }

    //         // تحديث حالة الكتاب إلى "available"
    //         $book->status = 'available';
    //         $book->save();

    //         // تحديث سجل الاستعارة
    //         $borrowRecord->update(['returned_at' => $currentDateTime]);

    //         return ['borrowRecord' => $borrowRecord, 'message' => $message];
    //     } catch (Exception $e) {
    //         Log::error('Error returning book with ID ' . $borrowRecord->book_id . ': ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error returning book with ID'));

    //     }
    // }
    // public function isBookAlreadyBorrowed($bookId)
    // {
    //     try {
    //         return BorrowRecord::where('book_id', $bookId)
    //                             ->whereNull('returned_at')
    //                             ->exists();
    //     } catch (Exception $e) {
    //         Log::error('Error checking if book with ID ' . $bookId . ' is already borrowed: ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error checking if book with ID'));

    //     }
    // }

    // public function deleteAllBorrowRecords()
    // {
    //     try {
    //         BorrowRecord::query()->delete();
    //         Log::info('All borrow records have been deleted.');
    //     } catch (Exception $e) {
    //         Log::error('Error deleting all borrow records: ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error deleting all borrow records'));

    //     }
    // }

    // public function deleteBorrowRecord($id)
    // {
    //     try {
    //         $borrowRecord = BorrowRecord::findOrFail($id);
    //         $borrowRecord->delete();
    //         Log::info('Borrow record with ID ' . $id . ' has been deleted.');
    //     } catch (Exception $e) {
    //         Log::error('Error deleting borrow record with ID ' . $id . ': ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error deleting borrow record with ID'));

    //     }
    // }

    // public function getBorrowRecordsByUserId($userId)
    // {
    //     try {
    //         return BorrowRecord::where('user_id', $userId)->get();
    //     } catch (Exception $e) {
    //         Log::error('Error retrieving borrow records for user ID ' . $userId . ': ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error retrieving borrow records for user ID '));

    //     }
    // }

    // public function getBorrowRecordById($id)
    // {
    //     try {
    //         return BorrowRecord::find($id);
    //     } catch (Exception $e) {
    //         Log::error('Error finding borrow record with ID ' . $id . ': ' . $e->getMessage());
    //         // throw $e;
    //         throw new Exception(ApiResponseService::error('Error finding borrow record with ID '));

    //     }
    // }
      /**
     * Create a new borrow record.
     *
     * @param int $bookId
     * @param string $borrowedAt
     * @return BorrowRecord
     * @throws Exception
     */
    public function createBorrowRecord($bookId)
    {
        try {
            $book = Book::findOrFail($bookId);

            if ($book->status !== 'available') {
                throw new Exception('Book is not available for borrowing.');
            }

            // if (Carbon::parse($borrowedAt)->isBefore(Carbon::today())) {
            //     throw new Exception('Borrowing date cannot be before today.');
            // }
            $borrowedAt = Carbon::now();

            $book->status = 'borrowed';
            $book->save();

            return BorrowRecord::create([
                'book_id' => $bookId,
                'borrowed_at' => $borrowedAt,
                'due_date' => Carbon::parse($borrowedAt)->addDays(14),
            ]);
        } catch (Exception $e) {
            Log::error('Error creating borrow record: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error creating borrow record'));
        }
    }

    /**
     * Return a borrowed book.
     *
     * @param BorrowRecord $borrowRecord
     * @return array
     * @throws Exception
     */
    public function returnBook(BorrowRecord $borrowRecord)
    {
        try {
            $book = Book::findOrFail($borrowRecord->book_id);

            if ($book->status !== 'borrowed') {
                throw new Exception('Book is not currently borrowed.');
            }

            if ($borrowRecord->user_id !== auth()->id()) {
                throw new Exception('Unauthorized to return this book.');
            }

            $currentDateTime = Carbon::now();

            if ($currentDateTime->isBefore($borrowRecord->borrowed_at)) {
                throw new Exception('Cannot return book before the borrowed date.');
            } elseif ($currentDateTime->isAfter($borrowRecord->due_date)) {
                $message = 'Book returned late. Please be mindful of return dates.';
            } else {
                $message = 'Thank you for returning the book on time.';
            }

            $book->status = 'available';
            $book->save();

            $borrowRecord->update(['returned_at' => $currentDateTime]);

            return ['borrowRecord' => $borrowRecord, 'message' => $message];
        } catch (Exception $e) {
            Log::error('Error returning book with ID ' . $borrowRecord->book_id . ': ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error returning book with ID'));
        }
    }

    /**
     * Check if a book is already borrowed.
     *
     * @param int $bookId
     * @return bool
     * @throws Exception
     */
    public function isBookAlreadyBorrowed($bookId)
    {
        try {
            return BorrowRecord::where('book_id', $bookId)
                                ->whereNull('returned_at')
                                ->exists();
        } catch (Exception $e) {
            Log::error('Error checking if book with ID ' . $bookId . ' is already borrowed: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error checking if book with ID'));
        }
    }

    /**
     * Delete all borrow records.
     *
     * @return void
     * @throws Exception
     */
    public function deleteAllBorrowRecords()
    {
        try {
            BorrowRecord::query()->delete();
            Log::info('All borrow records have been deleted.');
        } catch (Exception $e) {
            Log::error('Error deleting all borrow records: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error deleting all borrow records'));
        }
    }

    /**
     * Delete a specific borrow record.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteBorrowRecord($id)
    {
        try {
            $borrowRecord = BorrowRecord::findOrFail($id);
            $borrowRecord->delete();
            Log::info('Borrow record with ID ' . $id . ' has been deleted.');
        } catch (Exception $e) {
            Log::error('Error deleting borrow record with ID ' . $id . ': ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error deleting borrow record with ID'));
        }
    }

    /**
     * Get borrow records by user ID.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function getBorrowRecordsByUserId($userId)
    {
        try {
            return BorrowRecord::where('user_id', $userId)->get();
        } catch (Exception $e) {
            Log::error('Error retrieving borrow records for user ID ' . $userId . ': ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error retrieving borrow records for user ID'));
        }
    }

    /**
     * Get a borrow record by ID.
     *
     * @param int $id
     * @return BorrowRecord|null
     * @throws Exception
     */
    public function getBorrowRecordById($id)
    {
        try {
            return BorrowRecord::find($id);
        } catch (Exception $e) {
            Log::error('Error finding borrow record with ID ' . $id . ': ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error finding borrow record with ID'));
        }
    }
}
