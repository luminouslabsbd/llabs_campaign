@php
if($settings['overrideTitle']) {
    $pageTitle = $settings['overrideTitle'];
} else {
    $pageTitle = trans('common.view_item_', ['item' => $settings['subject_column'] ? parse_attr($form['data']->{$settings['subject_column']}) : trans('common.item')]) .
    config('default.page_title_delimiter') . $settings['title'];
}
@endphp

@extends($settings['guard'].'.layouts.default')

@section('page_title', $pageTitle . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
    <div class="w-full">
        <div class="relative p-4 lg:p-6">
            <div class="mb-5">
                <div class="w-full flex flex-row items-center justify-between">
                    @if($settings['list'])
                        <div class="mb-5">
                            <a href="{{ route($settings['guard'].'.data.list', ['name' => $dataDefinition->name]) }}"
                                class="ll-back-btn w-fit flex text-sm items-center justify-start">
                                <x-ui.icon icon="left" class="h-3.5 w-3.5 mr-2" />
                                {{ trans('common.back_to_list') }}
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-row items-center justify-end">
                        @if ($settings['edit'])
                            <a href="{{ route($settings['guard'].'.data.edit', ['name' => $dataDefinition->name, 'id' => $form['data']->id]) }}"
                                class="w-full flex items-center btn-sm text-sm mr-2 btn-warning ll-warning-btn">
                                <x-ui.icon class="h-3.5 w-3.5 mr-2" icon="pencil" />
                                {{ trans('common.edit') }}
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
                            {{ trans('common.view_item_', ['item' => $settings['subject_column'] ? parse_attr($form['data']->{$settings['subject_column']}) : trans('common.item')]) }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="ll-user-view-page flex items-center gap-x-4">
                @if ($form['columns'])
                    @foreach ($form['columns'] as $column)
                        @if (!$column['hidden'])
                            @if($column['container_start::view'])
                                <div class="{{ $column['container_start::view'] }}">
                            @endif
                            @if($column['classes::view'])
                                <div class="{{ $column['classes::view'] }}">
                            @endif
                            <div class="mb-2">
                                <div class="ll-label {{($column['type'] == 'image' || $column['type'] == 'avatar') ? 'hidden' : ''}}"><span>{{ $column['text'] }}</span><span>:</span></div>
                                @if ($column['type'] == 'image' || $column['type'] == 'avatar')
                                    @if ($form['data']->{$column['name']})
                                        <script>
                                            let imgModalSrc_{{ $column['name'] }} = "{{ $form['data']->{$column['name']} }}";
                                            let imgModalDesc_{{ $column['name'] }} = "{{ parse_attr($column['text']) }}";
                                        </script>
                                        <a @click="$dispatch('img-modal', {  imgModalSrc: imgModalSrc_{{ $column['name'] }}, imgModalDesc: imgModalDesc_{{ $column['name'] }} })"
                                            class="cursor-pointer w-fit inline-block">
                                            <img src="{{ $form['data']->{$column['name']} !== null && $column['conversion'] !== null ? $form['data']->{$column['name'] . '-' . $column['conversion']} : $form['data']->{$column['name']} }}"
                                                alt="{{ parse_attr($column['text']) }}"
                                                class="h-auto max-w-xs {{ $column['type'] == 'avatar' ? 'rounded-[43px] ll-user-view-avatar' : 'rounded-lg' }}">
                                        </a>
                                    @else
                                        <x-ui.icon icon="no-symbol" class="w-5 h-5" />
                                    @endif
                                @elseif (in_array($column['type'], ['date_time']))
                                    <span class="format-date-time">{!! $form['data']->{$column['name']} !!}</span>
                                @elseif (in_array($column['format'], ['datetime-local']))
                                    <span class="format-date-time-local">{!! $form['data']->{$column['name']} !!}</span>
                                @else
                                    {!! $form['data']->{$column['name']} !!}
                                @endif
                            </div>
                            @if($column['classes::view'])
                                </div>
                            @endif
                            @if($column['container_end::view'])
                                </div>
                            @endif
                        @endif
                    @endforeach
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
            const partnerRewardsView = ["/partner/manage/rewards/view"];
            const pathsToCheck = ["/admins/view/", "/networks/view", "/partners/view", "/members/view", "/partner/manage/clubs/view", "/partner/manage/cards/view/", "/partner/manage/rewards/view", "/partner/manage/staff/view"];
            const currentPathname = window.location.pathname;
            if (pathsToCheck.some(path => currentPathname.includes(path))) {
                $('body').addClass('networks-view-page');

                if (partnerRewardsView.some(path => currentPathname.includes(path))) {
                    $('body').addClass('partner-rewards-view-page');
                }
                
                const childDivs = $('.ll-user-view-page > div');
                const divCount = childDivs.length;
                const secondDivCount = Math.floor(divCount / 2) + 1;
                const thirdDivCount = divCount - secondDivCount;
                
                childDivs.slice(0, secondDivCount).wrapAll('<div class="ll-user-other-info-left w-1/2"></div>');
                childDivs.slice(secondDivCount).wrapAll('<div class="ll-user-other-info-right w-1/2"></div>');
                $('body.networks-view-page .ll-user-other-info-left .ll-label').removeClass('hidden');
            } else{
                // first wrapping
                $('.ll-user-view-page > div:first').wrap('<div class="ll-user-avatar-container"></div');
                
                const remainingDivs = $('.ll-user-view-page > div:not(:first)');
                remainingDivs.wrapAll('<div class="ll-user-details-container w-full"></div');
    
                // second time wrapping
                $('.ll-user-details-container > div:first-child, .ll-user-details-container > div:first-child + div').wrapAll('<div class="ll-user-main-info mb-8"></div>');
                $(".ll-user-details-container > div:not(.ll-user-main-info)").wrapAll("<div class='ll-user-others-info grid grid-cols-1 sm:grid-cols-2 gap-4'></div>");
    
                // third time wrapping
                const userOthersInfo = $('.ll-user-others-info');
                const childDivs = userOthersInfo.children('div');
                const divCount = childDivs.length;
    
                const secondDivCount = Math.floor(divCount / 2);
                const thirdDivCount = divCount - secondDivCount;
    
                childDivs.slice(0, secondDivCount + 1).wrapAll('<div class="ll-user-other-info-left"></div>');
                childDivs.slice(secondDivCount + 1).wrapAll('<div class="ll-user-other-info-right"></div>');
            }
        });
    </script>
    @stop
