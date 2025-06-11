<div class="flex items-center">
    <span>{{ $option['name'] }}</span>
    <div class="flex ml-2">
        @foreach($option['colors'] as $color)
            <div class="w-4 h-4 rounded-full" style="background-color: {{ $color['hex'] }};" title="{{ $color['name'] }}"></div>
        @endforeach
    </div>
</div>
