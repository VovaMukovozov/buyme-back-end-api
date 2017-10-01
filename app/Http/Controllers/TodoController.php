<?php namespace App\Http\Controllers;

use Exception;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Todo;

class TodoController extends Controller {

	private function apiResponse($status, $message, $data=null){
		return response()->json([
			'message' => $message,
			'data' => $data
		], $status);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try {
			$todoList = Todo::all()->toArray();
			if(empty($todoList)){
				return $this->apiResponse(404, 'empty list');
			}
			return $this->apiResponse(200, 'your todos', $todoList);
		} catch(Exception $e) {
		  return $this->apiResponse(500, 'server error', $e->getMessage());
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		try {
			Todo::create(['title' => $request->input('title')]);
			return $this->apiResponse(201, 'added new node');
		} catch(Exception $e) {
			return $this->apiResponse(500, 'server error', $e->getMessage());
		}
	}



	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		try {
			$todo = Todo::find($id);
			if(!$todo){
				return $this->apiResponse(404, 'Todo not found');
			}
			$todo->active = !$todo->active;
			$todo->save();
			$message = 'your todo (ID:'.$todo->id.')';
			$message = ($todo->active) ? 'your todo (ID:'.$todo->id.') is active' : 'your todo (ID:'.$todo->id.') is inactive';
			return $this->apiResponse(200, $message);
		} catch(Exception $e) {
			return $this->apiResponse(500, 'server error', $e->getMessage());
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try {
			$todo = Todo::find($id);
			if(!$todo){
				return $this->apiResponse(404, 'Todo not found');
			}
			$todo->delete();
			$message = 'your todo (ID:'.$todo->id.') deleted';
			return $this->apiResponse(200, $message);
		} catch(Exception $e) {
			return $this->apiResponse(500, 'server error', $e->getMessage());
		}
	}

}
