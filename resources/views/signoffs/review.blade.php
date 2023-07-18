@extends('layouts.app')

@section('page', "Signoff {$model::getShortClassName()}")

@section('content')
<div class="container {{ $signoff->extraWide ? 'container-xxl' : '' }}">
    <div class="row justify-content-center">
        <div class="col">
            <h1 class="text-center">Signoff</h1>
            <h4 class="text-center mb-0">{{ $model::getShortClassName() }}: {{ $model->displayName }}</h4>
            <h4 class="text-center">Submitted By: <a href="{{ route('users.show', $signoff->user->id) }}">{{ $signoff->user->name }}</a></h4>

            <form id="signoff-form" method="POST" action="{{ route('signoffs.update', $signoff->id) }}">
                @csrf
                @method('post')

                @include($signoff->stepView, ['signoffForm' => true])

                <input type="hidden" name="signoff_form" value="1">
                <input type="hidden" name="signoff_step" value="{{ $signoff->step }}">

                <div class="card" style="margin-top: -2px;">
                    <div class="card-body">
                        @include('partials.signoffs.history')

                        <div class="mt-3 mb-5 input-wrap{{ $errors->has('signoff_comment') ? ' input-danger' : '' }}">
                            <label for="signoff_comment">Comment</label>
                            <textarea type="text" name="signoff_comment"></textarea>
                            @error('signoff_comment')
                            <small class="error-message">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row justify-content-between" style="align-items: flex-end">
                            <div class="col-6">
                                <div class="row ">
                                    <div class="col-4">
                                        <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('signoffs.index')) }}" title="Cancel">
                                            <i class="material-icons">cancel</i>
                                            Cancel
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button class="primary-btn block-btn" name="action" value="save" type="submit">
                                            <i class="material-icons">save</i>
                                            Save Without Signoff
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                @if (!$userResponse)
                                @include('signoffs.responses')
                                @else
                                <div class="error-message">
                                    You have already {{ $userResponse ? 'approved' : 'rejected' }} this {{ $model::getShortClassName() }}.
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{!! BladeHelper::writeModelChanges($changes) !!}
@endsection
