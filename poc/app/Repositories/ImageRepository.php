<?php

namespace App\Repositories;

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
     * @return array<ImageModel> The list of images.
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
     * Recovers an image by his ID.
     *
     * @param int $id
     * @return ImageModel|null The image data, or null if not found.
     * @throws Exception
     */
    public function findById(int $id): ImageModel|null
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
     * Create a new image.
     *
     * @param  string $name
     * @param  string $category
     * @param  string $path
     * @param  int $size
     * @param  int $width
     * @param  int $height
     * @param  string $type
     * @return int|bool The ID of the image created or False in case of failure.
     * @throws Exception
     */
    public function create(string $name, string $category, string $path, int $size, int $width, int $height, string $type): int|bool
    {
        try {
            return $this->imageModel->save([
                'name'     => $name,
                'category' => $category,
                'path'     => $path,
                'size'     => $size,
                'width'    => $width,
                'height'   => $height,
                'type'     => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            $message = 'Error creating the image : ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Update an image
     *
     * @param  ImageModel $image
     * @param  string $name
     * @param  string $category
     * @param  string $path
     * @param  int $size
     * @param  int $width
     * @param  int $height
     * @param  string $type
     * @return bool
     * @throws Exception
     */
    public function update(ImageModel $image, string $name, string $category, string $path, int $size, int $width, int $height, string $type): bool
    {
        try {
            return $this->imageModel->update($image->id, [
                'name'     => $name,
                'category' => $category,
                'path'     => $path,
                'size'     => $size,
                'width'    => $width,
                'height'   => $height,
                'type'     => $type,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            $message = 'Error updating the image with ID ' . $image->id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new DatabaseException($message);
        }
    }

    /**
     * Delete an image
     *
     * @param  ImageModel $image
     * @return bool
     * @throws DatabaseException
     */
    public function delete(ImageModel $image): bool
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
