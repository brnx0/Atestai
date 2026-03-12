<?php

namespace App\Traits;

trait SanitizesUtf8
{
    /**
     * Recursively sanitizes data to UTF-8 encoding.
     *
     * @param mixed $data
     * @return mixed
     */
    public function sanitizeUtf8($data)
    {
        if (is_array($data) || $data instanceof \Illuminate\Support\Collection) {
            $cleaned_data = [];
            foreach ($data as $key => $value) {
                $cleaned_data[$key] = $this->sanitizeUtf8($value);
            }
            return $cleaned_data;
        }

        if (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'auto');
        }

        return $data;
    }
}
