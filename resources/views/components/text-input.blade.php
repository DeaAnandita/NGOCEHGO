<<<<<<< HEAD
'class' => 'border-gray-300 bg-gray-50 text-gray-800 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm'
=======
@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'
    ]) }}
>
>>>>>>> bd7da46 (darkmode)
