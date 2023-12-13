@php
if($settings['overrideTitle']) {
    $pageTitle = $settings['overrideTitle'];
} else {
    $pageTitle = trans('common.edit_item_', ['item' => $settings['subject_column'] ? parse_attr($form['data']->{$settings['subject_column']}) : trans('common.item')]) . config('default.page_title_delimiter') . $settings['title'];
}
@endphp
@extends($settings['guard'].'.layouts.default')

@section('page_title', $pageTitle . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <div class="w-full @if (request()->route()->getName() == 'member.data.edit') ll-member-user-settings-page @endif" @onclicktab="window.appSetImageUploadHeight()">
        <div class="relative p-4 lg:p-6">
            <div
                class="mb-3">
                <div class="w-full flex flex-row items-center justify-between">
                    @if($settings['list'])
                        <div class="mb-5">
                            <a href="{{ route($settings['guard'].'.data.list', ['name' => $dataDefinition->name]) }}"
                                class="ll-back-btn w-fit flex text-sm items-center justify-start">
                                <x-ui.icon icon="left" class="mr-2" />
                                {{ trans('common.back_to_list') }}
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-row items-center justify-end">
                        @if ($settings['view'])
                            <a href="{{ route($settings['guard'].'.data.view', ['name' => $dataDefinition->name, 'id' => $form['data']->id]) }}"
                                class="w-full flex items-center btn-sm text-sm mr-2 btn-primary ll-primary-btn">
                                <x-ui.icon class="h-3.5 w-3.5 mr-2" icon="magnifying-glass" />
                                {{ trans('common.view') }}
                            </a>
                        @endif
                        @if ($settings['delete'])
                            <button type="button" class="w-full flex items-center btn-sm btn-danger text-sm ll-danger-btn"
                                @click="deleteItem('{{ $form['data']->id }}', '{{ $settings['subject_column'] ? str_replace("'", "\'", parse_attr($form['data']->{$settings['subject_column']})) : null }}')">
                                <x-ui.icon class="h-3.5 w-3.5 mr-2" icon="trash" />
                                {{ trans('common.delete') }}
                            </button>
                        @endif
                    </div>
                </div>

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
                        <div class="text-gray-400 font-medium">
                            {{ trans('common.edit_item_', ['item' => $settings['subject_column'] ? parse_attr($form['data']->{$settings['subject_column']}) : trans('common.item')]) }}
                        </div>
                    @endif
                </div>
            </div>
            @php
            $hasTabs = !empty($form['tabs']);
            @endphp
            <div class="">
                <x-forms.messages class="mt-4" />
                <x-forms.form-open
                    :novalidate="$hasTabs"
                    action="{{ route($settings['guard'].'.data.edit.post', ['name' => $dataDefinition->name, 'id' => $form['data']->id]) }}"
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
                            
                            @if ($settings['editRequiresPassword'])
                                <x-forms.input 
                                    value=""
                                    class="ll-input-container change-password mb-7"
                                    class-label=""
                                    type="password"
                                    name="current_password_required_to_save_changes"
                                    icon="key"
                                    :label="trans('common.current_password_to_save_changes')"
                                    :placeholder="trans('common.current_password')"
                                    :required="true"
                                />
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
                                <button type="submit" class="w-full btn-primary ll-primary-btn" onmouseover="this.style.background='blue';" onmouseout="this.style.background='#2963FF';">{{ trans('common.save_changes') }}<span
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

    @if ($settings['delete'])
        <script>
            function deleteItem(id, item) {
                if (item == null) item = "{{ trans('common.this_item') }}";

                appConfirm('{{ trans('common.confirm_deletion') }}', _lang.delete_confirmation_text.replace(":item",
                    '<strong>' + item + '</strong>'), {
                    'btnConfirm': {
                        'click': function() {
                            // Submit form
                            const form = document.getElementById('formDataDefinition');
                            form.action =
                                '{{ route($settings['guard'].'.data.delete.post', ['name' => $dataDefinition->name]) }}/' + id;
                            form.submit();
                        }
                    }
                });
            }
        </script>
    @endif
    
    <script>
        $(document).ready(function() {
            const pathsToCheck = ["/networks/edit"];
            const partnersPath = ["/partners/edit"];
            const accountEditPath = ["/manage/account/edit"];
            const clubsEditPath = ["/partner/manage/clubs/edit"];
            const partnerRewardsEditPath = ["/partner/manage/rewards/edit"];
            const partnerCardEditPath = ["/partner/manage/cards/edit", "/partner/manage/rewards/edit"];
            const currentPathname = window.location.pathname;

            const formInputs = $('.ll-user-add-form-inputs');
            const childDivs = formInputs.children('div');
            const divCount = childDivs.length;
            
            if (pathsToCheck.some(path => currentPathname.includes(path))) {
                $('body').addClass('networks-edit-page');
            }else if (clubsEditPath.some(path => currentPathname.includes(path))) {
                $('body').addClass('partner-club-insert-page');
            }else if (partnerCardEditPath.some(path => currentPathname.includes(path))) {
                $('body').addClass('partner-card-insert-page');

                if (partnerRewardsEditPath.some(path => currentPathname.includes(path))) {
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
            } else{
                if(partnersPath.some(path => currentPathname.includes(path))){
                    $('body').addClass('partners-add-page');
                }
                
                if (accountEditPath.some(path => currentPathname.includes(path))){
                    $('body').addClass('account-edit-page');
                }

                childDivs.eq(0).wrap('<div class="ll-upload-img"></div>');

                const secondDivCount = Math.floor((divCount - 1) / 2);
                const thirdDivCount = divCount - 1 - secondDivCount;

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
