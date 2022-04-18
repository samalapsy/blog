@props(['disabled' => false])

<textarea rows="10" placeholder="Enter your message here" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>{{ $attributes['value'] }}</textarea>
