<?php

namespace App\Http\Controllers\APIv1;

use App\Http\Requests\Requests\FolderCreateRequest;
use App\Http\Requests\Requests\FileCreateRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Folder;
use App\Models\Node;
use App\Models\File;
use App\Models\Nodeable;

class CloudController extends ApiController
{
    public function index(): JsonResponse
    {
        $roots = Node::isRoot()->with('nodeable')->get();

        $tree = Node::tree()->get();
        $allTree = $tree->toTree();

        return response()
            ->json([
                'roots' => $allTree
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

    public function storeFolder(FolderCreateRequest $request)
    {
        $validatedData = $request->validated();

        $user = auth()->user();
        $folder = Folder::create([
            'name' => $validatedData['name'],
            'author_id' => $user->id
        ]);

        if ($folder) {

            Nodeable::create([
                'node_id' => random_int(50, 500),
                'nodeable_type' => 'App/Models/Folder',
                'nodeable_id' => $folder->id
            ]);

            Node::create([
                'nodeType' => 'Folder',
                'nodeable_type' => 'App/Models/Folder',
                'nodeable_id' => $folder->id,
                'parent_id' => $request->parent_id
            ]);

            return response()->json(['New Folder' => $folder]);
        } else {
            return $this->showNone();
        }
    }

    public function storeFile(FileCreateRequest $request)
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        $fileName = time() . '_' . $validatedData['file']->getClientOriginalName();
        $filePath = $request->file('file')->StoreAs('uploads', $fileName, 'public');

        $file = File::create([
            'name' => $fileName,
            'path' => '/storage/' . $filePath,
            'size' => $request->file->getSize(),
            'author_id' => $user->id
        ]);

        if ($file) {

            Nodeable::create([
                'node_id' => random_int(50, 500),
                'nodeable_type' => 'App/Models/File',
                'nodeable_id' => $file->id
            ]);

            Node::create([
                'nodeType' => 'File',
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
