<?php

namespace Modules\Client\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\app\Models\Receipt;
use Modules\Client\app\Models\Ticket;
use Modules\Client\app\Services\ReceiptService;
use Modules\Seller\app\Models\Sales;

class ReceiptController extends Controller
{
    public array $data = [];

    public function __construct(
        private ReceiptService $receiptService
    )
    {  
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //

        return response()->json($this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //

        return response()->json($this->data);
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $data = Receipt::findOrFail($id);
            $tickets = json_decode($data->description);
            $dataTickets = Ticket::whereIn('id', $tickets)->get();
            $data->tickets = $dataTickets;
            return response_success($data);
        } catch (\Throwable $th) {
           return response_error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::find($request->user_id);
            if(boolval($request->accept !== "true" ? 1 : 0  )){
                $data = $this->receiptService->update($id, ['is_active' => false,'transaction' => true, 'status' => Receipt::STATUS_CANCEL],false);
                $tickets = json_decode($data->description);
                Ticket::whereIn('id',$tickets)
                ->update(['user_taxid' => null, 'updated_by' => auth()->user()->id]);
                Sales::whereIn('tickets_id',$tickets)
                ->delete();
                $dataTickets = Ticket::whereIn('id',$tickets)->get();
                $template = 'emails.payment-tickets-faild';
                sendEmail($user->email,'Fallo la veracidad del comprobante de pago',$template,[
                    'user' => $user,
                    'tickets' => $dataTickets,
                    'observation' => $request->observation
                ]);
                DB::commit();
                return response_update($user);
            }

            $data = $this->receiptService->update($id, ['is_active' => false,'transaction' => true, 'status' => Receipt::STATUS_CONFIRM],false);
            $tickets = json_decode($data->description);
            Ticket::whereIn('id',$tickets)
            ->update(['is_buy' => true, 'updated_by' => auth()->user()->id]);
            Sales::whereIn('tickets_id',$tickets)
            ->update(['is_complete' => true,'updated_by' => auth()->user()->id]);
            $dataTickets = Ticket::whereIn('id',$tickets)->get();
            $template = 'emails.payment-tickets-aprove';
            sendEmail($user->email,'Verificacion de boletos HAYU24', $template,[
                'tickets' => $dataTickets,
                'user' => $user,
            ]);
            DB::commit();
            return response_update($data);
        } catch (\Throwable $th) {
            DB::rollBack();
           return response_error($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        //

        return response()->json($this->data);
    }


    public function showByUser($taxid): JsonResponse
    {
        try {
            $data = Receipt::where('organizer_raffles_taxid', $taxid)
            ->where('is_active', true)
            ->paginate(10);
            return response()->json($data);
        } catch (\Throwable $th) {
           return response_error($th->getMessage());
        }
    }
    
}
