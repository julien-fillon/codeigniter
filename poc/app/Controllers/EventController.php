<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\EventService;
use App\Services\ImageService;
use App\Validators\EventValidator;
use App\Entities\ImageEntity;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class EventController extends BaseController
{

    /**
     * @var EventService $service Service body to manage business logic.
     */
    protected $eventService;
    /**
     * @var ImageService $service Service body to manage business logic.
     */
    protected $imageService;

    public function __construct()
    {
        helper(['form', 'url']); // Load the necessary helpers
        $this->eventService = new EventService();
        $this->imageService = new ImageService();
    }

    /**
     * Displays the list of events.
     *
     * @return string Render events in sight.
     */
    public function index(): string
    {
        $data['events'] = $this->eventService->getList();
        return view('dashboard/events/index', $data);
    }

    /**
     * Displays the creation form.
     *
     * @return string Render of the Vue.
     */
    public function create(): string
    {
        return view('dashboard/events/create');
    }

    /**
     * Records a new event.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store(): RedirectResponse
    {
        try {
            if (!$this->validate(EventValidator::rules())) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $data = $this->request->getPost();
            $this->eventService->createEvent($data);

            return redirect()->route('events.index')->with('success', 'Event created successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Displays the publishing form for an event.
     *
     * @param int $id The ID of the event.
     * @return string Render of the Vue.
     */
    public function edit(int $id): string|RedirectResponse
    {
        try {
            $data['event'] = $this->eventService->getEvent($id);
            $data['entity_type'] = ImageEntity::CATEGORY_EVENT;
            return view('dashboard/events/edit', $data);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Updates an existing event.
     *
     * @param int $id The ID of the event.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id): RedirectResponse
    {
        try {

            if (!$this->validate(EventValidator::rules())) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $data = $this->request->getPost();
            $this->eventService->updateEvent($id, $data);

            return redirect()->route('events.index')->with('success', 'Event updated successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * API d'Association of images with an event
     *
     * @param int $eventId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function attachImages($eventId): ResponseInterface
    {
        try {
            $data = $this->request->getJSON(true);
            $imageIds = $data['image_ids'] ?? [];

            $images = $this->eventService->attachImagesToEvent($eventId, $imageIds);

            return $this->response->setJSON(['success' => true, 'images' => $images]);
        } catch (\Exception $e) {
            log_message('error', '[ImageController] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Load images for the event in a modal
     *
     * @param int $eventId Event ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function loadImages(int $eventId): ResponseInterface
    {
        try {
            // Recover all the available images
            $images = $this->imageService->getListCategory(ImageEntity::CATEGORY_EVENT);

            // Check if the event has associated images
            $event = $this->eventService->getEvent($eventId);

            $associatedImageIds = array_column($event['images'], 'id');

            // Generate HTML for images (improves the Ajax answer)
            $html = view('dashboard/images/modal/image_list', [
                'images' => $images,
                'associatedImageIds' => $associatedImageIds,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'html'    => $html,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[EventController] Failed to load images: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Deletes an event by his ID.
     *
     * @param int $id The ID of the event.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $this->eventService->deleteEvent($id);
            return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
