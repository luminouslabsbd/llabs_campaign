@extends('member.layouts.default')

@section('page_title', auth('member')->user()->name . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<section class="w-full">
    {{-- <div class="py-8 px-4 mx-auto sm:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">{{ trans('common.welcome_user', ['user' => auth('member')->user()->name]) }} {{ Arr::random(['😎', '🤠', '😊', '😃', '🙃', '🤩', '😉']) }}</h2>
            <p class="font-light text-gray-500 dark:text-gray-400 sm:text-xl">{!! trans('common.memberDashboardBlocksTitle') !!}</p>
        </div>

        <div class="space-y-8 ll-dashboard-grid md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 md:gap-8 xl:gap-8 md:space-y-0">
            @foreach($dashboardBlocks as $block)
            <a href="{{ $block['link'] }}" class="group block p-6 bg-white rounded shadow dark:bg-gray-800">
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
                <h3 class="font-semibold mb-3">Total Followed Cards</h3>
                <h1 class="font-extrabold text-3xl TotalFollowedCards"></h1>
            </div>

            <div class="ll-dashboard-info-card">
                <h3 class="font-semibold mb-3">Total Transactions Cards</h3>
                <h1 class="font-extrabold text-3xl TotalTransactionsCards"></h1>
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
                        label: 'Followed Cards',
                        data: [datasetsData.last7DaysFollowedCardCount[0].count, datasetsData.last7DaysFollowedCardCount[1].count, datasetsData.last7DaysFollowedCardCount[2].count, datasetsData.last7DaysFollowedCardCount[3].count, datasetsData.last7DaysFollowedCardCount[4].count, datasetsData.last7DaysFollowedCardCount[5].count, datasetsData.last7DaysFollowedCardCount[6].count],
                        borderColor: '#739072',
                        backgroundColor: '#739072',
                        tension: 0.4
                    },
                    {
                        label: 'Transactions Cards',
                        data: [datasetsData.last7DaysTrxCardsCount[0].count, datasetsData.last7DaysTrxCardsCount[1].count, datasetsData.last7DaysTrxCardsCount[2].count, datasetsData.last7DaysTrxCardsCount[3].count, datasetsData.last7DaysTrxCardsCount[4].count, datasetsData.last7DaysTrxCardsCount[5].count, datasetsData.last7DaysTrxCardsCount[6].count],
                        borderColor: '#FD8D14',
                        backgroundColor: '#FD8D14',
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
        createDayChart('{{ route("luminouslabs::member.getLastSevenDaysData") }}', 7, 'll-custom-dashboard');

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

        // Get Count
        getAdminCardCount('{{ route("luminouslabs::member.getDashboardCardCount") }}');

        function getAdminCardCount(routeName) {
            $.ajax({
                url: routeName,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('.TotalFollowedCards').text(response.followedCards ?? 0);
                    $('.TotalTransactionsCards').text(response.cards ?? 0);

                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status, error);
                }
            });
        }


    });
</script>
@stop
