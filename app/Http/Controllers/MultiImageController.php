<?php

namespace App\Http\Controllers;

use App\Models\MultiImage;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

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
                $name = time().rand(1,100).'.'.$image->extension();
                $image->move(storage_path(). '/app/public/upload/', $name);
                $path[] = $name;
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
        }

        $row->image = array_values($images);
        $row->save();
        return back();
    }
}
