<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Directorio;

use App\Http\Requests\CreateDirectorioRequest;
use App\Http\Requests\UpdateDirectorioRequest;

class DirectorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('txtbuscar')){
            $txtfiltro = $request->get('txtbuscar');
            $directorios = Directorio::where('name','like',"%$txtfiltro%")
                                    ->orWhere('phone','like',"%$txtfiltro%")
                                    ->get();
        }else{
            $directorios = Directorio::get();
        }

       return $directorios;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDirectorioRequest $request)
    {
        $input = $request->all();
        Directorio::create($input);
        return response()->json([
            'codeRpta' => 200,
            'message' => 'Registro Creado correctamente'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Directorio  $directorio
     * @return \Illuminate\Http\Response
     */
    public function show(Directorio $directorio)
    {
        return $directorio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDirectorioRequest $request, Directorio $directorio)
    {
        //return response()->json(['rpta'=>'ok']);

        $input = $request->all();
        $directorio->update($input);
        return response()->json([
            'codeRpta' => 200,
            'message' => 'Registro Actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Directorio::destroy($id);

        return response()->json([
            'codeRpta' => 200,
            'message' => 'Registro Borrado correctamente'
        ], 200);
    }
}
