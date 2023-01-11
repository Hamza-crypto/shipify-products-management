@extends('layouts.app')

@section('title', 'Add Group')

@section('scripts')
    <script>
        $(document).ready(function () {


            $("#type").change(function () {

                var type = $('#type').val();
                console.log(type);
                if (type == 'auto') {
                    $('#trigger_at').attr('disabled', false);
                } else {
                    $('#trigger_at').val('0').trigger('change');
                    $('#trigger_at').attr('disabled', true);
                }
            });

        });
    </script>
@endsection

@section('content')

    <h1 class="h3 mb-3">Add New Group</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    @if(session('error'))
                        <x-alert type="danger">{{ session('error') }}</x-alert>
                    @endif


                        <form method="post" action="{{ route('groups.store') }}">
                            @csrf

                        <div class="row">
                            <div class="col-12">
                                <x-input type="text" label="Group" name="name" placeholder="Enter group name"></x-input>
                            </div>
                        </div>



                        <div class="form-group">

                            <button type="submit" class="btn btn-lg btn-primary">Add New Group</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
