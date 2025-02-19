<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ImageService;

class ImageController extends BaseController
{

    protected $imageService;

    public function __construct()
    {
        $this->imageService = new ImageService();
    }

    public function index()
    {
        $data['images'] = $this->imageService->getList();
        return view('dashboard/images/index', $data);
    }

    public function upload()
    {
        if ($this->request->getMethod() === 'POST') {
            $file = $this->request->getFile('image');
            $name = $this->request->getPost('name');
            $category = $this->request->getPost('category');

            if ($this->imageService->uploadImage($file, $name, $category)) {
                return view('/dashboard/images/index', [
                    'success' => 'Image uploaded successfully !',
                ]);
            }
        }

        return view('/dashboard/images/index', [
            'error' => 'Failed to upload image.',
        ]);
    }
}
