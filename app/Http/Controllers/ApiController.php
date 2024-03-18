<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request){         /* <- Register API(POST, formdata)  */

        $validatedData = $request -> validate([
            'name'      => 'nullable|string',
            'username'  => 'required|string|alpha_dash:ascii|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed|'
        ]);

        $user = User::create([
            "name"      => $request -> name,
            "username"  => $request -> username,
            "email"     => $request -> email,
            "password"  => Hash::make($request -> password)
        ]);
        return response()->json([
            [$user, $validatedData],
            "status" => true,
            "message" => "User created"]);

    }
    public function login(Request $request){            /* <- LogIn API(POST, formdata)  */
        $validatedData = $request -> validate([
            'username'  => 'required',
            'password'  => 'required'
        ]);

        $user = User::where("username", $request -> username) -> first();

        if(!empty($user)) {
            if (Hash::check(
                $request -> password,
                $user    -> password,
            )) {
                $token = $user -> createToken("userToken") -> plainTextToken;
                return response() -> json([
                    "status"  => true,
                    "message" => "The user is logged in",
                    "token"   => $token
                ]);
            }
            return response() -> json([
                "status"  => false,
                "message" => "Incorrect password"
            ]);
        }

        return response() -> json([
            "status"  => false,
            "message" => "Incorrect username"]);
    }

    public function profile(){                          /* <- Profile API(GET)  */
        $data = Auth::user();

        return response() -> json([
            "status"  => true,
            "message" => "Profile date",
            "user"    => $data
        ]);
    }

    public function logout(){                           /* <- LogOut API(GET)  */
        Auth::user() -> tokens() -> delete();

        return response() -> json([
            "status"  => true,
            "message" => "The user is logged out"
        ]);
    }

    public function update(Request $request){         /* <- Update API(PUT) */
        $updateData = $request->validate([
            'username' => 'string|alpha_dash:ascii',
            'name' => 'string',
        ]);

        $user = Auth::user();
        $user -> update([
        'name' => $request -> name,
        'username' => $request -> username
        ]);

        return response()->json([
            $user,
            "status"  => true,
            "message" => "The user is updated"
        ]);
    }

    public function delete(){                           /* <- LogOut API(GET)  */
        $user = Auth::user() -> delete();
        Auth::user() -> tokens() -> delete();

        return response() -> json([
            "status"  => true,
            "message" => "The user is logged out"
        ]);
    }
}
