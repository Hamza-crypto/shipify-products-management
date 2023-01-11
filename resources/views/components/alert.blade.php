@section('alert')
    <script>
        window.notyf.open({
            'type': '{{ $type }}',
            'message': '{{ $slot }}',
            'duration': 12000,
            'ripple': true,
            'dismissible': true
        });
    </script>
@endsection
