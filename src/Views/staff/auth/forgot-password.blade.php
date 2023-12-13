@extends('staff.layouts.default')

@section('page_title', trans('common.forgot_password') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <section class="w-full admin-forgot-page place-self-center mx-auto lg:h-full ll-login-container">
        <div class="grid lg:grid-cols-2 lg:h-full">
            <div class="ll-login-left flex justify-center items-center py-6 px-4 g:py-0 sm:px-0">
                <img class="login-img" src="{{ asset('luminouslabs/ll_imgs/login.png') }}" alt="Image">
            </div>

            <div class="ll-login-right flex justify-center items-center py-6 px-4 lg:py-6 sm:px-0">
                <div class="w-full space-y-4 md:space-y-6 max-w-lg xl:max-w-lg p-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{!! trans('common.forgot_password_title') !!}</h2>
                    <x-forms.messages />

                    @if (!Session::has('success'))
                        <x-forms.form-open class="ll-form-container space-y-4 md:space-y-6" :action="route('staff.forgot_password.post')" method="POST" />
                        <x-forms.input class="ll-input-container" type="email" name="email" :label="trans('common.email_address')" :placeholder="trans('common.your_email')"
                            :required="true" />

                        <x-forms.button :label="trans('common.send_reset_link')" button-class="ll-primary-btn w-full btn-sm" />
                        <x-forms.form-close />
                    @endif

                    <div class="space-y-2 md:space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
                        <p class="text-sm ll-text-primary-color font-light">
                            {{ trans('common.login_text') }} <a href="{{ route('staff.login') }}"
                                class="font-semibold text-link">{{ trans('common.login_link') }}</a>
                        </p>
                    </div>
                </div>

                <div class="ll-login-bottom-logo">
                    <img class="login-img" src="{{ asset('luminouslabs/ll_imgs/logo_grey.png') }}" alt="Logo">
                </div>
            </div>
    </section>
@stop
