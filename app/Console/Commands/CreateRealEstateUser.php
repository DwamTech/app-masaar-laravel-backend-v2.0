<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Services\OtpService;
use Illuminate\Http\Request;

class CreateRealEstateUser extends Command
{
    protected $signature = 'app:create-real-estate-user';

    protected $description = 'Creates a new real estate user via the API.';

    public function handle()
    {
        $this->info('Creating real estate user...');

        $controller = new RegisteredUserController(new OtpService());

        $request = new Request([
            'name' => 'abdalrhman',
            'email' => 'abdalrhman0mahmoud1@gmail.com',
            'password' => 'Abdo0101@',
            'phone' => '010' . mt_rand(10000000, 99999999),
            'user_type' => 'real_estate_office',
            'office_name' => 'Test Office',
            'office_address' => 'Test Address',
        ]);

        $response = $controller->store($request);

        $this->info('User creation response:');
        $this->info($response->getContent());
    }
}
