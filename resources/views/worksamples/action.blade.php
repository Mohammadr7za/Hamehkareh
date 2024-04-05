
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['contactus.destroy', $query->id], 'method' => 'delete','data--submit'=>'plan'.$query->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(auth()->user()->hasAnyRole(['admin','manager']))
        <a class="mr-3" href="{{ route('worksamples.delete', $query->id) }}" data--submit="plan{{$query->id}}"
            data--confirmation='true'
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.worksamples') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.worksamples') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}
