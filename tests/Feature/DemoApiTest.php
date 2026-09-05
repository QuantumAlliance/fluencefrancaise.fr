<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\ExamPrep;
use App\Support\DemoCatalogMapper;
use Tests\TestCase;

class DemoApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // This repository has no database/ directory — no migrations, factories or seeders —
        // and phpunit.xml has the sqlite test connection commented out. These tests describe
        // the intended HTTP contract and will run once schema exists in the repo.
        // To enable: add migrations, uncomment DB_CONNECTION/DB_DATABASE in phpunit.xml,
        // then delete this markTestSkipped call.
        $this->markTestSkipped('No test database: repository has no migrations. See plan Task 2.');
    }

    public function test_courses_endpoint_is_reachable_without_authentication(): void
    {
        $this->getJson('/api/demo/courses')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_courses_endpoint_returns_only_whitelisted_keys(): void
    {
        $response = $this->getJson('/api/demo/courses')->assertOk();

        foreach ($response->json('data.data') as $course) {
            $this->assertSame(DemoCatalogMapper::COURSE_KEYS, array_keys($course));
        }
    }

    public function test_courses_endpoint_excludes_inactive_records(): void
    {
        $ids = collect($this->getJson('/api/demo/courses')->json('data.data'))->pluck('id');

        $inactiveIds = Course::where('course_is_active', false)->pluck('id');

        $this->assertEmpty($ids->intersect($inactiveIds));
    }

    public function test_course_detail_endpoint_is_reachable_without_authentication(): void
    {
        $id = Course::where('course_is_active', true)->value('id');

        $this->getJson("/api/demo/courses/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_course_detail_endpoint_returns_only_whitelisted_keys(): void
    {
        $id = Course::where('course_is_active', true)->value('id');

        $response = $this->getJson("/api/demo/courses/{$id}")->assertOk();

        $this->assertSame(DemoCatalogMapper::COURSE_KEYS, array_keys($response->json('data')));
    }

    public function test_course_detail_endpoint_404s_for_an_inactive_course(): void
    {
        $id = Course::where('course_is_active', false)->value('id');

        $this->getJson("/api/demo/courses/{$id}")
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_course_detail_endpoint_404s_for_an_unknown_id(): void
    {
        $this->getJson('/api/demo/courses/99999999')->assertNotFound();
    }

    public function test_exam_preps_endpoint_is_reachable_without_authentication(): void
    {
        $this->getJson('/api/demo/exam-preps')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_exam_preps_endpoint_returns_only_whitelisted_keys(): void
    {
        $response = $this->getJson('/api/demo/exam-preps')->assertOk();

        foreach ($response->json('data.data') as $examPrep) {
            $this->assertSame(DemoCatalogMapper::EXAM_PREP_KEYS, array_keys($examPrep));
        }
    }

    public function test_exam_prep_detail_endpoint_is_reachable_without_authentication(): void
    {
        $id = ExamPrep::where('exam_prep_is_active', true)->value('id');

        $this->getJson("/api/demo/exam-preps/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_exam_prep_detail_endpoint_returns_only_whitelisted_keys(): void
    {
        $id = ExamPrep::where('exam_prep_is_active', true)->value('id');

        $response = $this->getJson("/api/demo/exam-preps/{$id}")->assertOk();

        $this->assertSame(DemoCatalogMapper::EXAM_PREP_KEYS, array_keys($response->json('data')));
    }

    public function test_exam_prep_detail_endpoint_404s_for_an_inactive_exam_prep(): void
    {
        $id = ExamPrep::where('exam_prep_is_active', false)->value('id');

        $this->getJson("/api/demo/exam-preps/{$id}")
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_exam_prep_detail_endpoint_404s_for_an_unknown_id(): void
    {
        $this->getJson('/api/demo/exam-preps/99999999')->assertNotFound();
    }

    public function test_demo_endpoints_are_rate_limited(): void
    {
        foreach (range(1, 30) as $ignored) {
            $this->getJson('/api/demo/courses')->assertOk();
        }

        $this->getJson('/api/demo/courses')->assertStatus(429);
    }
}
