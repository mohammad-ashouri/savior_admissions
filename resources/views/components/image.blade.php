@props(['alt'=>'عکس نمونه','src'=>''])

<div class="relative group overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
    <!-- عکس -->
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="max-w-full max-h-full object-contain rounded-lg transform group-hover:scale-105 transition-transform duration-300"
    />

    <!-- متن روی عکس -->
    <div
        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <p class="text-white text-lg font-bold">{{ $alt }}</p>
    </div>
</div>
