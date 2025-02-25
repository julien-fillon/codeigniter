<?php

namespace App\Repositories;

use App\Entities\EventEntity;
use App\Entities\ImageEntity;
use App\Enums\ImageCategory;
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

    /**
     * Associate images with an event.
     *
     * @param EventEntity $event
     * @param array<ImageEntity> $images
     */
    public function attachImages(EventEntity $event, array $images): void
    {
        $db = db_connect();
        $builder = $db->table('event_images');

        // Deletes old associations
        $builder->where('event_id', $event->id)->delete();

        // Add the new associations
        $data = [];
        if (!empty($images)) {
            foreach ($images as $image) {
                $data[] = [
                    'event_id' => $event->id,
                    'image_id' => $image->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }

            $builder->insertBatch($data);
        }
    }

    /**
     * Recovers all the images related to an event.
     *
     * @param EventEntity $event
     * @return array
     */
    public function findImagesByEvent(EventEntity $event): array
    {
        $db = db_connect();
        $builder = $db->table('event_images');
        $builder->select('images.*');
        $builder->join('images', 'images.id = event_images.image_id');
        $builder->where('event_images.event_id', $event->id);
        $builder->where('images.category', ImageCategory::EVENT->value);

        return $builder->get()->getResultArray();
    }

    /**
     * Recovers all events related to an image.
     *
     * @param ImageEntity $image
     * @return array
     */
    public function findEventsByImageId(ImageEntity $image): array
    {
        $db = db_connect();
        $builder = $db->table('event_images');
        $builder->select('events.*');
        $builder->join('events', 'events.id = event_images.event_id');
        $builder->where('event_images.image_id', $image->id);

        return $builder->get()->getResultArray();
    }
}
