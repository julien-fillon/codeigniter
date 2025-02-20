<?php

namespace App\Services;

use App\Repositories\ImageRepository;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Images\Exceptions\ImageException;
use Config\Database;
use RuntimeException;

class ImageService
{
    private const IMAGE_WIDTH = 200;
    private const IMAGE_HEIGHT = 200;
    private const IMAGE_TYPE = 'webp';

    protected $imageRepo;
    protected $db;

    public function __construct()
    {
        $this->imageRepo = new ImageRepository();
        $this->db = Database::connect();
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
                'path' => $image->path,
                'category' => $image->category
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
                $message = 'Failed to create target directory: ' . $targetDirectory;
                log_message('error', $message);
                throw new \Exception($message);
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
                $message = 'Error when uploading the image : ' . $e->getMessage();
                log_message('error', $message);
                $this->cleanupFiles([$tmpPath, $fullDestPath]);
                throw new \Exception($message);
            }
        }

        return false;
    }



    /**
     * updateImage
     *
     * @param  string $id
     * @param  UploadedFile $file
     * @param  string $name
     * @param  string $category
     * @return bool
     */
    public function updateImage(string $id, UploadedFile $file, string $name, string $category): bool
    {
        $image = $this->imageRepo->findImageById($id);
        if (!$image) {
            $message = 'Image not found';
            log_message('error', $message);
            throw new \Exception($message);
        }

        $size = $image->size;
        $destPath = $image->path;
        $fullDestPath = FCPATH . $destPath;

        // Backup of the original file before modification
        $backupPath = $fullDestPath . '.bak';
        if (!copy($fullDestPath, $backupPath)) {
            $message = 'Failed to create backup for ' . $fullDestPath;
            log_message('error', $message);
            throw new \Exception($message);
        }

        try {

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $size = $file->getSize();
                $tmpPath = $file->getTempName();

                $imageService = service('image');

                $imageService->withFile($tmpPath)
                    ->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, true, 'width')
                    ->convert(IMAGETYPE_WEBP)
                    ->save($fullDestPath);

                $this->cleanupFiles([$tmpPath]);
            }

            // Database update
            $success = $this->imageRepo->update($image, $name, $category, $size, self::IMAGE_WIDTH, self::IMAGE_HEIGHT, self::IMAGE_TYPE);
            if ($success) {
                $this->cleanupFiles([$backupPath]);
            }

            return $success;
        } catch (\Exception $e) {
            $message = 'Error when uploading the image : ' . $e->getMessage();
            log_message('error', $message);

            if (file_exists($backupPath)) {
                rename($backupPath, $fullDestPath); // Restoration of the original file
            }

            if (isset($tmpPath)) {
                $this->cleanupFiles([$tmpPath]);
            }

            throw new \Exception($message);
        }
    }

    /**
     * deleteImage
     *
     * @param  int $id
     * @return bool
     */
    public function deleteImage(int $id): bool
    {
        $this->db->transStart();

        try {
            $image = $this->imageRepo->findImageById($id);

            if (!$image) {
                $message = 'Image not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            // Delete the stored file
            $filePath = FCPATH . $image->path;
            // Delete file
            if (file_exists($filePath) && !unlink($filePath)) {
                $message = 'Error when deleting the file';
                log_message('error', $message);
                throw new \Exception($message);
            }

            // Delete information in the database
            $this->imageRepo->delete($image);

            $this->db->transComplete(); // Valid the transaction

            return true;
        } catch (\Exception $e) {
            $this->db->transRollback(); // Cancels the transaction in the event of an error

            $message = "Error when deleting the image:" . $e->getMessage();
            log_message('error', $message);

            // If the file has been deleted but the BDD fails, it is restored
            if (!file_exists(FCPATH . $image->path)) {
                file_put_contents($filePath, file_get_contents('php://input'));
            }

            throw new \Exception($message);
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
