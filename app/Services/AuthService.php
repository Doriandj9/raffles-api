<?php

namespace App\Services;

use App\Core\BaseService;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Http\UploadedFile;

class AuthService extends BaseService {
    use FileHandler;
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function savePhoto(UploadedFile $file, string $ci){
        $uri = "users/$ci/verify";
        return $this->storeFile($file,$uri);
    }

   
}