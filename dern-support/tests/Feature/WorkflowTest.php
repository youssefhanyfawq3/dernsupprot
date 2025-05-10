<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SupportRequest;
use App\Models\Technician;
use App\Models\SatisfactionRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_support_request_workflow()
    {
        // 1. Create a customer and staff user
        $customer = User::factory()->create(['is_staff' => false]);
        $staff = User::factory()->create(['is_staff' => true]);

        // 2. Create a technician
        $technician = Technician::create([
            'name' => 'John Tech',
            'email' => 'john@tech.com',
            'specialization' => 'Hardware',
            'is_available' => true
        ]);

        // 3. Customer creates a support request
        $response = $this->actingAs($customer)->post('/support-requests', [
            'title' => 'Computer not working',
            'description' => 'My computer wont start',
            'service_type' => 'Hardware',
            'priority' => 'high'
        ]);

        $this->assertDatabaseHas('support_requests', [
            'title' => 'Computer not working',
            'user_id' => $customer->id
        ]);

        $request = SupportRequest::latest()->first();

        // 4. Staff assigns technician
        $response = $this->actingAs($staff)->patch("/support-requests/{$request->id}", [
            'technician_id' => $technician->id,
            'status' => 'in_progress'
        ]);

        $this->assertDatabaseHas('support_requests', [
            'id' => $request->id,
            'technician_id' => $technician->id,
            'status' => 'in_progress'
        ]);

        // 5. Staff marks request as resolved
        $response = $this->actingAs($staff)->patch("/support-requests/{$request->id}", [
            'status' => 'resolved'
        ]);

        $this->assertDatabaseHas('support_requests', [
            'id' => $request->id,
            'status' => 'resolved'
        ]);
        $this->assertNotNull(SupportRequest::find($request->id)->resolved_at);

        // 6. Customer adds satisfaction rating
        $response = $this->actingAs($customer)->post('/satisfaction-ratings', [
            'support_request_id' => $request->id,
            'rating' => 5,
            'comment' => 'Great service!'
        ]);

        $this->assertDatabaseHas('satisfaction_ratings', [
            'support_request_id' => $request->id,
            'rating' => 5
        ]);
    }
}
