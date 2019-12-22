<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
  private function successResponse($data, $code)
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

  protected function generarPassword($longitud)
  {
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890#.!@";

    for($i=0;$i<$longitud;$i++) {
       $password .= substr($str,rand(0,62),1);
    }

    return $password;
  }
}