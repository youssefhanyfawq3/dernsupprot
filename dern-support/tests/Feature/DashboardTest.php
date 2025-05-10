<?php

use App\Models\User;
use App\Models\SupportRequest;
use App\Models\Technician;

test('guest cannot access dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('customer can see their support requests on dashboard', function () {
    $user = User::factory()->create(['is_staff' => false]);
    
    $request = SupportRequest::create([
        'title' => 'Test Request',
        'description' => 'Test Description',
        'status' => 'open',
        'priority' => 'medium',
        'service_type' => 'Software',
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    
    $response->assertOk()
        ->assertSee('Test Request')
        ->assertDontSee('Technician Workload'); // Should not see staff metrics
});

test('staff can see all metrics on dashboard', function () {
    $staff = User::factory()->create(['is_staff' => true]);
    
    $technician = Technician::create([
        'name' => 'Test Tech',
        'email' => 'tech@test.com',
        'specialization' => 'Software',
        'is_available' => true
    ]);

    $request = SupportRequest::create([
        'title' => 'Staff Test Request',
        'description' => 'Test Description',
        'status' => 'open',
        'priority' => 'high',
        'service_type' => 'Software',
        'user_id' => User::factory()->create()->id,
        'technician_id' => $technician->id
    ]);

    $response = $this->actingAs($staff)->get('/dashboard');
    
    $response->assertOk()
        ->assertSee('Technician Workload')
        ->assertSee('Test Tech')
        ->assertSee('Staff Test Request');
});
