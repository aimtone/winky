<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;
use App\User; 
use App\Mail\UserEmailVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Webpatser\Uuid\Uuid;
use App\Http\Controllers\WebsiteController;


class UserController extends Controller
{

    public function register(Request $request) {         
        $input = $request->all();

        if(!isset($input['user_uuid']) || is_null($input['user_uuid']) || empty($input['user_uuid'])) {
            
            $validator = Validator::make($request->all(), [
               'name' => 'required|min:2',
               'lastname' => 'required|min:2',
               'email' => 'required|email',
               'password' => 'required|min:6'
            ]);
            
            if ($validator->fails()) {
               $errors_array = $validator->errors()->toArray();

               if(isset($errors_array['name'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['name'][0], 'data' => null], 200);
               }
               if(isset($errors_array['lastname'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['lastname'][0], 'data' => null], 200);
               }

               if(isset($errors_array['email'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['email'][0], 'data' => null], 200);
               }
               if(isset($errors_array['password'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['password'][0], 'data' => null], 200);
               }
            }

            
            $findEmail = User::where('email', '=', $input['email'])->get();
            if($findEmail->count() != 0) {
               return response()->json(['statusCode' => 200, 'statusMessage' => "The email '".$input['email']."' already exists.", 'data' => null], 200);
            }

            $input['password'] = bcrypt($input['password']);
            $input['email_verification_key'] = Uuid::generate()->string;
            $user = User::create($input)->id; 
            

            if($user != 0) {
               $user = User::find($user);
               $user['token'] = $user->createToken('WinkyApp')->accessToken;
               $user['email_verification_key'] = $input['email_verification_key'];
               Mail::to($input['email'])->send(new UserEmailVerification($user)); 

               return response()->json(['statusCode' => 200, 'statusMessage' => "The user has been created", 'data' => $user], 200);
            } else {
               return response()->json(['statusCode' => 200, 'statusMessage' => 'The user has not been created', 'data' => null], 200); 
            }
        } else {
           $existUser = User::where('uuid', '=', $input['user_uuid'])->where('status', '=', 1)->get(); 

           if($existUser->count() != 0) {
            
            $validator = Validator::make($request->all(), [
               'email' => 'required|email'
            ]);
            
            if ($validator->fails()) {
               $errors_array = $validator->errors()->toArray();
               
               if(isset($errors_array['email'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['email'][0], 'data' => null], 200);
               }
               
            }

            $findEmail = User::where('email', '=', $input['email'])->get();
            if($findEmail->count() != 0) {
               return response()->json(['statusCode' => 200, 'statusMessage' => "The email '".$input['email']."' already exists.", 'data' => null], 200);
            } else {
               $input['password'] = '';
               $input['email_verification_key'] = Uuid::generate()->string;
               $user = User::create($input)->id; 
               if($user != 0) {
                  $user = User::find($user);
                  $user['email_verification_key'] = $input['email_verification_key'];
                  Mail::to($input['email'])->send(new UserEmailVerification($user));
                  return response()->json(['statusCode' => 200, 'statusMessage' => "The operator has been created", 'data' => $user], 200);
               } else {
                  return response()->json(['statusCode' => 200, 'statusMessage' => 'The operator has not been created', 'data' => null], 200); 
               }
            }
           } else {
            return response()->json(['statusCode' => 200, 'statusMessage' => 'You are not authorized to execute this action', 'data' => null], 200); 

           }
        }

       }
       
       public function login(Request $request){
          $input = $request->all();
          $isActive = User::where('email', '=', $input['email'])->where('password', "!=", "")->get();

          if($isActive->count() != 0) {
             if(Auth::attempt([
               'email' => $input['email'], 
               'password' => $input['password'],
               'status' => 1
              ])){
                 $user = Auth::user(); 
                 $user['token'] =  $user->createToken('WinkyApp')->accessToken;
                 return response()->json([
                  'ok' => true, 
                  'message' => trans('messages.UserController.login.userHasBeenLogged'), 
                  'error' => 0, 
                  'data' => $user
                 ], 200);
              } else {
                 if($isActive[0]->status) {
                  return response()->json([
                     'ok' => false, 
                     'message' => trans('messages.UserController.login.passwordIsWrong'), 
                     'data' => null
                    ], 200);
                 } else {
                  return response()->json([
                     'ok' => false, 
                     'message' => trans('messages.UserController.login.userIsInactive'), 
                    ], 200);
                 }
                     
              }
          } else {
             $existEmail = User::where('email', '=', $input['email'])->get();
             if($existEmail->count() != 0) {
               return response()->json([
                  'ok' => false, 
                  'message' => trans('messages.UserController.login.verifyYourEmail'), 
                 ], 200);  
             } else {
               return response()->json([
                  'ok' => false, 
                  'message' => trans('messages.UserController.login.emailNotRegistered'), 
                 ], 200); 

             }
          }
          
        }

        public function logout() {
         $user = Auth::user()->token();
         $user->revoke();
         return response()->json([
            'ok' => true, 
            'message' => 'The user has logged out', 
            'errorCode' => 0, 
            'data' => null
           ], 200);

        }

        public function verificate($email_verification_key) {
           $user = User::where('email_verification_key', '=', $email_verification_key)->get();
           
           if($user->count() != 0) {
            $user = $user->makeVisible(['email_verified_at']);
            if(is_null($user[0]['email_verified_at']) || empty($user[0]['email_verified_at']) ) {
               $user = $user->makeHidden(['email_verified_at']);
               if($user[0]->update(['email_verified_at' => Carbon::now()])) {
                  $user = $user->makeVisible(['password']);
                  if(is_null($user[0]['password']) || empty($user[0]['password']) ) {
                     $user = $user->makeHidden(['password']);
                     return response()->json(["usuario verificado seras a la pagina de crecion de password y nombre y apellido"], 200);
                  } else {
                     return response()->json(["usuario verificado a lapagina de iniciar sesion"], 200);
                     
                  }
               } else {
                  return response()->json(["usuario NO verificado, te sera enviado otro correo con otro codigo"], 200);
               }
            } else {
               $user = $user->makeVisible(['password']);
               if(is_null($user[0]['password']) || empty($user[0]['password']) ) {
                  $user = $user->makeHidden(['password']);
                  return response()->json(["usuario YA verificado, pero aun no crea passwrd, se redirige a pagina de creacion de password"], 200);
               } else {
                  return response()->json(["usuario YA verificado, y con password seras redirigido a la pagina de inicar sesion"], 200);

               }
            }
            
           } else {
              return response()->json(["este token no existe oha caducado, se redirige a la pagina principal de winky"], 200);
           } 
        }

        public function updateOperator(Request $request, $email_verification_key) {
         $user = User::where('email_verification_key', '=', $email_verification_key)->where('email_verified_at', '!=', null)->where('password', '=', "")->get();
         if($user->count() != 0) {
            $validator = Validator::make($request->all(), [
               'name' => 'required|min:2',
               'lastname' => 'required:min:2',
               'password' => 'required|min:6'
            ]);
            if ($validator->fails()) {
               $errors_array = $validator->errors()->toArray();
               
               if(isset($errors_array['name'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['name'][0], 'data' => null], 200);
               }
               if(isset($errors_array['lastname'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['lastname'][0], 'data' => null], 200);
               }
               if(isset($errors_array['password'])) {
                  return response()->json(['statusCode' => 200, 'statusMessage' => $errors_array['password'][0], 'data' => null], 200);
               }
            }
            $input = $request->all();
            if($user[0]->update(['name' => $input['name'], 'lastname' => $input['lastname'], 'password' => bcrypt($input['password'])])) {
               return response()->json(['statusCode' => 200, 'statusMessage' => "The operator credentials has been updated", 'data' => null], 200);
            } else {
               return response()->json(['statusCode' => 200, 'statusMessage' => "The operator credentials has not been updated", 'data' => null], 200);
            }
         } else {
            return response()->json(['statusCode' => 200, 'statusMessage' => "You do not have authorization to update your information from this page", 'data' => null], 200);
         }
        }
          
        public function getUser() {
         $user = Auth::user();
         $user['websites'] = (new WebsiteController)->getUserWebsites($user->uuid);
         return response()->json([
            'ok' => true, 
            'message' => 'The user is logged', 
            'errorCode' => 0, 
            'data' => $user
           ], 200);
        }
}
