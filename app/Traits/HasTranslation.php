<?php

namespace App\Traits;

trait HasTranslation
{
    /**
     * Get translated value of a field.
     * Falls back to the base field if _en is null or locale is not 'en'.
     */
    public function trans(string $field): mixed
    {
        if (app()->getLocale() === 'en') {
            $enField = $field . '_en';
            $enValue = $this->getAttribute($enField);

            if (! empty($enValue)) {
                return $enValue;
            }
        }

        return $this->getAttribute($field);
    }
}
