@extends('layouts.app')
@section('navbar_home')
  @guest
    @if (Route::has('login'))
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
      </li>
    @endif

    @if (Route::has('register'))
      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
      </li>
    @endif
  @else

    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ url('/home') }}">Home</a>
    </li>

    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ route('logout') }}"
      onclick="event.preventDefault();
      document.getElementById('logout-form').submit();">
      {{ __('Logout') }}
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  </li>
</div>
</li>
@endguest
@endsection
@section('content')

  <div class="container">
    @if(Session::has('message'))
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endforeach
    @endif

    @if(Session::has('error'))
      <div class="alert alert-danger alert-dismissible fade show">
        {{ Session::get('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    @endif

    <div class="row justify-content-center">
      <div class="col-lg-4">
        <h4>Profile Information</code></h5>
          <span class="text-justify mb-3" style="padding-top:-3px;">Update your account's profile information and email address.<br><br> When You change your email ,you need to verify your email else the account will be blocked</span>
        </div>

        <div class="col-lg-8 text-center pt-0">
          <div class="card py-4 mb-5 mt-md-3 bg-white rounded " style="box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175)">

            {{ html()->modelForm($user,'PATCH', route('profile.update',[$user->uid]))->open() }}

                <div class="form-group px-3">
                    {{ html()->label('Name : ')->class('col-12 text-left pl-0') }}
                    {{ html()->text('displayName')->class('col-md-8 form-control') }}

                    {{ html()->label('Email : ')->class('col-12 text-left pl-0 pt-3') }}
                    {{ html()->email('email')->class('col-md-8 form-control') }}
                </div>

                <div class="form-group row mb-0 mr-4">

              <div class="col-md-8 offset-md-4 text-right">
                {{ html()->submit('Save')->class('btn btn-primary') }}
              </div>
            </div>


          </div>
        </div>

      </div>
      <div class="border-bottom border-grey"></div>

      <div class="row justify-content-center pt-5">
        <div class="col-lg-4">
          <h4>Update Password</code></h5>
            <span class="text-justify" style="padding-top:-3px;">Ensure your account is using a long, random password to stay secure.</span>
          </div>

          <div class="col-lg-8 text-center pt-0">
            <div class="card py-4 mb-5 mt-md-3 bg-white rounded" style="box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175)">

                <div class="form-group px-3">
                     {{ html()->label('New Password : ')->class('col-12 text-left pl-0') }}
                    {{ html()->password('new_password')->class('col-md-8 form-control') }}
                </div>

                <div class="form-group px-3">
                     {{ html()->label('Confirm Password : ')->class('col-12 text-left pl-0') }}
                    {{ html()->password('new_confirm_password')->class('col-md-8 form-control') }}
                </div>

                <div class="form-group row mb-0 mr-4">
                    <div class="col-md-8 offset-md-4 text-right">
                    {{ html()->submit('Save')->class('btn btn-primary') }}
                    </div>
              </div>

               {{ html()->closeModelForm() }}
            </div>
          </div>

        </div>

        <div class="border-bottom border-grey"></div>

        <div class="container">
  {{-- Show session messages --}}
  @if(Session::has('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      {{ Session::get('message') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  @if ($errors->any())
    @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $error }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endforeach
  @endif

  @if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      {{ Session::get('error') }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  @endif

  {{-- Profile update and password update sections here (unchanged) --}}
  <!-- Your existing Profile Information and Update Password forms -->

  {{-- Delete Account Section --}}
  <div class="border-bottom border-grey"></div>

  <div class="row justify-content-center pt-5">
    <div class="col-lg-4">
      <h4>Delete Account</h4>
      <span class="text-justify" style="padding-top:-3px;">
        Permanently delete your account. This action cannot be undone.
      </span>
    </div>

    <div class="col-lg-8 pt-0">
      <div class="card py-4 mb-5 mt-md-3 bg-white rounded"
           style="box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175)">
        <div class="text-left px-3 mb-3">
          Once your account is deleted, all of its resources and data will be permanently deleted.
          Before deleting, please download any data or information you wish to keep.
        </div>

        {{-- Delete form requires email and password verification --}}
        <form method="POST" action="{{ route('profile.verifyDelete', [$user->uid]) }}">
          @csrf
          @method('DELETE')

          <div class="form-group px-3">
            <label for="email">Confirm Email</label>
            <input type="email" id="email" name="email" class="form-control" required
                   placeholder="Enter your email">
          </div>

          <div class="form-group px-3">
            <label for="password">Confirm Password</label>
            <input type="password" id="password" name="password" class="form-control" required
                   placeholder="Enter your password">
          </div>

          <div class="form-group row mb-0 mr-4 px-3">
            <div class="col-md-8 offset-l-4 text-left">
              <button type="submit" class="btn btn-danger pl-3">
                Delete Account
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection