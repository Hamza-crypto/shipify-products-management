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
    <h1 class="h3 mb-3">All Gateways</h1>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="message-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Message</th>
                            <th>Trigger at</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        @foreach($messages as $message)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    @if ($message->type == 'auto')
                                        <span class="badge badge-success">{{$message->type}}</span>
                                    @elseif($message->type == 'favorite')
                                        <span class="badge badge-primary">{{$message->type}}</span>
                                    @endif
                                </td>
                                <td>{{ str_limit( $message->body, $limit = 30) }}</td>

                                <td>  @if ($message->trigger_at == 'trade_start')
                                        <span class="badge badge-warning">{{$message->trigger_at}}</span>
                                    @elseif($message->trigger_at == 'release_coin')
                                        <span class="badge badge-danger">{{$message->trigger_at}}</span>
                                    @elseif($message->type == 'favorite')
                                        <span class="badge badge-secondary"> - - -</span>
                                    @endif
                                </td>

                                <td>{{ $message->created_at->diffForHumans() }}</td>
                                <td class="table-action">
                                    <a href="{{ route('messages.edit', $message->id) }}" class="btn"
                                       style="display: inline">
                                        <i class="fa fa-edit text-info"></i>
                                    </a>
                                    <form method="post" action="{{ route('messages.destroy', $message->id) }}"
                                          onsubmit="return confirmSubmission(this, 'Are you sure you want to delete this message ')"

                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                href="{{ route('messages.destroy', $message->id) }}">
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
