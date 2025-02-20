<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ImageService;
use App\Validators\ImageValidator;

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

            if (!$this->validate(ImageValidator::rules())) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $file = $this->request->getFile('image');
            $name = $this->request->getPost('name');
            $category = $this->request->getPost('category');

            try {
                $message = $this->imageService->uploadImage($file, $name, $category)
                    ? ['success', 'Image uploaded successfully !']
                    : ['error', 'An error occurred when uploading the image !'];
            } catch (\Exception $e) {
                $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
            }
        }

        return redirect()->to('/dashboard/images')->with($message[0], $message[1]);
    }

    /*public function update($id)
    {
        $validation = $this->validate([
            'file_name' => 'required|min_length[3]|max_length[255]'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $message = $this->imageService->updateImage($id, ['file_name' => $this->request->getPost('file_name')]);
            return redirect()->to('/image-library')->with('success', 'Image mise à jour avec succès');
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
        }
    }*/

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
