<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    /**
     * Uji Administrator dapat melihat halaman manajemen penugasan.
     */
    public function test_admin_can_view_tasks_page(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/tasks');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Penugasan');
    }

    /**
     * Uji Administrator dapat membuat tugas pengecekan baru.
     */
    public function test_admin_can_create_new_task(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/tasks', [
            'title' => 'Pengecekan Kebersihan Toilet Lt. 3',
            'description' => 'Pastikan sabun dan tisu terisi, lantai kering.',
            'location' => 'Toilet Lt. 3',
            'interval_type' => 'harian',
            'target_time' => 'Setiap 2 Jam Sekali',
        ]);

        $response->assertRedirect('/admin/tasks');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Pengecekan Kebersihan Toilet Lt. 3',
            'location' => 'Toilet Lt. 3',
            'interval_type' => 'harian',
        ]);
    }

    /**
     * Uji filter interval tugas (harian, mingguan, bulanan) di panel Admin.
     */
    public function test_admin_can_filter_tasks_by_interval(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/tasks?interval=harian');
        $response->assertStatus(200);

        $responseMingguan = $this->actingAs($admin)->get('/admin/tasks?interval=mingguan');
        $responseMingguan->assertStatus(200);
    }

    /**
     * Uji Cleaning Service dapat mengakses halaman jadwal & daftar penugasan.
     */
    public function test_cleaning_service_can_view_jadwal_kerja_tasks(): void
    {
        $cs = User::where('role', 'cs')->first() ?? User::factory()->create(['role' => 'cs']);

        $response = $this->actingAs($cs)->get('/jadwal-kerja-cs');

        $response->assertStatus(200);
        $response->assertSee('Penugasan Rutin');
    }

    /**
     * Uji Cleaning Service dapat mengirimkan laporan pengerjaan dengan foto bukti.
     */
    public function test_cleaning_service_can_submit_task_proof(): void
    {
        Storage::fake('public');

        $cs = User::where('role', 'cs')->first() ?? User::factory()->create(['role' => 'cs']);
        $task = Task::first();

        $file = UploadedFile::fake()->create('bukti_toilet.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($cs)->post("/cs/tasks/{$task->id}/submit", [
            'photo_proof' => $file,
            'notes' => 'Toilet lt 1 telah dibersihkan dan sabun diisi ulang.',
        ]);

        $response->assertRedirect('/jadwal-kerja-cs');
        $this->assertDatabaseHas('task_submissions', [
            'task_id' => $task->id,
            'user_id' => $cs->id,
            'notes' => 'Toilet lt 1 telah dibersihkan dan sabun diisi ulang.',
        ]);
    }

    /**
     * Uji validasi penolakan ketika submit laporan tanpa mengunggah foto bukti.
     */
    public function test_submit_task_fails_without_photo_proof(): void
    {
        $cs = User::where('role', 'cs')->first() ?? User::factory()->create(['role' => 'cs']);
        $task = Task::first();

        $response = $this->actingAs($cs)->post("/cs/tasks/{$task->id}/submit", [
            'notes' => 'Tanpa foto bukti',
        ]);

        $response->assertSessionHasErrors('photo_proof');
    }

    /**
     * Uji Dashboard Admin menampilkan rekapitulasi progres tugas dan galeri laporan.
     */
    public function test_admin_dashboard_shows_task_progress_and_gallery(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('TUGAS SELESAI');
        $response->assertSee('Galeri Bukti Pengerjaan');
    }
}
