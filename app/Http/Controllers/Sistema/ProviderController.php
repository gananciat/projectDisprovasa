<?php

namespace App\Http\Controllers\Sistema;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\ProviderPhone;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ProviderController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::with('phones','municipality.departament')->get();
        return $this->showAll($providers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'direction' => 'required',
            'nit' => 'required',
            'phones' => 'required',
            'municipalities_id' => 'required|integer|exists:categories,id'
        ];
        
        $this->validate($request, $rules);

            DB::beginTransaction();
            $data = $request->all();
            $provider = Provider::create($data);
             
            foreach ($request->phones as $phone) {
                ProviderPhone::create([
                    'number'=>$phone['number'],
                    'provider_id'=>$provider->id
                ]);
            }

            DB::commit();

            return $this->showOne($provider,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        return $this->showOne($provider);
    }

    public function showByNit($nit)
    {
        $provider = Provider::where('nit',$nit)->first();
        if(is_null($provider)) return $this->errorResponse('nit '.$nit. ' no coincide con ningun proveedor registrado',422);

        return $this->showOne($provider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider)
    {

        $rules = [
            'name' => 'required',
            'direction' => 'required',
            'nit' => 'required',
            'phones' => 'required',
            'municipalities_id' => 'required|integer|exists:categories,id' 
        ];
        
        $this->validate($request, $rules);
        DB::beginTransaction();

            $provider->name = $request->name;
            $provider->direction = $request->direction;
            $provider->nit = $request->nit;
            $provider->municipalities_id = $request->municipalities_id;

            $provider->phones()->delete();

            foreach ($request->phones as $phone) {
                ProviderPhone::create([
                    'number'=>$phone['number'],
                    'provider_id'=>$provider->id
                ]);
            }

            $provider->save();
        DB::commit();

        return $this->showOne($provider,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        $provider->phones()->delete();
        $provider->delete();
        return $this->showOne($provider,201);
    }
}
