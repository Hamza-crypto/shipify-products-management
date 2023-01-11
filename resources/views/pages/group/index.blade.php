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
                            <th>Group name</th>
                            <th>Added at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        @foreach($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{$group->name}}</td>

                                <td>{{ $group->created_at->diffForHumans() }}</td>
                                <td class="table-action">
                                    <form method="post" action="{{ route('groups.destroy', $group->id) }}"
                                          onsubmit="return confirmSubmission(this, 'Are you sure you want to delete this group ')"

                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                href="{{ route('groups.destroy', $group->id) }}">
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
