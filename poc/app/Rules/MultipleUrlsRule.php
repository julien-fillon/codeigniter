<?php

namespace App\Rules;

class MultipleUrlsRule
{
    /**
     * Validates a chain containing several URLs separated by commas.
     * 
     * @param string $value The value of the field to validate.
     * @return bool Return to True if all the URLs are valid, otherwise False.
     */
    public function valid_multiple_urls(string $value): bool
    {

        log_message('debug', 'Custom validation rule "valid_multiple_urls" has been invoked.');

        // Divide the chain into an url table
        $urls = array_map('trim', explode(',', $value));

        // Check each URL with Filter_var
        foreach ($urls as $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false; // Return false if only one URL is not valid
            }
        }

        return true; // Return true if all the URLs are valid
    }
}
