<?php

namespace App\Services;

use App\Entities\ImageEntity;
use App\Repositories\EventRepository;
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

    protected $eventRepo;
    protected $imageRepo;
    protected $db;

    public function __construct()
    {
        $this->imageRepo = new ImageRepository();
        $this->eventRepo = new EventRepository();
        $this->db = Database::connect();
    }

    /**
     * Recovers the full list of images.
     *
     * @return array<ImageEntity> The list of images.
     * @throws \RuntimeException
     */
    public function getList(): array
    {
        $imageList = [];
        try {
            $images = $this->imageRepo->findAll();
            foreach ($images as $image) {
                $imageList[] = [
                    'id' => $image->id,
                    'name' => $image->name,
                    'path' => $image->path,
                    'category' => $image->category
                ];
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to retrieve events: ' . $e->getMessage());
        }

        return $imageList;
    }

    /**
     * Recovers the full list of images.
     *
     * @param  string $category
     * @return array<ImageEntity> The list of images.
     * @throws \RuntimeException
     */
    public function getListCategory(string $category): array
    {
        $imageList = [];
        try {
            $images = $this->imageRepo->findByCategory($category);
            foreach ($images as $image) {
                $imageList[] = [
                    'id' => $image->id,
                    'name' => $image->name,
                    'path' => $image->path,
                    'category' => $image->category
                ];
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to retrieve events: ' . $e->getMessage());
        }

        return $imageList;
    }

    /**
     * Upload a new image.
     * 
     * @param UploadedFile $file
     * @param string $name
     * @param string $category
     * @return bool
     * @throws \RuntimeException|\Exception
     */
    public function uploadImage(UploadedFile $file, string $name, string $category): bool|ImageEntity
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

                $date = date('Y-m-d H:i:s');
                $image = new ImageEntity();
                $image->name = $name;
                $image->category = $category;
                $image->path = $destPath;
                $image->size = $size;
                $image->width = self::IMAGE_WIDTH;
                $image->height = self::IMAGE_HEIGHT;
                $image->type = self::IMAGE_TYPE;
                $image->created_at = $date;
                $image->updated_at = $date;

                $image->id = $this->imageRepo->create($image);
                return $image;
            } catch (ImageException | RuntimeException $e) {
                $message = 'Error when uploading the image : ' . $e->getMessage();
                log_message('error', $message);
                $this->cleanupFiles([$tmpPath, $fullDestPath]);
                throw new \RuntimeException($message);
            }
        }

        return false;
    }

    /**
     * Updates an existing image.
     *
     * @param  string $id
     * @param  UploadedFile $file
     * @param  string $name
     * @param  string $category
     * @return bool
     * @throws \RuntimeException|\Exception
     */
    public function updateImage(string $id, UploadedFile $file, string $name, string $category): bool
    {
        $image = $this->imageRepo->findById($id);
        if (!$image) {
            $message = 'Image not found';
            log_message('error', $message);
            throw new \Exception($message);
        }

        $size = $image->size;
        $destPath = $image->path;
        $originalFullDestPath = FCPATH . $destPath;

        // Backup of the original file before modification
        $backupPath = $originalFullDestPath . '.bak';
        if (!copy($originalFullDestPath, $backupPath)) {
            $message = 'Failed to create backup for ' . $originalFullDestPath;
            log_message('error', $message);
            throw new \Exception($message);
        }

        try {

            if ($file && $file->isValid() && !$file->hasMoved()) {

                // Generate a unique path with a date under-arborescence
                $dateFolder = date('d-m-Y');
                $targetDirectory = 'uploads/' . $dateFolder . '/';

                // Generation of a file name with extension ".webp"
                $targetFileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME) . '.' . self::IMAGE_TYPE;
                $fullDestPath = FCPATH . $targetDirectory . $targetFileName;
                $destPath = '/' . $targetDirectory . $targetFileName;

                $size = $file->getSize();
                $tmpPath = $file->getTempName();

                $imageService = service('image');

                $imageService->withFile($tmpPath)
                    ->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, true, 'width')
                    ->convert(IMAGETYPE_WEBP)
                    ->save($fullDestPath);

                $this->cleanupFiles([$tmpPath, $originalFullDestPath]);
            }

            $image->name = $name;
            $image->category = $category;
            $image->path = $destPath;
            $image->size = $size;
            $image->width = self::IMAGE_WIDTH;
            $image->height = self::IMAGE_HEIGHT;
            $image->type = self::IMAGE_TYPE;
            $image->updated_at = date('Y-m-d H:i:s');

            // Database update
            $success = $this->imageRepo->update($image);
            if ($success) {
                $this->cleanupFiles([$backupPath]);
            }

            return $success;
        } catch (\Exception $e) {
            $message = 'Error when uploading the image : ' . $e->getMessage();
            log_message('error', $message);

            if (file_exists($backupPath)) {
                rename($backupPath, $originalFullDestPath); // Restoration of the original file
            }

            if (isset($tmpPath)) {
                $this->cleanupFiles([$tmpPath, $fullDestPath]);
            }

            throw new \RuntimeException($message);
        }
    }

    /**
     * Associate an image with an entity according to the category
     *
     * @param int $entityId ID of the entity (ex: event)
     * @param string $category Category to which the image belongs
     * @param ImageEntity $image The image entity to associate
     * @return bool Indicates if the association was successfully carried out
     * @throws Exception If the entity to which associating the image does not exist
     */
    public function associateImageWithEntity(int $entityId, string $category, ImageEntity $image): bool
    {
        if (empty($entityId)) {
            log_message('error', 'entity_id ne peut pas être vide');
            throw new \Exception('Entity ID cannot be empty');
        }

        try {
            switch ($category) {
                case ImageEntity::CATEGORY_EVENT:

                    $event = $this->eventRepo->findById($entityId);

                    if (!$event) {
                        $message = sprintf('Événement non trouvé pour ID : %d', $entityId);
                        log_message('error', $message);
                        throw new \Exception($message);
                    }

                    // Recover the existing images associated with this event
                    $images = $this->eventRepo->findImagesByEvent($event);

                    // Create a list of image IDs, including the ID of the new image
                    $imageIds = array_merge(array_column($images, 'id'), [$image->id]);

                    // Recover complete image entities from their IDS
                    $updatedImages = $this->imageRepo->findByIds($imageIds);

                    // Attach the updated images to the event
                    $this->eventRepo->attachImages($event, $updatedImages);
                    break;

                default:
                    $message = sprintf('Catégorie inconnue : %s', $category);
                    log_message('error', $message);
                    throw new \Exception($message);
            }

            return true;
        } catch (\Throwable $e) {
            $message = sprintf('Error during the association of the image: %s', $e->getMessage());
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Deletes an image by his ID.
     *
     * @param  int $id
     * @return bool
     * @throws \RuntimeException|\Exception
     */
    public function deleteImage(int $id): bool
    {
        $this->db->transStart();

        try {
            $image = $this->imageRepo->findById($id);

            if (!$image) {
                $message = 'Image not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            if (!empty($image->path)) {
                // Delete the stored file
                $filePath = FCPATH . $image->path;
                // Delete file
                if (file_exists($filePath) && !unlink($filePath)) {
                    $message = 'Error when deleting the file';
                    log_message('error', $message);
                    throw new \Exception($message);
                }
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
            if (!file_exists($filePath)) {
                file_put_contents($filePath, file_get_contents('php://input'));
            }

            throw new \RuntimeException($message);
        }

        return false;
    }

    /**
     * Delete given files if they exist
     *
     * @param  array<string> $files
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
