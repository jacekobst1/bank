<script type="text/javascript">
    $(function() {
       $('[name=role_id]').change(function() {
           if ($(this).val() == 1 ) {
                $('#password-div').removeClass('d-none').addClass('d-flex');
                $('#password-verify-div').removeClass('d-none').addClass('d-flex');
                $('#password-input').attr('disabled', false);
                $('#password-verify-input').attr('disabled', false);
           } else {
               $('#password-div').addClass('d-none').removeClass('d-flex');
               $('#password-verify-div').addClass('d-none').removeClass('d-flex');
               $('#password-input').attr('disabled', true);
               $('#password-verify-input').attr('disabled', true);
           }
       });
    });
</script>

{!! Form::open(['route' => 'settings.users.store', 'class' => 'modal-form']) !!}
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Creating new user') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('First name') }}
                    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                {{ __('Last name') }}
                {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('PESEL') }}
                    {!! Form::text('pesel', null, ['class' => 'form-control', 'maxlength' => 11]) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('E-mail') }}
                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('Address') }}
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('Zip code') }}
                    {!! Form::text('zip_code', null, ['class' => 'form-control', 'maxlength' => 6]) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('City') }}
                    {!! Form::text('city', null, ['class' => 'form-control']) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('Role') }}
                    {!! Form::select('role_id', $roles, 2, ['class' => 'form-control']) !!}
                </label>
            </div>
            <div
                    id="password-div"
                    class="col-12 col-md-6 justify-content-center justify-content-md-start d-none"
            >
                <label>
                    {{ __('Password') }}
                    {!! Form::password('password', [
                        'id' => 'password-input',
                        'class' => 'form-control',
                        'disabled'
                    ]) !!}
                </label>
            </div>
            <div
                id="password-verify-div"
                class="col-12 col-md-6 justify-content-center justify-content-md-start d-none"
            >
                <label>
                    {{ __('Password confirm') }}
                    {!! Form::password('password_verify', [
                        'id' => 'password-verify-input',
                        'class' => 'form-control',
                        'disabled'
                    ]) !!}
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary modal-submit-btn">
            {{ __('Create') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('Close') }}
        </button>
    </div>
{!! Form::close() !!}
