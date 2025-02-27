<?php

namespace App\Services;

use App\Entities\DateEntity;
use App\Repositories\DateRepository;
use App\Repositories\EventRepository;
use App\Repositories\ImageRepository;
use Config\Database;

class DateService
{

    protected $dateRepo;
    protected $imageRepo;
    protected $eventRepo;
    protected $db;

    public function __construct()
    {
        $this->dateRepo = new DateRepository();
        $this->imageRepo = new ImageRepository();
        $this->eventRepo = new EventRepository();
        $this->db = Database::connect();
    }

    /**
     * Recovers a single date by his ID.
     *
     * @param int $id The ID of the date.
     * @return DateEntity Details of the date.
     * @throws \RuntimeException
     */
    public function getDate(int $id): DateEntity
    {
        try {
            $date = $this->dateRepo->findById($id);

            if (!$date) {
                $message = 'Date not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            // Image associated with the date
            $date->images = $date->getImage();

            return $date;
        } catch (\Exception $e) {
            $message = 'Unable to retrieve date with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Creates a new date.
     *
     * @param array $datas Date data.
     * @return int|bool The ID of the date created or False in case of failure.
     * @throws \RuntimeException
     */
    public function createDate(array $datas): int|bool
    {
        try {

            $date = date('Y-m-d H:i:s');

            $datas['created_at'] = $date;
            $datas['updated_at'] = $date;

            $event = $this->eventRepo->findById($datas['event_id']);
            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            $date = new DateEntity();
            $date->fill($datas);

            return $this->dateRepo->create($date);
        } catch (\Exception $e) {
            $message = 'Unable to create the date: ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Updates an existing date.
     *
     * @param int $id The ID of the date.
     * @param array $datas New data for the date.
     * @return bool True in case of success, false if not.
     * @throws \RuntimeException|\Exception
     */
    public function updateDate($id, array $datas): bool
    {
        try {
            $date = $this->dateRepo->findById($id);

            if (!$date) {
                $message = 'Date not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            $event = $this->eventRepo->findById($datas['event_id']);
            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            $datas['updated_at'] = date('Y-m-d H:i:s');

            $date->fill($datas);

            return $this->dateRepo->update($date);
        } catch (\Exception $e) {
            $message = 'Unable to update date with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Associate images with a date
     *
     * @param int $dateId
     * @param int $imageId
     * @return bool
     * @throws \Exception
     */
    public function attachImagesToDate(int $dateId, int $imageId): bool
    {
        // Check if the date exists
        $date = $this->dateRepo->findById($dateId);
        if (!$date) {
            throw new \Exception('Date not found.');
        }

        // Check if the images exist
        $image = $this->imageRepo->findById($imageId);
        if (!$image) {
            throw new \Exception('Image not found.');
        }
        // Associate images with the date
        return $this->dateRepo->associateImageToDate($date, $image);
    }

    /**
     * Deletes a date by his ID.
     *
     * @param int $id The ID of the date.
     * @return bool True in case of success, false if not.
     * @throws \RuntimeException|\Exception
     */
    public function deleteDate($id): bool
    {
        try {
            $date = $this->dateRepo->findById($id);

            if (!$date) {
                $message = 'Date not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            return $this->dateRepo->delete($date);
        } catch (\Exception $e) {
            $message = 'Unable to delete date with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }
}
