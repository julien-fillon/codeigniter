<?php

namespace App\Services;

use App\Repositories\ImageRepository;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Images\Exceptions\ImageException;
use RuntimeException;

class ImageService
{
    private const IMAGE_WIDTH = 200;
    private const IMAGE_HEIGHT = 200;
    private const IMAGE_TYPE = 'webp';

    protected $imageRepo;

    public function __construct()
    {
        $this->imageRepo = new ImageRepository();
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $imageList = [];
        $images = $this->imageRepo->findAllImages();
        foreach ($images as $image) {
            $imageList[] = [
                'id' => $image->id,
                'name' => $image->name,
                'path' => $image->path
            ];
        }

        return $imageList;
    }

    /**
     * @param UploadedFile $file
     * @param string $name
     * @param string $category
     * @return bool
     */
    public function uploadImage(UploadedFile $file, string $name, string $category): bool
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $size = $file->getSize();
            $tmpPath = $file->getTempName();

            // Generate a unique path with a date under-arborescence
            $dateFolder = date('d-m-Y');
            $targetDirectory = 'uploads/' . $dateFolder . '/';

            // Generation of a file name with extension ".webp"
            $targetFileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME) . '.' . self::IMAGE_TYPE;
            $fullDestPath = FCPATH . $targetDirectory . $targetFileName;
            $destPath = '/' . $targetDirectory . $targetFileName;

            // Verification and creation of the directory if necessary
            if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
                log_message('error', 'Failed to create target directory: ' . $targetDirectory);
                return false;
            }

            try {
                $image = service('image');

                $image->withFile($tmpPath)
                    ->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, true, 'width')
                    ->convert(IMAGETYPE_WEBP)
                    ->save($fullDestPath);

                $this->cleanupFiles([$tmpPath]);

                return $this->imageRepo->create($name, $category, $destPath, $size, self::IMAGE_WIDTH, self::IMAGE_HEIGHT, self::IMAGE_TYPE);
            } catch (ImageException | RuntimeException $e) {
                log_message('error', 'Error when uploading the image : ' . $e->getMessage());
                $this->cleanupFiles([$tmpPath, $fullDestPath]);
                throw new \Exception("Error when uploading the image :" . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * deleteImage
     *
     * @param  int $id
     * @return bool
     */
    public function deleteImage(int $id): bool
    {
        try {
            $image = $this->imageRepo->findImageById($id);

            if ($image) {
                // Delete the stored file
                $filePath = WRITEPATH . 'uploads/' . $image->path;
                if (is_file($filePath)) {
                    unlink($filePath);
                }

                // Delete information in the database
                return $this->imageRepo->deleteImage($image);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error when deleting the image:" . $e->getMessage());
        }

        return false;
    }

    /**
     * Delete given files if they exist
     *
     * @param  array $files
     * @return void
     */
    private function cleanupFiles(array $files): void
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
