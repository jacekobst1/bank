{!! Form::model($user, ['route' => ['settings.users.update', $user->id], 'class' => 'modal-form']) !!}
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Editing the user') }}
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
                    {!! Form::select('role_id', $roles, null, ['class' => 'form-control']) !!}
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary modal-submit-btn">
            {{ __('Save') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('Close') }}
        </button>
    </div>
{!! Form::close() !!}
