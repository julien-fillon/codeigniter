<?php

namespace App\Services;

use App\Entities\EventEntity;
use App\Repositories\DateRepository;
use App\Repositories\EventRepository;
use App\Repositories\ImageRepository;
use Config\Database;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Transliterator;

class EventService
{

    protected $eventRepo;
    protected $imageRepo;
    protected $dateRepo;
    protected $db;

    public function __construct()
    {
        $this->eventRepo = new EventRepository();
        $this->imageRepo = new ImageRepository();
        $this->dateRepo = new DateRepository();
        $this->db = Database::connect();
    }

    /**
     * Recovers the full list of events.
     *
     * @return array<EventEntity> The list of events.
     * @throws \RuntimeException
     */
    public function getList(): array
    {
        $eventList = [];
        try {
            $events = $this->eventRepo->findAll();
            foreach ($events as $event) {
                $eventList[] = [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'organizer_name' => $event->organizer_name,
                    'organizer_surname' => $event->organizer_surname
                ];
            }
        } catch (\Exception $e) {
            $message = 'Unable to retrieve events: ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }

        return $eventList;
    }

    /**
     * Recovers a single event by his ID.
     *
     * @param int $id The ID of the event.
     * @return EventEntity Details of the event.
     * @throws \RuntimeException
     */
    public function getEvent(int $id): EventEntity
    {
        try {
            $event = $this->eventRepo->findById($id);

            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            // Image associated with the event
            $event->images = $this->eventRepo->findImagesByEvent($event);
            $event->dates = $this->eventRepo->findDatesForEvent($event);

            return $event;
        } catch (\Exception $e) {
            $message = 'Unable to retrieve event with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Creates a new event.
     *
     * @param array $datas Event data.
     * @return int|bool The ID of the event created or False in case of failure.
     * @throws \RuntimeException
     */
    public function createEvent(array $datas): int|bool
    {
        try {
            // Generation of Slug and Shorturl
            $datas['slug'] = $this->generateSlug($datas, 'slug', 'event_name');
            $datas['shorturl'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7);

            if (isset($datas['social_links']) && !empty($datas['social_links'])) {
                $datas['social_links'] = $this->convertUrlsToJson($datas['social_links']);
            } else {
                $datas['social_links'] = null;
            }

            $date = date('Y-m-d H:i:s');

            $datas['created_at'] = $date;
            $datas['updated_at'] = $date;

            $datas['qrcode'] = $this->generateQRCode($datas['shorturl'], $datas['slug']);

            $event = new EventEntity();
            $event->fill($datas);

            return $this->eventRepo->create($event);
        } catch (\Exception $e) {

            if (isset($datas['qrcode']) && file_exists($datas['qrcode'])) {
                unlink($datas['qrcode']);
            }

            $message = 'Unable to create the event: ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Updates an existing event.
     *
     * @param int $id The ID of the event.
     * @param array $datas New data for the event.
     * @return bool True in case of success, false if not.
     * @throws \RuntimeException|\Exception
     */
    public function updateEvent($id, array $datas): bool
    {
        try {
            $event = $this->eventRepo->findById($id);

            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            if (isset($datas['social_links']) && !empty($datas['social_links'])) {
                $datas['social_links'] = $this->convertUrlsToJson($datas['social_links']);
            } else {
                $datas['social_links'] = null;
            }

            $datas['slug'] = $this->generateSlug($datas, 'slug', 'event_name');

            $datas['updated_at'] = date('Y-m-d H:i:s');

            $event->fill($datas);

            return $this->eventRepo->update($event);
        } catch (\Exception $e) {
            $message = 'Unable to update event with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);
            throw new \RuntimeException($message);
        }
    }

    /**
     * Associate images with an event
     *
     * @param int $eventId
     * @param array $imageIds
     */
    public function attachImagesToEvent(int $eventId, array $imageIds): array
    {
        // Check if the event exists
        $event = $this->eventRepo->findById($eventId);
        if (!$event) {
            throw new \Exception('Event not found.');
        }

        // Check if the images exist
        $images = $this->imageRepo->findByIds($imageIds);
        if (!empty($images) && (count($images) !== count($imageIds))) {
            throw new \Exception('One or more images not found.');
        }
        // Associate images with the event
        $this->eventRepo->attachImages($event, $images);

        return $this->eventRepo->findImagesByEvent($event);
    }

    /**
     * Deletes an event by his ID.
     *
     * @param int $id The ID of the event.
     * @return bool True in case of success, false if not.
     * @throws \RuntimeException|\Exception
     */
    public function deleteEvent($id): bool
    {
        try {
            $event = $this->eventRepo->findById($id);

            if (!$event) {
                $message = 'Event not found';
                log_message('error', $message);
                throw new \Exception($message);
            }

            if (!empty($event->qrcode)) {
                // Delete the stored file
                $filePath = FCPATH . $event->qrcode;
                // Delete file
                if (file_exists($filePath) && !unlink($filePath)) {
                    $message = 'Error when deleting the file';
                    log_message('error', $message);
                    throw new \Exception($message);
                }
            }

            return $this->eventRepo->delete($event);
        } catch (\Exception $e) {
            $message = 'Unable to delete event with ID ' . $id . ': ' . $e->getMessage();
            log_message('error', $message);

            // If the file has been deleted but the BDD fails, it is restored
            if (!file_exists($filePath)) {
                file_put_contents($filePath, file_get_contents('php://input'));
            }

            throw new \RuntimeException($message);
        }
    }

    /**
     * Converts a chain of URLs into a JSON structure with the names of platforms as keys.
     *
     * @param string $urlString The chain containing the URLs.
     * @return string JSON in desired format.
     */
    private function convertUrlsToJson(string $urlString): string
    {
        $urls = explode(", ", $urlString);
        $result = [];

        foreach ($urls as $url) {

            // If the URL does not have a scheme (http:// or https://), add "http://"
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $url = 'https://' . $url; // Default to http:// if no scheme is provided
            }

            $host = parse_url($url, PHP_URL_HOST);

            if ($host === null) {
                log_message('error', 'Invalid URL encountered: ' . $url);
                continue; // Skip invalid URLs
            }

            // Remove common subdomains like 'www.' or country/language codes (e.g., 'fr.')
            $hostParts = explode('.', $host); // Split into parts (e.g., ['www', 'youtube', 'com'])

            // Extract the platform name (ensure we exclude 'www', 'fr', etc.)
            if (count($hostParts) > 2) {
                // Assume second-to-last part is the platform (e.g., youtube in www.youtube.com)
                $platform = $hostParts[count($hostParts) - 2];
            } else {
                // Fallback: If there's no subdomain, use the first part
                $platform = $hostParts[0];
            }

            // Associate the platform with its URL in the table
            $result[$platform] = $url;
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * Generates the slug
     *
     * @param  array $datas
     * @param  string $key
     * @param  string $fallbackKey
     * @return string
     */
    private function generateSlug(array $datas, string $key, string $fallbackKey): string
    {
        $value = '';

        if (isset($datas[$key])) {
            $value = $datas[$key];
        } elseif (isset($datas[$fallbackKey])) {
            $value = $datas[$fallbackKey];
        }

        // Remove the accents and generate a clean slug with url_title
        return strtolower(url_title($this->removeAccents($value), '-', true));
    }

    /**
     * Replaces accents in a text
     *
     * @param  string $text
     * @return string
     */
    private function removeAccents(string $text): string
    {
        // Use of transliterator to convert the accentuated characters into unchanged
        if (class_exists('Transliterator')) {
            $transliterator = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
            if ($transliterator !== null) {
                $text = $transliterator->transliterate($text);
            }
        }

        // If Transliterator is not available, use a single correspondence table
        $search = ['à', 'á', 'â', 'ä', 'ã', 'å', 'ą', 'ç', 'ć', 'č', 'ď', 'è', 'é', 'ê', 'ě', 'ë', 'ę', 'ē', 'î', 'ï', 'ı', 'í', 'ł', 'ñ', 'ň', 'ô', 'ö', 'ò', 'ó', 'õ', 'ő', 'ù', 'ü', 'û', 'ú', 'ů', 'ý', 'ÿ', 'ž', 'ź', 'ż'];
        $replace = ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'c', 'c', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'l', 'n', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'z', 'z', 'z'];

        return str_replace($search, $replace, $text);
    }


    /**
     * Generate qrcode file
     *
     * @param  string $shortUrl
     * @param  string $slugName
     * @return string
     */
    private function generateQRCode(string $shortUrl, string $slugName): string
    {
        $eventUrl = env('app.baseURL') . 'events/' . $shortUrl;

        $writer = new PngWriter();
        // Create QR code
        $qrCode = new QrCode(
            data: $eventUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0), // Black color for blocks
            backgroundColor: new Color(255, 255, 255) // White color for the background
        );

        $result = $writer->write($qrCode, null, null);

        // Determine the recording path (in the "Public/Qrcodes" file)
        $outputDir = FCPATH . 'qrcodes/';
        // Check if the recording folder exists, otherwise create it
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $targetFileName = 'qrcodes/' .  $slugName . '-' . $shortUrl . '.png';
        $outputFile = FCPATH . $targetFileName;

        // Save the QR code generated
        $result->saveToFile($outputFile);

        return '/' . $targetFileName;
    }
}
