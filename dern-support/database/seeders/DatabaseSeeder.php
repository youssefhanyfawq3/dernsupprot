<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Technician;
use App\Models\SupportRequest;
use App\Models\SatisfactionRating;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@dernsupport.com',
            'password' => Hash::make('password'),
            'is_staff' => true,
        ]);

        // Create regular user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create technicians
        $technicians = [
            ['name' => 'Alice Smith', 'email' => 'alice@dernsupport.com', 'specialization' => 'Hardware'],
            ['name' => 'Bob Johnson', 'email' => 'bob@dernsupport.com', 'specialization' => 'Software'],
            ['name' => 'Carol Williams', 'email' => 'carol@dernsupport.com', 'specialization' => 'Network'],
        ];

        foreach ($technicians as $techData) {
            Technician::create($techData);
        }

        // Create support requests
        $requests = [
            [
                'title' => 'Computer not starting',
                'description' => 'My computer wont turn on after power outage',
                'priority' => 'high',
                'service_type' => 'Hardware',
                'status' => 'resolved',
                'user_id' => $user->id,
                'technician_id' => 1,
                'resolved_at' => now()->subDays(2),
            ],
            [
                'title' => 'Email configuration issue',
                'description' => 'Cannot send or receive emails',
                'priority' => 'medium',
                'service_type' => 'Software',
                'status' => 'in_progress',
                'user_id' => $user->id,
                'technician_id' => 2,
            ],
            [
                'title' => 'Printer not connecting',
                'description' => 'Network printer is not responding',
                'priority' => 'low',
                'service_type' => 'Network',
                'status' => 'open',
                'user_id' => $user->id,
                'technician_id' => 3,
            ],
        ];

        foreach ($requests as $requestData) {
            $request = SupportRequest::create($requestData);
            
            // Add comments
            Comment::create([
                'support_request_id' => $request->id,
                'user_id' => $user->id,
                'content' => 'Initial request details provided.',
            ]);

            Comment::create([
                'support_request_id' => $request->id,
                'user_id' => $admin->id,
                'content' => 'Technician has been assigned.',
            ]);

            // Add satisfaction rating for resolved requests
            if ($request->status === 'resolved') {
                SatisfactionRating::create([
                    'support_request_id' => $request->id,
                    'rating' => 4,
                    'comment' => 'Great service, quick resolution',
                ]);
            }
        }
    }
}
