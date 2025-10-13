<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::firstOrCreate(
            ['username' => 'admin'], // cek berdasarkan username
            [
                'role_id' => 1,
                'name' => 'Admin Desa',
                'email' => 'admin@desa.com',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'warga1'],
            [
                'role_id' => 2,
                'name' => 'Warga Satu',
                'email' => 'warga1@desa.com',
                'password' => Hash::make('warga123'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'dea'],
            [
                'role_id' => 3,
                'name' => 'Dea Asti Anandita',
                'email' => 'dea@desa.com',
                'password' => Hash::make('dea12345'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'dimas'],
            [
                'role_id' => 3,
                'name' => 'Putra Nurhadi',
                'email' => 'dimas@desa.com',
                'password' => Hash::make('dimas123'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'rema'],
            [
                'role_id' => 3,
                'name' => 'Rema Cantika Ralita Triya',
                'email' => 'rema@desa.com',
                'password' => Hash::make('rema1234'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'bela'],
            [
                'role_id' => 3,
                'name' => 'Belani Estiningtyas',
                'email' => 'bela@desa.com',
                'password' => Hash::make('bela1234'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'nayla'],
            [
                'role_id' => 3,
                'name' => 'Nayla Desti Fitriani',
                'email' => 'nayla@desa.com',
                'password' => Hash::make('nayla123'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'sri'],
            [
                'role_id' => 3,
                'name' => 'Sri Lutfiningsih',
                'email' => 'sri@desa.com',
                'password' => Hash::make('sri12345'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'sofi'],
            [
                'role_id' => 3,
                'name' => 'Ina Dia Sofiana Putri',
                'email' => 'sofi@desa.com',
                'password' => Hash::make('sofi1234'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'disa'],
            [
                'role_id' => 3,
                'name' => 'Andisa Diandra Pramita',
                'email' => 'disa@desa.com',
                'password' => Hash::make('disa1234'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'anysa'],
            [
                'role_id' => 3,
                'name' => 'Anysa Puji Rahayu',
                'email' => 'anysa@desa.com',
                'password' => Hash::make('anysa123'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'luthfiah'],
            [
                'role_id' => 3,
                'name' => 'Luthfiah Nur Faizah',
                'email' => 'luthfiah@desa.com',
                'password' => Hash::make('luthfiah'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'fathir'],
            [
                'role_id' => 3,
                'name' => 'Mohammad Fathir Ramdhani',
                'email' => 'fathir@desa.com',
                'password' => Hash::make('fathir12'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'aghib'],
            [
                'role_id' => 3,
                'name' => 'Helmi Aghib Rizki',
                'email' => 'aghib@desa.com',
                'password' => Hash::make('aghib123'),
            ]
        );
    }
}
