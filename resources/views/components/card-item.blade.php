<a href="{{ $link }}" class="text-red-600 font-semibold text-sm mt-4 inline-block">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-40 object-cover">
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            <p class="text-gray-600 text-sm mt-2">{{ $description }}</p>
            {{ $linkText }}
        </div>
    </div>
</a>