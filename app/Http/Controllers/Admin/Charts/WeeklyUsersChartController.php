<?php

  namespace App\Http\Controllers\Admin\Charts;

  use Backpack\CRUD\app\Http\Controllers\ChartController;
  use ConsoleTVs\Charts\Classes\Chartjs\Chart;
  use Illuminate\Support\Facades\Http;
  use GuzzleHttp\Client;

  /**
   * Class WeeklyUsersChartController
   * @package App\Http\Controllers\Admin\Charts
   * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
   */
  class WeeklyUsersChartController extends ChartController {
    public function setup() {

      // Получение даты
      $data = $this->getChartData();
      $time_intervals = $data->time_intervals;
      $date = [];
      foreach ($time_intervals as $interval) {
        $date[] = $interval[0];
      }

      $this->chart = new Chart();
      // MANDATORY. Set the labels for the dataset points
      $this->chart->labels($date);

      // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
      $this->chart->load(backpack_url('charts/weekly-users'));

      // OPTIONAL
      // $this->chart->minimalist(false);
      // $this->chart->displayLegend(true);
    }

    protected function getChartData() {
      $api = env('YANDEX_METRICS_API');
      $token = env('YANDEX_METRICS_TOKEN');
      $counter_id = env('YANDEX_METRICS_COUNTER_ID');
      $new_users_for_last_30_days = '/bytime?metrics=ym:s:newUsers&group=day&period=week&pretty=true&date1=30daysAgo&date2=today';
      $request_uri = $api . $new_users_for_last_30_days . '&id=' . $counter_id;

      // Запрос к API Яндекс Метрики
      $client = new Client();
      $response = $client->request(
          'get',
          $request_uri,
          ['headers' => [
              'Authorization' => 'Bearer ' . $token
          ]])
          ->getBody()
          ->getContents();

      return $response = json_decode($response);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */

    public function data() {
      $data = $this->getChartData();
      $new_clients_count = $data->data[0]->metrics[0];

      $this->chart->dataset('Новые посетители', 'line', $new_clients_count)
          ->color('rgba(66, 186, 150)')
          ->backgroundColor('rgba(179, 227, 213, 0.7)');
    }
  }