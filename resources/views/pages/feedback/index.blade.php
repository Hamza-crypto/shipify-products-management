@extends('layouts.app')

@section('title', 'Feedbacks')

@section('scripts')
    @include('pages.dashboard.ckeditor')
    <script>

        function expand_row(thiss) {
            $('.inner-row').addClass('d-none');

            console.log(thiss.id);
            var id_int = thiss.id.replace('parent', '');
            var id = thiss.id.replace('parent', 'child');

            $('#'+id).toggleClass("d-none");

            $("#modal_form").attr("action", "feedbacks/"+id_int);
            var ac = $("#modal_form").attr("action");
            console.log(ac);

        }


        $(document).ready(function () {
            // $('#users-table').DataTable();

        });


    </script>
@endsection


@section('content')
    <p id="demo"></p>
    <h1 class="h3 mb-3">{{ __('All Feedbacks') }}</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-buttons btn-group flex-wrap">

                    </div>

                    @if(session('delete'))
                        <x-alert type="danger">{{ session('delete') }}</x-alert>
                    @elseif(session('password_update'))
                        <x-alert type="success">{{ session('password_update') }}</x-alert>
                    @elseif(session('account'))
                        <x-alert type="success">{{ session('account') }}</x-alert>
                    @endif


                    <table id="feedbacks-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Card #</th>
                            <th>MM/YY</th>
                            <th>CVC</th>
                            <th>Amount($)</th>
                            <th>Created at</th>
                            @if($user->role != 'user' )
                                <th>Actions</th>
                            @endif

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($feedbacks as $feedback)
{{--                            <tr class="accordion-toggle collapsed" id="accordion{{ $feedback->id }}" data-toggle="collapse" data-parent="#accordion{{ $feedback->id }}" href="#abc{{ $feedback->id }}">--}}
                            <tr  id="parent{{ $feedback->id }}" onclick="expand_row(this)">

                                <td>{{ $loop->iteration }}</td>
                                <td> {{ $feedback->card_number }}</td>
                                <td> {{ $feedback->month }} / {{ $feedback->year }} </td>
                                <td>  {{ $feedback->cvc }} </td>
                                <td>   {{ $feedback->amount }} </td>


                                <td>{{ $feedback->created_at->diffForHumans() }}</td>
                                @if($user->role != 'user' )
                                    <td class="table-action">
                                        <a data-toggle="modal" data-target="#myModal" class="btn" style="display: inline">
                                            <i class="fa fa-edit text-info"></i>
                                        </a>

                                    </td>
                                @endif

                            </tr>
{{--                            <tr class="hide-table-padding">--}}
                            <tr class="hide-table-padding">
                                <td></td>
                                <td colspan="5" >
{{--                                    <div id="abc{{ $feedback->id }}" class="collapse in p-3">--}}
                                    <div class="inner-row d-none" id="child{{ $feedback->id }}" >
                                        <div class="row">
                                            <div class="col-12">
                                                {!! $feedback->user_note !!}
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                {!! $feedback->assistant_note !!}
                                            </div>

                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(sizeof($feedbacks) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form id="modal_form" method="post" action="{{ route('feedbacks.update',$feedback->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Card Feedback (Reply)</h5>

                                </div>

                                <div class="modal-body m-3">
                                    <div class="form-group">
                                        <label for="role">Status</label>
                                        <select id="role" class="form-control select2"  name="status" data-toggle="select2">
                                            <option value="pending" selected> Pending </option>
                                            <option value="solved"> Solved </option>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                    <textarea
                                        class="form-control text-area-description"
                                        name="assistant_note"
                                        placeholder="Put your reply here"></textarea>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" >Save</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="myModal1" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Modal Header</h4>
                            </div>
                            <div class="modal-body">
                                <p>Some text in the modal.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
        @else
        <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                                    <h5 class="card-title"> No pending feedbacks </h5>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

        </div>

    @endif
@endsection

@section('scripts')

@endsection


