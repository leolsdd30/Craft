<?php
/**
 * Input Validator
 * Validates and sanitizes user input
 */
class Validator {
    private array $errors = [];

    /**
     * Validate required field
     */
    public function required(string $field, $value, ?string $label = null): self {
        if (empty(trim((string) $value))) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.required');
        }
        return $this;
    }

    /**
     * Validate email format
     */
    public function email(string $field, $value, ?string $label = null): self {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.invalid_email');
        }
        return $this;
    }

    /**
     * Validate minimum string length
     */
    public function minLength(string $field, $value, int $min, ?string $label = null): self {
        if (!empty($value) && mb_strlen(trim($value)) < $min) {
            $this->errors[$field] = ($label ?? $field) . ' ' . sprintf(__('validation.min_length'), $min);
        }
        return $this;
    }

    /**
     * Validate maximum string length
     */
    public function maxLength(string $field, $value, int $max, ?string $label = null): self {
        if (!empty($value) && mb_strlen(trim($value)) > $max) {
            $this->errors[$field] = ($label ?? $field) . ' ' . sprintf(__('validation.max_length'), $max);
        }
        return $this;
    }

    /**
     * Validate password confirmation match
     */
    public function confirmed(string $field, $value, $confirmation, ?string $label = null): self {
        if ($value !== $confirmation) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.confirmed');
        }
        return $this;
    }

    /**
     * Validate integer
     */
    public function integer(string $field, $value, ?string $label = null): self {
        if (!empty($value) && filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.integer');
        }
        return $this;
    }

    /**
     * Validate numeric value within range
     */
    public function between(string $field, $value, $min, $max, ?string $label = null): self {
        if (!empty($value) && (floatval($value) < $min || floatval($value) > $max)) {
            $this->errors[$field] = ($label ?? $field) . ' ' . sprintf(__('validation.between'), $min, $max);
        }
        return $this;
    }

    /**
     * Validate Algerian phone number (05, 06, 07)
     */
    public function phone(string $field, $value, ?string $label = null): self {
        if (!empty($value) && !preg_match('/^0[567]\d{8}$/', $value)) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.phone');
        }
        return $this;
    }

    /**
     * Validate unique value in database
     */
    public function unique(string $field, $value, string $table, ?int $exceptId = null, ?string $label = null): self {
        if (empty($value)) return $this;

        $db = getDB();
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$field} = :value";
        $params = ['value' => $value];

        if ($exceptId !== null) {
            $sql .= " AND id != :except_id";
            $params['except_id'] = $exceptId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        if ($stmt->fetch()['count'] > 0) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.unique');
        }
        return $this;
    }

    /**
     * Validate value exists in a set
     */
    public function in(string $field, $value, array $allowed, ?string $label = null): self {
        if (!empty($value) && !in_array($value, $allowed)) {
            $this->errors[$field] = ($label ?? $field) . ' ' . __('validation.invalid');
        }
        return $this;
    }

    /**
     * Check if validation passed
     */
    public function passes(): bool {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     */
    public function fails(): bool {
        return !empty($this->errors);
    }

    /**
     * Get all errors
     */
    public function errors(): array {
        return $this->errors;
    }

    /**
     * Get first error message
     */
    public function firstError(): string {
        return reset($this->errors) ?: '';
    }

    /**
     * Store errors in session for display after redirect
     */
    public function flashErrors(): void {
        $_SESSION['flash']['errors'] = $this->errors;
        $_SESSION['flash']['old_input'] = $_POST;
    }

    /**
     * Static helper: sanitize string
     */
    public static function sanitize(string $value): string {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
