@extends('layouts.institution')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Subscription Required',
        text: 'You must have an active subscription to submit papers.',
        confirmButtonText: 'View Plans',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('institution.plans') }}";
        }
    });
</script>
@endsection
