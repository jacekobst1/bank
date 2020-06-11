{!! Form::open([
        'route' => ['settings.users.change-password', $user->id],
        'method' => 'PATCH',
        'class' => 'modal-form'
]) !!}
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Changing password') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('Password') }}
                    {!! Form::password('password', [
                            'class' => 'form-control',
                            'autocomplete' => 'new-password'
                    ]) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                    {{ __('Password confirm') }}
                    {!! Form::password('password_verify', ['class' => 'form-control']) !!}
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary modal-submit-btn">
            {{ __('Change') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('Close') }}
        </button>
    </div>
{!! Form::close() !!}
