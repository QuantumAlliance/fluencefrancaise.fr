<?php

namespace App\Support;

use App\Models\Course;
use App\Models\ExamPrep;

/**
 * Projects catalogue records down to the fields that are safe to expose publicly.
 *
 * This is the single security boundary for the guest demo. The authenticated browse
 * endpoints return whole models (including lesson content); nothing here may.
 */
final class DemoCatalogMapper
{
    public const COURSE_KEYS = [
        'id', 'title', 'subtitle', 'description', 'image_url', 'level', 'category', 'language',
        'total_texts', 'outline',
    ];

    /** Upper bound on published outline entries, so a huge course cannot bloat the response. */
    private const MAX_OUTLINE = 200;

    public const EXAM_PREP_KEYS = self::COURSE_KEYS;

    public static function mapCourse(Course $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->course_title,
            'subtitle' => $course->course_subtitle,
            'description' => $course->course_description,
            'image_url' => self::storageUrl($course->course_image),
            'level' => $course->course_level,
            'category' => $course->course_category,
            'language' => $course->course_language,
            'total_texts' => self::count($course->course_total_texts),
            'outline' => self::outline($course->course_json_content),
        ];
    }

    public static function mapExamPrep(ExamPrep $examPrep): array
    {
        return [
            'id' => $examPrep->id,
            'title' => $examPrep->exam_prep_title,
            'subtitle' => $examPrep->exam_prep_subtitle,
            'description' => $examPrep->exam_prep_description,
            'image_url' => self::storageUrl($examPrep->exam_prep_image),
            'level' => $examPrep->exam_prep_level,
            'category' => $examPrep->exam_prep_category,
            'language' => $examPrep->exam_prep_language,
            'total_texts' => self::count($examPrep->exam_prep_total_texts),
            'outline' => self::outline($examPrep->exam_prep_json_content),
        ];
    }

    /**
     * Publish the syllabus — lesson titles only — from the stored lesson JSON.
     *
     * This is the one place the demo touches `*_json_content`, and it must only ever
     * emit title strings. The same blob holds lesson bodies, questions and answers;
     * none of that may leave the server. Titles are catalogue information, the way a
     * table of contents is, and are what makes the demo worth browsing.
     *
     * Shape follows CourseView.vue:820 — an array of sections, where a section either
     * carries an activities[] list or is itself one entry. Anything unrecognised
     * yields an empty outline rather than a guess.
     */
    private static function outline($json): array
    {
        if (! is_string($json) || trim($json) === '') {
            return [];
        }

        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            return [];
        }

        $sections = array_is_list($decoded) ? $decoded : [$decoded];
        $titles = [];

        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }

            if (isset($section['activities']) && is_array($section['activities'])) {
                foreach ($section['activities'] as $activity) {
                    if (is_array($activity) && ($title = self::titleOf($activity)) !== null) {
                        $titles[] = $title;
                    }
                }

                continue;
            }

            if (($title = self::titleOf($section)) !== null) {
                $titles[] = $title;
            }
        }

        return array_slice($titles, 0, self::MAX_OUTLINE);
    }

    /**
     * The only keys ever read out of a lesson node. Never body, content or questions.
     */
    private static function titleOf(array $node): ?string
    {
        foreach (['title', 'section'] as $key) {
            if (isset($node[$key]) && is_string($node[$key]) && trim($node[$key]) !== '') {
                return trim($node[$key]);
            }
        }

        return null;
    }

    /**
     * A published count is only worth showing when it is a real positive number.
     * Null keeps the page from claiming a size the record does not actually state.
     */
    private static function count($value): ?int
    {
        return is_numeric($value) && (int) $value > 0 ? (int) $value : null;
    }

    /**
     * Resolve catalogue art to a browser-usable URL.
     *
     * Records do not agree on a single format: uploads store a relative path
     * (`courses/images/x.jpg`, AdminController@329), while other records hold a full URL
     * or an already-rooted `/storage/` path — both cases the admin and exam prep screens
     * already branch on. Prefixing blindly turns those into `/storage/https://…`.
     *
     * A record with no image of its own returns null; the demo pages then render their
     * own placeholder. The banner is deliberately not substituted — it is wide header
     * art, not card art.
     */
    private static function storageUrl(?string $path): ?string
    {
        $path = is_string($path) ? rtrim(trim($path), '/') : '';

        if ($path === '') {
            return null;
        }

        // Absolute URL or protocol-relative: already addressable, leave it alone.
        if (preg_match('#^(https?:)?//#i', $path) === 1) {
            return $path;
        }

        $path = ltrim($path, '/');

        return str_starts_with(strtolower($path), 'storage/')
            ? '/' . $path
            : '/storage/' . $path;
    }
}
