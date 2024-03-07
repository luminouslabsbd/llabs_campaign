@extends('partner.layouts.default')

@section('page_title', trans('common.partner') . config('default.page_title_delimiter') . trans('common.dashboard') .
    config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
            integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

        #modal {
            padding: 0px !important;
        }

        #modal .modal-dialog {
            max-width: calc(50% - 50px);
        }

        .ll-wallet-data-form label {
            outline: none;
        }

        .ll-wallet-data-form input[type="color"] {
            padding: 0px 2px;
        }

        .apple-store-or-coupon-card,
        .apple-member-card,
        .google-card {
            padding: 30px;
            border-radius: 15px;
            /* margin-top: 20px; */
        }

        .apple-store-or-coupon-card.store-card,
        .google-card.store-card {
            background-color: #1e3181;
        }

        .apple-store-or-coupon-card.generic-pass,
        .apple-member-card.generic-pass,
        .google-card.generic-pass {
            background-color: #f8d419;
        }

        .apple-store-or-coupon-card.generic-pass label,
        .apple-member-card.generic-pass label,
        .google-card.generic-pass label,
        .apple-store-or-coupon-card.generic-pass img,
        .apple-member-card.generic-pass img,
        .google-card.generic-pass img,
        .apple-store-or-coupon-card.coupon label,
        .google-card.coupon label,
        .apple-store-or-coupon-card.coupon img,
        .google-card.coupon img,
        .apple-store-or-coupon-card.store-card label,
        .google-card.store-card label,
        .apple-store-or-coupon-card.store-card img,
        .google-card.store-card img,
        #nameText {
            color: #fff;
        }

        .apple-store-or-coupon-card.coupon,
        .google-card.coupon {
            background-color: #8a7865;
        }

        .apple-store-or-coupon-card input,
        .apple-member-card input,
        .google-card input {
            padding: 5px 8px;
        }

        #logo-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            /* border: 1px solid #dee2e6; */
            object-fit: cover;
            margin-bottom: 24px;
        }

        .google-card #hero-image,
        .apple-store-or-coupon-card #hero-image {
            height: 80px;
            width: 100%;
            margin-bottom: 24px;
            object-fit: cover;
        }

        .google-card #hero-image {
            margin-top: 24px;
            margin-bottom: 0px;
        }

        .apple-member-card #hero-image {
            width: 120px;
            height: 90px;
            object-fit: cover;
        }

        @media (min-width: 640px) {
            .sm\:items-end {
                align-items: flex-end;
            }
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
                                    <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                                    </svg>
                                    Back to list
                                </a>
                            </div>

                            <div class="flex flex-row items-center justify-end">
                                <a href="{{ route('luminouslabs::partner.campain.manage') }}"
                                   class="w-full flex items-center btn-sm text-sm mr-2 btn-primary ll-primary-btn">
                                    <svg class="h-3.5 w-3.5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z">
                                        </path>
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>

                        <div class="w-full flex items-center space-x-3">
                            <a href="{{ route('luminouslabs::partner.campain.create') }}">
                                <h5 class="dark:text-white font-semibold flex items-center">
                                    <svg class="inline-block w-5 h-5 mr-2 dark:text-white"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                        </path>
                                    </svg>
                                    Campaign create
                                </h5>
                            </a>
                        </div>
                    </div>
                    <form class="ll-user-add-form space-y-4 md:space-y-6"
                          action="{{ route('luminouslabs::partner.campain.storge') }}" method="POST"
                          enctype="multipart/form-data" id="CampaignStorge">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mt-4">
                                <label for="name" class="input-label">Campaign Name</label>
                                <input type="text" id="name" name="name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" required="" x-bind:type="input">
                            </div>

                            <div class="mt-4">
                                <label for="name" class="input-label">Card ID</label>
                                <select class="form-select" required name="card_id" aria-label="Default select example">
                                    <option selected>Select Your Card</option>
                                    @foreach ($cards as $card)
                                        <option value="{{ $card->id }}">{{ $card->name }} -
                                            {{ $card->unique_identifier }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="name" class="input-label">Template</label>

                                <div class="d-flex gap-3">
                                    <select class="form-select" id="templateSelector" required name="template_info"
                                            aria-label="Default select example">
                                        <option selected>Select Your Template</option>
                                        @foreach ($templates as $template)
                                            <option value="{{ $template['id'] }}|{{ $template['pass_type'] }}">Template -
                                                {{ $template['id'] }} | Type - {{ $template['pass_type'] }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary template-add-icon"
                                            id="template-add-icon">+</button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="unit_price_for_coupon" class="input-label">Unit Price For Coupon</label>
                                <input type="number" id="unit_price_for_coupon" name="unit_price_for_coupon"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" required="" x-bind:type="input">
                            </div>

                            <div class="mt-4">
                                <label for="unit_price_for_point" class="input-label">Unit Price For Point</label>
                                <input type="number" id="unit_price_for_point" name="unit_price_for_point"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" required="" x-bind:type="input">
                            </div>

                            <div class="mt-4">
                                <label for="coupon" class="input-label">Coupon</label>
                                <input type="text" id="coupon" name="coupon"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="" x-bind:type="input">
                            </div>

                            {{-- <div class="ll-checkbox">
                            <span class="label-title">Campaign Type Setting</span>
                            <input type="checkbox" id="price_check" name="price_check">
                            <label for="Price">Only Prize</label><br>
                            <input type="checkbox" id="point_check" name="point_check">
                            <label for="Price"> Prize & Point</label><br>
                        </div> --}}

                            <div class="ll-checkbox">
                                <span class="label-title">Campaign Type Setting</span>
                                <input type="radio" id="price_check" name="campaign_type" checked value="only_prize">
                                <label for="price_check">Only Prize</label><br>
                                <input type="radio" id="point_check" name="campaign_type" value="prize_and_point">
                                <label for="point_check">Prize & Point</label><br>
                            </div>


                        </div>

                        <div class="spinner-form-wrapper">
                            <div class="title">
                                <h4 class="" style="color: white;">Spinner Settings</h4>
                                <button type="button" id="add-input">+ Add Input</button>
                            </div>
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
                                        <input type="number" name="init_prize[0]" class="rounded form-input" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-1/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Available prize</span>
                                        <input type="number" name="available_prize[0]" class="rounded form-input" />
                                    </label>
                                </div>
                                <div class="w-full sm:w-2/12">
                                    <label class="flex flex-col gap-2">
                                        <span class=" text-gray-700">Is wining label</span>
                                        <input type="checkbox" name="is_wining_label[0]" class="rounded" />
                                    </label>
                                </div>
                                <button type="button" style="margin-bottom: 0px"
                                        class="w-full sm:w-1/12 remove-input bg-red-500 text-white hover:border hover:border-red-500 hover:text-red-500">Remove</button>
                            </div>
                            <div class="spinner-form" id="input-container"></div>
                        </div>

                        <div class="m-0">
                            <textarea hidden="hidden" name="wallet_obj" id="wallet_obj" cols="30" rows="10"></textarea>
                            <textarea hidden="hidden" name="template_response_obj" id="template_response_obj" cols="30" rows="10"></textarea>
                        </div>

                        <div class="mt-3 text-right">
                            <button type="submit" class="w-full btn-primary ll-primary-btn"
                                    style="max-width: 200px; width: 100%;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="ll-user-add-form ll-wallet-data-form space-y-4 md:space-y-6" enctype="multipart/form-data"
                         id="CampaignStorge">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLable"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body row mx-0">
                            <div class="col-7">

                            </div>

                            <div class="col-5 additional-card">
                                <h5>Settings</h5>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="name" class="input-label">Select Wallet</label>
                                        <select class="form-select" id="wallet" required name="card_id"
                                                aria-label="Default select example">
                                            {{-- <option disabled>Select Your Wallet</option> --}}
                                            <option selected value="0">Google Wallet</option>
                                            <option value="1">Apple Wallet</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="name" class="input-label">Select Card</label>
                                        <select class="form-select" id="card" required name="card_id"
                                                aria-label="Default select example">
                                            {{-- <option disabled>Select Your Card</option> --}}
                                            <option selected value="0">Store Card</option>
                                            <option value="1">Generic Pass</option>
                                            <option value="2">Coupon</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="input-label" contenteditable="true">Name</label>
                                    <input type="text" id="card-name" name="card_name"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                           placeholder="" x-bind:type="input">
                                </div>

                                <div class="mt-4">
                                    <label class="input-label" contenteditable="true">Logo</label>
                                    <input type="file" id="logo" name="logo" accept="image/png"
                                           class="border border-gray-300 text-sm rounded-lg block w-full" placeholder="">
                                </div>

                                <div class="mt-4">
                                    <label class="input-label" contenteditable="true">Hero Image</label>
                                    <input type="file" id="hero-img-input" name="hero-img-input" accept="image/png"
                                           class="border border-gray-300 text-sm rounded-lg block w-full" placeholder="">
                                </div>

                                <div class="mt-4">
                                    <label class="input-label" contenteditable="true">Background Color</label>
                                    <input type="color" id="background-color" name="background-color"
                                           class="border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                                           placeholder="">
                                </div>

                                <div class="mt-4">
                                    <label class="input-label" contenteditable="true">Label Color</label>
                                    <input type="color" id="label-color" name="label-color"
                                           class="border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                                           placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" data-dismiss="modal"
                                    id="save-wallet-data">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Add Input
            var index = 1;
            $("#add-input").on("click", function() {

                // var newInput = '<div class="input-container"><input type="text" name="dynamicInput[]" class="dynamic-input" /><button type="button" class="remove-input">Remove</button></div>';
                var newInput =
                    `
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

            // modal form code
            let selectedWallet = "0";
            let selectedCard = "0";
            let dataObj;
            let bgColor;
            let nameText;
            let labelColor;
            // let logoImageFile = "https://i.postimg.cc/HxbDRWwt/placeholder.png";
            // let heroImageFile = "https://i.postimg.cc/HxbDRWwt/placeholder.png";
            let logoImageFile = "https://i.postimg.cc/L6FjZjHs/sidebar-logo.png";
            let heroImageFile = "https://i.postimg.cc/4dzxC7j8/hero-image.png";


            $("#card-name").parent("div").hide();
            $('#template-add-icon').click(function() {
                $("#modalLable").text("Create Default Template");
                $('#modal').modal('show');

                // template select
                $("#templateSelector").hide();
            });

            // showInputs(selectedWallet, selectedCard);

            $("#wallet").on("change", function() {
                selectedWallet = $(this).val();

                showInputs(selectedWallet, selectedCard);
            });

            $("#card").on("change", function() {
                selectedCard = $(this).val();

                showInputs(selectedWallet, selectedCard);
            });

            function showInputs(walletId, cardId) {
                let parentElement = $(".ll-wallet-data-form .modal-body > .col-7");

                if (walletId == 0) {
                    $(".apple-member-card, .apple-store-or-coupon-card").remove();

                    if ((cardId == 0) || (cardId == 1) || (cardId == 2)) {
                        const html = `<div class="google-card ${cardId == 0 ? "store-card" : cardId == 1 ? "generic-pass" : "coupon"}">
                            <div class="d-flex justify-content-between align-item-center">
                                <img id="logo-image" src="${logoImageFile ? logoImageFile : ""}" alt="Logo">
                                <h6 id="nameText">${nameText ? nameText : ""}</h6>
                            </div>

                            <div class="">
                                <div class="first-row grid grid-cols-3 gap-4">
                                    <div class="">
                                        <label class="input-label" contenteditable="true">First Name</label>
                                        <input type="text" id="first-row-first-element" name="first-row-first-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="">
                                        <label class="input-label" contenteditable="true">Last Name</label>
                                        <input type="text" id="first-row-second-element" name="first-row-second-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="">
                                        <label class="input-label" contenteditable="true">Username</label>
                                        <input type="text" id="first-row-third-element" name="first-row-third-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>
                                </div>

                                <div class="second-row grid grid-cols-3 gap-4">
                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Email</label>
                                        <input type="text" id="second-row-first-element" name="second-row-first-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Mobile</label>
                                        <input type="text" id="second-row-second-element" name="second-row-second-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Address Line 1</label>
                                        <input type="text" id="second-row-third-element" name="second-row-third-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>
                                </div>

                                <div class="third-row grid grid-cols-3 gap-4">
                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Address Line 2</label>
                                        <input type="text" id="third-row-first-element" name="third-row-first-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Postal Code</label>
                                        <input type="text" id="third-row-second-element" name="third-row-second-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">City</label>
                                        <input type="text" id="third-row-third-element" name="third-row-third-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>
                                </div>

                                <div class="fourth-row grid grid-cols-3 gap-4">
                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">District</label>
                                        <input type="text" id="fourth-row-first-element" name="fourth-row-first-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Country</label>
                                        <input type="text" id="fourth-row-second-element" name="fourth-row-second-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="mt-4">
                                        <label class="input-label" contenteditable="true">Nationality</label>
                                        <input type="text" id="fourth-row-third-element" name="fourth-row-third-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>
                                </div>
                            </div>

                            <div class="hero-image-container"><img id="hero-image" src="${heroImageFile ? heroImageFile : ""}" alt="Hero Image"></div>
                        </div>`;

                        $(".google-card").remove();
                        $("#logo-image").remove();
                        $("#hero-image").remove();
                        $("#nameText").remove();

                        parentElement.append(html);
                        $(".apple-store-or-coupon-card, .apple-member-card, .google-card").css("background-color",
                            bgColor);
                        $(".apple-store-or-coupon-card > div label, .apple-member-card > div label, .google-card > div label, #nameText, .ll-wallet-data-form img")
                            .css("color", labelColor);

                        $("#card-name").parent("div").hide();
                        $("#nameText").text("");

                    } else {
                        $(".google-card").remove();
                    }

                } else if (walletId == 1) {
                    if ((cardId == 0) || (cardId == 2)) {
                        $(".google-card").remove();

                        const storeOrCouponCardHtml = `<div class="apple-store-or-coupon-card ${cardId == 0 ? "store-card" : cardId == 2 ? "coupon" : ""}">
                            <div class="d-flex justify-content-between align-item-center">
                                <img id="logo-image" src="${logoImageFile ? logoImageFile : ""}" alt="Logo">
                                <h6 id="nameText">${nameText ? nameText : ""}</h6>
                            </div>

                            <div class="hero-image-container">
                                <img id="hero-image" src="${heroImageFile ? heroImageFile : ""}" alt="Hero Image">
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="">
                                    <label class="input-label" contenteditable="true">First Name</label>
                                    <input type="text" id="first-row-first-element" name="first-row-first-element"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" x-bind:type="input">
                                </div>

                                <div class="">
                                    <label class="input-label" contenteditable="true">Last Name</label>
                                    <input type="text" id="first-row-second-element" name="first-row-second-element"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" x-bind:type="input">
                                </div>

                                <div class="">
                                    <label class="input-label" contenteditable="true">Username</label>
                                    <input type="text" id="first-row-third-element" name="first-row-third-element"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" x-bind:type="input">
                                </div>
                            </div>
                        </div>`;

                        $("#logo-image").remove();
                        $("#hero-image").remove();
                        $("#nameText").remove();
                        $(".apple-member-card, .apple-store-or-coupon-card").remove();

                        parentElement.append(storeOrCouponCardHtml);
                        $(".apple-store-or-coupon-card, .apple-member-card, .google-card").css("background-color",
                            bgColor);
                        $(".apple-store-or-coupon-card > div label, .apple-member-card > div label, .google-card > div label, #nameText, .ll-wallet-data-form img")
                            .css("color", labelColor);
                        $("#card-name").parent("div").show();

                        if (cardId == 0) {
                            $("#nameText").text("");
                            $("#card-name").val("");
                            $("#card-name").attr("type", "number");
                            $("#card-name").prev().text("Points");
                        } else if (cardId == 2){
                            $("#nameText").text("");
                            $("#card-name").val("");
                            $("#card-name").attr("type", "date");
                            $("#card-name").prev().text("Expiry Date");
                        }


                    } else if (cardId == 1) {
                        $(".google-card").remove();

                        const memberCardHtml = `<div class="apple-member-card generic-pass">
                            <div class="d-flex justify-content-between align-item-center">
                                <img id="logo-image" src="${logoImageFile ? logoImageFile : ""}" alt="Logo">
                                <h6 id="nameText">${nameText ? nameText : ""}</h6>
                            </div>
                            <div>
                                <div class="first-row d-flex justify-content-between align-item-center">
                                    <div class="">
                                        <label class="input-label" contenteditable="true">First Name</label>
                                        <input type="text" id="first-row-first-element" name="first-row-first-element"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="" x-bind:type="input">
                                    </div>

                                    <div class="hero-image-container">
                                        <img id="hero-image" src="${heroImageFile ? heroImageFile : ""}" alt="Hero Image">
                                    </div>
                                </div>

                                <div class="">
                                    <div class="second-row grid grid-cols-3 gap-4">
                                        <div class="mt-4">
                                            <label class="input-label" contenteditable="true">Last Name</label>
                                            <input type="text" id="second-row-first-element" name="second-row-first-element"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" x-bind:type="input">
                                        </div>

                                        <div class="mt-4">
                                            <label class="input-label" contenteditable="true">Username</label>
                                            <input type="text" id="second-row-second-element" name="second-row-second-element"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" x-bind:type="input">
                                        </div>

                                        <div class="mt-4">
                                            <label class="input-label" contenteditable="true">Email</label>
                                            <input type="text" id="second-row-third-element" name="second-row-third-element"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" x-bind:type="input">
                                        </div>
                                    </div>

                                    <div class="third-row grid grid-cols-3 gap-4">
                                        <div class="mt-4">
                                            <label class="input-label" contenteditable="true">Mobile</label>
                                            <input type="text" id="third-row-first-element" name="third-row-first-element"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" x-bind:type="input">
                                        </div>

                                        <div class="mt-4">
                                            <label class="input-label" contenteditable="true">Address Line 1</label>
                                            <input type="text" id="third-row-second-element" name="third-row-second-element"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" x-bind:type="input">
                                        </div>

                                        <div class="mt-4">
                                            <label class="input-label" contenteditable="true">Address Line 2</label>
                                            <input type="text" id="third-row-third-element" name="third-row-third-element"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" x-bind:type="input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                        $("#logo-image").remove();
                        $("#hero-image").remove();
                        $("#nameText").remove();
                        $(".apple-member-card, .apple-store-or-coupon-card").remove();

                        parentElement.append(memberCardHtml);
                        $(".apple-store-or-coupon-card, .apple-member-card, .google-card").css("background-color",
                            bgColor);
                        $(".apple-store-or-coupon-card > div label, .apple-member-card > div label, .google-card > div label, #nameText, .ll-wallet-data-form img")
                            .css("color", labelColor);
                        $("#card-name").parent("div").show();

                        $("#nameText").text("");
                        $("#card-name").val("");
                        $("#card-name").attr("type", "number");
                        $("#card-name").prev().text("Points");

                    } else {
                        $(".apple-member-card, .apple-store-or-coupon-card").remove();
                    }
                }
            }


            // save wallet and card data
            $("#save-wallet-data").on("click", function() {
                // get data based on condition
                if (selectedWallet == 0) {
                    dataObj = {};

                    dataObj = {
                        waletType: selectedWallet,
                        cardType: selectedCard,
                        firstRowFirstElementLabel: $("#first-row-first-element").prev().text(),
                        firstRowFirstElementVal: $("#first-row-first-element").val(),
                        firstRowSecondElementLabel: $("#first-row-second-element").prev().text(),
                        firstRowSecondElementVal: $("#first-row-second-element").val(),
                        firstRowThirdElementLabel: $("#first-row-third-element").prev().text(),
                        firstRowThirdElementVal: $("#first-row-third-element").val(),
                        secondRowFirstElementLabel: $("#second-row-first-element").prev().text(),
                        secondRowFirstElementVal: $("#second-row-first-element").val(),
                        secondRowSecondElementLabel: $("#second-row-second-element").prev().text(),
                        secondRowSecondElementVal: $("#second-row-second-element").val(),
                        secondRowThirdElementLabel: $("#second-row-third-element").prev().text(),
                        secondRowThirdElementVal: $("#second-row-third-element").val(),
                        thirdRowFirstElementLabel: $("#third-row-first-element").prev().text(),
                        thirdRowFirstElementVal: $("#third-row-first-element").val(),
                        thirdRowSecondlementLabel: $("#third-row-second-element").prev().text(),
                        thirdRowSecondlementVal: $("#third-row-second-element").val(),
                        thirdRowThirdElementLabel: $("#third-row-third-element").prev().text(),
                        thirdRowThirdElementVal: $("#third-row-third-element").val(),
                        fourthRowFirstElementLabel: $("#fourth-row-first-element").prev().text(),
                        fourthRowFirstElementVal: $("#fourth-row-first-element").val(),
                        fourthRowSecondElementLabel: $("#fourth-row-second-element").prev().text(),
                        fourthRowSecondElementVal: $("#fourth-row-second-element").val(),
                        fourthRowThirdElementLabel: $("#fourth-row-third-element").prev().text(),
                        fourthRowThirdElementVal: $("#fourth-row-third-element").val(),
                        cardNameLabel: $("#card-name").prev().text(),
                        cardNameVal: $("#card-name").val(),
                        logoLabel: $("#logo").prev().text(),
                        logoVal: $("#logo").val(),
                        heroImageLabel: $("#hero-img-input").prev().text(),
                        heroImageVal: $("#hero-img-input").val(),
                        backgroundColorLabel: $("#background-color").prev().text(),
                        backgroundColorVal: $("#background-color").val(),
                        labelColorLabel: $("#label-color").prev().text(),
                        labelColorVal: $("#label-color").val(),
                        uploadedLogo: logoImageFile.startsWith('data:image') ? logoImageFile : "",
                        upladedHeroImg: heroImageFile.startsWith('data:image') ? heroImageFile : "",
                    };
                } else if (selectedWallet == 1) {
                    if ((selectedCard == 0) || (selectedCard == 2)) {
                        dataObj = {};

                        dataObj = {
                            waletType: selectedWallet,
                            cardType: selectedCard,
                            firstRowFirstElementLabel: $("#first-row-first-element").prev().text(),
                            firstRowFirstElementVal: $("#first-row-first-element").val(),
                            firstRowSecondElementLabel: $("#first-row-second-element").prev().text(),
                            firstRowSecondElementVal: $("#first-row-second-element").val(),
                            firstRowThirdElementLabel: $("#first-row-third-element").prev().text(),
                            firstRowThirdElementVal: $("#first-row-third-element").val(),
                            cardNameLabel: $("#card-name").prev().text(),
                            cardNameVal: $("#card-name").val(),
                            logoLabel: $("#logo").prev().text(),
                            logoVal: $("#logo").val(),
                            heroImageLabel: $("#hero-img-input").prev().text(),
                            heroImageVal: $("#hero-img-input").val(),
                            backgroundColorLabel: $("#background-color").prev().text(),
                            backgroundColorVal: $("#background-color").val(),
                            labelColorLabel: $("#label-color").prev().text(),
                            labelColorVal: $("#label-color").val(),
                            uploadedLogo: logoImageFile.startsWith('data:image') ? logoImageFile : "",
                            upladedHeroImg: heroImageFile.startsWith('data:image') ? heroImageFile : ""
                        };
                    } else if (selectedCard == 1) {
                        dataObj = {};

                        dataObj = {
                            waletType: selectedWallet,
                            cardType: selectedCard,
                            firstRowFirstElementLabel: $("#first-row-first-element").prev().text(),
                            firstRowFirstElementVal: $("#first-row-first-element").val(),
                            secondRowFirstElementLabel: $("#second-row-first-element").prev().text(),
                            secondRowFirstElementVal: $("#second-row-first-element").val(),
                            secondRowSecondElementLabel: $("#second-row-second-element").prev().text(),
                            secondRowSecondElementVal: $("#second-row-second-element").val(),
                            secondRowThirdElementLabel: $("#second-row-third-element").prev().text(),
                            secondRowThirdElementVal: $("#second-row-third-element").val(),
                            thirdRowFirstElementLabel: $("#third-row-first-element").prev().text(),
                            thirdRowFirstElementVal: $("#third-row-first-element").val(),
                            thirdRowSecondElementLabel: $("#third-row-second-element").prev().text(),
                            thirdRowSecondElementVal: $("#third-row-second-element").val(),
                            thirdRowThirdElementLabel: $("#third-row-third-element").prev().text(),
                            thirdRowThirdElementVal: $("#third-row-third-element").val(),
                            cardNameLabel: $("#card-name").prev().text(),
                            cardNameVal: $("#card-name").val(),
                            logoLabel: $("#logo").prev().text(),
                            logoVal: $("#logo").val(),
                            heroImageLabel: $("#hero-img-input").prev().text(),
                            heroImageVal: $("#hero-img-input").val(),
                            backgroundColorLabel: $("#background-color").prev().text(),
                            backgroundColorVal: $("#background-color").val(),
                            labelColorLabel: $("#label-color").prev().text(),
                            labelColorVal: $("#label-color").val(),
                            uploadedLogo: logoImageFile.startsWith('data:image') ? logoImageFile : "",
                            upladedHeroImg: heroImageFile.startsWith('data:image') ? heroImageFile : ""

                        };
                    }
                }

                // pass the data to textarea
                $('#wallet_obj').val(JSON.stringify(dataObj));
            });

            // change background color
            $("#background-color").on("change", function() {
                bgColor = $(this).val();
                $(".apple-store-or-coupon-card, .apple-member-card, .google-card").css("background-color",
                    bgColor);
            });

            // change name
            $("#card-name").on("change", function() {
                nameText = $(this).val();
                $("#nameText").text(nameText);
                $("#nameText").css({border: "1px dashed", height: "fit-content", padding: "15px"});
            });

            // change background color
            $("#label-color").on("change", function() {
                labelColor = $(this).val();
                $(".apple-store-or-coupon-card > div label, .apple-member-card > div label, .google-card > div label, #nameText, .ll-wallet-data-form img, .first-row, .second-row, .third-row, .fourth-row")
                    .css("color", labelColor);
            });

            // logo image show
            $("#logo").change(function(event) {
                handleImageUpload(event.target, "logo", $('#logo-image'));
            });

            // hero image show
            $("#hero-img-input").change(function(event) {
                handleImageUpload(event.target, "hero", $('#hero-image'));
            });

            $('#templateSelector').change(function() {
                // hide create button
                $("#template-add-icon").hide();
                $("#modalLable").text("Modify Template");

                let selectedOption = $(this).children("option:selected");
                let templateInfo = selectedOption.val().split('|');
                let templateId = templateInfo[0];
                let passType = templateInfo[1];

                // Make AJAX request
                $.ajax({
                    url: 'https://keoswalletapi.luminousdemo.com/api/get-data-from-loyalty-for-edit',
                    type: 'get',
                    data: {
                        pass_id: templateId,
                        pass_type: passType,
                    },
                    success: function(response) {
                        // Handle the response data here
                        $('#template_response_obj').val(JSON.stringify(response));

                        const parsedData = JSON.parse(response.pass_data);
                        const passType = response.pass_type;
                        const activeCardName = passType == "google" ? parsedData.passDetails.activeCardName : Object.keys(parsedData)[0];

                        selectedWallet = passType == "google" ? 0 : 1;
                        selectedCard = activeCardName == "StoreCard" ? 0 : (activeCardName == "GenericPass" ? 1 : 2);

                        // show modal
                        $('#modal').modal('show');

                        // create card
                        showInputs(selectedWallet, selectedCard);

                        // set values
                        $("#wallet").val(selectedWallet);
                        $("#card").val(selectedCard);
                        $("#wallet").prop('disabled', true);
                        $("#card").prop('disabled', true);

                        if (passType == "google") {
                            // first row field hide show
                            if (parsedData.textModulesData["firstRowData"].length) {
                                $("#first-row-first-element").parent("div").hide();
                                $("#first-row-second-element").parent("div").hide();
                                $("#first-row-third-element").parent("div").hide();

                                if (typeof parsedData.textModulesData["firstRowData"][0] != 'undefined') {
                                    $("#first-row-first-element").parent("div").show();
                                    $("#first-row-first-element").parent("div").addClass("user-filled-field");
                                    $("#first-row-first-element").prev().text(parsedData?.textModulesData['firstRowData'][0]?.label?.value);
                                    $("#first-row-first-element").val(parsedData?.textModulesData['firstRowData'][0]?.displayValue?.value);

                                }

                                if (typeof parsedData.textModulesData["firstRowData"][1] != 'undefined') {
                                    $("#first-row-second-element").parent("div").show();
                                    $("#first-row-second-element").parent("div").addClass("user-filled-field");
                                    $("#first-row-second-element").prev().text(parsedData?.textModulesData['firstRowData'][1]?.label?.value);
                                    $("#first-row-second-element").val(parsedData?.textModulesData['firstRowData'][1]?.displayValue?.value);

                                }

                                if (typeof parsedData.textModulesData["firstRowData"][2] != 'undefined') {
                                    $("#first-row-third-element").parent("div").show();
                                    $("#first-row-third-element").parent("div").addClass("user-filled-field");
                                    $("#first-row-third-element").prev().text(parsedData?.textModulesData['firstRowData'][2]?.label?.value);
                                    $("#first-row-third-element").val(parsedData?.textModulesData['firstRowData'][2]?.displayValue?.value);
                                }
                            } else{
                                $(".first-row").children("div").hide();
                                $(".first-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                            }

                            // second row field hide show
                            if (parsedData.textModulesData["secondRowData"].length) {
                                $("#second-row-first-element").parent("div").hide();
                                $("#second-row-second-element").parent("div").hide();
                                $("#second-row-third-element").parent("div").hide();

                                if (typeof parsedData.textModulesData["secondRowData"][0] != 'undefined') {
                                    $("#second-row-first-element").parent("div").show();
                                    $("#second-row-first-element").parent("div").addClass("user-filled-field");
                                    $("#second-row-first-element").prev().text(parsedData?.textModulesData['secondRowData'][0]?.label?.value);
                                    $("#second-row-first-element").val(parsedData?.textModulesData['secondRowData'][0]?.displayValue?.value);

                                }

                                if (typeof parsedData.textModulesData["secondRowData"][1] != 'undefined') {
                                    $("#second-row-second-element").parent("div").show();
                                    $("#second-row-second-element").parent("div").addClass("user-filled-field");
                                    $("#second-row-second-element").prev().text(parsedData?.textModulesData['secondRowData'][1]?.label?.value);
                                    $("#second-row-second-element").val(parsedData?.textModulesData['secondRowData'][1]?.displayValue?.value);

                                }

                                if (typeof parsedData.textModulesData["secondRowData"][2] != 'undefined') {
                                    $("#second-row-third-element").parent("div").show();
                                    $("#second-row-third-element").parent("div").addClass("user-filled-field");
                                    $("#second-row-third-element").prev().text(parsedData?.textModulesData['secondRowData'][2]?.label?.value);
                                    $("#second-row-third-element").val(parsedData?.textModulesData['secondRowData'][2]?.displayValue?.value);
                                }
                            } else{
                                $(".second-row").children("div").hide();
                                $(".second-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                            }

                            // third row field hide show
                            if (parsedData.textModulesData["thirdRowData"].length) {
                                $("#third-row-first-element").parent("div").hide();
                                $("#third-row-second-element").parent("div").hide();
                                $("#third-row-third-element").parent("div").hide();

                                if (typeof parsedData.textModulesData["thirdRowData"][0] != 'undefined') {
                                    $("#third-row-first-element").parent("div").show();
                                    $("#third-row-first-element").parent("div").addClass("user-filled-field");
                                    $("#third-row-first-element").prev().text(parsedData?.textModulesData['thirdRowData'][0]?.label?.value);
                                    $("#third-row-first-element").val(parsedData?.textModulesData['thirdRowData'][0]?.displayValue?.value);

                                }

                                if (typeof parsedData.textModulesData["thirdRowData"][1] != 'undefined') {
                                    $("#third-row-second-element").parent("div").show();
                                    $("#third-row-second-element").parent("div").addClass("user-filled-field");
                                    $("#third-row-second-element").prev().text(parsedData?.textModulesData['thirdRowData'][1]?.label?.value);
                                    $("#third-row-second-element").val(parsedData?.textModulesData['thirdRowData'][1]?.displayValue?.value);

                                }

                                if (typeof parsedData.textModulesData["thirdRowData"][2] != 'undefined') {
                                    $("#third-row-third-element").parent("div").show();
                                    $("#third-row-third-element").parent("div").addClass("user-filled-field");
                                    $("#third-row-third-element").prev().text(parsedData?.textModulesData['thirdRowData'][2]?.label?.value);
                                    $("#third-row-third-element").val(parsedData?.textModulesData['thirdRowData'][2]?.displayValue?.value);
                                }
                            } else{
                                $(".third-row").children("div").hide();
                                $(".third-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                            }

                            // fourth row field hide show
                            if (parsedData.textModulesData["fourthRowData"].length) {
                                $("#fourth-row-first-element").parent("div").hide();
                                $("#fourth-row-second-element").parent("div").hide();
                                $("#fourth-row-third-element").parent("div").hide();

                                if (typeof parsedData.textModulesData["fourthRowData"][0] != 'undefined') {
                                    $("#fourth-row-first-element").parent("div").show();
                                    $("#fourth-row-first-element").parent("div").addClass("user-filled-field");
                                    $("#fourth-row-first-element").prev().text(parsedData?.textModulesData['fourthRowData'][0]?.label?.value);
                                    $("#fourth-row-first-element").val(parsedData?.textModulesData['fourthRowData'][0]?.displayValue?.value);
                                }

                                if (typeof parsedData.textModulesData["fourthRowData"][1] != 'undefined') {
                                    $("#fourth-row-second-element").parent("div").show();
                                    $("#fourth-row-second-element").parent("div").addClass("user-filled-field");
                                    $("#fourth-row-second-element").prev().text(parsedData?.textModulesData['fourthRowData'][1]?.label?.value);
                                    $("#fourth-row-second-element").val(parsedData?.textModulesData['fourthRowData'][1]?.displayValue?.value);
                                }

                                if (typeof parsedData.textModulesData["fourthRowData"][2] != 'undefined') {
                                    $("#fourth-row-third-element").parent("div").show();
                                    $("#fourth-row-third-element").parent("div").addClass("user-filled-field");
                                    $("#fourth-row-third-element").prev().text(parsedData?.textModulesData['fourthRowData'][2]?.label?.value);
                                    $("#fourth-row-third-element").val(parsedData?.textModulesData['fourthRowData'][2]?.displayValue?.value);
                                }
                            } else{
                                $(".fourth-row").children("div").hide();
                                $(".fourth-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                            }

                            $(".google-card").css('background-color', parsedData?.passDetails?.color);
                            $(".ll-user-add-form img, #nameText, .user-filled-field label, .first-row, .second-row, .third-row, .fourth-row").css('color', parsedData?.passDetails?.labelColor);
                            $("#background-color").val(parsedData?.passDetails?.color);
                            $("#label-color").val(parsedData?.passDetails?.labelColor);
                        } else{
                            let appleData = selectedCard == 0 ? parsedData.StoreCard : (selectedCard == 1 ? parsedData.GenericPass : parsedData.Coupon);
                            if (selectedCard == 0 || selectedCard == 2) {
                                let appleSecondaryFormData = selectedCard == 0 ? parsedData.StoreCard.secondaryFormsData : parsedData.Coupon.secondaryFormsData;
                                // let appleData = selectedCard == 0 ? parsedData.StoreCard : parsedData.Coupon;

                                // first row field hide show
                                if (appleSecondaryFormData.length) {
                                    $("#first-row-first-element").parent("div").hide();
                                    $("#first-row-second-element").parent("div").hide();
                                    $("#first-row-third-element").parent("div").hide();

                                    if (typeof appleSecondaryFormData[0] != 'undefined') {
                                        $("#first-row-first-element").parent("div").show();
                                        $("#first-row-first-element").parent("div").addClass("user-filled-field");
                                        $("#first-row-first-element").prev().text(appleSecondaryFormData[0]?.label?.value);
                                        $("#first-row-first-element").val(appleSecondaryFormData[0]?.displayValue?.value);

                                    }

                                    if (typeof appleSecondaryFormData[1] != 'undefined') {
                                        $("#first-row-second-element").parent("div").show();
                                        $("#first-row-second-element").parent("div").addClass("user-filled-field");
                                        $("#first-row-second-element").prev().text(appleSecondaryFormData[1]?.label?.value);
                                        $("#first-row-second-element").val(appleSecondaryFormData[1]?.displayValue?.value);

                                    }

                                    if (typeof appleSecondaryFormData[2] != 'undefined') {
                                        $("#first-row-third-element").parent("div").show();
                                        $("#first-row-third-element").parent("div").addClass("user-filled-field");
                                        $("#first-row-third-element").prev().text(appleSecondaryFormData[2]?.label?.value);
                                        $("#first-row-third-element").val(appleSecondaryFormData[2]?.displayValue?.value);
                                    }

                                    if (selectedCard == 0) {
                                        const pointText = parsedData?.StoreCard?.headerFields?.points?.label?.value;
                                        const point = parsedData?.StoreCard?.headerFields?.points?.displayValue?.value;

                                        $("#nameText").text(`${pointText}: ${point}`);
                                        $("#nameText").css({border: "1px dashed", height: "fit-content", padding: "15px"});
                                        $("#card-name").val(point);
                                    } else{
                                        const date = new Date(parsedData?.Coupon?.headerFields?.expiryDate?.displayValue?.value);
                                        const formattedDate = date.toISOString().split('T')[0];
                                        $("#nameText").text(formattedDate);
                                        $("#nameText").css({border: "1px dashed", height: "fit-content", padding: "15px"});
                                        $("#card-name").val(formattedDate);
                                    }
                                } else{
                                    $(".first-row").children("div").hide();
                                    $(".first-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                                }

                            } else {
                                if (Object.keys(parsedData.GenericPass).length != 0) {
                                    // first row field hide show
                                    $(".first-row").children("div").children('label').hide();
                                    $(".first-row").children("div").children('input').hide();
                                    if (typeof parsedData.GenericPass.primaryFormsData != 'undefined') {
                                        $(".first-row").children("div").children('label').show();
                                        $(".first-row").children("div").children('input').show();
                                        $("#first-row-first-element").parent("div").addClass("user-filled-field");
                                        $("#first-row-first-element").prev().text(parsedData.GenericPass.primaryFormsData?.label?.value);
                                        $("#first-row-first-element").val(parsedData.GenericPass.primaryFormsData?.displayValue?.value);

                                    } else{
                                        $(".first-row").children("div").children('label').hide();
                                        $(".first-row").children("div").children('input').hide();
                                        $(".first-row > div:first-child").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                                    }

                                    // second row field hide show
                                    if (parsedData.GenericPass.secondaryFormsData.length) {
                                        $("#second-row-first-element").parent("div").hide();
                                        $("#second-row-second-element").parent("div").hide();
                                        $("#second-row-third-element").parent("div").hide();

                                        if (typeof parsedData.GenericPass.secondaryFormsData[0] != 'undefined') {
                                            $("#second-row-first-element").parent("div").show();
                                            $("#second-row-first-element").parent("div").addClass("user-filled-field");
                                            $("#second-row-first-element").prev().text(parsedData.GenericPass.secondaryFormsData[0]?.label?.value);
                                            $("#second-row-first-element").val(parsedData.GenericPass.secondaryFormsData[0]?.displayValue?.value);
                                        }

                                        if (typeof parsedData.GenericPass.secondaryFormsData[1] != 'undefined') {
                                            $("#second-row-second-element").parent("div").show();
                                            $("#second-row-second-element").parent("div").addClass("user-filled-field");
                                            $("#second-row-second-element").prev().text(parsedData.GenericPass.secondaryFormsData[1]?.label?.value);
                                            $("#second-row-second-element").val(parsedData.GenericPass.secondaryFormsData[1]?.displayValue?.value);
                                        }

                                        if (typeof parsedData.GenericPass.secondaryFormsData[2] != 'undefined') {
                                            $("#second-row-third-element").parent("div").show();
                                            $("#second-row-third-element").parent("div").addClass("user-filled-field");
                                            $("#second-row-third-element").prev().text(parsedData.GenericPass.secondaryFormsData[2]?.label?.value);
                                            $("#second-row-third-element").val(parsedData.GenericPass.secondaryFormsData[2]?.displayValue?.value);
                                        }
                                    } else{
                                        $(".second-row").parent("div").hide();
                                        $(".second-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                                    }

                                    // third row field hide show
                                    if (parsedData.GenericPass.auxiliaryFormsData.length) {
                                        $("#third-row-first-element").parent("div").hide();
                                        $("#third-row-second-element").parent("div").hide();
                                        $("#third-row-third-element").parent("div").hide();

                                        if (typeof parsedData.GenericPass.auxiliaryFormsData[0] != 'undefined') {
                                            $("#third-row-first-element").parent("div").show();
                                            $("#third-row-first-element").parent("div").addClass("user-filled-field");
                                            $("#third-row-first-element").prev().text(parsedData.GenericPass.auxiliaryFormsData[0]?.label?.value);
                                            $("#third-row-first-element").val(parsedData.GenericPass.auxiliaryFormsData[0]?.displayValue?.value);
                                        }

                                        if (typeof parsedData.GenericPass.auxiliaryFormsData[1] != 'undefined') {
                                            $("#third-row-second-element").parent("div").show();
                                            $("#third-row-second-element").parent("div").addClass("user-filled-field");
                                            $("#third-row-second-element").prev().text(parsedData.GenericPass.auxiliaryFormsData[1]?.label?.value);
                                            $("#third-row-second-element").val(parsedData.GenericPass.auxiliaryFormsData[1]?.displayValue?.value);
                                        }

                                        if (typeof parsedData.GenericPass.auxiliaryFormsData[2] != 'undefined') {
                                            $("#third-row-third-element").parent("div").show();
                                            $("#third-row-third-element").parent("div").addClass("user-filled-field");
                                            $("#third-row-third-element").prev().text(parsedData.GenericPass.auxiliaryFormsData[2]?.label?.value);
                                            $("#third-row-third-element").val(parsedData.GenericPass.auxiliaryFormsData[2]?.displayValue?.value);
                                        }
                                    } else{
                                        $(".third-row").parent("div").hide();
                                        $(".third-row").css({'border': '1px dashed', 'height' : '62px', 'margin-top' : '30px'});
                                    }
                                }
                            }

                            $(".google-card, .apple-store-or-coupon-card, .apple-member-card").css('background-color', appleData?.passDetails?.color);
                            $(".ll-user-add-form img, #nameText, .user-filled-field label").css('color', appleData?.passDetails?.labelColor);

                            const appleBgHexColorValue = rgbToHex(parsedData?.Coupon?.passDetails?.color) ? rgbToHex(parsedData?.Coupon?.passDetails?.color) : parsedData?.Coupon?.passDetails?.color;
                            const appleLabelHexColorValue = rgbToHex(parsedData?.Coupon?.passDetails?.labelColor) ? rgbToHex(parsedData?.Coupon?.passDetails?.labelColor) : parsedData?.Coupon?.passDetails?.labelColor;
                            $("#background-color").val(appleBgHexColorValue);
                            $("#label-color").val(appleLabelHexColorValue);

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Function to convert RGB to hexadecimal
            function rgbToHex(rgb) {
                if (!rgb) {
                    return false;
                }

                const match = rgb.match(/\d+/g);
                if (!match || match.length < 3) {
                    return false;
                }

                const [r, g, b] = match;
                const hexR = parseInt(r).toString(16).padStart(2, '0');
                const hexG = parseInt(g).toString(16).padStart(2, '0');
                const hexB = parseInt(b).toString(16).padStart(2, '0');
                return `#${hexR}${hexG}${hexB}`;
            }

            // handle image upload
            function handleImageUpload(inputElement, imageFor, imageElement) {
                let file = inputElement.files[0];
                let imageType = /^image\//;

                if (!imageType.test(file.type)) {
                    alert("Please select an image file.");
                    inputElement.val("");
                    return;
                }

                let reader = new FileReader();
                reader.onload = function(e) {
                    const base64Image = e.target.result;

                    if (imageFor == "logo") {
                        logoImageFile = base64Image;
                    } else {
                        heroImageFile = base64Image;
                    }

                    imageElement.attr("src", e.target.result);
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
@stop
