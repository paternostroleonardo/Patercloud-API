<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;
use App\Models\Objeto;
use App\Models\Folder;
use App\Models\File;

class ChildrenSeeder extends Seeder
{
    use HasFactory;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Folder::factory()
        ->count(4)
        ->create()->each(function (Folder $folder) {
            $folders = Objeto::whereNull('parent_id')->select('id')->get();
            Objeto::create([
                'objectable_type' => 'App/Models/Folder',
                'objectable_id' => $folder->id,
                'parent_id' => $folders->random()->id
            ]);
        });

        File::factory()
        ->count(10)
        ->create()->each(function (File $file) {
            $folders = Objeto::whereNull('parent_id')->select('id')->get();
            Objeto::create([
                'objectable_type' => 'App\Models\File',
                'objectable_id' => $file->id,
                'parent_id' => $folders->random()->id
            ]);
        });
    }
}
