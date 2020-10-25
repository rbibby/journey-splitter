<html>
    <head>
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

        <title>{{ $placeName }} | Journey Splitter</title>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>

    <body>

        <header class="flex items-center justify-between bg-indigo-100 border-b border-indigo-500 py-4 px-10">
            <h1 class="text-4xl font-light">{{ $placeName }}</h1>
            <a href="javascript:close();" class="text-lg">< Back</a>
        </header>

        @if (count($photoIds) != 0)
        <div class="h-48 overflow-x-scroll flex mb-4">
            @foreach ($photoIds as $id)
            <img src="https://maps.googleapis.com/maps/api/place/photo?maxheight=192&photoreference={{ $id }}&key={{ config('services.google-maps.key') }}">
            @endforeach
        </div>
        @endif
        

        <section class="flex flex-wrap mt-4">
            @foreach(['cafe' => 'Cafes', 'gas_station' => 'Petrol Stations', 'meal_takeaway' => 'Takeaways', 'park' => 'Parks / Open Spaces', 'parking' => 'Car Parking'] as $type => $friendlyName)
                @if(count($places[$type]) != 0)
                <section class="p-4 w-full md:w-1/3">
                    <h3 class="flex items-center text-xl text-indigo-900 font-light mb-4">
                        <img src="{{ $places[$type]['0']['icon']}}" class="h-8 mr-2" />
                        {{ $friendlyName }}
                    </h3>

                    @foreach ($places[$type] as $place)
                        <div class="p-4 bg-indigo-100 border border-indigo-200 my-2">
                            <p class="flex items-baseline justify-between">
                                <span class="font-bold">{{ $place['name']}}</span>
                                @isset($place['rating']) <span class="text-sm">Rating: {{ $place['rating'] }}/5</span>@endisset
                            </p>

                            @isset($place['vicinity'])
                            <p class="text-sm mt-1">
                                {{ $place['vicinity'] }}
                            </p>
                            @endisset
                            
                        </div>
                    @endforeach
                </section>
                @endif
            @endforeach
        </section>
    </body>
</html>