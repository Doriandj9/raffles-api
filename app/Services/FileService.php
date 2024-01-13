<?php

namespace App\Services;

use App\Core\BaseService;
use App\Models\File;
use App\Traits\FileHandler;
use Illuminate\Http\UploadedFile;

class FileService extends BaseService {
    use FileHandler;
    public function __construct(File $file)
    {
        $this->model = $file;
    }

    public function rafflesPayment(UploadedFile $file, string $ci){
        $type = 'raffles_payment_plan';
        $uri = "users/$ci/$type";
        return $this->storeFile($file,$uri);
    }

   
}