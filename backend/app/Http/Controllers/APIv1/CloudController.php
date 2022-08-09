<?php

namespace App\Http\Controllers\APIv1;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Node;
use App\Models\File;
use App\Models\Nodeable;
use Illuminate\Http\JsonResponse;

class CloudController extends ApiController
{
    public function index(): JsonResponse
    {
        $roots = Node::isRoot()->with('nodeable')->get();

        return response()
            ->json([
                'roots' => $roots
            ]);
    }

    public function indexMe(): JsonResponse
    {
        $roots = Node::isRoot()->with('nodeable')->where('author_id', Auth::user()->id)->get();

        return response()
            ->json([
                'roots' => $roots
            ]);
    }

    public function childrens($id): JsonResponse
    {
        $node = Node::Find($id);

        if ($node->node_type == "App\Models\File") {
            return $this->showMessage(
                __("Lo siento esto es un archivo...")
            );
        }

        $childrens = Node::where('parent_id', $node->id)->with(['folders', 'files'])->get();

        return response()
            ->json([
                'childrens' => $childrens
            ]);
    }

    public function storeFolder(Request $request)
    {
        $folder = Folder::create([
            'name' => $request->name,
            'author_id' => 1
        ]);

        if ($folder) {

            Nodeable::create([
                'node_id' => random_int(50, 500),
                'nodeable_type' => 'App/Models/Folder',
                'nodeable_id' => $folder->id
            ]);

            Node::create([
                'nodeType' => 'Folder',
                'parent_id' => $request->parent_id
            ]);

            return response()->json(['New Folder' => $folder]);
        } else {
            return $this->showNone();
        }
    }

    public function storeFile(Request $request)
    {
        $fileName = time() . '_' . $request->file->getClientOriginalName();
        $filePath = $request->file('file')->StoreAs('uploads', $fileName, 'public');

        $file = File::create([
            'name' => $fileName,
            'path' => '/storage/' . $filePath,
            'size' => $request->file->getSize(),
            'author_id' => 1
        ]);

        if ($file) {

            Nodeable::create([
                'node_id' => random_int(50, 500),
                'nodeable_type' => 'App/Models/File',
                'nodeable_id' => $file->id
            ]);

            Node::create([
                'nodeable_type' => 'App/Models/File',
                'nodeable_id' => $file->id,
                'parent_id' => $request->parent_id
            ]);

            return response()->json(['New File' => $file]);
        } else {
            return $this->showNone();
        }
    }
}
