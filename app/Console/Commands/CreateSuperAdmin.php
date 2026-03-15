<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'nativemeta:admin {name} {email} {password?}';
    protected $description = 'Create a super admin user';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password') ?? 'password';

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists.");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'role' => 'admin',
            'is_super_admin' => true,
        ]);

        $this->info("Super Admin '{$name}' created successfully.");
        $this->info("Login: {$email}");
        $this->info("Password: " . ($this->argument('password') ? '********' : 'password'));

        return 0;
    }
}
