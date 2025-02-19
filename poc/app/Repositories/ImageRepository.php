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
     * @return array
     */
    public function findAllImages(): array|null
    {
        try {
            return $this->imageModel->findAll();
        } catch (Exception $e) {
            log_message('error', 'Erreur ImageRepository : ' . $e->getMessage());
            return null;
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
            ]);
        } catch (Exception $e) {
            log_message('error', 'Erreur create image ImageRepository : ' . $e->getMessage());
            return false;
        }
    }
}
