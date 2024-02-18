<?php

namespace Modules\UserRaffle\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Traits\FileHandler;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Modules\UserRaffle\app\Http\Requests\BankAccounts as RequestsBankAccounts;
use Modules\UserRaffle\app\Http\Services\BankAccountServices;
use Modules\UserRaffle\app\Models\BankAccount;

class BankAccountsController extends Controller
{
    use FileHandler;
    public array $data = [];


    public function __construct(
        private BankAccountServices $bankAccountServices
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
    public function store(RequestsBankAccounts $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->validations();
            $additional= ['user_id' => auth()->user()->id];
            $ci = auth()->user()->taxid;
            $type = 'qr_bank_accoutns';
            $uri = "users/$ci/$type";
            if($request->hasFile('qr_image')){
                $path = $this->storeFile($request->file('qr_image'),$uri);
                $additional['qr_image'] = $path;
            }
            $data = $this->bankAccountServices->save($additional);
            DB::commit();
            return response_create($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response_error($th->getMessage(),200);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $data = BankAccount::findOrFail($id);
            return response_success($data);
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->only(['taxid','name_account','account_number','bank_name','type']);
            if($request->has('qr_image_file')){
                $ci = auth()->user()->taxid;
                $type = 'qr_bank_accoutns';
                $uri = "users/$ci/$type";
                $typeFile = 'application/image';
                $filename = $request->qr_file_name;
                $tempFile = tempnam(sys_get_temp_dir(),"tempfile");
                file_put_contents($tempFile, base64_decode($request->qr_image_file));
                $file = new UploadedFile($tempFile,$filename,$typeFile,null,true);
                $path = $this->storeFile($file,$uri);
                $data['qr_image'] = $path;
            }
            $data = $this->bankAccountServices->update($id,$data,false);

            return response_success($data);
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),200);
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


    public function showAccountsByUser($user_id){
        try {
            $data = BankAccount::where('user_id',$user_id)->get();
            return response_success($data);
        } catch (\Throwable $e) {
            return response_error($e->getMessage(),200);
        }
    }

    public function validations(): void
    {
        $accounts = auth()->user()->bankAccounts;

        foreach($accounts as $account){
            if($account->is_account_local){
                throw new ErrorException(Messages::NOT_PERMITE_MORE_ONE_ACCOUNT);
            }
        }

    }
}
