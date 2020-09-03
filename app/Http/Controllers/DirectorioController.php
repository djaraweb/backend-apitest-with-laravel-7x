<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Directorio;

use App\Http\Requests\CreateDirectorioRequest;
use App\Http\Requests\UpdateDirectorioRequest;
use Illuminate\Support\Facades\Storage;

class DirectorioController extends ApiController
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
                                    ->orderBy('name','asc')
                                    ->get();
        }else{
            $directorios = Directorio::orderBy('name','asc')->get();
        }

        return $this->responseToSuccess(compact('directorios'));
    }

    private function uploadAvatarContact($file){
        $nameFile = time().".". $file->getClientOriginalExtension();
        $pathFile = Storage::putFileAs('avatarContact',$file, $nameFile);
        $pathFile = asset('upload/'.$pathFile);

        return $pathFile;
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
        if ($request->has('avatar'))
            $input['avatar'] = $this->uploadAvatarContact($request->avatar);

        Directorio::create($input);
        return $this->responseToSuccess('Registro Creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  Directorio  $directorio
     * @return \Illuminate\Http\Response
     */
    public function show(Directorio $directorio)
    {
        return $this->responseToSuccess(compact('directorio'));
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
        $input = $request->all();
        if ($request->has('avatar'))
            $input['avatar'] = $this->uploadAvatarContact($request->avatar);

        $directorio->update($input);

        return $this->responseToSuccess('Registro Actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Directorio $directorio)
    {
        $directorio->delete();
        return $this->responseToSuccess('Registro Borrado correctamente');
    }
}
