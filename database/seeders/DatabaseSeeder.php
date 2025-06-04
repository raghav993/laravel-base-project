<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends DynamicModelSeeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        $this->runForModel(User::class, 10);

        // $this->runForModel(Category::class, 10);

        // $this->runForModel(Post::class, 50);
    }
}
