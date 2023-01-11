@extends('layouts.app')

@section('title', 'Bins')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#bins-table').DataTable();
        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('danger'))
        <x-alert type="error">{{ session('danger') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">All BINS</h1>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="bins-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>BIN</th>
                            <th>Min Amount($)</th>
                            <th>Max Amount($)</th>
                            <th>Gateway</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <active-trades/>
                        @foreach($bins as $bin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>


                                <td>{{ $bin->number }}</td>
                                <td>{{ $bin->min_amount }}</td>
                                <td>{{ $bin->max_amount }}</td>
                                @if(isset($bin->gateway))
                                    <td>{{ $bin->gateway->title  }}</td>
                                    @else
                                    <td></td>
                                @endif



                                <td>{{ $bin->created_at->diffForHumans() }}</td>
                                <td class="table-action">
                                    <a href="{{ route('bins.edit', $bin->id) }}" class="btn"
                                       style="display: inline">
                                        <i class="fa fa-edit text-info"></i>
                                    </a>
                                    <form method="post" action="{{ route('bins.destroy', $bin->id) }}"
                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                onclick="confirm('Are you sure you want to delete this BIN?')"
                                                href="{{ route('bins.destroy', $bin->id) }}">
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
