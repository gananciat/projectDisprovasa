<?php

namespace App\Models;

use App\Models\Disbursement;
use App\Models\Person;
use App\Models\School;
use App\Models\Year;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    const ALIMENTACION = 'ALIMENTACION';
    const GRATUIDAD = 'GRATUIDAD';
    const UTILES = 'UTILES';
    const VALIJA_DIDACTICA = 'VALIJA DIDACTICA';

    protected $table = 'balances';
    protected $fillable = ['balance','start_date','end_date','schools_id',
                          'people_id','year','subtraction','subtraction_temporary','code','type_balance','current','disbursement_id'];

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    public function year()
    {
        return $this->hasOne(Year::class);
    }

    public function schools()
    {
        return $this->belongsTo(School::class,'schools_id');
    }

    public function disbursement()
    {
        return $this->belongsTo(Disbursement::class,'disbursement_id');
    }
}
