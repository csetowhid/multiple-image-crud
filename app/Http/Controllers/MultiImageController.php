<?php

namespace App\Http\Controllers;

use App\Models\MultiImage;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MultiImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable',
            'image.*' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);

        $path = [];
        if ($request->hasFile('image')){
            foreach ($request->image as  $image){
                $path[] = $image->store('upload');
                
            }
        }
        MultiImage::create([
            'name' => $request->name,
            'image' => $path,
        ]);

        return back();
    }


    public function remove($reqimage,$id)
    {
        $row = MultiImage::where('id',$id)->first();
        
        $images = json_decode($row->image,true);

        if($reqimage){
            $images = array_diff($images, array($reqimage));
            unlink(storage_path(). '/app/public/upload/'.$reqimage);
        }

        $row->image = array_values($images);
        $row->save();
        return back();
    }

    public function edit($id)
    {
        $data['imageData'] = MultiImage::where('id',$id)->first();
        return view('edit',$data);
    }

    public function update(Request $request, $id)
    {
        $imageData = MultiImage::where('id',$id)->first();

        $image = json_decode($imageData->image);

        if($request->hasFile('image')){
            foreach($request->file('image') as $file){
                $image[] = $file->store('upload');
            }
        }

        $imageData->name = $request->name;
        $imageData->image =  $image;
        $imageData->save();
        return back();

    }
}
