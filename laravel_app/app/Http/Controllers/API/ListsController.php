<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lists;

class ListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Lists::all();
        return response()->json($lists, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $texts = $request->texts;
        $uni_id = $request->uni_id;
        $createdList = Lists::create([
            "texts" => $texts,
            "unquid_id" => $uni_id
        ]);
        return response()->json([$createdList, 'msg' => 'List created successfully'], 200);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $texts = $request->texts;
        $updatedList = Lists::where('unquid_id', $id)->update([
            "texts" => $texts
        ]);
        return response()->json([$updatedList, 'msg' => 'List updated successfully'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $status = $request->status;
        $updatedStatus = Lists::where('unquid_id', $id)->update([
            "status" => $status
        ]);
        return response()->json([$updatedStatus, 'msg' => 'Status updated successfully'], 200);
    }

    public function checkAll()
    {
        Lists::where("status", '0')->update(["status" => '1']);
        return response()->json(['msg' => 'Checked all status successfully'], 200);
    }

    public function uncheckAll()
    {
        Lists::where("status", '1')->update(["status" => '0']);
        return response()->json(['msg' => 'Unchecked all status successfully'], 200);
    }

    public function clearCompleted()
    {
        Lists::where("status", "1")->delete();
        return response()->json(['msg' => "Clear completed lists successfully"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $list = Lists::find($id);
        $list = Lists::where("unquid_id", $id);
        $list->delete();
        return response()->json(['msg' => 'List Deleted Successfully!!!'], 200);
    }
}
