<?php

namespace App\Http\Controllers\Items;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Items\Item;
use Session;
class ItemsController extends Controller
{
    public function manageItemAjax()
    {   

        $session_accesstoken = Session::get('accessToken');
       return view('manage-item-ajax')->with($session_accesstoken);
          // return redirect(asset('manage-item-ajax'), $status = 301, $headers = ['Authorization','Bearer '.$session_accesstoken['access_token']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Item::latest()->paginate(5);
        return response()->json($items);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create = Item::create($request->all());
        return response()->json($create);
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
        $edit = Item::find($id)->update($request->all());
        return response()->json($edit);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Item::find($id)->delete();
        return response()->json(['done']);
    }
}
