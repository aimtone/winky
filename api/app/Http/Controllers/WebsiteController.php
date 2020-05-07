<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Website;
use App\Plan;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BotController;
use App\Http\Controllers\Plan_PlanitemController;

class WebsiteController extends Controller
{
    public function getUserWebsites($user_uuid) {
        return Website::where('user_uuid', '=', $user_uuid)->where('status', '=', 1)->get();
    }
    public function create(Request $request) {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'protocol' => 'required|min:7|max:8|regex:/https?:\/\//i',
            'address' => 'required|regex:/[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)?/i'
         ]);
         
         if ($validator->fails()) {
            $errors_array = $validator->errors()->toArray();
            if(isset($errors_array['protocol'])) {
               return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['protocol'][0], 'data' => null], 200);
            }
            if(isset($errors_array['address'])) {
               return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['address'][0], 'data' => null], 200);
            }
         }

        $userLogged = Auth::user();
        

        // determinar cuantos sitios web puede registrar
        $plan = (new Plan_PlanitemController)->getPlan_Planitem($userLogged['plan_uuid']);
        $number_of_bots = 0;
        foreach ($plan['items'] as $item) {
            if($item->planitem_uuid == 'd32cc116-88d9-11ea-9e87-9828a60067ab') {
                $number_of_bots = $item->value;
                break;
            }
        }

        if($number_of_bots == 0) {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'It has not been determined to determine if you can register more sites', 'data' => null], 200); 
        }
        if($this->getUserWebsites($userLogged['uuid'])->count() >= $number_of_bots) {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'You can no longer register more sites', 'data' => null], 200); 
        }

        $input['user_uuid'] = $userLogged['uuid'];
        //Veifica que el sitio web no este registrado y verificado
        $isWebsite = (Website::where('address', '=', $input['address'])->where('verified_website', '=', 1)->get())->count() != 0;

        // Si el sitio web existe y esta verificado, ya nadie mas lo puede tomar
        if($isWebsite) {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'This website is already verified, you cannot register it', 'data' => null], 200); 
        } else {
        // Sino existe el sitio o no esta verificado, alguien mas lo puede registrar
            DB::beginTransaction();
            try {
                $result = Website::create($input)->id;
                if($result != 0) {
                    $result = Website::find($result);
                    $bot = (new BotController)->create($result['uuid']);
                    if($bot != null) {
                        DB::commit();
                        $result['bot'] = $bot;
                        return response()->json(['statusCode' => 200, 'statusMessage' => 'OK', 'data' => $result], 200); 
                    } else {
                        DB::rollBack();
                        return response()->json(['statusCode' => 200, 'statusMessage' => 'No stored record', 'data' => null], 200); 
                    }
                } else {
                    DB::rollBack();
                    return response()->json(['statusCode' => 200, 'statusMessage' => 'No stored record', 'data' => null], 200); 
                }
            } catch (\Illuminate\Database\QueryException $exception) {
                $errorInfo = $exception->errorInfo;
                if($errorInfo[0] == 23000 && $errorInfo[1] == 1062) {
                    return response()->json(['statusCode' => 200, 'statusMessage' => 'You have previously registered this website', 'data' => null], 200); 
                } else {
                    return response()->json(['statusCode' => 200, 'statusMessage' => 'An unexpected error has occurred', 'data' => null], 200); 
                }
                DB::rollBack();
            }
        }  
    }
    public function getWebsitePublicClientInfo($uuid) {
        $result = Website::where('uuid', '=', $uuid)->where('status', '=', 1)->get();
        if($result->count() != 0) {
            $bot = (new BotController)->getBotPublicClientInfo($uuid);
            if($bot->count() != 0) {
                $result[0]['name'] = $bot[0]['name'];
                $result = $result->makeHidden(['verified_website','user_uuid', 'uuid']);
                return response()->json(['statusCode' => 200, 'statusMessage' => 'OK', 'data' => $result], 200); 
            } else {
                return response()->json(['statusCode' => 200, 'statusMessage' => 'This bot does not exists', 'data' => null], 200); 
            }
        } else {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'This website does not exists', 'data' => null], 200); 

        }
    }
}
