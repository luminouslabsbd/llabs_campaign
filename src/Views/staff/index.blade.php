@extends('staff.layouts.default')

@section('page_title', trans('common.staff_member') . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<section class="">
    {{-- <div class="py-8 px-4 mx-auto sm:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">{{ trans('common.welcome_user', ['user' => trans('common.staff_member')]) }} {{ Arr::random(['😎', '🤠', '😊', '😃', '🙃', '🤩', '😉']) }}</h2>
            <p class="font-light text-gray-500 dark:text-gray-400 sm:text-xl">{!! trans('common.staffDashboardBlocksTitle') !!}</p>
        </div>

        <div class="space-y-8 ll-dashboard-grid md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-8 xl:gap-8 md:space-y-0">
            @foreach($dashboardBlocks as $block)
            <a href="{{ $block['link'] }}" class="group block p-6 bg-white rounded shadow dark:bg-gray-800 @if(isset($block['class'])) {{ $block['class'] }} @endif">
                <div class="ll-dashboard-icon-wrapper flex justify-center items-center mb-4 w-10 h-10 rounded bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                    <x-ui.icon :icon="$block['icon']" class="w-5 h-5 text-primary-600 lg:w-6 lg:h-6 dark:text-primary-300" />
                </div>
                <h3 class="mb-2 text-xl font-bold dark:text-white group-hover:underline">{!! $block['title'] !!}</h3>
                <p class="font-light text-gray-500 dark:text-gray-400">{!! $block['desc'] !!}</p>
            </a>
            @endforeach
        </div>
    </div> --}}

    <div class="px-4 lg:px-6 py-4 lg:py-6 mx-auto">
        <div class="ll-dashboard-info-card-contaienr space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-7 xl:gap-7 md:space-y-0">
            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Total Members</h3>
                <h1 class="font-extrabold text-3xl">{{ $totalMember }}</h1>
            </div>
        </div>
    </div>
</section>
@stop
