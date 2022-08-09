<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Folder;
use App\Models\Node;
use App\Models\Nodeable;

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
                Nodeable::create([
                    'node_id' => random_int(1, 30),
                    'nodeable_type' => 'App\Models\Folder',
                    'nodeable_id' => $folder->id,
                ]);
                Node::create([
                    'nodeType' => 'Folder',
                    'nodeable_type' => 'App\Models\Folder',
                    'nodeable_id' => $folder->id,
                    'parent_id' => null,
                ]);
            });
    }
}
