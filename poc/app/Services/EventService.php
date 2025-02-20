<?php

namespace App\Services;

use App\Repositories\EventRepository;
use Config\Database;

class EventService
{

    protected $eventRepo;
    protected $db;

    public function __construct()
    {
        $this->eventRepo = new EventRepository();
        $this->db = Database::connect();
    }

    /**
     * Recovers the full list of events.
     *
     * @return array<EventModel> The list of events.
     * @throws \RuntimeException
     */
    public function getList(): array
    {
        $eventList = [];
        try {
            $events = $this->eventRepo->findAll();
            foreach ($events as $event) {
                $eventList[] = [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'organizer_name' => $event->organizer_name,
                    'organizer_surname' => $event->organizer_surname
                ];
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to retrieve events: ' . $e->getMessage());
        }

        return $eventList;
    }

    /**
     * Creates a new event.
     *
     * @param array $data Event data.
     * @return int|bool The ID of the event created or False in case of failure.
     * @throws \RuntimeException
     */
    public function createEvent(array $data)
    {
        try {
            // Generation of Slug and Shorturl
            $data['slug'] = (isset($data['slug'])) ? strtolower(url_title($data['slug'], '-', true)) : strtolower(url_title($data['event_name'], '-', true));
            $data['shorturl'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7);

            return $this->eventRepo->create($data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to create the event: ' . $e->getMessage());
        }
    }

    /**
     * Updates an existing event.
     *
     * @param int $id The ID of the event.
     * @param array $data New data for the event.
     * @return bool True in case of success, false if not.
     * @throws \RuntimeException|\Exception
     */
    public function updateEvent($id, array $data)
    {
        try {
            $event = $this->eventRepo->findById($id);

            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            return $this->eventRepo->update($event, $data);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to update event with ID ' . $id . ': ' . $e->getMessage());
        }
    }

    /**
     * Deletes an event by his ID.
     *
     * @param int $id The ID of the event.
     * @return bool True in case of success, false if not.
     * @throws \RuntimeException|\Exception
     */
    public function deleteEvent($id)
    {
        try {
            $event = $this->eventRepo->findById($id);

            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            return $this->eventRepo->delete($event);
        } catch (\Exception $e) {
            $message = 'Unable to delete event with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }
}
