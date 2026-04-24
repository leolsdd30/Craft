<?php
class CraftsmanProfile extends Model {
    protected string $table = 'craftsman_profiles';

    public function getWithUser(int $id): ?array {
        $result = $this->query(
            "SELECT cp.*, u.name, u.email, u.phone, u.avatar, u.created_at as member_since,
                    c.name_en as category_en, c.name_ar as category_ar, c.icon as category_icon,
                    w.name_en as wilaya_en, w.name_ar as wilaya_ar,
                    cm.name_en as commune_en, cm.name_ar as commune_ar
             FROM craftsman_profiles cp
             JOIN users u ON cp.user_id = u.id
             JOIN categories c ON cp.category_id = c.id
             JOIN wilayas w ON cp.wilaya_id = w.id
             LEFT JOIN communes cm ON cp.commune_id = cm.id
             WHERE cp.id = :id",
            ['id' => $id]
        );
        return $result[0] ?? null;
    }

    public function getByUserId(int $userId): ?array {
        return $this->findBy('user_id', $userId);
    }

    public function getTopRated(int $limit = 6): array {
        return $this->query(
            "SELECT cp.*, u.name, u.avatar,
                    c.name_en as category_en, c.name_ar as category_ar,
                    w.name_en as wilaya_en, w.name_ar as wilaya_ar
             FROM craftsman_profiles cp
             JOIN users u ON cp.user_id = u.id
             JOIN categories c ON cp.category_id = c.id
             JOIN wilayas w ON cp.wilaya_id = w.id
             WHERE cp.is_verified = 1 AND cp.is_available = 1 AND u.is_active = 1
             ORDER BY cp.avg_rating DESC, cp.total_reviews DESC
             LIMIT {$limit}"
        );
    }
}
