<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Models\WebApplication;

class ApplicationService{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function index($input) {
        $input->validate([
           'picture' => 'required'
        ],[
            'picture.required' => 'กรุณาอัปโหลดรูปภาพ'
        ]);
        $app = new WebApplication();
        $imageApp = $input->file('picture');

        [$file_path, $file_name] = $this->fileService->storeFile($imageApp, 'app');

        $app->fill([
            'name' => Arr::get($input, 'name'),
            'url' => Arr::get($input, 'url'),
            'background_style' => Arr::get($input, 'background_style'),
            'button_style' => Arr::get($input, 'button_style'),
            'picture_path' => $file_path,
        ]);
        $app->save();

        return $app;
    }

    public function getAppDetail($id){
        return WebApplication::find($id);
    }

    public function EditApp($id,$input){
        $app = WebApplication::find($id);
        if(isset($app)) {
            $imageProfile = $input->file('picture');

            $file_path = $app->picture_path;
            if(isset($imageProfile) && gettype($imageProfile) === 'object') {
                [$file_path, $file_name] = $this->fileService->storeFile($imageProfile, 'user');
            }

            $app->fill([
                'name' => Arr::get($input, 'name') ?? $app->name,
                'url' => Arr::get($input, 'url') ?? $app->url,
                'picture_path' => $file_path
            ]);
            $app->save();
            return $app;
        }
        return false;
    }

    public function updateStatus($id , $input){
        $status = true;
        if ($input){
            $app = WebApplication::find($id);
            if ($app){
                if ($app->show_status === false){
                    $app->show_status = $status;
                }else{
                    $app->show_status = !$input->show_status;
                }
                $app->save();
                return true;
            }
        }
        return false;
    }

    public function deleteApp($id){
        $app = WebApplication::find($id);
        if ($app){
            $app->delete();
            return true;
        }
        return false;
    }
}
