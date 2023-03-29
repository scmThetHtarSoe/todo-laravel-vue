<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the todos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::all();
        return response()->json($todos, 200);
    }

    /**
     * Store a newly created todo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $texts = $request->texts;
        $uni_id = $request->uni_id;
        $createdTodo = Todo::create([
            "texts" => $texts,
            "unquid_id" => $uni_id
        ]);
        return response()->json([$createdTodo, 'msg' => 'List created successfully'], 200);
    }

    /**
     * Update the specified todo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $texts = $request->texts;
        $updatedTodo = Todo::where('unquid_id', $id)->update([
            "texts" => $texts
        ]);
        return response()->json([$updatedTodo, 'msg' => 'List updated successfully'], 200);
    }

    /**
     * Update the specified status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $status = $request->status;
        $updatedStatus = Todo::where('unquid_id', $id)->update([
            "status" => $status
        ]);
        return response()->json([$updatedStatus, 'msg' => 'Status updated successfully'], 200);
    }

    /**
     * Update the all status to complete state in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAll()
    {
        Todo::where("status", '0')->update(["status" => '1']);
        return response()->json(['msg' => 'Checked all status successfully'], 200);
    }

    /**
     * Update the all status to uncomplete state in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function uncheckAll()
    {
        Todo::where("status", '1')->update(["status" => '0']);
        return response()->json(['msg' => 'Unchecked all status successfully'], 200);
    }

    /**
     * Remove the completed todos from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCompleted()
    {
        Todo::where("status", "1")->delete();
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
        $todo = Todo::where("unquid_id", $id);
        $todo->delete();
        return response()->json(['msg' => 'List Deleted Successfully!!!'], 200);
    }
}
