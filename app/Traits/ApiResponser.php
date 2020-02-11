<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
  protected function successResponse($data, $code = 200)
  {
    return response()->json($data, $code);
  }

  protected function errorResponse($message, $code)
  {
    return response()->json(['error' => $message, 'code' => $code], $code);
  }

  protected function showAll(Collection $collection, $code = 200)
  {
    return $this->successResponse(['data' => $collection, 'code' => $code], $code);
  }

  protected function showAllSinCollection($informacion, $code = 200)
  {
    return $this->successResponse(['data' => $informacion, 'code' => $code], $code);
  }

  protected function showOne(Model $instance, $code = 200)
  {
    return $this->successResponse(['data' => $instance, 'code' => $code], $code);
  }

  protected function showQuery($data, $code = 200)
  {
    return response()->json(['data'=>$data],$code);
  }

  protected function showAllPaginate(Collection $collection, $code = 200)
  { 
    if ($collection->isEmpty()) {
      return $this->successResponse(['data' => $collection], $code);
    }

    $transformer = $collection->first()->transformer;


    //$collection = $this->filterData($collection);
    //$collection = $this->sortData($collection);
    $collection = $this->paginate($collection);

    return $this->successResponse($collection, $code);
  }

  protected function paginate(Collection $collection)
  {
    $rules = [
      'per_page' => 'integer|min:2|max:50'
    ];

    Validator::validate(request()->all(), $rules);

    $page = LengthAwarePaginator::resolveCurrentPage();

    $perPage = 10;

    if (request()->has('per_page')) {
      $perPage = (int) request()->per_page;
    }

    $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

    $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
      'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    $paginated->appends(request()->all());

    return $paginated;
  }

  protected function generarPassword($longitud)
  {
    $password = '';
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890#.!@";

    for($i=0;$i<$longitud;$i++) {
       $password .= substr($str,rand(0,62),1);
    }

    return $password;
  }
}