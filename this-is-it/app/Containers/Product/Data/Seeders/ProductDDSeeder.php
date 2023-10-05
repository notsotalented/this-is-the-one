<?php

namespace App\Containers\Product\Data\Seeders;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Parents\Seeders\Seeder;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Storage;
use DB;

class ProductDDSeeder extends Seeder
{
  protected $users = [
    'ijn' => [
      'name' => 'Imperial Japanese Navy',
      'email' => 'ijn@gmail.com',
      'avatar' => '',
    ]
  ];

  protected $dataSet = [
    'tachibana' => [
      'name' => 'Tachibana',
      'description' => 'Tachibana (橘) was a Sakura-class destroyer of the Imperial Japanese Navy. Sister ship Sakura at Sasebo, 1918.',
      'brand' => 'Sasebo',
      'quantity' => '1',
      'price' => '999',
      'userId' => '2',
      'image' => ['Ship_PJSD001_Tachibana_1912.png', 'Ship_PJSD014_Tachibana_1912_Asus.png']
    ],
    'atago' => [
      'name' => 'Atago',
      'description' => 'Atago (愛宕) was the second vessel in the Takao-class heavy cruisers, active in World War II with the Imperial Japanese Navy (IJN).',
      'brand' => 'Kure',
      'quantity' => '1',
      'price' => '999',
      'userId' => '2',
      'image' => ['Ship_PJSC038_Atago_1944.png', 'Ship_PJSC598_Black_Atago.png', 'Ship_PJSC708_ARP_Takao.png']
    ],
    'takao' => [
      'name' => 'Maya',
      'description' => 'Maya (摩耶) was one of four Takao-class heavy cruisers, active in World War II with the Imperial Japanese Navy (IJN).',
      'brand' => 'Kure',
      'quantity' => '1',
      'price' => '999',
      'userId' => '2',
      'image' => ['Ship_PJSC517_Maya.png', 'Ship_PJSC718_ARP_Maya.png']
    ],
    'yamakaze' => [
      'name' => 'Yamakaze',
      'description' => 'Yamakaze (山風, "Mountain Wind") was an Umikaze-class destroyer of the Imperial Japanese Navy.',
      'brand' => 'Mitsubishi',
      'quantity' => '1',
      'price' => '999',
      'userId' => '2',
      'image' => ['Ship_PJSD002_Umikaze_1925.png']
    ],
    'mutsuki' => [
      'name' => 'Mutsuki',
      'description' => 'The Japanese destroyer Mutsuki (睦月, "January") was the name ship of her class of twelve destroyers built for the Imperial Japanese Navy (IJN) during the 1920s.',
      'brand' => 'Mitsubishi',
      'quantity' => '1',
      'price' => '999',
      'userId' => '2',
      'image' => ['Ship_PJSD105_Mutsuki.png']
    ],
      'kamikaze' => [
        'name' => 'Kamikaze',
        'description' => 'The Japanese destroyer Kamikaze (神風, "Divine Wind" or "Spirit Wind") was the lead ship of nine Kamikaze-class destroyers built for the Imperial Japanese Navy (IJN) during the 1920s.',
        'brand' => 'Mitsubishi',
        'quantity' => '1',
        'price' => '999',
        'userId' => '2',
        'image' => ['Ship_PJSD017_Kamikaze_1930.png', 'Ship_PJSD025_True_Kamikaze.png', 'Ship_PJSD026_Camo_Kamikaze.png']
      ],
  ];

  public function run()
  {
    //Seed users
    $inputBig = $this->users;
    foreach ($inputBig as $key => $input) {
      //Create avatar

      //Insert to user table
      DB::table('users')->insert([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => bcrypt('12345'),
        'confirmed' => 1,
      ]);
    }


    //Seed products
    $inputBig = $this->dataSet;

    foreach ($inputBig as $key => $input) {
      $photo = new Collection;

      foreach ($input['image'] as $key => $image) {
        $imageOriginal = new UploadedFile(storage_path('app/public/seeder/ijn-dd/' . $image), $image);
        $canvas = Apiato::call('Product@ImageProcessingTask', [$imageOriginal]);

        //Get canvas extension
        $extension = '.' . substr($canvas->mime, strpos($canvas->mime, '/') + 1);
        //Name = time() + extension
        $time = time();
        $name = $input['userId'] . '_' . $time . '_' . $key . '.' . $extension;
        while(Storage::exists('public/uploads/product_images/' . $name)){
          $time = time();
          $name = $input['userId'] . '_' . $time . '_' . $key . '.' . $extension;
        }


        Storage::disk('public')->put('/uploads/product_images/' . $name, $canvas);
        $photo->push($name);
      }

      Apiato::call('Product@AddProductToUserAction', [
        new DataTransporter($input),
        $photo,
      ]);
    }

  }
}
