<?php

class PostManager
{
    private string $storage;
    private string $uploadsDir;
    private string $uploadsWebPath;

    public function __construct()
    {
        $this->storage = __DIR__ . "/../data/posts.json";
        $this->uploadsDir = __DIR__ . "/../images/user-uploads";
        $this->uploadsWebPath = "images/user-uploads";

        if (!is_dir($this->uploadsDir)) {
            mkdir($this->uploadsDir, 0777, true);
        }

        if (!file_exists($this->storage)) {
            file_put_contents($this->storage, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public function getAll(): array
    {
        $data = json_decode(file_get_contents($this->storage), true);
        return is_array($data) ? $data : [];
    }

    public function getByAuthor(string $email): array
    {
        return array_values(array_filter($this->getAll(), function ($post) use ($email) {
            return isset($post['author_email']) && $post['author_email'] === $email;
        }));
    }

    public function add(array $post): array
    {
        $all = $this->getAll();
        $all[] = $post;
        file_put_contents($this->storage, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $post;
    }

    public function deleteById(string $id, string $authorEmail): bool
    {
        $all = $this->getAll();
        $found = false;
        $remaining = [];

        foreach ($all as $post) {
            if (($post['id'] ?? '') === $id && ($post['author_email'] ?? '') === $authorEmail) {
                $imagePath = __DIR__ . "/../" . ($post['cover'] ?? '');
                if (strpos($post['cover'] ?? '', 'images/user-uploads/') === 0 && file_exists($imagePath)) {
                    @unlink($imagePath);
                }
                $found = true;
                continue;
            }
            $remaining[] = $post;
        }

        if ($found) {
            file_put_contents($this->storage, json_encode($remaining, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        return $found;
    }

    public function handleUpload(array $file): array
    {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'upload_error'];
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            return ['ok' => false, 'error' => 'file_too_large'];
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!isset($allowed[$mime])) {
            return ['ok' => false, 'error' => 'invalid_type'];
        }

        $ext = $allowed[$mime];
        $filename = uniqid('post_', true) . '.' . $ext;
        $destination = $this->uploadsDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['ok' => false, 'error' => 'move_failed'];
        }

        return [
            'ok' => true,
            'path' => $this->uploadsWebPath . '/' . $filename,
        ];
    }
}
