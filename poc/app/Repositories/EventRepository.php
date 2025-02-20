<?php

namespace App\Repositories;

use App\Models\EventModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class EventRepository
{
    /**
     * @var EventModel $eventModel Event model instance
     */
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    /**
     * List events
     *
     * @return array<EventModel> The list of events.
     * @throws Exception
     */
    public function findAll(): array|null
    {
        try {
            return $this->eventModel->findAll();
        } catch (Exception $e) {
            $message = 'Error fetching all events: ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Recovers an event by his ID.
     *
     * @param int $id
     * @return EventModel|null The event data, or null if not found.
     * @throws Exception
     */
    public function findById(int $id): EventModel|null
    {
        try {
            return $this->eventModel->find($id);
        } catch (\Exception $e) {
            $message = 'Error fetching the event with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Creates a new event.
     *
     * @param array $data Event data.
     * @return int|bool The ID of the event created or False in case of failure.
     * @throws Exception
     */
    public function create(array $data): int|bool
    {
        try {
            return $this->eventModel->insert($data);
        } catch (\Exception $e) {
            $message = 'Error creating the event: ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Updates an existing event.
     *
     * @param EventModel $event
     * @param array $data New data from the event.
     * @return bool True in case of success, false if not.
     * @throws Exception
     */
    public function update(EventModel $event, array $data): bool
    {
        try {
            return $this->eventModel->update($event->id, $data);
        } catch (\Exception $e) {
            $message = 'Error updating the event with ID ' . $event->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Deletes an event
     *
     * @param EventModel $event
     * @return bool True in case of success, false if not.
     * @throws Exception
     */
    public function delete(EventModel $event): bool
    {
        try {
            return $this->eventModel->delete($event->id);
        } catch (\Exception $e) {
            $message = 'Error deleting the event with ID ' . $event->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }
}
