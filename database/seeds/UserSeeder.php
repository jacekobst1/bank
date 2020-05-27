<?php

use App\Models\User;
use App\Models\Bill;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private function insert($data, $roles) {
        $user = User::where('email', 'like', $data['email'])->first() ?? new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->pesel = $data['pesel'];
        $user->address = $data['address'];
        $user->zip_code = $data['zip_code'];
        $user->city = $data['city'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();
        $user->syncRoles($roles);

        if ($user->first_name === 'user') {
            $bill = new Bill();
            $bill->number = randomNumber(26);
            $bill->save();
            $user->bills()->attach($bill);

            $bill = new Bill();
            $bill->number = randomNumber(26);
            $bill->save();
            $user->bills()->attach($bill);
        }

        return $user;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insert(
            [
               'first_name' => 'Jacek',
               'last_name' => 'Obst',
               'pesel' => '98010198765',
               'address' => 'ul. Powstańców Wielkopolskich 1',
               'zip_code' => '61895',
               'city' => 'Poznań',
               'email' => 'jacekobst1@gmail.com',
               'password' => 'zaq1@WSX'
            ],
            ['admin']
        );
        $this->insert(
            [
                'first_name' => 'Mateusz',
                'last_name' => 'Ponicki',
                'pesel' => '97010198765',
                'address' => 'ul. Powstańców Wielkopolskich 1',
                'zip_code' => '61895',
                'city' => 'Poznań',
                'email' => 'matisfable@gmail.com',
                'password' => 'zaq1@WSX'
            ],
            ['admin']
        );
        $this->insert(
            [
                'first_name' => 'Wojciech',
                'last_name' => 'Sołtysiak',
                'pesel' => '97010198765',
                'address' => 'ul. Powstańców Wielkopolskich 1',
                'zip_code' => '61895',
                'city' => 'Poznań',
                'email' => 'woj.soltysiak@gmail.com',
                'password' => 'zaq1@WSX'
            ],
            ['admin']
        );
        $this->insert(
            [
                'first_name' => 'user',
                'last_name' => 'user',
                'pesel' => '97010198765',
                'address' => 'ul. Powstańców Wielkopolskich 1',
                'zip_code' => '61895',
                'city' => 'Poznań',
                'email' => 'user@gmail.com',
                'password' => 'zaq1@WSX'
            ],
            ['user']
        );
    }
}
