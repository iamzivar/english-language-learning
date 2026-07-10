<?php
function resolveImagePath(?string $path, string $defaultPath): string {
    // If empty, return default
    if (!$path || trim($path) === '') {
        return $defaultPath;
    }

    $normalized = trim($path);

    // Ensure assets prefix
    if (str_starts_with($normalized, 'images/')) {
        $normalized = 'assets/' . $normalized;
    }

    // If path does not start with assets/, try to fix common cases
    if (!str_starts_with($normalized, 'assets/')) {
        // If looks like just a filename, prepend assets/images/
        if (!str_contains($normalized, '/')) {
            $normalized = 'assets/images/' . $normalized;
        }
    }

    // If file exists as-is, return it
    if (file_exists($normalized)) {
        return $normalized;
    }

    // Try swapping common extensions jpg <-> png
    $ext = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png'])) {
        $altExt = $ext === 'png' ? 'jpg' : 'png';
        $altPath = preg_replace('/\.' . preg_quote($ext, '/') . '$/i', '.' . $altExt, $normalized);
        if ($altPath && file_exists($altPath)) {
            return $altPath;
        }
    }

    // Try adding assets/images/ prefix if missing
    if (!str_starts_with($normalized, 'assets/images/')) {
        $tryPath = 'assets/images/' . ltrim($normalized, '/');
        if (file_exists($tryPath)) {
            return $tryPath;
        }
    }

    // Fallback
    return $defaultPath;
}

function resolveCourseImage(?string $title, ?string $category, ?string $imageUrl, string $defaultPath = 'assets/images/grammar1.png'): string {
    // Prefer explicit image if valid
    $resolved = resolveImagePath($imageUrl, '');
    if ($resolved !== '') {
        return $resolved;
    }

    $titleLower = strtolower($title ?? '');
    $catLower = strtolower($category ?? '');

    // Map keywords/categories to provided course images
    if (str_contains($titleLower, 'ielts') || str_contains($catLower, 'ielts') || $catLower === 'exam') {
        return 'assets/images/ielts1.png';
    }
    if (str_contains($titleLower, 'toefl') || str_contains($catLower, 'toefl')) {
        return 'assets/images/toefl1.png';
    }
    if (str_contains($titleLower, 'conversation') || $catLower === 'conversation') {
        return 'assets/images/conversation1.png';
    }
    if (str_contains($titleLower, 'grammar') || $catLower === 'grammar' || $catLower === 'beginner' || $catLower === 'intermediate' || $catLower === 'advanced') {
        // Use different grammar images to add variety
        $hash = crc32(($titleLower ?: $catLower) . 'grammar');
        $pick = $hash % 3; // 0..2
        if ($pick === 0) return 'assets/images/grammar1.png';
        if ($pick === 1) return 'assets/images/grammar2.png';
        return 'assets/images/grammar3.png';
    }
    if (str_contains($titleLower, 'game')) {
        return 'assets/images/game1.png';
    }

    // Generic fallback
    return resolveImagePath(null, $defaultPath);
}

