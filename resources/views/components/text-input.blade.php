@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm text-gray-700 placeholder-gray-400 font-medium']) }}>

