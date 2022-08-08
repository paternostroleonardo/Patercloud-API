<?php

namespace App\Http\Controllers\APIv1;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Objeto;
use App\Models\File;

class CloudController extends ApiController
{
    public function index()
    {
        $roots = Objeto::isRoot()->with('objectable')->get()->values();

        return response()
            ->json([
                'childrens' => Objeto::tree(),
                'roots' => $roots
            ]);
    }

    public function storeFolder(Request $request)
    {
        $name = $request->name;
        Folder::create(['name' => $name]);
        $folder = Folder::latest('id')->first();
        $folder->objects()->create();
        if ($folder) {
            return $this->showMessage(
                __("Carpeta creada con exito..."),
                response()->json(['New Folder' => $folder])
            );
        } else {
            return $this->showNone();
        }
    }

    public function storeFile(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:csv, txt, xls, xls, pdf|max:2048'
        ]);

        $fileModel = new File;
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->StoreAs('uploads', $fileName, 'public');
            $fileModel->name = time() . '_' . $request->file->getClientOriginalName();
            $fileModel->path = '/storage/' . $filePath;
            $fileModel->size = $request->file->getSize();
            $fileModel->save();
        }
        $file = File::latest('id')->first();
        $file->objects()->create();
        if ($file) {
            return $this->showMessage(
                __("Archivo creado con exito..."),
                response()->json(['New File' => $file])
            );
        } else {
            return $this->showNone();
        }
    }
}
