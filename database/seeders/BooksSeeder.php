<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => '1984',
            'author' => 'George Orwell',
            'classification' => ' Education',
            'description' => 'A dystopian novel about a totalitarian regime.',
            'published_at' => Carbon::now(),
            'status' => 'available',
        ]);

        Book::create([
            'title' => 'To Kill a Mockingbird',
            'author' => 'Harper Lee',
            'classification' => 'novel',
            'description' => 'A novel about racial injustice in the Deep South.',
            'published_at' => Carbon::now(),
            'status' => 'available',
        ]);
    }
}
