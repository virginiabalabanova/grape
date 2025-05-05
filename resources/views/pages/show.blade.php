@php
use App\Support\GrapesRenderer;
@endphp

@if($page->content && isset($page->content['pages'][0]['frames'][0]['component']))
    {!! GrapesRenderer::render($page->content['pages'][0]['frames'][0]['component']) !!}
@else
    <p class="text-gray-500">No content available.</p>
@endif
