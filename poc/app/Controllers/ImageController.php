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

            try {
                $message = $this->imageService->uploadImage($file, $name, $category)
                    ? ['success', 'Image deleted successfully !']
                    : ['error', 'The image does not exist !'];
            } catch (\Exception $e) {
                $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
            }
        }

        return redirect()->to('/dashboard/images')->with($message[0], $message[1]);
    }

    public function delete($id)
    {

        try {
            $message = $this->imageService->deleteImage($id)
                ? ['success', 'Image deleted successfully !']
                : ['error', 'The image does not exist !'];
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when deleting the image : ' . $e->getMessage()];
        }

        return redirect()->to('/dashboard/images')->with($message[0], $message[1]);
    }
}
