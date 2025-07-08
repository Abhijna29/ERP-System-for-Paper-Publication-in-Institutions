@php
    // Map roles to layouts (adjust paths as per your project)
    $layouts = [
        'admin' => 'layouts.admin',
        'researcher' => 'layouts.researcher',
        'reviewer' => 'layouts.reviewer',
        'institution' => 'layouts.institution',
        'department' => 'layouts.department',
    ];

    // Pick layout or default to researcher layout
    $layout = $layouts[$role];
@endphp

@extends($layout)
@section('content')
<div class="container py-4">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-4">{{ __('Frequently Asked Questions')}}</h5>
             @if($faqs->isEmpty())
                        <p class="text-muted text-center">No FAQs available at the moment.</p>
            @else
                <div class="accordion " id="faqAccordion">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item mb-3 border rounded-3 shadow-sm overflow-hidden">
                            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                <button class="accordion-button collapsed fw-medium text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                                    {{ $faq->title }}
                                </button>
                            </h2>
                            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse
                                
                                " aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {{ $faq->description }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            </div>
    </div>
</div>
@endsection
