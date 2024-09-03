<?php

namespace App\Models;

use App\Models\BorrowRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'author',
        'classification',
        'description',
        'published_at',
        'status'
    ];
     /**
     * Get the borrow records associated with the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
    /**
     * Get the ratings associated with the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
     /**
     * Scope a query to only include available books.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include borrowed books.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }
}
