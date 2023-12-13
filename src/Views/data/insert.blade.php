@php
if($settings['overrideTitle']) {
    $pageTitle = $settings['overrideTitle'];
} else {
    $pageTitle = trans('common.add_item') . config('default.page_title_delimiter') . $settings['title'];
}
@endphp

@extends($settings['guard'].'.layouts.default')

@section('page_title', $pageTitle . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <div class="w-full" @onclicktab="window.appSetImageUploadHeight()">
        <div class="relative p-4 lg:p-6">
            <div
                class="mb-3">
                @if($settings['list'])
                    <div class="mb-5">
                        <a href="{{ route($settings['guard'].'.data.list', ['name' => $dataDefinition->name]) }}"
                            class="ll-back-btn w-fit flex text-sm items-center justify-start">
                            <x-ui.icon icon="left" class="mr-2" />
                            {{ trans('common.back_to_list') }}
                        </a>
                    </div>
                @endif
                <div class="w-full flex items-center space-x-3">
                    @if($settings['list'])
                        <a href="{{ route($settings['guard'].'.data.list', ['name' => $dataDefinition->name]) }}">
                    @endif
                        <h5 class="dark:text-white font-semibold flex items-center">
                            @if($settings['icon'])
                                <x-ui.icon :icon="$settings['icon']" class="inline-block w-5 h-5 mr-2 dark:text-white" />
                            @endif
                            @if($settings['overrideTitle'])
                                {!! $settings['overrideTitle'] !!}
                            @else
                                {!! $settings['title'] !!}
                            @endif
                        </h5>
                    @if($settings['list'])
                        </a>
                    @endif
                    @if(!$settings['overrideTitle'])
                        <div class="text-gray-400 font-medium">{{ trans('common.add_item') }}</div>
                    @endif
                </div>
            </div>
            @php
            $hasTabs = !empty($form['tabs']);
            @endphp
            <div>
                <x-forms.messages class="mt-4" />
                <x-forms.form-open :novalidate="$hasTabs" action="{{ route($settings['guard'].'.data.insert.post', ['name' => $dataDefinition->name]) }}"
                    enctype="multipart/form-data" id="formDataDefinition" method="POST" class="ll-user-add-form space-y-4 md:space-y-6" />
                @if ($form['columns'])
                    <div class="ll-user-add-form-inputs grid grid-cols-7 gap-x-5">
                        @if($hasTabs)
                            <x-ui.tabs :tabs="array_values($form['tabs'])" active-tab="1" class="ll-tab-content-container space-y-4 md:space-y-6 py-6">
                            @php
                            $previousTab = null;
                            @endphp
                            
                            @foreach ($form['columns'] as $column)
                                @if (!$column['hidden'])
                                    @if($column['tab'] && $column['tab'] !== $previousTab)
                                        @if($previousTab !== null)
                                            </x-slot>
                                        @endif
                                        <x-slot :name="$column['tab']">
                                    @endif

                                    @if($column['container_start::insert'])
                                        <div class="{{ $column['container_start::insert'] }}">
                                    @endif
                                    @if($column['classes::insert'])
                                        <div class="{{ $column['classes::insert'] }}">
                                    @endif
                                    @include('data.form', compact('form', 'column'))
                                    @if($column['classes::insert'])
                                        </div>
                                    @endif
                                    @if($column['container_end::insert'])
                                        </div>
                                    @endif
                                    @php
                                    $previousTab = $column['tab'];
                                    @endphp
                                @endif
                            @endforeach
                                </x-slot>
                            </x-ui.tabs>
                        @else
                            @foreach ($form['columns'] as $column)
                                @if (!$column['hidden'])
                                    @if($column['container_start::insert'])
                                        <div class="{{ $column['container_start::insert'] }}">
                                    @endif
                                    @if($column['classes::insert'])
                                        <div class="{{ $column['classes::insert'] }}">
                                    @endif
                                    <!-- Include From  -->
                                    @include('data.form', compact('form', 'column'))

                                    @if($column['classes::insert'])
                                        </div>
                                    @endif
                                    @if($column['container_end::insert'])
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-7 ll-user-add-form-footer gap-x-5">
                        <div class="ll-user-form-empty"></div>
                        @if($settings['list'])
                            <div class="col-span-3">
                                <a href="{{ route($settings['guard'].'.data.list', ['name' => $dataDefinition->name]) }}"
                                class="btn w-full ll-btn-white">{{ trans('common.cancel') }}</a>
                            </div>
                        @endif
                        <div class="col-span-3">
                            <button type="submit" class="w-full btn-primary ll-primary-btn">{{ trans('common.create') }}<span
                                class="form-dirty hidden">&nbsp;*</span></button>
                        </div>
                    </div>
                @endif
                <x-forms.form-close />
                @if (session('current_tab_index'))
                    <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        window.openTab({{ session('current_tab_index') }});
                    });
                    </script>
                @endif
                @if ($errors->any())
                    <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        window.openTabWithInvalidElement();
                    });
                    </script>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const networksPath = ["/networks/insert"];
            const partnersInsertPath = ["/partners/insert"];
            const clubsInsertPath = ["/partner/manage/clubs/insert"];
            const partnerRewardsInsertPath = ["/partner/manage/rewards/insert"];
            const partnerCardInsertPath = ["/partner/manage/cards/insert", "/partner/manage/rewards/insert"];
            const currentPathname = window.location.pathname;

            if (networksPath.some(path => currentPathname.includes(path))) {
                $('body').addClass('networks-edit-page');
            } else if (clubsInsertPath.some(path => currentPathname.includes(path))) {
                $('body').addClass('partner-club-insert-page');
            } else if (partnerCardInsertPath.some(path => currentPathname.includes(path))) {
                $('body').addClass('partner-card-insert-page');

                if (partnerRewardsInsertPath.some(path => currentPathname.includes(path))) {
                    $('body').addClass('partner-rewards-insert-page');
                }
                
                function wrapChildrenWithClass(containerIndex, startSlice, endSlice, wrapperClass) {
                    var targetContainer = $(".ll-tab-content-container:eq(" + containerIndex + ")");
                    var targetChildren = targetContainer.children().slice(startSlice, endSlice);

                    targetChildren.wrapAll("<div class='" + wrapperClass + "'></div>");
                }

                // details tab start
                wrapChildrenWithClass(0, 0, 2, 'grid grid-cols-2 gap-4');
                wrapChildrenWithClass(0, 2, 4, 'grid grid-cols-2 gap-4');
                // wrapChildrenWithClass(0, 3, 5, 'grid grid-cols-2 gap-4');
                // details tab end

                // rules tab start
                // if ($(".ll-tab-content-container:eq(1)").children('div').hasClass('grid grid-cols-3 gap-4')) {
                //     $(".ll-tab-content-container:eq(1)").children('div').removeClass('grid grid-cols-3 gap-4');
                // }
                
                wrapChildrenWithClass(1, 0, 2, 'grid grid-cols-2 gap-4');
                // wrapChildrenWithClass(1, 2, 4, 'grid grid-cols-2 gap-4');
                // rules tab end

                // card text tab start
                wrapChildrenWithClass(2, 0, 3, 'grid grid-cols-2 gap-4');
                // card text tab end

                // contact tab start
                wrapChildrenWithClass(3, 0, 4, 'grid grid-cols-2 gap-4');
                // contact text tab end
            }else {
                if(partnersInsertPath.some(path => currentPathname.includes(path))){
                    $('body').addClass('partners-add-page');
                }

                const formInputs = $('.ll-user-add-form-inputs');
                const childDivs = formInputs.children('div');
                const divCount = childDivs.length;

                // Select the first div inside the form and wrap it with a new div
                childDivs.eq(0).wrap('<div class="ll-upload-img"></div>');

                // Calculate the number of divs for the second and third divs
                const secondDivCount = Math.floor((divCount - 1) / 2);
                const thirdDivCount = divCount - 1 - secondDivCount;

                // Select the remaining div elements and wrap them in two separate divs
                childDivs.slice(1, secondDivCount + 1).wrapAll('<div class="col-span-3"></div>');
                childDivs.slice(secondDivCount + 1).wrapAll('<div class="col-span-3"></div>');
            }


            // change checkbox to switch
            const checkboxElement = $('.ll-user-add-form-inputs').find('input[type="checkbox"]');
            const checkboxHtml = $(`<label class="ll-slider"></label>`);

            checkboxElement.removeClass('w-4 h-4 bg-gray-100 rounded border-gray-300  focus:ring-blue-100 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600');

            checkboxElement.wrap('<div class="ll-switch"></div>');
            checkboxElement.after(checkboxHtml);

            $('.ll-slider').on('click', function(){
                const hiddenInput = $(this).closest('.ll-switch').prev('input[type="hidden"]');
                const mainCheckbox = $(this).closest('.ll-switch').find('input[type="checkbox"]');
                
                if (hiddenInput.val() == 1) {
                    hiddenInput.val(0);
                    mainCheckbox.prop('checked', false);
                } else{
                    hiddenInput.val(1);
                    mainCheckbox.prop('checked', true);
                }
            });
        });
    </script>
@stop
