<?php

namespace App\Repositories;

use App\Entities\EventEntity;
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
     * @return array<EventEntity>|null The list of events.
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
     * @return EventEntity|null The event data, or null if not found.
     * @throws Exception
     */
    public function findById(int $id): EventEntity|null
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
     * @param EventEntity $event
     * @return bool True in case of success, false if not.
     * @throws Exception
     */
    public function create(EventEntity $event): bool
    {
        try {
            return $this->eventModel->save($event);
        } catch (\Exception $e) {
            $message = 'Error creating the event: ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Updates an existing event.
     *
     * @param EventEntity $event
     * @return bool True in case of success, false if not.
     * @throws Exception
     */
    public function update(EventEntity $event): bool
    {
        try {
            return $this->eventModel->save($event);
        } catch (\Exception $e) {
            $message = 'Error updating the event with ID ' . $event->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Deletes an event
     *
     * @param EventEntity $event
     * @return bool True in case of success, false if not.
     * @throws Exception
     */
    public function delete(EventEntity $event): bool
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
