<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\ExamPrep;
use App\Support\DemoCatalogMapper;
use Tests\TestCase;

class DemoCatalogMapperTest extends TestCase
{
    private function makeCourse(): Course
    {
        $course = new Course([
            'course_title' => 'French A1 Foundations',
            'course_subtitle' => 'Start here',
            'course_description' => 'An introduction to French.',
            'course_category' => 'grammar',
            'course_language' => 'French',
            'course_level' => 'Beginner',
            'course_json_content' => '{"secret":"TOP_SECRET_LESSON_BODY"}',
            'course_total_texts' => 12,
            'course_image' => 'courses/a1.png',
            'course_is_active' => true,
            'custom_url' => 'https://internal.example.com/private',
            'custom_url_target' => '_blank',
            'display_order' => 3,
        ]);
        $course->id = 42;

        return $course;
    }

    public function test_map_course_returns_exactly_the_whitelisted_keys(): void
    {
        $mapped = DemoCatalogMapper::mapCourse($this->makeCourse());

        $this->assertSame(DemoCatalogMapper::COURSE_KEYS, array_keys($mapped));
    }

    public function test_map_course_copies_the_public_values(): void
    {
        $mapped = DemoCatalogMapper::mapCourse($this->makeCourse());

        $this->assertSame(42, $mapped['id']);
        $this->assertSame('French A1 Foundations', $mapped['title']);
        $this->assertSame('Start here', $mapped['subtitle']);
        $this->assertSame('An introduction to French.', $mapped['description']);
        $this->assertSame('Beginner', $mapped['level']);
        $this->assertSame('grammar', $mapped['category']);
        $this->assertSame('French', $mapped['language']);
        $this->assertSame(12, $mapped['total_texts']);
    }

    public function test_map_course_nulls_a_missing_or_zero_text_count(): void
    {
        // The detail page hides the structure block on null, rather than claiming a size
        // the record never stated.
        $course = $this->makeCourse();
        $course->course_total_texts = 0;
        $this->assertNull(DemoCatalogMapper::mapCourse($course)['total_texts']);

        $course->course_total_texts = null;
        $this->assertNull(DemoCatalogMapper::mapCourse($course)['total_texts']);
    }

    public function test_map_course_never_leaks_private_fields(): void
    {
        $encoded = json_encode(DemoCatalogMapper::mapCourse($this->makeCourse()));

        $this->assertStringNotContainsString('TOP_SECRET_LESSON_BODY', $encoded);
        $this->assertStringNotContainsString('internal.example.com', $encoded);
    }

    public function test_map_course_builds_a_storage_image_url(): void
    {
        $mapped = DemoCatalogMapper::mapCourse($this->makeCourse());

        $this->assertSame('/storage/courses/a1.png', $mapped['image_url']);
    }

    public function test_map_course_returns_null_image_when_absent(): void
    {
        $course = $this->makeCourse();
        $course->course_image = null;

        $this->assertNull(DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_strips_trailing_slashes_from_image_path(): void
    {
        $course = $this->makeCourse();
        $course->course_image = 'courses/a1.png//';

        $this->assertSame('/storage/courses/a1.png', DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_passes_an_absolute_image_url_through_unchanged(): void
    {
        // admin/Courses.vue:607 branches on startsWith('http'), so records can hold a full URL.
        $course = $this->makeCourse();
        $course->course_image = 'https://cdn.example.com/course-art/a1.png';

        $this->assertSame(
            'https://cdn.example.com/course-art/a1.png',
            DemoCatalogMapper::mapCourse($course)['image_url']
        );
    }

    public function test_map_course_does_not_double_prefix_an_already_rooted_storage_path(): void
    {
        $course = $this->makeCourse();
        $course->course_image = '/storage/courses/a1.png';

        $this->assertSame('/storage/courses/a1.png', DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_roots_a_bare_storage_prefixed_path(): void
    {
        $course = $this->makeCourse();
        $course->course_image = 'storage/courses/a1.png';

        $this->assertSame('/storage/courses/a1.png', DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_does_not_substitute_the_banner_for_a_missing_image(): void
    {
        // A course with no image of its own stays imageless, so the demo page renders
        // its placeholder. The banner is wide header art, not card art.
        $course = $this->makeCourse();
        $course->course_image = null;
        $course->course_banner = 'courses/banners/a1-wide.jpg';

        $this->assertNull(DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_ignores_the_banner_when_an_image_is_set(): void
    {
        $course = $this->makeCourse();
        $course->course_banner = 'courses/banners/a1-wide.jpg';

        $this->assertSame('/storage/courses/a1.png', DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_ignores_a_whitespace_only_image(): void
    {
        $course = $this->makeCourse();
        $course->course_image = '   ';
        $course->course_banner = null;

        $this->assertNull(DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_exam_prep_does_not_substitute_the_banner_for_a_missing_image(): void
    {
        $examPrep = new ExamPrep([
            'exam_prep_title' => 'TCF Oral',
            'exam_prep_image' => null,
            'exam_prep_banner' => 'exam-preps/banners/tcf-wide.jpg',
        ]);

        $this->assertNull(DemoCatalogMapper::mapExamPrep($examPrep)['image_url']);
    }

    /**
     * Mirrors the real shape parsed by CourseView.vue:820 — an array of sections, some
     * carrying an activities[] list, each activity holding questions and body text.
     */
    private function realisticContent(): string
    {
        return json_encode([
            [
                'category' => 'Reading',
                'difficulty' => 'A1',
                'activities' => [
                    [
                        'title' => 'Les salutations',
                        'body' => 'SECRET_BODY_ONE',
                        'questions' => [
                            ['prompt' => 'SECRET_QUESTION_ONE', 'answer' => 'SECRET_ANSWER_ONE'],
                        ],
                    ],
                    [
                        'title' => 'Les nombres',
                        'content' => 'SECRET_BODY_TWO',
                        'questions' => [['prompt' => 'SECRET_QUESTION_TWO']],
                    ],
                ],
            ],
            [
                'section' => 'Grammaire de base',
                'text' => 'SECRET_BODY_THREE',
                'questions' => [['prompt' => 'SECRET_QUESTION_THREE']],
            ],
        ]);
    }

    public function test_map_course_publishes_the_lesson_titles_as_an_outline(): void
    {
        $course = $this->makeCourse();
        $course->course_json_content = $this->realisticContent();

        $this->assertSame(
            ['Les salutations', 'Les nombres', 'Grammaire de base'],
            DemoCatalogMapper::mapCourse($course)['outline']
        );
    }

    public function test_map_course_outline_leaks_no_lesson_body_or_questions(): void
    {
        $course = $this->makeCourse();
        $course->course_json_content = $this->realisticContent();

        $encoded = json_encode(DemoCatalogMapper::mapCourse($course));

        foreach ([
            'SECRET_BODY_ONE', 'SECRET_BODY_TWO', 'SECRET_BODY_THREE',
            'SECRET_QUESTION_ONE', 'SECRET_QUESTION_TWO', 'SECRET_QUESTION_THREE',
            'SECRET_ANSWER_ONE', 'questions', 'body', 'content',
        ] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $encoded);
        }
    }

    public function test_map_course_outline_is_empty_for_unusable_content(): void
    {
        $course = $this->makeCourse();

        foreach ([null, '', '   ', 'not json at all', '"a string"', '123'] as $value) {
            $course->course_json_content = $value;
            $this->assertSame([], DemoCatalogMapper::mapCourse($course)['outline']);
        }
    }

    public function test_map_course_outline_skips_untitled_entries(): void
    {
        $course = $this->makeCourse();
        $course->course_json_content = json_encode([
            ['title' => 'Real lesson'],
            ['body' => 'SECRET_BODY'],
            ['title' => '   '],
            ['title' => 'Another real lesson'],
        ]);

        $this->assertSame(
            ['Real lesson', 'Another real lesson'],
            DemoCatalogMapper::mapCourse($course)['outline']
        );
    }

    public function test_map_exam_prep_publishes_its_own_outline(): void
    {
        $examPrep = new ExamPrep([
            'exam_prep_title' => 'TEF Oral',
            'exam_prep_json_content' => json_encode([
                ['title' => 'Section A', 'body' => 'SECRET_EXAM_BODY'],
            ]),
        ]);

        $mapped = DemoCatalogMapper::mapExamPrep($examPrep);

        $this->assertSame(['Section A'], $mapped['outline']);
        $this->assertStringNotContainsString('SECRET_EXAM_BODY', json_encode($mapped));
    }

    public function test_map_exam_prep_returns_exactly_the_whitelisted_keys(): void
    {
        $examPrep = new ExamPrep([
            'exam_prep_title' => 'TEF Written',
            'exam_prep_subtitle' => 'Written module',
            'exam_prep_description' => 'Prepare for the TEF written exam.',
            'exam_prep_category' => 'written',
            'exam_prep_language' => 'French',
            'exam_prep_level' => 'B2',
            'exam_prep_json_content' => '{"secret":"TOP_SECRET_EXAM_BODY"}',
            'exam_prep_image' => 'exams/tef.png',
            'exam_prep_is_active' => true,
        ]);
        $examPrep->id = 7;

        $mapped = DemoCatalogMapper::mapExamPrep($examPrep);

        $this->assertSame(DemoCatalogMapper::EXAM_PREP_KEYS, array_keys($mapped));
        $this->assertSame('TEF Written', $mapped['title']);
        $this->assertSame('/storage/exams/tef.png', $mapped['image_url']);
        $this->assertStringNotContainsString('TOP_SECRET_EXAM_BODY', json_encode($mapped));
    }
}
