<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cantones;
use App\Models\User;
use App\Models\provincia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    //Registrarse en el login
    public function register(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:8',
            
        ]);
        User::create([
            'name' => $validData['name'],
            'email' => $validData['email'],
            'password' => Hash::make($validData['password']),
            
        ]);
        return response()->json(['message' => 'Usuario registrado'], 201);
    }

    //logiarse
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('name', 'password'))) {
            return response()->json(['message' => 'Credenciales invalidas'], 401);
        }

        $user = User::where('name', $request->name)->first();
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'accesToken'=>$token,
                'tokenType'=>'Bearer'
            ],
            200

        );

    }

    public function showUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

       // mostrar tipos
       public function showTipos(){
        $tipo = provincia::where('eliminado', 1)->get();
        return response()->json($tipo, 200);
    }

       //ANIMAL
      //mostrar animal

       public function showCanton()
       {
         $cantone = cantones::where('eliminado', 1)->get();
         return response()->json($cantone, 200);
       }

       //Ingresar animal
    public function ingresarCanton(Request $request)
    {
        $validData = $request->validate([
            'cantones' => 'required|string|max:255',
            'provincia_id' => 'required'
        ]);
        $a = cantones::create([
            'cantones' => $validData['cantones'],
            'provincia_id' => $validData['provincia_id'],
            'eliminado' => 1,
        ]);
        /* $this->logo->store('','fotos'); */

        $customFileName;
        
        if ($request->imagen) {
            $customFileName = uniqid() . '_.' . $request->imagen->extension();
            $request->imagen->storeAs('public/animal', $customFileName);
            $a->imagen = $customFileName;
            $a->save();
        }
        return response()->json(['message' => 'Canton registrado'], 201);
        
    }

        //Borrar animal
        public function destroyCanton($id)
        {
            $cantone = cantones::find($id);
            if (is_null($cantone)) {
                return response()->json(['message' => 'Animal no encontrado'], 404);
            }
            $cantone->eliminado = 0;
            $cantone->save();
            return response()->json(['message' => 'Animal eliminado'], 200);
        }

           //busca para editar
    public function editAnimal($id)
    {
        $cantone = cantones::find($id);
        if (is_null($cantone)) {
            return response()->json(['message' => 'Animal no encontrado'], 404);
        }
        return response()->json($cantone, 200);    
    } 

     //El que edita de verdad
     public function updateCanton(Request $request, $id)
     {
         $cantone = cantones::find($id);
         if (is_null($cantone)) {
             return response()->json(['message' => 'Animal no encontrado'], 404);
         }
         $validateData = $request->validate([
             'cantones' => 'required|string|max:255',
             'provincia_id' => 'required'
         ]);
         $cantone->cantones = $validateData['cantones'];
         $cantone->provincia_id = $validateData['provincia_id'];
 
         $customFileName;
         
         if ($request->imagen) {
             $customFileName = uniqid() . '_.' . $request->imagen->extension();
             $request->imagen->storeAs('public/animal', $customFileName);
             $imageTemp = $cantone->imagen; //imagen temporal
             $cantone->imagen = $customFileName;
             
             $cantone->save();
 
             if($imageTemp!=null)
             {    
                 if(file_exists('public/animal' . $imageTemp));
                 {
                     Storage::delete('public/animal' . $imageTemp);
                 }
             }
             
             
         }
         
         return response()->json(['message' => 'Animal actualizado'], 201);
  
     }
}
