<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $table = 'disbursement';
    protected $fillable = ['name'];

    public function balances()
    {
        return $this->hasMany(Balance::class, 'disbursement_id', 'id');
    }
}
