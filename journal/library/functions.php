<?php

    /**
     * Escape HTML special characters in a string
     * to prevent XSS attacks.
     * @param string $value
     * @return string
     * */
    function e($value): string {
        return htmlspecialchars($value, ENT_QUOTES);
    }
