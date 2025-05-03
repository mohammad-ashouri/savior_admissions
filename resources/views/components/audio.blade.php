@props(['src'=>''])
<div class="max-w-full mx-auto bg-white rounded-lg shadow-md p-4 flex items-center justify-between">
    <audio class="w-full h-auto max-w-full" controls>
        <source src="{{ $src }}">
        Your browser does not support the video tag.
    </audio>
</div>
