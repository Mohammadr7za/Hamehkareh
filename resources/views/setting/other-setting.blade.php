{{ Form::model($othersetting, ['method' => 'POST','route' => ['otherSetting'],'enctype'=>'multipart/form-data','data-toggle'=>'validator','id' => 'myForm']) }}

{{ Form::hidden('id', null, ['placeholder' => 'id', 'class' => 'form-control']) }}
{{ Form::hidden('type', $page, ['placeholder' => 'id', 'class' => 'form-control']) }}

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="social_login" class="mb-0">{{ __('messages.enable_social_login') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="social_login" id="social_login" {{ !empty($othersetting->social_login) ? 'checked' : '' }}>
            <label class="custom-control-label" for="social_login"></label>
        </div>
    </div>
</div>

<div class="form-padding-box mb-3" id='social_login_data'>
    <div class="row">
        <div class="col-md-12">
                <div class="form-group d-flex justify-content-between mb-2">
                <label for="google_login" class="mb-0">{{ __('messages.enable_google_login') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="google_login" id="google_login" {{ !empty($othersetting->google_login) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="google_login"></label>
                </div>
            </div>
            <div class="form-group d-flex justify-content-between mb-2">
                <label for="apple_login" class="mb-0">{{ __('messages.enable_apple_login') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="apple_login" id="apple_login" {{ !empty($othersetting->apple_login) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="apple_login"></label>
                </div>
            </div>
             <div class="form-group d-flex justify-content-between mb-0">
                <label for="otp_login" class="mb-0">{{ __('messages.enable_otp_login') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="otp_login" id="otp_login" {{ !empty($othersetting->otp_login) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="otp_login"></label>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="blog" class="mb-0">{{ __('messages.enable_post_job_request') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="post_job_request" id="post_job_request" {{ !empty($othersetting->post_job_request) ? 'checked' : '' }}>
            <label class="custom-control-label" for="post_job_request"></label>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="blog" class="mb-0">{{ __('messages.enable_blog') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="blog" id="blog" {{ !empty($othersetting->blog) ? 'checked' : '' }}>
            <label class="custom-control-label" for="blog"></label>
        </div>
    </div>
</div>

  @hasanyrole('admin')

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="maintenance_mode" class="mb-0">{{ __('messages.enable_maintenance_mode') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="maintenance_mode" id="maintenance_mode" {{ !empty($othersetting->maintenance_mode) ? 'checked' : '' }}>
            <label class="custom-control-label" for="maintenance_mode"></label>
        </div>
    </div>
</div>

<!-- <div class="form-padding-box mb-3" id='maintenance_mode_code'>
    <div class="row">
        <div class="form-group col-sm-6 mb-0">
            {{ Form::label('maintenance_mode_secret_code',trans('messages.maintenance_mode_secret_code').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::text('maintenance_mode_secret_code',old('maintenance_mode_secret_code'),['id'=>'maintenance_mode_secret_code','placeholder' => trans('messages.maintenance_mode_secret_code'),'class' =>'form-control','required']) }}
            <small class="help-block with-errors text-danger"></small>
        </div>
       
    </div>
</div> -->

 @endhasanyrole

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="force_update_user_app" class="mb-0">{{ __('messages.enable_user_app_force_update') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="force_update_user_app" id="force_update_user_app" {{ !empty($othersetting->force_update_user_app) ? 'checked' : '' }}>
            <label class="custom-control-label" for="force_update_user_app"></label>
        </div>
    </div>
</div>

<div class="form-padding-box mb-3" id='user_verson_code'>
    <div class="row">
        <div class="form-group col-sm-6 mb-0">
            {{ Form::label('user_app_minimum_version',trans('messages.user_app_minimum_version').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::number('user_app_minimum_version',old('user_app_minimum_version'),['id'=>'user_app_minimum_version','placeholder' => '1','class' =>'form-control']) }}
            <small class="help-block with-errors text-danger"></small>
        </div>
        <div class="form-group col-sm-6 mt-sm-0 mt-3 mb-0">
            {{ Form::label('user_app_latest_version',trans('messages.user_app_latest_version').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::number('user_app_latest_version',old('user_app_latest_version'),['id'=>'user_app_latest_version','placeholder' => '2','class' =>'form-control']) }}
            <small class="help-block with-errors text-danger"></small>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="force_update_provider_app" class="mb-0">{{ __('messages.enable_provider_app_force_update') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="force_update_provider_app" id="force_update_provider_app" {{ !empty($othersetting->force_update_provider_app) ? 'checked' : '' }}>
            <label class="custom-control-label" for="force_update_provider_app"></label>
        </div>
    </div>
</div>

<div class="form-padding-box mb-3" id='provider_verson_code'>
    <div class="row">
        <div class="form-group col-sm-6 mb-0">
            {{ Form::label('provider_app_minimum_version',trans('messages.provider_app_minimum_version').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::number('provider_app_minimum_version',old('provider_app_minimum_version'),['id'=>'provider_app_minimum_version','placeholder' => '1','class' =>'form-control']) }}
            <small class="help-block with-errors text-danger"></small>
        </div>
        <div class="form-group col-sm-6 mt-sm-0 mt-3 mb-0">
            {{ Form::label('provider_app_latest_version',trans('messages.provider_app_latest_version').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::number('provider_app_latest_version',old('provider_app_latest_version'),['id'=>'provider_app_latest_version','placeholder' => '2','class' =>'form-control']) }}
            <small class="help-block with-errors text-danger"></small>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="force_update_admin_app" class="mb-0">{{ __('messages.enable_admin_app_force_update') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="force_update_admin_app" id="force_update_admin_app" {{ !empty($othersetting->force_update_admin_app) ? 'checked' : '' }}>
            <label class="custom-control-label" for="force_update_admin_app"></label>
        </div>
    </div>
</div>

<div class="form-padding-box mb-3" id='admin_verson_code'>
    <div class="row">
        <div class="form-group col-sm-6 mb-0">
            {{ Form::label('admin_app_minimum_version',trans('messages.admin_app_minimum_version').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::number('admin_app_minimum_version',old('admin_app_minimum_version'),['id'=>'admin_app_minimum_version','placeholder' => '1','class' =>'form-control']) }}
            <small class="help-block with-errors text-danger"></small>
        </div>
        <div class="form-group col-sm-6 mt-sm-0 mt-3 mb-0">
            {{ Form::label('admin_app_latest_version',trans('messages.admin_app_latest_version').' ',['class'=>'form-control-label'], false ) }}
            {{ Form::number('admin_app_latest_version',old('admin_app_latest_version'),['id'=>'admin_app_latest_version','placeholder' => '2','class' =>'form-control']) }}
            <small class="help-block with-errors text-danger"></small> 
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="advanced_payment_setting">{{ __('messages.advance_payment_setting') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="advanced_payment_setting" id="advanced_payment_setting" {{ !empty($othersetting->advanced_payment_setting) ? 'checked' : '' }}>
            <label class="custom-control-label" for="advanced_payment_setting"></label>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 d-flex justify-content-between">
        <label for="wallet">{{ __('messages.enable_user_wallet') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="wallet" id="wallet" {{ !empty($othersetting->wallet) ? 'checked' : '' }}>
            <label class="custom-control-label" for="wallet"></label>
        </div>
    </div>
</div>



{{ Form::submit(__('messages.save'), ['class' => "btn btn-md btn-primary float-md-right"]) }}
{{ Form::close() }}

<script>

var maintenance_mode = $("input[name='maintenance_mode']").prop('checked');

checkMaintenanceUpdateSetting(maintenance_mode);

$('#maintenance_mode').change(function(){
    value = $(this).prop('checked');
    checkMaintenanceUpdateSetting(value);
});
function checkMaintenanceUpdateSetting(value){
    if(value == true){
        $('#maintenance_mode_code').removeClass('d-none');
          $("#maintenance_mode_secret_code").prop("required", true);
    }else{
        $('#maintenance_mode_code').addClass('d-none');
          $("#maintenance_mode_secret_code").prop("required", false);
    }
}




var force_update_user_app = $("input[name='force_update_user_app']").prop('checked');

checkForceUpdateSettingOption(force_update_user_app);

$('#force_update_user_app').change(function(){
    value = $(this).prop('checked');
    checkForceUpdateSettingOption(value);
});
function checkForceUpdateSettingOption(value){
    if(value == true){
        $('#user_verson_code').removeClass('d-none');
         $("#user_app_latest_version").prop("required", true);
        $("#user_app_minimum_version").prop("required", true);
    }else{
        $('#user_verson_code').addClass('d-none');
         $("#user_app_latest_version").prop("required", false);
        $("#user_app_minimum_version").prop("required", false);
    }
}

var force_update_provider_app = $("input[name='force_update_provider_app']").prop('checked');

checkProviderForceUpdateSetting(force_update_provider_app);

$('#force_update_provider_app').change(function(){
    value = $(this).prop('checked');
    checkProviderForceUpdateSetting(value);
});
function checkProviderForceUpdateSetting(value){
    if(value == true){
        $('#provider_verson_code').removeClass('d-none');
        $("#provider_app_latest_version").prop("required", true);
        $("#provider_app_minimum_version").prop("required", true);
    }else{
        $('#provider_verson_code').addClass('d-none');
        $("#provider_app_latest_version").prop("required", false);
        $("#provider_app_minimum_version").prop("required", false);
          
    }
}

var force_update_admin_app = $("input[name='force_update_admin_app']").prop('checked');

checkAdminForceUpdateSetting(force_update_admin_app);

$('#force_update_admin_app').change(function(){
    value = $(this).prop('checked');
    checkAdminForceUpdateSetting(value);
});
function checkAdminForceUpdateSetting(value){
    if(value == true){
        $('#admin_verson_code').removeClass('d-none');
        $("#admin_app_latest_version").prop("required", true);
        $("#admin_app_minimum_version").prop("required", true);
    }else{
        $('#admin_verson_code').addClass('d-none');
        $("#admin_app_latest_version").prop("required", false);
        $("#admin_app_minimum_version").prop("required", false);
    }
}


var social_login = $("input[name='social_login']").prop('checked');

checkOtherSettingOption(social_login);

$('#social_login').change(function(){
    value = $(this).prop('checked');
    checkOtherSettingOption(value);
});
function checkOtherSettingOption(value){
    if(value == true){
        $('#social_login_data').removeClass('d-none');
    }else{
        $('#social_login_data').addClass('d-none');
    }
}
</script>