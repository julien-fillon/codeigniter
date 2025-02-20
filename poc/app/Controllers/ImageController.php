<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ImageService;
use App\Validators\ImageValidator;

class ImageController extends BaseController
{

    protected $imageService;
    protected $redirect = '/dashboard/images';

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

        return redirect()->to($this->redirect)->with($message[0], $message[1]);
    }

    public function update(int $id)
    {
        // true because it is an edition
        if (!$this->validate(ImageValidator::rules(true))) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('image');
        $name = $this->request->getPost('name');
        $category = $this->request->getPost('category');

        try {
            $message = $this->imageService->updateImage($id, $file, $name, $category)
                ? ['success', 'Successful update image!']
                : ['error', 'A problem occurred when updating the image !'];
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
        }

        return redirect()->to($this->redirect)->with($message[0], $message[1]);
    }

    public function delete(int $id)
    {

        try {
            $message = $this->imageService->deleteImage($id)
                ? ['success', 'Image deleted successfully !']
                : ['error', 'The image does not exist !'];
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when deleting the image : ' . $e->getMessage()];
        }

        return redirect()->to($this->redirect)->with($message[0], $message[1]);
    }
}
