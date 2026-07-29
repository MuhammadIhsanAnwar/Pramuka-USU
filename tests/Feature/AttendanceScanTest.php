<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class AttendanceScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_scan_page_and_submit_creates_attendance_record(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $agenda = EventAgenda::query()->create([
            'name' => 'Latihan Rutin',
            'location' => 'Sekretariat',
            'description' => 'Latihan mingguan',
            'starts_at' => now()->addDay(),
            'status' => 'published',
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'radius' => 500,
            'qr_token' => (string) Str::uuid(),
        ]);

        $scanUrl = URL::temporarySignedRoute(
            'attendance.scan',
            now()->addHours(6),
            [
                'eventAgenda' => $agenda->slug,
                'token' => $agenda->qr_token,
            ],
        );

        $response = $this->actingAs($user)->get($scanUrl);

        $response->assertStatus(200);
        $response->assertViewIs('presensi.scan');
        $response->assertSee('Foto Bukti');
        $response->assertSee('Lokasi Presensi');

        $submitUrl = URL::temporarySignedRoute(
            'attendance.submit',
            now()->addHours(6),
            [
                'eventAgenda' => $agenda->slug,
                'token' => $agenda->qr_token,
            ],
        );

        $postResponse = $this->actingAs($user)
            ->post($submitUrl, [
                'photo' => UploadedFile::fake()->image('presensi.jpg'),
                'latitude' => $agenda->latitude,
                'longitude' => $agenda->longitude,
                'notes' => 'Presensi ujicoba dengan foto',
            ]);

        $postResponse->assertRedirect(route('agenda.index'));
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'event_agenda_id' => $agenda->id,
            'status' => 'hadir',
            'method' => 'qr',
        ]);

        $attendance = Attendance::query()->where('user_id', $user->id)->where('event_agenda_id', $agenda->id)->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->photo_path);
        $this->assertTrue(Storage::disk('public')->exists($attendance->photo_path));
        $this->assertEquals('hadir', $attendance->status);
    }

    public function test_qr_scan_marks_late_if_started_more_than_15_minutes_ago(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $agenda = EventAgenda::query()->create([
            'name' => 'Latihan Terlambat',
            'location' => 'Sekretariat',
            'description' => 'Latihan mingguan',
            'starts_at' => now()->subMinutes(20),
            'status' => 'published',
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'radius' => 500,
            'qr_token' => (string) Str::uuid(),
        ]);

        $submitUrl = URL::temporarySignedRoute(
            'attendance.submit',
            now()->addHours(6),
            [
                'eventAgenda' => $agenda->slug,
                'token' => $agenda->qr_token,
            ],
        );

        $postResponse = $this->actingAs($user)
            ->post($submitUrl, [
                'photo' => UploadedFile::fake()->image('presensi.jpg'),
                'latitude' => $agenda->latitude,
                'longitude' => $agenda->longitude,
                'notes' => 'Presensi terlambat',
            ]);

        $postResponse->assertRedirect(route('agenda.index'));
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'event_agenda_id' => $agenda->id,
            'status' => 'terlambat',
            'method' => 'qr',
        ]);
    }
}
