<?php
namespace App\Traits;

use Illuminate\Http\Request;
trait ImageUploadTrait
{
    public function uploadImage(Request $request,string $path, string $name)
    {
        if($request->hasFile($name)) {
            $file = $request->file($name);
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = time(). $originalName .".". $extension;
            $file->move($path, $filename);
            return $filename;
        }
        return null;
    }
}



