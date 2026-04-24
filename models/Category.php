<?php
class Category extends Model {
    protected string $table = 'categories';

    public function getActive(): array {
        return $this->query(
            "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC"
        );
    }

    public function getName(array $category): string {
        $key = 'name_' . Lang::current();
        return $category[$key] ?? $category['name_en'];
    }
}
