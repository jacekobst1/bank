<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private function insert($data) {
        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->pesel = $data['pesel'];
        $user->address = $data['address'];
        $user->zip_code = $data['zip_code'];
        $user->city = $data['city'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insert([
           'first_name' => 'Jacek',
           'last_name' => 'Obst',
           'pesel' => '12345678901',
           'address' => 'ul. Kwietna 12',
           'zip_code' => '61111',
           'city' => 'Poznań',
           'email' => 'jacekobst1@gmail.com',
           'password' => 'zaq1@WSX'
        ]);
    }
}
