@extends('layouts.app')

@section('title')
    Utenti
@endsection


@section('content')
    <div class="container-fluid ">
        <div class="mb-3 border-bottom">
            <h1 class="h2">Utenti</h1>
        </div>
        <div class="row">
            <div class="col-md-8">
                <table class="table table-striped" id="userTable">
                    <thead>
                        <tr class="bg-secondary text-white">
                            <th scope="col">#</th>
                            <th scope="col">Utente</th>
                            {{--<th scope="col">Tipo</th>--}}
                            <td style="width: 4%"></td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th scope="row" style="width: 9%">{{ $user->id }}</th>
                            <td>{{ $user->username }}</td>
                            {{--<td>{{ $user->type }}</td>--}}
                            <td style="width: 9%">
                                <i class="far fa-trash-alt deleteUser" style="font-size: 20px;cursor: pointer"></i>
                                <i class="far fa-edit editUser" style="font-size: 20px;cursor: pointer"></i>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        Crea utente
                    </div>
                    <div class="card-body">
                        <form>
                            @csrf
                            <div class="form-group">
                                <label for="username">{{ __('Username') }}</label>
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('username') }}</strong>
                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
                                @endif
                            </div>
                            <!--
                          <div class="form-check form-group">
                            <input class="form-check-input" type="checkbox" value="" id="adminCheck">
                            <label class="form-check-label" for="adminCheck">
                            Admin
                            </label>
                          </div> -->

                            <button type="button" onclick="newUser()" class="btn btn-primary">
                                {{ __('Crea') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal per modifica utente -->
        <div class="modal" tabindex="-1" role="dialog" id="userModal">

            <div class="modal-dialog" role="document">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Modifica</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form>
                            <div class="form-group row">
                                <label for="idModal" class="col-md-4 col-form-label text-md-right">{{ __('Id') }}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly class="form-control-plaintext" id="idModal" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="usernameModal" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
                                <div class="col-md-6">
                                    <input class="form-control" id="usernameModal" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="typeModal" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="password" id="passwordModal" placeholder="password">
                                    <small id="passwordHelp" class="form-text text-muted">Lasciando vuoto questo campo si manterrà la password corrente.</small>
                                </div>
                            </div>
                            {{--
                                                        <div class="form-group row">
                                <label for="adminCheck" class="col-md-4 col-form-label text-md-right">{{ __('Admin') }}</label>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="adminCheckModal">
                                    </div>
                                </div>
                            </div>

                            --}}
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnSave" class="btn btn-primary">Salva modifiche</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/users.js') }}"></script>
@endsection
