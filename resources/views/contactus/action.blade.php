
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['contactus.destroy', $plan->id], 'method' => 'delete','data--submit'=>'plan'.$plan->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(auth()->user()->hasAnyRole(['admin','demo_admin']))
        <a class="mr-3" href="{{ route('contactus.destroy', $plan->id) }}" data--submit="plan{{$plan->id}}"
            data--confirmation='true'
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.contactus') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.contactus') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}
