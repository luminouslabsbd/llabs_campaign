@extends('admin.layouts.default')

@section('page_title', ((auth('admin')->user()->role == 1) ? trans('common.administrator') : trans('common.manager')) . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<section class="">
    {{-- <div class="py-8 px-4 mx-auto sm:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-md text-center mb-4 lg:mb-4">
        </div>

        <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">{{ trans('common.welcome_user', ['user' => (auth('admin')->user()->role == 1) ? trans('common.administrator') : trans('common.manager')]) }} {{ Arr::random(['😎', '🤠', '😊', '😃', '🙃', '🤩', '😉']) }}</h2>
            @if(auth('admin')->user()->role == 1)
            <p class="font-semibold text-gray-500 dark:text-gray-400 sm:text-xl mb-2">{!! trans('common.current_version_in_use', ['version' => '<span class="ll-dark-theme-light-blue px-2.5 py-0.5 rounded dark:bg-yellow-800 dark:text-yellow-300" style="background: #eef3ff; color: #2963FF">' . config('version.current') . '</span>']) !!} @if($hasMigrations) <a href="{{ route('admin.migrate') }}" class="text-link underline">{{ trans('common.database_update') }}</a> @else {{ trans('common.database_up_to_date') }} @endif</p>
            @endif
            <p class="font-light text-gray-500 dark:text-gray-400 sm:text-xl">{!! (auth('admin')->user()->role == 1) ? trans('common.adminDashboardBlocksTitle') : trans('common.managerDashboardBlocksTitle') !!}</p>
        </div>

        <div class="space-y-8 ll-dashboard-grid md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-8 xl:gap-8 md:space-y-0">
            @foreach($dashboardBlocks as $block)
            <a href="{{ $block['link'] }}" class="group block p-6 bg-white rounded shadow dark:bg-gray-800">
                <div class="ll-dashboard-icon-wrapper flex justify-center items-center mb-4 w-10 h-10 rounded lg:h-12 lg:w-12 dark:bg-primary-900">
                    <x-ui.icon :icon="$block['icon']" class="w-5 h-5 lg:w-6 lg:h-6 dark:text-primary-300" style="color: #2963FF"/>
                </div>
                <h3 class="mb-2 text-xl font-bold dark:text-white group-hover:underline" style="color: #2963FF">{!! $block['title'] !!}</h3>
                <p class="font-light text-gray-500 dark:text-gray-400">{!! $block['desc'] !!}</p>
            </a>
            @endforeach
        </div>
    </div> --}}

    <div class="px-4 lg:px-6 py-4 lg:py-6 mx-auto">
        <div class="ll-dashboard-info-card-contaienr space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-7 xl:gap-7 md:space-y-0">
            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Card Views</h3>
                <h1 class="font-extrabold text-3xl">{{ $cardsSums['views'] ?? 0 }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Reward Views</h3>
                <h1 class="font-extrabold text-3xl">{{ $totalRewardViews ?? 0 }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Points Issued</h3>
                <h1 class="font-extrabold text-3xl">{{ $cardsSums['number_of_points_issued'] ?? 0 }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Rewards Claimed</h3>
                <h1 class="font-extrabold text-3xl">{{ $cardsSums['number_of_rewards_redeemed'] ?? 0 }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Total Partners</h3>
                <h1 class="font-extrabold text-3xl">{{ $totalPartners }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Total Staff</h3>
                <h1 class="font-extrabold text-3xl">{{ $staffsTotal ?? 0 }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Total Members</h3>
                <h1 class="font-extrabold text-3xl">{{ $membersTotal ?? 0 }}</h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Total Cards</h3>
                <h1 class="font-extrabold text-3xl">{{ $totalCards ?? 0 }}</h1>
            </div>
        </div>

        <div class="ll-dashboard-chart-container mt-8">
            <div class="ll-dashboard-chart-heading mb-7 flex justify-between items-center">
                <h1 class="font-semibold text-2xl">Performance Over Time</h1>
                <div>
                    {{-- <button type="button" class="ll-csv-download-btn">Download CSV</button> --}}
                    <span class="ll-filter-select">Last 7 Days</span>
                </div>
            </div>
            <div class="ll-dashboard-chart-body">
                <canvas id="ll-custom-dashboard" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // get dates start
    function getDates(dayCount) {
        let previousDays = [];

        function formatDate(date) {
            const options = { month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        // Get the current date
        const currentDate = new Date();

        // get previous days
        for (let i = 0; i < dayCount; i++) {
            let day = new Date();
            day.setDate(currentDate.getDate() - i);
            const formattedDate = formatDate(day);
            previousDays.push(formattedDate);
        }

        return previousDays.reverse();
    }
    // get dates end

    // chart creation start
    function createNewChart(canvas, labels, datasetsData) {
        const ctx = document.getElementById(`${canvas}`);

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Card Views',
                    data: [datasetsData.cardsData[0].views, datasetsData.cardsData[1].views, datasetsData.cardsData[2].views, datasetsData.cardsData[3].views, datasetsData.cardsData[4].views, datasetsData.cardsData[5].views, datasetsData.cardsData[6].views],
                    borderColor: '#739072',
                    backgroundColor: '#739072',
                    tension: 0.4
                },
                {
                    label: 'Reward Views',
                    data: [datasetsData.rewardViewsData[0].count, datasetsData.rewardViewsData[1].count, datasetsData.rewardViewsData[2].count, datasetsData.rewardViewsData[3].count, datasetsData.rewardViewsData[4].count, datasetsData.rewardViewsData[5].count, datasetsData.rewardViewsData[6].count],
                    borderColor: '#FD8D14',
                    backgroundColor: '#FD8D14',
                    tension: 0.4
                },
                {
                    label: 'Points Issued',
                    data: [datasetsData.cardsData[0].points_issued, datasetsData.cardsData[1].points_issued, datasetsData.cardsData[2].points_issued, datasetsData.cardsData[3].points_issued, datasetsData.cardsData[4].points_issued, datasetsData.cardsData[5].points_issued, datasetsData.cardsData[6].points_issued],
                    borderColor: '#6C5F5B',
                    backgroundColor: '#6C5F5B',
                    tension: 0.4
                },
                {
                    label: 'Rewards Claimed',
                    data: [datasetsData.cardsData[0].rewards_redeemed, datasetsData.cardsData[1].rewards_redeemed, datasetsData.cardsData[2].rewards_redeemed, datasetsData.cardsData[3].rewards_redeemed, datasetsData.cardsData[4].rewards_redeemed, datasetsData.cardsData[5].rewards_redeemed, datasetsData.cardsData[6].rewards_redeemed],
                    borderColor: '#2986cc',
                    backgroundColor: '#2986cc',
                    tension: 0.4
                },
                {
                    label: 'Total Partners',
                    data: [datasetsData.partnersData[0].count, datasetsData.partnersData[1].count, datasetsData.partnersData[2].count, datasetsData.partnersData[3].count, datasetsData.partnersData[4].count, datasetsData.partnersData[5].count, datasetsData.partnersData[6].count],
                    borderColor: '#FF6464',
                    backgroundColor: '#FF6464',
                    tension: 0.4
                },
                {
                    label: 'Total Staff',
                    data: [datasetsData.staffData[0].count, datasetsData.staffData[1].count, datasetsData.staffData[2].count, datasetsData.staffData[3].count, datasetsData.staffData[4].count, datasetsData.staffData[5].count, datasetsData.staffData[6].count],
                    borderColor: '#D0A2F7',
                    backgroundColor: '#D0A2F7',
                    tension: 0.4
                },
                {
                    label: 'Total Members',
                    data: [datasetsData.membersData[0].count, datasetsData.membersData[1].count, datasetsData.membersData[2].count, datasetsData.membersData[3].count, datasetsData.membersData[4].count, datasetsData.membersData[5].count, datasetsData.membersData[6].count],
                    borderColor: '#d8caa9',
                    backgroundColor: '#d8caa9',
                    tension: 0.4
                },
                {
                    label: 'Total Cards',
                    data: [datasetsData.totalCardsData[0].count, datasetsData.totalCardsData[1].count, datasetsData.totalCardsData[2].count, datasetsData.totalCardsData[3].count, datasetsData.totalCardsData[4].count, datasetsData.totalCardsData[5].count, datasetsData.totalCardsData[6].count],
                    borderColor: '#A1CCD1',
                    backgroundColor: '#A1CCD1',
                    tension: 0.4
                }
            ]
        };

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                legend: {
                    position: 'top',
                }
                }
            }
        });
    }
    // chart creation end

    // create chart
    createDayChart('{{ route("admin.getLastSevenDaysData") }}', 7, 'll-custom-dashboard');

    function createDayChart(routeName, days, canvasSelector) {
        $.ajax({
            url: routeName,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                createNewChart(canvasSelector, getDates(days), response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
            }
        });
    }


});
</script>
@stop
