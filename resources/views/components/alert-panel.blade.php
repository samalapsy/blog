

@if(isset($attributes['type']))

@php
    $css_class = null;
    switch ($attributes['type']) {
        case 'success':
            $css_class = ' text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800';
        break;

        case 'error':
            $css_class = ' text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800';
        break;

        default:
        $css_class = ' text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800';
        break;
    }
@endphp

    <div class="p-4 mb-4 text-sm  {{ $css_class }}" role="alert">
        <span class="font-medium">{{ ucfirst($attributes['type']) }} !</span>  {{ $attributes['message'] }}
    </div>
@endif
