@extends(backpack_view('blank'))

{{--@php--}}
{{--  $widgets['before_content'][] = [--}}
{{--      'type'        => 'jumbotron',--}}
{{--      'heading'     => trans('backpack::base.welcome'),--}}
{{--      'content'     => trans('backpack::base.use_sidebar'),--}}
{{--      'button_link' => backpack_url('logout'),--}}
{{--      'button_text' => trans('backpack::base.logout'),--}}
{{--  ];--}}
{{--@endphp--}}

@section('content')
  @php

    Widget::add( [
      'type' => 'div',
      'class' => 'row',
      'content' => [
        [
          'type' => 'chart',
          'wrapperClass' => 'col-md-12',
          // 'class' => 'col-md-6',
          'controller' => \App\Http\Controllers\Admin\Charts\WeeklyUsersChartController::class,
          'content' => [
            'header' => 'Новые посетители за последние 30 дней', // optional
          ]
        ],
      ]
    ]);
  @endphp
@endsection