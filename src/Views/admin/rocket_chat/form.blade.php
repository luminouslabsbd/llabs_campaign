@extends('admin.layouts.default')

@section('page_title', ((auth('admin')->user()->role == 1) ? trans('common.administrator') : trans('common.manager')) . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<section class="">        
    <div class="w-full">
        <div class="relative p-4 lg:p-6">
            <div class="mb-3">
                
                <div class="w-full flex flex-row items-center justify-between">
                    <div class="mb-5">
                        <a href="{{route('admin.rocket_chat')}}" class="ll-back-btn w-fit flex text-sm items-center justify-start">
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                            </svg>
                            Back to list
                        </a>
                    </div>
    
                    <div class="flex flex-row items-center justify-end">
                        <a href="{{route('admin.rocket_chat')}}" class="w-full flex items-center btn-sm text-sm mr-2 btn-primary ll-primary-btn">
                            <svg class="h-3.5 w-3.5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                            </svg>
                            View
                        </a>
                    </div>
                </div>
    
                <div class="w-full flex items-center space-x-3">
                    <a href="{{route('admin.rocket_chat')}}">
                        <h5 class="dark:text-white font-semibold flex items-center">
                            <svg class="inline-block w-5 h-5 mr-2 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                            Rocket Chat Info
                        </h5>
                    </a>
                    <div class="text-gray-400 font-medium">
                        Edit Rocket Chat Info
                    </div>
                </div>
            </div>
                <form class="ll-user-add-form space-y-4 md:space-y-6" action="{{route('admin.rocket_chat.update')}}" method="POST" enctype="multipart/form-data" id="rocketChatData">
                    @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <label for="name" class="input-label">API Title </label>
                        <input 
                            type="text" 
                            id="api_title" 
                            name="api_title" 
                            value="{{ $rocketData != null ?  $rocketData->api_title : ''}}" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                            placeholder="" 
                            required="" 
                            x-bind:type="input"
                        >
                    </div>
                    <input type="hidden" name="id" value="{{ $rocketData != null ? $rocketData->id : ''}}">
                    <div class="mt-4">
                        <label for="name" class="input-label">API Url</label>
                        <input 
                            type="text" 
                            id="api_url" 
                            name="api_url" 
                            value="{{ $rocketData != null ?  $rocketData->api_url : ''}}" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                            placeholder="" 
                            required="" 
                            x-bind:type="input"
                        >
                    </div>
                    <div class="mt-4">
                        <label for="name" class="input-label">API Token </label>
                        <input 
                            type="text" 
                            id="api_token" 
                            name="api_token" 
                            value="{{ $rocketData != null ? Crypt::decryptString($rocketData->api_token) : ''}}" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                            placeholder="" 
                            required="" 
                            x-bind:type="input"
                        >
                    </div>
    
                    <div class="mt-4">
                        <label for="name" class="input-label">X User Id </label>
                        <input 
                            type="text" 
                            id="x_user_id" 
                            name="x_user_id" 
                            value="{{ $rocketData != null ?  $rocketData->x_user_id : ''}}" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                            placeholder="" 
                            required="" 
                            x-bind:type="input"
                        >
                    </div>
                </div>

                <div class="mt-3 text-right">
                    <button type="submit" class="w-full btn-primary ll-primary-btn" style="max-width: 200px; width: 100%;">Save</button>
                </div>
                    

                </form>
            
        </div>
    </div>
    
    
    
        
</section>
@stop
