@extends('layouts.researcher')
@section('content')
<div class="container">
    <h4>All Patents</h4>
    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <table class="table">
        <thead><tr><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($patents as $patent)
                <tr>
                    <td>{{ $patent->work_title }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($patent->type) }}</span></td>
                    <td>
                        @if($patent->type == 'filed')
                        <form action="{{ route('researcher.patents.markPublished', $patent->id) }}" method="POST">
                            @csrf
                            <input type="text" name="publication_number" class="form-control mb-2" placeholder="Enter Publication Number" required>
                            <button class="btn btn-sm btn-success">Mark as Published</button>
                        </form>

                     @elseif($patent->certificate_path)
                        <a href="{{ asset('storage/' . $patent->certificate_path) }}" target="_blank">View Certificate</a>
                    @else
                        <form action="{{ route('researcher.patents.uploadCertificate', $patent->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="certificate" class="form-control mb-1" required accept="application/pdf">
                            <button type="submit" class="btn btn-sm btn-primary">Upload Certificate</button>
                        </form>
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
