<html>
    <head>
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google-maps.key') }}&callback=initMap&libraries=&v=weekly"
            defer
            ></script>

        <title>{{ $start }} -> {{ $end }} | Journey Splitter</title>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>

    <body>
        <div class="h-screen w-screen flex flex-wrap flex-row-reverse md:flex-row">
            <section class="w-full md:w-4/5" id="map">
            </section>

            <section class="w-full h-full md:w-1/5 px-4 py-6 bg-indigo-100 border-l border-indigo-300 text-indigo-900 overflow-y-scroll">
                <p class="text-right text-xs">
                    <a href="{{ route('index') }}">New Search</a>
                </p>

                <p class="text-sm uppercase tracking-wide text-indigo-800">Start:</p>
                <p class="text-lg font-bold">{{ $start }}</p>

                <p class="text-sm uppercase tracking-wide text-indigo-800 mt-8">Destination:</p>
                <p class="text-lg font-bold">{{ $end }}</p>

                <p class="text-sm uppercase tracking-wide text-indigo-800 mt-8">Approximate Journey Time:</p>
                <p class="text-lg font-bold">{{ Time::inHoursAndMinutes($journeyTime) }}</p>

                <hr class="my-6">

                @if ($breaksNeeded == 0)
                <p>
                    Based on wanting to take a break at least every <span class="font-bold">
                    @if($breaksEveryHour > 0) {{ $breaksEveryHour }} {{ Str::plural('hour', $breaksEveryHour)}} @endif
                    @if($breaksEveryMinute > 0) {{ $breaksEveryMinute }} {{ Str::plural('minute', $breaksEveryMinute)}} @endif</span>, you 
                    can make the full journey without stopping!
                </p>
                @else

                <p>
                    Based on wanting to take a break at least every <span class="font-bold">
                    @if($breaksEveryHour > 0) {{ $breaksEveryHour }} {{ Str::plural('hour', $breaksEveryHour)}}@endif
                    @if($breaksEveryMinute > 0) {{ $breaksEveryMinute }} {{ Str::plural('minute', $breaksEveryMinute)}}@endif</span>, you 
                    should take <span class="font-bold">{{ $breaksNeeded }}</span> {{ Str::plural('break', $breaksNeeded) }} on your journey
                    {{ $breaksNeeded == 1 ? 'after' : 'spaced out every'}} <span class="font-bold">{{ Time::inHoursAndMinutes($breaksEvery)}}</span>.
                </p>

                <hr class="my-6">

                <p class="mb-4">
                    @if ($breaksNeeded == 1)
                    Here is an idea of somewhere to stop on the way:
                    @else
                    Here is some suggestions of places to stop on the way:
                    @endif
                </p>

                @foreach($breaks as $key => $break)
                <p class="text-sm tracking-wide text-indigo-800">
                    Stop {{ ($key + 1) }}:
                </p>
                <p class="text-lg font-bold mb-4">
                    <span class="mr-2">{{ $break['town'] }}</span>
                    <a class="text-sm font-normal underline" target="_blank" href="{{ route('place', [
                        'name' => $break['town'],
                        'lat' => $break['location']['lat'],
                        'long' => $break['location']['lng'],
                        'start' => $start,
                        'end' => $end,
                    ]) }}">Places to Visit</a>
                </p>
                @endforeach

                @endif
            </section>
        </div>

        <script>
        function initMap() {
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 7,
                center: { lat: 54.00366, lng: -2.547855 },
            });

            directionsRenderer.setMap(map);
            directionsService.route({
                origin: {
                    query: "{{ $start }}",
                },
                destination: {
                    query: "{{ $end }}",
                },
                travelMode: google.maps.TravelMode.DRIVING,
            },
            (response, status) => {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                } else {
                    window.alert("Directions request failed due to " + status);
                }
            }
            );

            @foreach ($breaks as $key => $break)
            new google.maps.Marker({
                position: {
                    lat: {{ $break['location']['lat'] }},
                    lng: {{ $break['location']['lng'] }}
                },
                map: map,
                label: '{{ ($key + 1) }}',
                title: "{{ $break['town'] }}"
            });
            @endforeach
        }
        </script>
    </body>
</html>