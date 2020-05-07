<?php

namespace App\Http\Controllers;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Planitem;

class PlanitemController extends Controller
{

  
	public function index()
    {
		  return response()->json(['statusCode' => 200, 'statusMessage' => 'OK', 'data' => Planitem::where('status', '=', '1')->get()], 200); 
    }

    public function show($id)
    {
      $result = Planitem::where('uuid', '=', $id)->where('status', '=', '1')->get();
      if($result->count() != 0) {
        return response()->json(['statusCode' => 200, 'statusMessage' => 'OK', 'data' => $result[0]], 200); 
      } else {
        return response()->json(['statusCode' => 200, 'statusMessage' => 'No record found', 'data' => null], 200); 
      }
    }

    public function store(Request $request)
    {
      $result = Planitem::create($request->all())->id;
      if($result != null) {
        return response()->json(['statusCode' => 200, 'statusMessage' => 'OK', 'data' => Planitem::find($result)], 200); 
      } else {
        return response()->json(['statusCode' => 200, 'statusMessage' => 'No stored record', 'data' => null], 200); 
      } 
    }

    public function update(Request $request, $id)
    {
        $result = Planitem::where('uuid', '=', $id)->where('status', '=', '1')->get();
        if($result->count() != 0) {
          if($result[0]->update($request->all())) {
            $result = Planitem::where('uuid', '=', $id)->where('status', '=', '1')->get();
            return response()->json(['statusCode' => 200, 'statusMessage' => 'The record has been updated', 'data' => $result[0]], 200); 
          } else {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'The record has not been updated', 'data' => $result[0]], 200); 
          }
        } else {
          return response()->json(['statusCode' => 200, 'statusMessage' => 'The record does not exist or has been deleted', 'data' => null], 200); 
        }
    }

    public function destroy($id)
    {
      $result = Planitem::where('uuid', '=', $id)->where('status', '=', '1')->get();

      if($result->count() != 0) {
          if($result[0]->update(['status' => 0])) {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'The record has been deleted', 'data' => null], 200); 
          } else {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'The record has not been deleted', 'data' => null], 200);
          }
      } else {
        return response()->json(['statusCode' => 200, 'statusMessage' => 'The record does not exist or has been deleted', 'data' => null], 200); 
      }
    }

}
