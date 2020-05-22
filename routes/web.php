<?php

  use GuzzleHttp\Client;
  use Illuminate\Support\Facades\Http;
  use Illuminate\Support\Facades\Route;

  /*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
  */

  Route::get('/', function () {
    return view('welcome');
  });

  Route::get('/ym', function () {
    $token = env('YANDEX_METRICS_TOKEN');
    $api = env('YANDEX_METRICS_API');
    $counter_id = env('YANDEX_METRICS_COUNTER_ID');

//    Не работает!
//    $response = Http::withToken($token)->get('https://api-metrika.yandex.net/stat/v1/data/bytime?metrics=ym:s:newUsers&id=51161417&group=day&period=week');

    $client = new Client();
    $req = $client->request('get', $api . '/bytime?metrics=ym:s:newUsers&group=day&period=week&pretty=true&id=' . $counter_id,
        ['headers' => [
            'Authorization' => 'Bearer ' . $token
        ]])
        ->getBody()
        ->getContents();
    $req = json_decode($req, false);

    $time_intervals = $req->time_intervals;
    $date = [];

    foreach ($time_intervals as $interval) {
      $date[] = $interval[0];
    }

    print_r($date);
  });


