<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\EventAgenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_scan_creates_single_attendance_record(): void
    {
        $user = User::factory()->create();

        $agenda = EventAgenda::query()->create([
            'name' => 'Latihan Rutin',
            'location' => 'Sekretariat',
            'description' => 'Latihan mingguan',
            'starts_at' => now()->addDay(),
            'status' => 'published',
        ]);

        $this->actingAs($user)
            ->get(route('attendance.scan', ['eventAgenda' => $agenda->slug, 'token' => $agenda->qr_token]))
            ->assertRedirect(route('agenda.index'));

        $this->assertDatabaseCount('attendances', 1);

        $this->actingAs($user)
            ->get(route('attendance.scan', ['eventAgenda' => $agenda->slug, 'token' => $agenda->qr_token]))
            ->assertRedirect(route('agenda.index'));

        $this->assertDatabaseCount('attendances', 1);
        $this->assertTrue(Attendance::query()->where('user_id', $user->id)->where('event_agenda_id', $agenda->id)->exists());
    }
}