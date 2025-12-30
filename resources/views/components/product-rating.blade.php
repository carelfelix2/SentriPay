@props(['product'])

@php
    $avgRating = number_format($product->averageRating(), 1);
    $totalReviews = $product->totalReviews();
    $ratingCounts = $product->ratingCounts();
    $maxCount = max($ratingCounts);
@endphp

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    <!-- Average Rating -->
    <div class="flex items-center gap-6">
        <div class="text-center">
            <div class="text-5xl font-bold text-gray-900">{{ $avgRating }}</div>
            <div class="flex items-center justify-center mt-2">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($avgRating))
                        <svg class="w-5 h-5" viewBox="0 0 20 20">
                            <path fill="#FACC15" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @elseif ($i - $avgRating < 1 && $i - $avgRating > 0)
                        <svg class="w-5 h-5" viewBox="0 0 20 20">
                            <defs>
                                <linearGradient id="half-{{ $product->id }}">
                                    <stop offset="50%" stop-color="#FACC15"/>
                                    <stop offset="50%" stop-color="#FEF08A"/>
                                </linearGradient>
                            </defs>
                            <path fill="url(#half-{{ $product->id }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" viewBox="0 0 20 20">
                            <path fill="#FEF08A" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endif
                @endfor
            </div>
            <div class="text-sm text-gray-600 mt-1">{{ $totalReviews }} ulasan</div>
        </div>

        <!-- Rating Breakdown with Counts -->
        <div class="flex-1 space-y-2">
            @foreach([5, 4, 3, 2, 1] as $star)
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 w-12">
                        <span class="text-sm font-medium text-gray-700">{{ $star }}</span>
                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    </div>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        @php
                            $percentage = $maxCount > 0 ? ($ratingCounts[$star] / $maxCount) * 100 : 0;
                        @endphp
                        <div class="bg-yellow-400 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 w-8 text-right">{{ $ratingCounts[$star] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
