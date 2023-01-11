@extends('layouts.app')

@section('title', 'Gateways')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#gateway-table').DataTable();
        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">All Gateways</h1>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="gateway-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <active-trades/>
                        @foreach($gateways as $gateway)
                            <tr>
                                <td>{{ $loop->iteration }}</td>


                                <td>{{ $gateway->title }}</td>

                                <td>{{ $gateway->created_at->diffForHumans() }}</td>
                                <td class="table-action">
                                    <a href="{{ route('gateways.edit', $gateway->id) }}" class="btn" style="display: inline">
                                        <i class="fa fa-edit text-info"></i>
                                    </a>
                                    <form method="post" action="{{ route('gateways.destroy', $gateway->id) }}"
                                          onsubmit="return confirmSubmission(this, 'Are you sure you want to delete gateway ' + '{{ "$gateway->title"  }}')"

                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                href="{{ route('gateways.destroy', $gateway->id) }}">
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
