@extends('admin.layouts.default')

@section('page_title', trans('common.login_title') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <section class="w-full admin-login-page place-self-center mx-auto lg:h-full ll-login-container">
        <div class="grid lg:grid-cols-2 lg:h-full">
            <div class="ll-login-left flex justify-center items-center py-6 px-4 g:py-0 sm:px-0">
                {{-- <div class="max-w-md xl:max-w-xl p-6">
                    <h1 class="mb-4 text-3xl font-extrabold tracking-tight leading-none text-white xl:text-5xl">
                        {!! trans('common.admin_login_block_title') !!}</h1>
                    @foreach (trans('common.admin_login_block_text') as $text)
                        <p class="mb-4 font-light text-primary-200 lg:mb-8">{!! $text !!}</p>
                    @endforeach
                </div> --}}

                <img class="login-img" src="{{ asset('luminouslabs/ll_imgs/login.png') }}" alt="Image">
            </div>
            <div class="ll-login-right flex justify-center items-center py-6 px-4 lg:py-6 sm:px-0">
                <div class="w-full space-y-4 md:space-y-6 max-w-lg xl:max-w-lg p-6">

                    @if(config('default.app_demo'))
                    <div class="flex p-4 mb-4 text-sm text-blue-800 border-2 border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 cursor-pointer" role="alert" onclick="document.getElementById('email').value='admin@example.com';document.getElementById('password').value='welcome123';">
                        <x-ui.icon icon="info" class="flex-shrink-0 w-5 h-5" />
                        <div class="ml-3 text-sm font-medium">
                            To access the demo, use the following login credentials: <span class="font-extrabold">admin@example.com / welcome123</span> (click to autofill).
                        </div>
                      </div>
                    @endif
                    
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ trans('common.login_title') }}</h2>

                    <x-forms.messages />

                    <x-forms.form-open class="ll-form-container space-y-4 md:space-y-6" :action="route('admin.login.post')" method="POST" />
                    <x-forms.input class="ll-input-container" type="email" name="email" :label="trans('common.email_address')" :placeholder="trans('common.your_email')"
                        :required="true" />
                    <x-forms.input class="ll-input-container" type="password" name="password" :label="trans('common.password')" :placeholder="trans('common.password')"
                        :required="true" />
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <x-forms.checkbox name="remember" :label="trans('common.remember_me')" />
                        </div>
                        <a href="{{ route('admin.forgot_password') }}" class="text-sm font-medium hover:underline dark:text-primary-500" style="color: #2963FF">{{ trans('common.forgot_password') }}</a>
                    </div>
                    <x-forms.button :label="trans('common.log_in')" button-class="ll-primary-btn w-full" />
                    <x-forms.form-close />
                </div>

                <div class="ll-login-bottom-logo">
                    <img class="login-img" src="{{ asset('luminouslabs/ll_imgs/logo_grey.png') }}" alt="Logo">
                </div>
            </div>
        </div>
    </section>
@stop
