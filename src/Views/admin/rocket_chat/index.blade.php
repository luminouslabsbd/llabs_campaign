@extends('admin.layouts.default')

@section('page_title', ((auth('admin')->user()->role == 1) ? trans('common.administrator') : trans('common.manager')) . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<section class="">        
        <div class="w-full">
            <div class="relative p-4 lg:p-6">
                <div class="mb-5">
                    <div class="w-full flex flex-row items-center justify-between">
                        <div class="mb-5">
                            <a href="{{route('admin.rocket_chat')}}" class="ll-back-btn w-fit flex text-sm items-center justify-start">
                                <svg class="h-3.5 w-3.5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                                </svg>
                                Back to list
                            </a>
                        </div>
                        @if($rocketData == null)
                        <div class="flex flex-row items-center justify-end">
                            <a href="{{route('admin.rocket_chat.add')}}" class="w-full flex items-center btn-sm text-sm mr-2 btn-warning ll-warning-btn">
                                <svg class="h-3.5 w-3.5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                </svg>
                                Add
                            </a>
                        </div>
                        @else 
                        <div class="flex flex-row items-center justify-end">
                            <a href="{{route('admin.rocket_chat.edit',['id' => $rocketData->id])}}" class="w-full flex items-center btn-sm text-sm mr-2 btn-warning ll-warning-btn">
                                <svg class="h-3.5 w-3.5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                </svg>
                                Edit
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="w-full flex items-center space-x-3">
                        <a href="{{route('admin.rocket_chat')}}">
                            <h5 class="dark:text-white font-semibold flex items-center">
                                <svg class="inline-block w-5 h-5 mr-2 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"></path>
                                </svg>
                                Rocket Chat Info
                            </h5>
                        </a>
                        <div class="text-gray-400 font-medium">
                            View
                        </div>
                    </div>
                </div>
                @if($rocketData != null)
                <div class="ll-user-view-page flex items-center gap-x-4 ll-rocket-chat-page">
                    <div class="ll-user-other-info-left">
                        <div class="mb-2">
                            <div class="ll-label"><span>API Title</span><span>:</span></div>
                            {{ $rocketData->api_title }}
                        </div>
                        <div class="mb-2">
                            <div class="ll-label"><span>API Url</span><span>:</span></div>
                            {{ $rocketData->api_url }}
                        </div>
                        
                        <div class="mb-2" style="align-items: flex-start">
                            <div class="ll-label" style="display: flex"><span>API Token</span><span>:</span></div>
                            <span class="format-date-time break-all" style="margin-left: 5px;">{{ $rocketData->api_token }}</span>
                        </div>
                        <div class="mb-2">
                            <div class="ll-label "><span>X User Id</span><span>:</span></div>
                             {{$rocketData->x_user_id}}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
</section>
@stop
