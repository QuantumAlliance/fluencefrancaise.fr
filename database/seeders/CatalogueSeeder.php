<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\ClassType;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseSection;
use Illuminate\Database\Seeder;

class CatalogueSeeder extends Seeder
{
    /**
     * Class types (which double as course categories — Category maps to the same
     * class_types table), courses with one section/lesson each, and books.
     *
     * Image paths point at files that actually exist in storage/app/public, so
     * the cards render real artwork instead of broken images.
     */
    public function run(): void
    {
        $group = ClassType::updateOrCreate(
            ['class_name' => 'Group'],
            [
                'name' => 'Group',
                'homepage_title' => 'Group Classes',
                'homepage_description' => 'Small groups, live with a teacher, twice a week.',
                'description' => 'Live group classes capped at six learners.',
                'features' => ['2 live classes / week', 'Max 6 learners', 'Homework feedback'],
                'is_popular' => true,
                'price' => 89.00,
                'currency' => 'CAD',
                'duration' => 'monthly',
                'is_active' => true,
                'display_order' => 1,
                'batch_schedule' => 'Sat & Sun 7AM-9AM PST',
            ]
        );

        $oneToOne = ClassType::updateOrCreate(
            ['class_name' => 'One-on-One'],
            [
                'name' => 'One-on-One',
                'homepage_title' => 'Private Lessons',
                'homepage_description' => 'Fully personalised, scheduled around you.',
                'description' => 'Private lessons with a dedicated tutor.',
                'features' => ['Flexible scheduling', 'Personalised syllabus', 'Exam coaching'],
                'is_popular' => false,
                'price' => 249.00,
                'currency' => 'CAD',
                'duration' => 'monthly',
                'is_active' => true,
                'display_order' => 2,
            ]
        );

        $courses = [
            [
                'course_title' => 'A1 Beginner French',
                'course_subtitle' => 'Start from zero and hold your first conversations',
                'course_description' => 'Greetings, articles, present tense, and everyday vocabulary. Builds the base for A2.',
                'course_category' => 'Beginner',
                'category_id' => $group->id,
                'course_level' => 'A1',
                'course_image' => 'courses/images/1764021681_6924d5b1276d5.jpg',
                'course_banner' => 'courses/banners/1764022409_6924d889736bc.jpg',
                'display_order' => 1,
                'sections' => ['Greetings & Introductions', 'Articles & Gender', 'Present Tense'],
            ],
            [
                'course_title' => 'A2 Elementary French',
                'course_subtitle' => 'Talk about the past and describe your routine',
                'course_description' => 'Passé composé, imparfait, futur simple, and partitive articles.',
                'course_category' => 'Elementary',
                'category_id' => $group->id,
                'course_level' => 'A2',
                'course_image' => 'courses/images/1765532887_693be4d777d99.png',
                'course_banner' => 'courses/banners/1764314505_69294d898074e.jpg',
                'display_order' => 2,
                'sections' => ['Passé Composé', 'Imparfait', 'Futur Simple'],
            ],
            [
                'course_title' => 'B1 Intermediate French',
                'course_subtitle' => 'Build fluency and prepare for DELF B1',
                'course_description' => 'Subjunctive, complex connectors, and structured argumentation.',
                'course_category' => 'Intermediate',
                'category_id' => $oneToOne->id,
                'course_level' => 'B1',
                'course_image' => 'courses/images/1766008584_69432708ac4e4.png',
                'course_banner' => 'courses/banners/1766008607_6943271fe115a.png',
                'display_order' => 3,
                'sections' => ['Subjunctive Mood', 'Connectors', 'DELF B1 Practice'],
            ],
        ];

        foreach ($courses as $data) {
            $sections = $data['sections'];
            unset($data['sections']);

            $course = Course::updateOrCreate(
                ['course_title' => $data['course_title']],
                $data + [
                    'course_language' => 'French',
                    'course_is_active' => true,
                    'course_total_texts' => count($sections),
                ]
            );

            foreach ($sections as $i => $name) {
                $section = CourseSection::updateOrCreate(
                    ['course_id' => $course->id, 'name' => $name],
                    ['description' => $name . ' — core material and exercises.', 'order' => $i + 1]
                );

                CourseLesson::updateOrCreate(
                    ['course_section_id' => $section->id, 'title' => 'Introduction to ' . $name],
                    [
                        'content' => '<p>Placeholder lesson content for <strong>' . e($name) . '</strong>.</p>',
                        'order' => 1,
                    ]
                );
            }
        }

        $books = [
            ['title' => 'French Grammar Essentials', 'cover_image' => 'books/images/1768071835_6962a29be9dd8.jpg'],
            ['title' => 'DELF B1 Practice Papers', 'cover_image' => 'books/images/1768071973_6962a32564e13.jpeg'],
            ['title' => 'Everyday French Vocabulary', 'cover_image' => 'books/images/1768072021_6962a355dcbff.jpeg'],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['title' => $book['title']],
                ['cover_image' => $book['cover_image'], 'is_active' => true]
            );
        }
    }
}
