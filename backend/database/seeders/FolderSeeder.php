<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Folder;
use App\Models\Objeto;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Folder::factory()
            ->count(8)
            ->createQuietly()->each(function (Folder $folder) {
            Objeto::create([
                    'objectable_type' => 'App\Models\File',
                    'objectable_id' => $folder->id,
                    'parent_id' => null
                ]);
            });
    }
}
