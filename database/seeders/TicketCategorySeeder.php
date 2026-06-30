<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bug Report', 'description' => 'Report a system bug or error'],
            ['name' => 'Feature Request', 'description' => 'Request a new feature or enhancement'],
            ['name' => 'Technical Support', 'description' => 'General technical issue or assistance'],
            ['name' => 'Maintenance', 'description' => 'Scheduled maintenance or updates'],
            ['name' => 'Other', 'description' => 'Other inquiries'],
        ];

        foreach ($categories as $category) {
            TicketCategory::create($category);
        }
    }
}
