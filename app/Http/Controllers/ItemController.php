<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();

        return response($items, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['picture_file']);

        $files = $request->picture_file;

        $temp_pics = array();

        foreach ($files as $picture) {
            
            $pic = Image::make($picture)->encode('png', 90);

            $name = '/images/' . Str::random(40) . '.png';

            Storage::disk('public')->put($name, $pic->getEncoded());
            
            array_push($temp_pics, $name);
            
        }

        $data['pictures'] = json_encode($temp_pics);

        $item = new Item;
        $item->fill($data);
        $item->save();

        return response($item, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $data = $request->except(['picture_file', '_method']);

        $files = $request->picture_file;

        $temp_pics = array();

        foreach ($files as $picture) {
            
            $pic = Image::make($picture)->encode('png', 90);
            
            $name = '/images/' . Str::random(40) . '.png';
            
            Storage::disk('public')->put($name, $pic->getEncoded());
            
            array_push($temp_pics, $name);
            
        }

        $data['pictures'] = json_encode($temp_pics);

        $result = Item::where('id', $id)->update($data, $id);

        return response($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();

        return response('Item deleted', 200);
    }
}
