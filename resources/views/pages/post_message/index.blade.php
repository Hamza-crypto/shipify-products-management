@extends('layouts.app')

@section('title', 'Gateways')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#message-table').DataTable();

        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">Message Posting Schedule</h1>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="message-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Message</th>
                            <th>Group</th>
                            <th>Post at</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        @foreach($post_messages as $post_message)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ str_limit( $post_message->body, $limit = 30) }}</td>


                                <td>{{ $post_message->group->name ?? '' }}</td>

                                <td>{{ $post_message->post_at }}</td>

                                <td>  @if ($post_message->status == 'pending')
                                        <span class="badge badge-warning">{{$post_message->status}}</span>
                                    @elseif($post_message->status == 'done')
                                        <span class="badge badge-success">{{$post_message->status}}</span>
                                    @endif
                                </td>

                                <td>{{ $post_message->created_at->diffForHumans() }}</td>
                                <td class="table-action">
                                    <a href="{{ route('post_messages.edit', $post_message->id) }}" class="btn"
                                       style="display: inline">
                                        <i class="fa fa-edit text-info"></i>
                                    </a>
                                    <form method="post" action="{{ route('post_messages.destroy', $post_message->id) }}"
                                          onsubmit="return confirmSubmission(this, 'Are you sure you want to delete this message ')"

                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                href="{{ route('post_messages.destroy', $post_message->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
