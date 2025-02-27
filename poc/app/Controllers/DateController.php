<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\DateService;
use App\Services\ImageService;
use App\Validators\DateValidator;
use App\Enums\ImageCategory;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class DateController extends BaseController
{

    /**
     * @var DateService $service Service body to manage business logic.
     */
    protected $dateService;
    /**
     * @var ImageService $service Service body to manage business logic.
     */
    protected $imageService;

    public function __construct()
    {
        helper(['form', 'url']); // Load the necessary helpers
        $this->dateService = new DateService();
        $this->imageService = new ImageService();
    }

    /**
     * Records a new date.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $request = service('request');
        $redirectUrl = $request->getServer('HTTP_REFERER');
        try {
            if (!$this->validate(DateValidator::rules())) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $data = $this->request->getPost();
            $this->dateService->createDate($data);

            return redirect()->to($redirectUrl)->with('success', 'Date created successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Complete edit form
     *
     * @param  int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function editForm(int $id): ResponseInterface
    {
        try {
            // Recover the image by ID
            $date = $this->dateService->getDate($id)->toArray();
            // Generate the HTML form (for injection in the modal)
            $formHtml = view('dashboard/dates/partial/edit_form', ['date' => $date]);
            return $this->response->setJSON([
                'success' => true,
                'html' => $formHtml,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[DateController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Updates an existing date.
     *
     * @param int $id The ID of the date.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        $request = service('request');
        $redirectUrl = $request->getServer('HTTP_REFERER');

        try {

            if (!$this->validate(DateValidator::rules())) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $data = $this->request->getPost();
            $this->dateService->updateDate($id, $data);

            return redirect()->to($redirectUrl)->with('success', 'Date updated successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Load images for the date in a modal
     *
     * @param int $dateId Date ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function loadImages(?int $dateId = null): ResponseInterface
    {
        try {
            // Recover all the available images
            $images = $this->imageService->getListCategory(ImageCategory::DATE->value);

            // Check if the date has associated images
            if ($dateId) {
                $date = $this->dateService->getDate($dateId)->toArray();
                $associatedImageIds = array_column($date['images'], 'id');
            }

            // Generate HTML for images (improves the Ajax answer)
            $html = view('dashboard/images/partial/dates/image_list', [
                'images' => $images,
                'associatedImageIds' => $associatedImageIds ?? [],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'html'    => $html,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[DateController] Failed to load images: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Deletes a date by his ID.
     *
     * @param int $id The ID of the date.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $this->dateService->deleteDate($id);
            return redirect()->route('dates.index')->with('success', 'Date deleted successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
