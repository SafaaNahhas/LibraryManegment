<?php
namespace App\Http\Controllers\Api;

use App\Models\Rating;

use App\Services\RatingService;
use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Http\Requests\RatingRequest\StoreRatingRequest;
use App\Http\Requests\RatingRequest\UpdateRatingRequest;

class RatingController extends Controller
{
    /**
    * @var RatingService
    */
    protected $ratingService;
    /**
     * RatingController constructor.
     *
     * @param RatingService $ratingService
     */
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }
    /**
     * Get all ratings for a specific book.
     *
     * @param int $bookId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($bookId)
    {

            $ratingsData = $this->ratingService->getRatingsByBookId($bookId);
            return ApiResponseService::success($ratingsData, 'Ratings retrieved successfully');

    }
    /**
     * Store a new rating for a book.
     *
     * @param StoreRatingRequest $request
     * @param int $bookId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRatingRequest $request, $bookId)
    {
        $data = $request->validated();
        $data['book_id'] = $bookId;
        $data['user_id'] = auth()->id();


        $rating = $this->ratingService->createRating($data);
        return ApiResponseService::success($rating, 'Rating created successfully');

    }
     /**
     * Update an existing rating.
     *
     * @param UpdateRatingRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRatingRequest $request, $id)
    {

        $rating = Rating::findOrFail($id);

        if ($rating->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        $updatedRating = $this->ratingService->updateRating($rating, $data);
        return ApiResponseService::success($updatedRating, 'Rating updated successfully');

    }
     /**
     * Delete a rating.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( $id)
    {

        $rating = Rating::findOrFail($id);

        if ($rating->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $this->ratingService->deleteRating($rating);
        return ApiResponseService::success(null, 'Rating deleted successfully');

    }
}
