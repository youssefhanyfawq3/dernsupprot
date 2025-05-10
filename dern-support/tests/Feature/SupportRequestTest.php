<?php

use App\Models\User;
use App\Models\SupportRequest;

test('staff can view all support requests', function () {
    $staff = User::factory()->create(['is_staff' => true]);
    $regularUser = User::factory()->create(['is_staff' => false]);
    
    $supportRequest = SupportRequest::create([
        'user_id' => $regularUser->id,
        'title' => 'Test Request',
        'description' => 'Test Description',
        'status' => 'pending'
    ]);

    $response = $this->actingAs($staff)->get('/support-requests');
    
    $response->assertOk();
    $response->assertViewHas('supportRequests');
    $response->assertSee('Test Request');
});

test('regular users can only view their own support requests', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $request1 = SupportRequest::create([
        'user_id' => $user1->id,
        'title' => 'User 1 Request',
        'description' => 'Test Description',
        'status' => 'pending'
    ]);

    $request2 = SupportRequest::create([
        'user_id' => $user2->id,
        'title' => 'User 2 Request',
        'description' => 'Test Description',
        'status' => 'pending'
    ]);

    $response = $this->actingAs($user1)->get('/support-requests');
    
    $response->assertOk();
    $response->assertSee('User 1 Request');
    $response->assertDontSee('User 2 Request');
});

test('only staff can update support request status', function () {
    $staff = User::factory()->create(['is_staff' => true]);
    $user = User::factory()->create();
    
    $request = SupportRequest::create([
        'user_id' => $user->id,
        'title' => 'Test Request',
        'description' => 'Test Description',
        'status' => 'pending'
    ]);

    $response = $this->actingAs($user)
        ->put("/support-requests/{$request->id}", ['status' => 'completed']);
    
    $response->assertForbidden();

    $response = $this->actingAs($staff)
        ->put("/support-requests/{$request->id}", ['status' => 'completed']);
    
    $response->assertRedirect();
    $this->assertEquals('completed', $request->fresh()->status);
});
