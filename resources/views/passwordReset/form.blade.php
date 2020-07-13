@extends('layout.app')
@section('title', 'Password Reset Form')
@section('content')
    <h1>Reset Your password</h1>

    <div class="flex-container pt-5" >
        <div class="row">

                <div class="col">
                    <img src="{{asset("/images/passwordForgot.gif")}}">
                </div>
                <div class="col container ">
                    <form action="" method="post">
                        @csrf
                        <h2>Hello kavishka</h2>

                        <input type="hidden" value="" id="token">
                        <input type="hidden" value="" id="email">
                        <div class="form-group">
                            <label for="password" >Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm" placeholder="Re-Enter Password" name="confirm">
                        </div>
                        <button type="submit" class="btn btn-info">Submit</button>
                    </form>
                </div>


{{--            <div class="col" >--}}
{{--                @hasSection('error')--}}
{{--                <img src="{{asset("/images/403.gif")}}">--}}
{{--                    @--}}
{{--            </div>--}}
        </div>
    </div>

@endsection
