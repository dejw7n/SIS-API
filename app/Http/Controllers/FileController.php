<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\DeferredFile;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class FileController extends Controller
{
    public function showAllFiles()
    {
        return response()->json(File::all());
    }

    public function showOneFile($id)
    {
        return response()->json(File::find($id));
    }

    public function delete($id)
    {
        File::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function upload(Request $request)
    {
        $session = Uuid::uuid4();

        $files = $request->file('files');

        $results = [];

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileDb = File::create([
                'name' => $fileName,
                'size' => $file->getSize(),
                'type' => $file->getClientOriginalExtension(),
            ]);
            DeferredFile::create([
                'session' => $session->toString(),
                'file_id' => $fileDb->id,
            ]);
            $file->move(storage_path('uploads\\' . $fileDb->id), $fileName);
            $results[] = $fileName;
        }

        return response()->json($session);
    }

    public function download($id)
    {
        $file = File::find($id);
        $folderPath = storage_path('uploads\\' . $id);
        $filePath = $folderPath . '/' . $file->name;

        if (!file_exists($filePath)) {
            abort(404);
        }
        //return response()->json($file->type);

        $content = file_get_contents($filePath);
        return response($content);
    }
}
