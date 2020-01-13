<?php

namespace App\Models;

use App\Models\Rol;
use App\Models\Icon;
use App\Models\MenuRol;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillableee = ['name','route','recursive','icon','route_name','hide','father'];

    public function rols()
    {
        return $this->belongsToMany(Rol::class)->using(MenuRol::class);        
    }
}
