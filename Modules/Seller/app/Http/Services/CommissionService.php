<?php

namespace Modules\Seller\app\Http\Services;

use App\Core\BaseService;
use Modules\Seller\app\Models\Commissions;
use Modules\UserRaffle\app\Models\Raffle;
use Illuminate\Http\Request;

class CommissionService extends BaseService {

    public function __construct(Commissions $commissions)
    {
        $this->model = $commissions;
    }

    public function storeCommission(Request $request){
        $user = auth()->user();
        $raffle = Raffle::find($request->raffles_id);
        $last3digitsTaxid = substr($user->taxid,-3);
        $idRaffle = $raffle->id;
        $userId= $user->id;
        $numberRamdom = rand(1,99);

        //El codigo esta conformado por los 3 ultimos digitos de la cedula,id de la rifa y el id del usuario
        $code = $last3digitsTaxid . $idRaffle . $userId . $numberRamdom;
        $url= env('APP_URL_FRONT') . '/payment/raffles/' . $raffle->id . '?seller_code=' . $code;

        $data = [
            'raffles_id' => $request->raffles_id,
            'user_taxid' => auth()->user()->taxid,
            'code' => $code,
            'status' => Commissions::STATUS_DRAFT,
            'url' => $url
        ];

        return $this->save($data,false);
        
    }

   
}