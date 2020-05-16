{!! Form::model($user,
    [
        'route' => ['settings.users.delete', $user->id],
        'method' => 'DELETE',
        'class' => 'modal-form'
    ]
)!!}
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Deleting the user') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        {{ __('Do you really want to delete the user ') }}
        <span class="font-weight-bold">{{ $user->first_name.' '.$user->last_name }}</span>?
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary modal-submit-btn">
            {{ __('Confirm') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('Close') }}
        </button>
    </div>
{!! Form::close() !!}
