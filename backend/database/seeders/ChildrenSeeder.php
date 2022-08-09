<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;
use App\Models\Folder;
use App\Models\File;
use App\Models\Node;
use App\Models\Nodeable;

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
                $folders = Node::whereNull('parent_id')->select('id')->get();
                Node::create([
                    'nodeType' => 'Folder',
                    'nodeable_type' => 'App/Models/Folder',
                    'nodeable_id' => $folder->id,
                    'parent_id' => $folders->random()->id
                ]);
                Nodeable::create([
                    'node_id' => random_int(1, 30),
                    'nodeable_type' => 'App/Models/Folder',
                    'nodeable_id' => $folder->id
                ]);
            });

        File::factory()
            ->count(4)
            ->create()->each(function (File $file) {
                $folders = Node::whereNull('parent_id')->select('id')->get();
                Node::create([
                    'nodeType' => 'File',
                    'nodeable_type' => 'App/Models/File',
                    'nodeable_id' => $file->id,
                    'parent_id' => $folders->random()->id
                ]);
                Nodeable::create([
                    'node_id' => random_int(1, 30),
                    'nodeable_type' => 'App/Models/File',
                    'nodeable_id' => $file->id
                ]);
            });
    }
}
