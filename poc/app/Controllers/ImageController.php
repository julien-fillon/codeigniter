<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\ImageEntity;
use App\Enums\ImageCategory;
use App\Services\EventService;
use App\Services\ImageService;
use App\Validators\ImageValidator;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class ImageController extends BaseController
{

    /**
     * @var ImageService $service Service body to manage business logic.
     */
    protected $imageService;
    /**
     * @var EventService $service Service body to manage business logic.
     */
    protected $eventService;
    protected $redirect = 'images.index';

    public function __construct()
    {
        helper(['form', 'url']); // Load the necessary helpers
        $this->imageService = new ImageService();
        $this->eventService = new EventService();
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
            $message = $this->imageService->uploadImage($file, $name, $category) ?
                ['success', 'Image uploaded successfully !'] :
                ['error', 'An error occurred when uploading the image !'];
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
        }

        return redirect()->route($this->redirect)->with($message[0], $message[1]);
    }

    /**
     * Upload a new image event.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function uploadImageEvent(): RedirectResponse
    {

        $message = ['error', 'An error occurred when uploading the image !'];

        if (!$this->validate(ImageValidator::rules())) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('image');
        $name = $this->request->getPost('name');
        $category = ImageCategory::EVENT->value;
        $entityId = $this->request->getPost('entity_id');

        try {

            // If the event does not exist returns throw \Exception
            $event = $this->eventService->getEvent($entityId);

            $image = $this->imageService->uploadImage($file, $name, $category);
            if ($image instanceof ImageEntity) {
                $message = $this->imageService->associateImageWithEntity($event, $image) ?
                    ['success', 'Image associated successfully !'] :
                    ['error', 'An error occurred when associated the image !'];
            }
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
        }

        return redirect()->route('events.edit', [$entityId])->with($message[0], $message[1]);
    }

    /**
     * Upload a new image date.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function uploadImageDate(): RedirectResponse
    {

        $request = service('request');
        $redirectUrl = $request->getServer('HTTP_REFERER');

        $message = ['error', 'An error occurred when uploading the image !'];

        if (!$this->validate(ImageValidator::rules())) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('image');
        $name = $this->request->getPost('name');
        $category = ImageCategory::DATE->value;

        try {
            $message = $this->imageService->uploadImage($file, $name, $category) ?
                ['success', 'Image uploaded successfully !'] :
                ['error', 'An error occurred when uploading the image !'];
        } catch (\Exception $e) {
            $message = ['error', 'An error occurred when uploading the image : ' . $e->getMessage()];
        }

        return redirect()->TO($redirectUrl)->with($message[0], $message[1]);
    }


    /**
     * Complete edit form
     *
     * @param  string $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editForm(string $id): ResponseInterface
    {
        try {
            // Recover the image by ID
            $image = $this->imageService->getImage($id)->toArray();
            // Generate the HTML form (for injection in the modal)
            $formHtml = view('dashboard/images/partial/edit_form', ['image' => $image]);
            return $this->response->setJSON([
                'success' => true,
                'html' => $formHtml,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ImageController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Updates an existing image.
     *
     * @param int $id Image Id.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $request = service('request');
        $redirectUrl = $request->getServer('HTTP_REFERER');

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

        return redirect()->to($redirectUrl)->with($message[0], $message[1]);
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

        return redirect()->route($this->redirect)->with($message[0], $message[1]);
    }
}
