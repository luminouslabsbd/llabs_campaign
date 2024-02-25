{{--
@extends('partner.layouts.default')

@section('page_title', trans('common.partner') . config('default.page_title_delimiter') . trans('common.dashboard') .
    config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        #CampaignStorge .form-select,
        .spinner-fomr-col input {
            color: rgb(17 24 39 / var(--tw-text-opacity));
            font-size: .875rem;
            line-height: 1.25rem;
            padding: .625rem;
            --tw-bg-opacity: 1;
            border-width: 1px;
            background-color: rgb(249 250 251 / var(--tw-bg-opacity));
            --tw-border-opacity: 1;
            border-color: rgb(209 213 219 / var(--tw-border-opacity));
            border-radius: .5rem;
            display: block;
            width: 100%;
        }

        .spinner-fomr-col input {
            margin-bottom: 15px;
        }

        .label-title,
        .title h4,
        .spinner-fomr-col h5 {
            margin-top: 15px;
            margin-bottom: .5rem;
            display: block;
            font-size: .875rem;
            line-height: 1.25rem;
            font-weight: 500;
            --tw-text-opacity: 1;
            color: rgb(17 24 39 / var(--tw-text-opacity));
        }

        .title h4 {
            font-size: 20px
        }

        #CampaignStorge .ll-checkbox label {
            color: rgb(17 24 39 / var(--tw-text-opacity));
            font-size: .875rem;
            line-height: 1.25rem;
            cursor: pointer;
        }

        .spinner-form,
        .title {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .title button,
        .remove-input {
            border: 1px solid green;
            background: green;
            padding: 10px 20px;
            border-radius: 5px;
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            line-height: 1;
            transition: all 0.3s ease-in;
        }

        .remove-input {
            background: red;
            border-color: red;
            margin-bottom: 15px;
        }

        .title button:hover {
            color: green;
            background: #ffffff;
        }

        .remove-input:hover {
            color: red;
            background: #ffffff;
        }

        .spinner-fomr-col {
            flex-basis: 25%;
        }

        #input-container {
            display: block;
        }
    </style>

    <section class="">
        <div class="w-full">
            <div class="relative p-4 lg:p-6">
                <div class="mb-3">
                    <div class="w-full flex flex-row items-center justify-between">
                        <div class="mb-5">
                            <a href="{{ route('luminouslabs::partner.campain.manage') }}"
                               class="ll-back-btn w-fit flex text-sm items-center justify-start">
                                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                                </svg>
                                Back to list
                            </a>
                        </div>

                    </div>

                    <div class="w-full flex items-center space-x-3">
                        <a href="{{ route('luminouslabs::partner.campain.edit', $result['id']) }}">
                            <h5 class="dark:text-white font-semibold flex items-center">
                                <svg class="inline-block w-5 h-5 mr-2 dark:text-white"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                    </path>
                                </svg>
                                Campaign Edit
                            </h5>
                        </a>
                    </div>
                </div>

                <form class="ll-user-add-form space-y-4 md:space-y-6"
                      action="{{ route('luminouslabs::partner.campain.update', $result['id']) }}" method="POST"
                      enctype="multipart/form-data" id="CampaignStorge">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mt-4">
                            <label for="name" class="input-label">Campaign Name</label>
                            <input type="text" value="{{ $result != null ? $result['name'] : '' }}" id="name"
                                   name="name"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="" required="" x-bind:type="input">
                        </div>

                        <div class="mt-4">
                            <label for="name" class="input-label">Card ID</label>
                            <select class="form-select" required name="card_id" aria-label="Default select example">
                                <option selected>Select Your Card</option>
                                @foreach ($cards as $card)
                                    <option {{ $result != null && $result['card_id'] == $card->id ? 'selected' : '' }}
                                            value="{{ $card->id }}">
                                        {{ $card->name }} - {{ $card->unique_identifier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="unit_price_for_coupon" class="input-label">Unit Price For Coupon</label>
                            <input type="number" id="unit_price_for_coupon" name="unit_price_for_coupon"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="" required=""
                                   value="{{ $result != null ? $result['unit_price_for_coupon'] : '' }}"
                                   x-bind:type="input">
                        </div>

                        <div class="mt-4">
                            <label for="unit_price_for_point" class="input-label">Unit Price For Point</label>
                            <input type="number" id="unit_price_for_point" name="unit_price_for_point"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="" value="{{ $result != null ? $result['unit_price_for_point'] : '' }}"
                                   required="" x-bind:type="input">
                        </div>

                        <div class="mt-4">
                            <label for="coupon" class="input-label">Coupon</label>
                            <input type="text" id="coupon" name="coupon"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                   placeholder="" value="{{ $result != null ? $result['coupon'] : '' }}"
                                   x-bind:type="input">
                        </div>

                        --}}
{{-- <div class="ll-checkbox">
                        <span class="label-title">Campaign Type Setting</span>
                        <input  {{ ($result != null && $result['price_check'] == 'on') ? 'checked' : '' }} type="checkbox" id="price_check" name="price_check">
                        <label for="Price"> Price</label><br>
                        <input {{  ($result != null && $result['point_check'] == 'on') ? 'checked' : '' }} type="checkbox" id="point_check" name="point_check">
                        <label for="Price"> Point</label><br>
                    </div> --}}{{--


                        <div class="ll-checkbox">
                            <span class="label-title">Campaign Type Setting</span>
                            <input {{ $result != null && $result['price_check'] == 'only_prize' ? 'checked' : '' }}
                                   type="radio" id="price_check" name="campaign_type" value="only_prize">
                            <label for="price_check">Only Prize</label><br>
                            <input
                                {{ $result != null && $result['point_check'] == 'prize_and_point' ? 'checked' : '' }}
                                type="radio" id="point_check" name="campaign_type" value="prize_and_point">
                            <label for="point_check">Prize & Point</label><br>
                        </div>
                    </div>

                    <div class="spinner-form-wrapper">
                        <div class="title">
                            <h4 class="" style="color: white;">Spinner Settings</h4>
                            <button type="button" id="add-input">+ Add Input</button>
                        </div>
                        @if ($result['spiner'])
                            @foreach ($result['spiner'] as $item)
                                <div id="dynimicInput">
                                    <div class=" flex flex-wrap items-stretch sm:items-end justify-start sm:justify-between gap-3 sm:gap-0 flex-col sm:flex-row">
                                        <input type="hidden" name="spinner_id[]" value="{{ $item['spiner_data_id'] }}">
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Label Title</span>
                                                <input type="text" name="label_title[]" class=" form-input rounded"
                                                       value="{{ $item['label_title'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Label Value</span>
                                                <input type="text" name="label_value[]" class="form-input rounded"
                                                       value="{{ $item['label_value'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Label Color</span>
                                                <input type="color" name="label_color[]" class="rounded form-input" style="background: {{ $item['label_color'] }}"
                                                       value="{{ $item['label_color'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-1/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Init prize</span>
                                                <input type="number" name="init_prize[]" class="rounded form-input"
                                                       value="{{ $item['init_prize'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-1/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Available prize</span>
                                                <input type="number"
                                                       name="available_prize[]"
                                                       value="{{ $item['available_prize'] }}"
                                                       class="rounded form-input" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Is wining label</span>
                                                <input type="hidden" name="is_wining_label[]" value="0">
                                                <input type="checkbox"
                                                       name="is_wining_label[]"
                                                       value="1"
                                                       {{ isset($item['is_wining_label']) && $item['is_wining_label'] === 1 ? 'checked' : null }}
                                                       class="rounded"
                                                />
                                            </label>
                                        </div>
                                        <button type="button"
                                                style="margin-bottom: 0px"
                                                class="remove-dynimic-input w-full sm:w-1/12 remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class=" flex flex-wrap items-stretch sm:items-end justify-start sm:justify-between gap-3 sm:gap-0 flex-col sm:flex-row">
                                <div class="w-full sm:w-2/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Label Title</span>
                                        <input type="text" name="label_title[]" class=" form-input rounded" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-2/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Label Value</span>
                                        <input type="text" name="label_value[]" class="form-input rounded" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-2/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Label Color</span>
                                        <input type="color" name="label_color[]" class="rounded form-input" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-1/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Init prize</span>
                                        <input type="number" name="init_prize[]" class="rounded form-input" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-1/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Available prize</span>
                                        <input type="number" name="available_prize[]" class="rounded form-input" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-2/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Is wining label</span>
                                        <input type="checkbox" name="is_wining_label[]" class="rounded" />
                                    </label>
                                </div>
                                <button type="button" style="margin-bottom: 0px"
                                        class="w-full sm:w-1/12 remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">Remove</button>
                            </div>
                        @endif
                        <div class="spinner-form" id="input-container"></div>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="submit" class="w-full btn-primary ll-primary-btn"
                                style="max-width: 200px; width: 100%;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>



    <script>
        $(document).ready(function() {
            var index = 1;
            // Add Input
            $("#add-input").on("click", function() {
                // var newInput =
                //     '<div class="input-container"><input type="text" name="dynamicInput[]" class="dynamic-input" /><button type="button" class="remove-input">Remove</button></div>';
                var newInput =
                `
                <input type="hidden" name="spinner_id[]" value="">
                <div
                    class=" flex flex-wrap items-stretch sm:items-end justify-start sm:justify-between gap-3 sm:gap-0 flex-col sm:flex-row">
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Label Title</span>
                            <input type="text" name="label_title[]" class=" form-input rounded" />
                        </label>
                    </div>
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Label Value</span>
                            <input type="text" name="label_value[]" class="form-input rounded" />
                        </label>
                    </div>
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Label Color</span>
                            <input type="color" name="label_color[]" class="rounded form-input" />
                        </label>
                    </div>
                    <div class="w-full sm:w-1/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Init prize</span>
                            <input type="number" name="init_prize[]" class="rounded form-input" />
                        </label>
                    </div>
                    <div class="w-full sm:w-1/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Available prize</span>
                            <input type="number" name="available_prize[]" class="rounded form-input" />
                        </label>
                    </div>
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Is wining label</span>
                            <input type="hidden" name="is_wining_label[]" value="0">
                            <input type="checkbox" name="is_wining_label[]" value="1" class="rounded" />
                        </label>
                    </div>
                    <button type="button" style="margin-bottom: 0px"
                        class="w-full sm:w-1/12 remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">Remove</button>
                </div>`;

                $("#input-container").append(newInput);
                index++;
            });


            // Remove Input
            $("#input-container").on("click", ".remove-input", function() {
                $(this).parent().remove()
            });

            //Remove Dynimic Input Box
            $(".remove-dynimic-input").click(function(){
                // Remove the parent container of the clicked button

                $.get("demo_test.asp", function(data, status){
                    alert("Data: " + data + "\nStatus: " + status);
                });

                $(this).closest('#dynimicInput').remove();
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listener to all elements with the class 'remove-input'
            var removeButtons = document.querySelectorAll('.remove-input-id');
            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Get the spiner_data_id from the data attribute
                    var spinerDataId = button.getAttribute('data-spiner-id');
                    // Confirm deletion with the user
                    var confirmDelete = confirm('Are you sure you want to delete this item?');
                    var spinnerDiv = document.querySelector('.spinner-single' + spinerDataId);
                    if (confirmDelete) {
                        var url = '{{ route('luminouslabs::partner.campain_spiner_remove', ['id' => ':spinerDataId']) }}';
                        url = url.replace(':spinerDataId', spinerDataId);
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4) {
                                if (xhr.status == 200) {
                                    spinnerDiv.remove();
                                } else {

                                }
                            }
                        };
                        xhr.onerror = function() {
                            console.error('Error sending the delete request.');
                        };
                        xhr.send();
                    }
                });
            });
        });
    </script>


@stop
--}}


@extends('partner.layouts.default')

@section('page_title', trans('common.partner') . config('default.page_title_delimiter') . trans('common.dashboard') .
    config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        #CampaignStorge .form-select,
        .spinner-fomr-col input {
            color: rgb(17 24 39 / var(--tw-text-opacity));
            font-size: .875rem;
            line-height: 1.25rem;
            padding: .625rem;
            --tw-bg-opacity: 1;
            border-width: 1px;
            background-color: rgb(249 250 251 / var(--tw-bg-opacity));
            --tw-border-opacity: 1;
            border-color: rgb(209 213 219 / var(--tw-border-opacity));
            border-radius: .5rem;
            display: block;
            width: 100%;
        }

        .spinner-fomr-col input {
            margin-bottom: 15px;
        }

        .label-title,
        .title h4,
        .spinner-fomr-col h5 {
            margin-top: 15px;
            margin-bottom: .5rem;
            display: block;
            font-size: .875rem;
            line-height: 1.25rem;
            font-weight: 500;
            --tw-text-opacity: 1;
            color: rgb(17 24 39 / var(--tw-text-opacity));
        }

        .title h4 {
            font-size: 20px
        }

        #CampaignStorge .ll-checkbox label {
            color: rgb(17 24 39 / var(--tw-text-opacity));
            font-size: .875rem;
            line-height: 1.25rem;
            cursor: pointer;
        }

        .spinner-form,
        .title {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .title button,
        .remove-input {
            border: 1px solid green;
            background: green;
            padding: 10px 20px;
            border-radius: 5px;
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            line-height: 1;
            transition: all 0.3s ease-in;
        }

        .remove-input {
            background: red;
            border-color: red;
            margin-bottom: 15px;
        }

        .title button:hover {
            color: green;
            background: #ffffff;
        }

        .remove-input:hover {
            color: red;
            background: #ffffff;
        }

        .spinner-fomr-col {
            flex-basis: 25%;
        }

        #input-container {
            display: block;
        }
    </style>
    <section class="">

        <section class="">
            <div class="w-full">
                <div class="relative p-4 lg:p-6">
                    <div class="mb-3">

                        <div class="w-full flex flex-row items-center justify-between">
                            <div class="mb-5">
                                <a href="{{ route('luminouslabs::partner.campain.manage') }}"
                                   class="ll-back-btn w-fit flex text-sm items-center justify-start">
                                    <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                                    </svg>
                                    Back to list
                                </a>
                            </div>

                        </div>

                        <div class="w-full flex items-center space-x-3">
                            <a href="{{ route('luminouslabs::partner.campain.edit', $result['id']) }}">
                                <h5 class="dark:text-white font-semibold flex items-center">
                                    <svg class="inline-block w-5 h-5 mr-2 dark:text-white"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                        </path>
                                    </svg>
                                    Campaign Edit
                                </h5>
                            </a>
                        </div>
                    </div>

                    <form class="ll-user-add-form space-y-4 md:space-y-6"
                          action="{{ route('luminouslabs::partner.campain.update', $result['id']) }}" method="POST"
                          enctype="multipart/form-data" id="CampaignStorge">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mt-4">
                                <label for="name" class="input-label">Campaign Name</label>
                                <input type="text" value="{{ $result != null ? $result['name'] : '' }}" id="name"
                                       name="name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" required="" x-bind:type="input">
                            </div>

                            <div class="mt-4">
                                <label for="name" class="input-label">Card ID</label>
                                <select class="form-select" required name="card_id" aria-label="Default select example">
                                    <option selected>Select Your Card</option>
                                    @foreach ($cards as $card)
                                        <option {{ $result != null && $result['card_id'] == $card->id ? 'selected' : '' }}
                                                value="{{ $card->id }}">
                                            {{ $card->name }} - {{ $card->unique_identifier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="unit_price_for_coupon" class="input-label">Unit Price For Coupon</label>
                                <input type="number" id="unit_price_for_coupon" name="unit_price_for_coupon"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" required=""
                                       value="{{ $result != null ? $result['unit_price_for_coupon'] : '' }}"
                                       x-bind:type="input">
                            </div>

                            <div class="mt-4">
                                <label for="unit_price_for_point" class="input-label">Unit Price For Point</label>
                                <input type="number" id="unit_price_for_point" name="unit_price_for_point"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" value="{{ $result != null ? $result['unit_price_for_point'] : '' }}"
                                       required="" x-bind:type="input">
                            </div>

                            <div class="mt-4">
                                <label for="coupon" class="input-label">Coupon</label>
                                <input type="text" id="coupon" name="coupon"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" value="{{ $result != null ? $result['coupon'] : '' }}"
                                       x-bind:type="input">
                            </div>

                            {{-- <div class="ll-checkbox">
                            <span class="label-title">Campaign Type Setting</span>
                            <input  {{ ($result != null && $result['price_check'] == 'on') ? 'checked' : '' }} type="checkbox" id="price_check" name="price_check">
                            <label for="Price"> Price</label><br>
                            <input {{  ($result != null && $result['point_check'] == 'on') ? 'checked' : '' }} type="checkbox" id="point_check" name="point_check">
                            <label for="Price"> Point</label><br>
                        </div> --}}

                            <div class="ll-checkbox">
                                <span class="label-title">Campaign Type Setting</span>
                                <input {{ $result != null && $result['price_check'] == 'only_prize' ? 'checked' : '' }}
                                       type="radio" id="price_check" name="campaign_type" value="only_prize">
                                <label for="price_check">Only Prize</label><br>
                                <input
                                    {{ $result != null && $result['point_check'] == 'prize_and_point' ? 'checked' : '' }}
                                    type="radio" id="point_check" name="campaign_type" value="prize_and_point">
                                <label for="point_check">Prize & Point</label><br>
                            </div>

                        </div>

                        <div class="spinner-form-wrapper">
                            <div class="title">
                                <h4 style="color: white;">Spinner Settings</h4>
                                    <button type="button" id="add-input">+ Add Input</button>
                            </div>
                            @if ($result['spiner'])
                                @foreach ($result['spiner'] as $key => $item)
                                    <input type="hidden" name="spiner_title_id[]" value="{{ $item['spiner_data_id'] }} ">
                                    <div
                                        class="spinner-single{{ $item['spiner_data_id'] }} flex flex-wrap items-stretch sm:items-end justify-start sm:justify-between gap-3 sm:gap-0 flex-col sm:flex-row">
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Label Title</span>
                                                <input type="text" name="label_title[]" class=" form-input rounded"
                                                       value="{{ $item['label_title'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Label Value</span>
                                                <input type="text" name="label_value[]" class="form-input rounded"
                                                       value="{{ $item['label_value'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Label Color</span>
                                                <input type="color"
                                                       name="label_color[]"
                                                       style="background: {{ $item['label_color'] }}"
                                                       class="rounded form-input"
                                                       value="{{ $item['label_color'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-1/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Init prize</span>
                                                <input type="number" name="init_prize[{{ $key }}]" class="rounded form-input"
                                                       value="{{ $item['init_prize'] }}" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-1/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Available prize</span>
                                                <input type="number" name="available_prize[{{ $key }}]"
                                                       value="{{ $item['available_prize'] }}" class="rounded form-input" />
                                            </label>
                                        </div>
                                        <div class="w-full sm:w-2/12">
                                            <label class="flex flex-col gap-2">
                                                <span class=" text-gray-700">Is wining label</span>
                                                <input type="checkbox"
                                                       name="is_wining_label[{{ $key }}]"
                                                       {{ isset($item['is_wining_label']) && $item['is_wining_label'] === 1 ? 'checked' : null }}
                                                       class="rounded" />
                                            </label>
                                        </div>
                                        <button type="button"
                                                style="margin-bottom: 0px"
                                                data-spiner-id="{{ $item['spiner_data_id'] }}"
                                                class="w-full sm:w-1/12 remove-input-id remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">Remove</button>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class=" flex flex-wrap items-stretch sm:items-end justify-start sm:justify-between gap-3 sm:gap-0 flex-col sm:flex-row">
                                    <div class="w-full sm:w-2/12">
                                        <label class="flex flex-col gap-2">
                                            <span class=" text-gray-700">Label Title</span>
                                            <input type="text" name="label_title[]" class=" form-input rounded" />
                                        </label>
                                    </div>
                                    <div class="w-full sm:w-2/12">
                                        <label class="flex flex-col gap-2">
                                            <span class=" text-gray-700">Label Value</span>
                                            <input type="text" name="label_value[]" class="form-input rounded" />
                                        </label>
                                    </div>
                                    <div class="w-full sm:w-2/12">
                                        <label class="flex flex-col gap-2">
                                            <span class=" text-gray-700">Label Color</span>
                                            <input type="color" name="label_color[]" class="rounded form-input" />
                                        </label>
                                    </div>
                                    <div class="w-full sm:w-1/12">
                                        <label class="flex flex-col gap-2">
                                            <span class=" text-gray-700">Init prize</span>
                                            <input type="number" name="init_prize[]" class="rounded form-input" />
                                        </label>
                                    </div>
                                    <div class="w-full sm:w-1/12">
                                        <label class="flex flex-col gap-2">
                                            <span class=" text-gray-700">Available prize</span>
                                            <input type="number" name="available_prize[]" class="rounded form-input" />
                                        </label>
                                    </div>
                                    <div class="w-full sm:w-2/12">
                                        <label class="flex flex-col gap-2">
                                            <span class=" text-gray-700">Is wining label</span>
                                            <input type="checkbox" name="is_wining_label[]" class="rounded" />
                                        </label>
                                    </div>
                                    <button type="button" style="margin-bottom: 0px"
                                            class="w-full sm:w-1/12 remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">Remove</button>
                                </div>
                            @endif

                            <div class="spinner-form" id="input-container"></div>

                        </div>

                        <div class="mt-3 text-right">
                            <button type="submit" class="w-full btn-primary ll-primary-btn"
                                    style="max-width: 200px; width: 100%;">Save</button>
                        </div>


                    </form>

                </div>
            </div>
        </section>


    </section>

    <script>
        $(document).ready(function() {
            var index = {{ count($result['spiner']) }};
            // Add Input
            $("#add-input").on("click", function() {
                // var newInput =
                //     '<div class="input-container"><input type="text" name="dynamicInput[]" class="dynamic-input" /><button type="button" class="remove-input">Remove</button></div>';
                var newInput =
                `
                <div
                    class=" flex flex-wrap items-stretch sm:items-end justify-start sm:justify-between gap-3 sm:gap-0 flex-col sm:flex-row">
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Label Title</span>
                            <input type="text" name="label_title[]" class=" form-input rounded" />
                        </label>
                    </div>
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Label Value</span>
                            <input type="text" name="label_value[]" class="form-input rounded" />
                        </label>
                    </div>
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Label Color</span>
                            <input type="color" name="label_color[]" class="rounded form-input" />
                        </label>
                    </div>
                    <div class="w-full sm:w-1/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Init prize</span>
                            <input type="number" name="init_prize[${index}]" class="rounded form-input" />
                        </label>
                    </div>
                    <div class="w-full sm:w-1/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Available prize</span>
                            <input type="number" name="available_prize[${index}]" class="rounded form-input" />
                        </label>
                    </div>
                    <div class="w-full sm:w-2/12">
                        <label class="flex flex-col gap-2">
                            <span class=" text-gray-700">Is wining label</span>
                            <input type="checkbox" name="is_wining_label[${index}]" class="rounded" />
                        </label>
                    </div>
                    <button type="button" style="margin-bottom: 0px"
                        class="w-full sm:w-1/12 remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">Remove</button>
                </div>`;

                $("#input-container").append(newInput);
                index++;
            });

            // Remove Input
            $("#input-container").on("click", ".remove-input", function() {
                $(this).parent().remove();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listener to all elements with the class 'remove-input'
            var removeButtons = document.querySelectorAll('.remove-input-id');
            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Get the spiner_data_id from the data attribute
                    var spinerDataId = button.getAttribute('data-spiner-id');
                    var confirmDelete = confirm('Are you sure you want to delete this item?');
                    var spinnerDiv = document.querySelector('.spinner-single' + spinerDataId);
                    if (confirmDelete) {
                        var url =
                            '{{ route('luminouslabs::partner.campain_spiner_remove', ['id' => ':spinerDataId']) }}';
                        url = url.replace(':spinerDataId', spinerDataId);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4) {
                                if (xhr.status == 200) {

                                    console.log('data response',xhr.response)

                                    spinnerDiv.remove();
                                } else {

                                }
                            }
                        };
                        xhr.onerror = function() {
                            console.error('Error sending the delete request.');
                        };
                        xhr.send();
                    }
                });
            });
        });
    </script>
@stop
