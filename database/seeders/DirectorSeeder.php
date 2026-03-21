<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Director;
use Illuminate\Database\Seeder;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Director::create(['name' => 'Christopher Nolan']);
        Director::create(['name' => 'Steven Spielberg']);
        Director::create(['name' => 'Quentin Tarantino']);
    }
}
