<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">
                    {{__('admin.user.create.header')}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.store')}}" method="post" id="storeForm">
                @csrf

                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="name" class="form-label">{{__('admin.user.create.input_name')}}</label>
                            <input name="name" id="name" required class="form-control" style="border-color:darkgrey"></input>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="role_id" class="form-label">{{__('admin.user.create.input_role')}}</label>
                            <select class="form-control form-select select2" data-bs-placeholder="Choose One" style="width:100%; border-color: darkgrey" name="role_id" id="role_id">
                                <option label>{{__('admin.user.create.choose_role')}}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="username" class="form-label">{{__('admin.user.create.input_username')}}</label>
                            <input type="text" name="username" id="username" required class="form-control" style="border-color:darkgrey"></input>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="email" class="form-label">{{__('admin.user.create.input_email')}}</label>
                            <input type="email" name="email" id="email" required class="form-control" style="border-color:darkgrey"></input>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="password" class="form-label">{{__('admin.user.create.input_password')}}</label>
                            <input type="password" name="password" id="password" required class="form-control" style="border-color:darkgrey"></input>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">{{__('admin.user.create.input_password_confirmation')}}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control" style="border-color:darkgrey"></input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{__('admin.button.close')}}
                    </button>
                    <button type="button" id="btnCreate" class="btn btn-primary">{{__('admin.button.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

