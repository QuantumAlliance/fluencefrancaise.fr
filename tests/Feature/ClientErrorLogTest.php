<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ClientErrorLogTest extends TestCase
{
    private function logPath(): string
    {
        return storage_path('logs/client-errors-' . date('Y-m-d') . '.log');
    }

    public function test_it_accepts_a_client_error_report(): void
    {
        $this->postJson('/api/client-error-log', [
            'kind' => 'js-error',
            'message' => 'Cannot read properties of undefined',
            'source' => 'https://example.test/app.js',
            'lineno' => 42,
            'url' => 'https://example.test/student/dashboard',
        ])
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_it_writes_the_report_to_the_client_error_log(): void
    {
        $marker = 'MARKER_' . uniqid();

        $this->postJson('/api/client-error-log', [
            'kind' => 'debug',
            'message' => $marker,
        ])->assertOk();

        $this->assertTrue(File::exists($this->logPath()), 'client-errors log file was not created');
        $this->assertStringContainsString($marker, File::get($this->logPath()));
    }

    public function test_it_requires_a_message(): void
    {
        $this->postJson('/api/client-error-log', ['kind' => 'js-error'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }

    public function test_it_rejects_an_over_long_message(): void
    {
        $this->postJson('/api/client-error-log', ['message' => str_repeat('x', 5000)])
            ->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }
}
