<?php

namespace App\Repositories;

use App\Entities\DateEntity;
use App\Entities\ImageEntity;
use App\Models\DateModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class DateRepository
{
    /**
     * @var DateModel $dateModel Date model instance
     */
    protected $dateModel;

    public function __construct()
    {
        $this->dateModel = new DateModel();
    }

    /**
     * List dates
     *
     * @return array<DateEntity>|null The list of dates.
     * @throws DatabaseException
     */
    public function findAll(): array|null
    {
        try {
            return $this->dateModel->findAll();
        } catch (Exception $e) {
            $message = 'Error fetching all dates : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Recovers a date by his ID.
     *
     * @param int $id
     * @return DateEntity|null The date data, or null if not found.
     * @throws DatabaseException
     */
    public function findById(int $id): DateEntity|null
    {
        try {
            return $this->dateModel->find($id);
        } catch (Exception $e) {
            $message = 'Error fetching the date with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Recover a list of dates by their IDS.
     *
     * @param array $dateIds List of IDS of dates to recover.
     * @return array<DateEntity> List of dates in the form of Array.
     * @throws DatabaseException
     */
    public function findByIds(array $dateIds): array
    {
        if (empty($dateIds)) {
            return []; // Return an empty painting if no ID is provided
        }

        // Use of a wherein clause to recover the dates
        try {
            return $this->dateModel
                ->whereIn('id', $dateIds)
                ->findAll();
        } catch (\Exception $e) {
            $message = 'Error when recovering dates : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Create a new date.
     *
     * @param  DateEntity $date
     * @return int The ID of the newly created date
     * @throws DatabaseException
     */
    public function create(DateEntity $date): int
    {
        try {
            if ($this->dateModel->save($date)) {
                return $this->dateModel->getInsertID();
            }

            throw new DatabaseException('Failed to save the date into the database.');
        } catch (Exception $e) {
            $message = 'Error creating the date : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Update a date
     *
     * @param  DateEntity $date
     * @return bool True in case of success, false if not.
     * @throws DatabaseException
     */
    public function update(DateEntity $date): bool
    {
        try {
            return $this->dateModel->save($date);
        } catch (\Exception $e) {
            $message = 'Error updating the date with ID ' . $date->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Associate an image on a date.
     *
     * @param DateEntity $date
     * @param ImageEntity $image
     * @return bool
     * @throws DatabaseException
     */
    public function associateImageToDate(DateEntity $date, ImageEntity $image): bool
    {
        try {
            $date = $this->dateModel->find($date);
            if (!$date) {
                throw new \Exception("Date not found.");
            }

            // Update the Image_id field
            $date->image_id = $image->id;
            return $this->dateModel->save($date);
        } catch (\Exception $e) {
            $message = 'Error attach image date with ID ' . $date->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Delete a date
     *
     * @param  DateEntity $date
     * @return bool
     * @throws DatabaseException
     */
    public function delete(DateEntity $date): bool
    {
        try {
            return $this->dateModel->delete($date->id);
        } catch (\Exception $e) {
            $message = 'Error deleting the date with ID ' . $date->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }
}
