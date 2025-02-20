<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\EventService;
use App\Validators\EventValidator;
use CodeIgniter\HTTP\RedirectResponse;

class EventController extends BaseController
{

    /**
     * @var EventService $service Service body to manage business logic.
     */
    protected $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
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

            return redirect()->to('dashboard/events')->with('success', 'Event created successfully!');
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

            return redirect()->to('/dashboard/events')->with('success', 'Event updated successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
            return redirect()->to('/dashboard/events')->with('success', 'Event deleted successfully!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
