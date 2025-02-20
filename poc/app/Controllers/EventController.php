<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\EventService;
use App\Validators\EventValidator;

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
    public function index()
    {
        $data['events'] = $this->eventService->getList();
        return view('dashboard/events/index', $data);
    }

    /**
     * Displays the creation form.
     *
     * @return string Render of the Vue.
     */
    public function create()
    {
        return view('dashboard/events/create');
    }

    /**
     * Records a new event.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {

        try {

            if (!$this->validate(EventValidator::rules())) {
                return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
            }

            $data = $this->request->getPost();
            $this->eventService->createEvent($data);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/events')->with('success', 'Event created successfully!');
    }
}
