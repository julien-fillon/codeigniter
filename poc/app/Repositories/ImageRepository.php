<?php

namespace App\Repositories;

use App\Entities\ImageEntity;
use App\Models\ImageModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class ImageRepository
{
    /**
     * @var ImageModel $imageModel Image model instance
     */
    protected $imageModel;

    public function __construct()
    {
        $this->imageModel = new ImageModel();
    }

    /**
     * List images
     *
     * @return array<ImageEntity>|null The list of images.
     * @throws Exception
     */
    public function findAll(): array|null
    {
        try {
            return $this->imageModel->findAll();
        } catch (Exception $e) {
            $message = 'Error fetching all images : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * List by category
     *
     * @param  string $category
     * @return array<ImageEntity>|null The list of images.
     * @throws Exception
     */
    public function findByCategory(string $category): array|null
    {
        try {
            return $this->imageModel
                ->where('category', $category)
                ->findAll();
        } catch (Exception $e) {
            $message = 'Error fetching all images : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Recovers an image by his ID.
     *
     * @param int $id
     * @return ImageEntity|null The image data, or null if not found.
     * @throws Exception
     */
    public function findById(int $id): ImageEntity|null
    {
        try {
            return $this->imageModel->find($id);
        } catch (Exception $e) {
            $message = 'Error fetching the image with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Recover a list of images by their IDS.
     *
     * @param array $imageIds List of IDS of images to recover.
     * @return array<ImageEntity> List of images in the form of Array.
     */
    public function findByIds(array $imageIds): array
    {
        if (empty($imageIds)) {
            return []; // Return an empty painting if no ID is provided
        }

        // Use of a wherein clause to recover the images
        try {
            return $this->imageModel
                ->whereIn('id', $imageIds)
                ->findAll();
        } catch (\Exception $e) {
            $message = 'Error when recovering images : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Create a new image.
     *
     * @param  ImageEntity $image
     * @return int The ID of the newly created image
     * @throws Exception
     */
    public function create(ImageEntity $image): int
    {
        try {
            if ($this->imageModel->save($image)) {
                return $this->imageModel->getInsertID();
            }

            throw new DatabaseException('Failed to save the image into the database.');
        } catch (Exception $e) {
            $message = 'Error creating the image : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Update an image
     *
     * @param  ImageEntity $image
     * @return bool True in case of success, false if not.
     * @throws Exception
     */
    public function update(ImageEntity $image): bool
    {
        try {
            return $this->imageModel->save($image);
        } catch (\Exception $e) {
            $message = 'Error updating the image with ID ' . $image->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Delete an image
     *
     * @param  ImageEntity $image
     * @return bool
     * @throws DatabaseException
     */
    public function delete(ImageEntity $image): bool
    {
        try {
            return $this->imageModel->delete($image->id);
        } catch (\Exception $e) {
            $message = 'Error deleting the image with ID ' . $image->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }
}
