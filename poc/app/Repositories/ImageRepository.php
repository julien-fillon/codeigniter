<?php

namespace App\Repositories;

use App\Models\ImageModel;
use Exception;

class ImageRepository
{
    protected $imageModel;

    public function __construct()
    {
        $this->imageModel = new ImageModel();
    }

    /**
     * List images
     *
     * @return array<ImageModel>
     */
    public function findAllImages(): array|null
    {
        try {
            return $this->imageModel->findAll();
        } catch (Exception $e) {
            $message = 'Error findAllImages in ImageRepository : ' . $e->getMessage();
            log_message('error', $message);
            throw new \Exception($message);
        }
    }

    /**
     * Detail image
     *
     * @param  int $id
     * @return ImageModel
     */
    public function findImageById(int $id): ImageModel|null
    {
        try {
            return $this->imageModel->find($id);
        } catch (Exception $e) {
            $message = 'Error findById in ImageRepository : ' . $e->getMessage();
            log_message('error', $message);
            throw new \Exception($message);
        }
    }

    /**
     * Create image
     *
     * @param  string $name
     * @param  string $category
     * @param  string $path
     * @param  int $size
     * @param  int $width
     * @param  int $height
     * @param  string $type
     * @return bool
     */
    public function create(string $name, string $category, string $path, int $size, int $width, int $height, string $type): bool
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
            $message = 'Error create image ImageRepository : ' . $e->getMessage();
            log_message('error', $message);
            throw new \Exception($message);
        }
    }

    /**
     * deleteImage
     *
     * @param  ImageModel $image
     * @return bool
     */
    public function deleteImage(ImageModel $image): bool
    {
        try {
            return $this->imageModel->delete($image->id);
        } catch (\Exception $e) {
            $message = 'Error when deleting the image : ' . $e->getMessage();
            log_message('error', $message);
            throw new \Exception($message);
        }
    }
}
