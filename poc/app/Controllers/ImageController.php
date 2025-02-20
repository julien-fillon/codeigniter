<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\ImageService;
use App\Validators\ImageValidator;
use CodeIgniter\HTTP\RedirectResponse;

class ImageController extends BaseController
{

    /**
     * @var ImageService $service Service body to manage business logic.
     */
    protected $imageService;
    protected $redirect = '/dashboard/images';

    public function __construct()
    {
        $this->imageService = new ImageService();
    }

    /**
     * Displays the login form
     *
     * @return string Render events in sight.
     */
    public function index(): string
    {
        $data['images'] = $this->imageService->getList();
        return view('dashboard/images/index', $data);
    }

    /**
     * Upload a new image.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function upload(): RedirectResponse
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

    /**
     * Updates an existing image.
     *
     * @param int $id Image Id.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): RedirectResponse
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

    /**
     * Deletes an image by his ID.
     *
     * @param int $id Image ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id): RedirectResponse
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
