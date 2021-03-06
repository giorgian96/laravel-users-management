<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::allows('is-admin')){
            // Get users
            $users = User::paginate(15);

            // Return collection of users as a resource
            return UserResource::collection($users);
        }else if(Gate::denies('is-admin')){
            return response()->json([
                'error' => 'You need to be an administrator to do this',
                'code' => 401,
            ], 401);
        }
    }

    // Create new user
    public function create(Request $request)
    {
        if(Gate::allows('is-admin')){
            $user = new User;

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            // $user->api_token = Str::random(60);
            $user->type = User::DEFAULT_TYPE;       

            if($user->save()){
                return new UserResource($user);
            }
        }else if(Gate::denies('is-admin')){
            return response()->json([
                'error' => 'You need to be an administrator to do this',
                'code' => 401,
            ], 401);
        }
    }

    // Update existing user
    public function update(Request $request, $id){
        if(Gate::allows('is-admin')){
            $user = User::findOrFail($id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            // $user->api_token = Str::random(60);

            if($user->save()){
                return new UserResource($user);
            }
        }else if(Gate::denies('is-admin')){
            return response()->json([
                'error' => 'You need to be an administrator to do this',
                'code' => 401,
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::allows('is-admin')){
            // Get user
            $user = User::findOrFail($id);

            // Return the user as resource
            return new UserResource($user);
        }else if(Gate::denies('is-admin')){
            return response()->json([
                'error' => 'You need to be an administrator to do this',
                'code' => 401,
            ], 401);
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::allows('is-admin')){
            // Get user
            $user = User::findOrFail($id);

            // Delete user
            if($user->delete()){
                return new UserResource($user);
            }
        }else if(Gate::denies('is-admin')){
            return response()->json([
                'error' => 'You need to be an administrator to do this',
                'code' => 401,
            ], 401);
        }        
    }

    // Login to the application
    public function apiLogin(Request $request){

        if($request->isMethod('get')){
            return response()->json([
                'error' => 'You need to be logged in to access this page',
                'code' => 401,
            ], 401);
        }

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (auth()->attempt($credentials)) {
            // Authentication passed
            $user = auth()->user();
            $user->api_token = Str::random(60);
            $user->save();
            return new UserResource($user);
        }else{
            return response()->json([
                'error' => 'Authentication failed',
                'code' => 401,
            ], 401);
        }
    }

    // Logout
    public function apiLogout(Request $request){
        if (auth()->user()) {
            $user = auth()->user();
            $user->api_token = null; // clear api token
            $user->save();
    
            return response()->json([
                'message' => 'Logout successful',
            ]);
        }
        
        return response()->json([
            'error' => 'Unable to logout user',
            'code' => 401,
        ], 401);
    }
}
