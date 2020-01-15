<?php

namespace App\Http\Controllers\Dashboard\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Disbursement;

class InformationController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function disbursement_school($id)
    {
        $disbursements = Disbursement::select('id','name')
                                    ->with(['balances' => function ($query) use ($id) {
                                        $query->select('id','balance','subtraction','subtraction_temporary',
                                                        'code','type_balance','schools_id','disbursement_id','current','year')
                                                ->with('schools:id,name')
                                                ->where([
                                                            ['schools_id', $id],
                                                            ['year', date('Y')]
                                                        ])
                                                ->orderBy('type_balance','asc');
                                    }])
                                    ->whereHas('balances')
                                    ->orderBy('name','asc')
                                    ->get();

        return $this->successResponse($disbursements);
    }

}
