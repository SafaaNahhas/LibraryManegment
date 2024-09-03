<?php
namespace App\Services;

use App\Models\Rating;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class RatingService
{
    /**
     * Create a new rating for a book.
     *
     * @param array $data
     * @return Rating
     * @throws Exception
     */
    public function createRating(array $data)
    {
        try {
            $book = Book::findOrFail($data['book_id']);

            $borrowRecord = $book->borrowRecords()
                ->where('user_id', $data['user_id'])
                ->whereNotNull('returned_at')
                ->first();

            if (!$borrowRecord) {
                throw new Exception('You must borrow and return the book before rating it.');
            }

            $rating = Rating::create($data);

            return $rating;
        } catch (Exception $e) {
            Log::error('Error creating rating: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error creating rating'));        }
    }
    /**
     * Update an existing rating.
     *
     * @param Rating $rating
     * @param array $data
     * @return Rating
     * @throws Exception
     */
    public function updateRating(Rating $rating, array $data)
    {
        try {
            $rating->update($data);
            return $rating;
        } catch (Exception $e) {
            Log::error('Error updating rating: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error updating rating'));
        }
    }
     /**
     * Get all ratings for a specific book.
     *
     * @param int $bookId
     * @return array
     * @throws Exception
     */
    public function getRatingsByBookId($bookId)
    {
        try {
            $ratings = Rating::where('book_id', $bookId)->get();
            $averageRating = $ratings->avg('rating');

            return [
                'ratings' => $ratings,
                'average_rating' => $averageRating,
            ];
        } catch (Exception $e) {
            Log::error('Error retrieving ratings for book ID ' . $bookId . ': ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error retrieving ratings'));
        }
    }
     /**
     * Delete a rating.
     *
     * @param Rating $rating
     * @return bool
     * @throws Exception
     */
    public function deleteRating(Rating $rating)
    {
        try {
            $rating->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Error deleting rating: ' . $e->getMessage());
            throw new Exception(ApiResponseService::error('Error deleting rating'));
        }
    }
}
