<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function users()
    {
        return view('users', ['users' => User::all()]);
    }

    //TODO: create user
    public function create(UserCreateRequest $request)
    {
        $input = $request->validated();
        $user = new User;
        $user->username = $input['username'];
        $user->password = Hash::make($input['password']);
        if($input['type']=="true")
            $user->type = "admin";
        else
            $user->type = "default";

        $user->save();
        return response()->json($user);
    }

    public function destroy(Request $request)
    {
        $input = $request->all();
        $user = User::find($input['id']);

        // controlla che l'utente sia admin
        if($user->type == 'admin')
        {
            // se è admin, controlla che non sia l'ultimo
            if(!(User::where('type', 'admin')->count() > 1))
            {
                $response = ['messaggio' => 'non è possibile eliminare'];
                return response()->json($response);
            }
        }

        $user->delete();

        $response = ['messaggio' => 'utente eliminato'];

        return response()->json($response);
    }

    public function editUser(Request $request)
    {
        $input = $request->all();


        $user = User::find($input['id']);
        $user->username = $input['username'];

        if( $input['password'] != "" && isset($input['password']) ){

            $user->password = Hash::make($input['password']);
        }
        if($input['type']=="true"){
            $user->type = "admin";
        }
        else
        {
            $user->type = "default";
        }
        $user->save();


        $response = ['messaggio' => 'utente modificato'];

        return response()->json($user);
    }
}
