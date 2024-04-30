@extends('member.layouts.default')

@section('page_title', trans('common.partner') . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <section class="">

        <div class="relative dark:bg-gray-800 m-0 px-5">

            <div class="px-0 py-3">
                <div class="w-full flex items-center space-x-3 my-3">
                    <h5 class="dark:text-white font-semibold flex items-center">
                        <svg class="inline-block w-5 h-5 mr-2 dark:text-white" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"></path>
                        </svg>
                        Passkit List
                    </h5>

                </div>
                {{--<div class="ll-main-content-container w-full flex flex-row items-center justify-between gap-x-7">
                    <div class="w-full">
                        <form class="flex items-center">
                            <label for="tableDataDefinition-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                    </svg>
                                </div>
                                <input type="search" name="search" id="tableDataDefinition-search" class="bg-gray-50 text-gray-900 text-sm block w-full pl-10 border-0 p-2 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white" placeholder="Search" value="" style="border-radius: 100vmax;">
                            </div>
                        </form>
                    </div>
                    <div class="flex flex-row items-center justify-between gap-x-5">
                        <a href="{{route('luminouslabs::partner.campain.create')}}" class="whitespace-nowrap w-fit flex text-sm items-center btn-primary ll-primary-btn">
                            <svg class="ll-plus-icon me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.75 5.5V10M10 7.75H5.5M14.5 7.75C14.5 11.4779 11.4779 14.5 7.75 14.5C4.02208 14.5 1 11.4779 1 7.75C1 4.02208 4.02208 1 7.75 1C11.4779 1 14.5 4.02208 14.5 7.75Z" stroke="#FAFAFA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            Add new item
                        </a>
                    </div>
                </div>--}}
            </div>

            <div class="">
                @if($passkits->count() > 0)
                    <div class="grid grid-cols-5 gap-5">
                        @foreach($passkits as $passkit)
                            <div class="shadow rounded p-3 hover:shadow-md hover:cursor-pointer dark:bg-red-500">
                                <div class="">
                                    <div class="">
                                        <h4 class="font-bold text-black">Id: {{ $passkit->template_id }}</h4>
                                        <h1 class="text-xl font-bold uppercase text-gray-600">{{ $passkit->template_pass_type }}</h1>
                                    </div>
                                    <div class="flex justify-end">
                                        <a href="{{ route('luminouslabs::download-passkit-template',['template_id' => $passkit->template_id , 'template_type' => $passkit->template_pass_type]) }}" class="flex justify-end bg-gray-200 p-2 rounded-full text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="">
                        <h1 class="text-2xl font-bold text-center mt-16">Passkit Not Found</h1>
                    </div>
                @endif
            </div>


        </div>

        <script>

            function deleteItem(id, item) {
                if (item == null) item = "{{ trans('common.this_item') }}";

                // Use a simple JavaScript confirm dialog
                const confirmation = confirm('Are you sure you want to delete ' + item + '?');

                if (confirmation) {
                    // If the user confirms, submit the form using fetch
                    var url = '{{ route("luminouslabs::partner.campain.delete", ["id" => ":id"]) }}';
                    url = url.replace(':id', id);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            window.location.reload(); // Reload the page
                        })
                        .catch(error => {
                            // Handle errors
                            console.error('There was a problem with the fetch operation:', error);
                        });
                }
            }

        </script>

    </section>

@stop
