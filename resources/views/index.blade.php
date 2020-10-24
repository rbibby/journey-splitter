<html>
    <head>
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

        <title>Journey Planner</title>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>

    <body class="bg-cover" style="background-image: url('{{ asset('img/background2.jpg') }}'">
        <div class="h-screen w-screen flex items-center justify-center">

            <div class="bg-white shadow-lg p-8 mx-8 w-full md:w-1/4">
                <h1 class="text-3xl font-light mb-4 text-gray-800">Journey Planner</h1>

                @if ($errors->any())
                    <div class="bg-red-200 border border-red-500 text-red-900 mb-4 p-4">
                        <p class="mb-1">
                            Please correct the following errors:
                        </p>
                        <ul class="list-disc ml-6">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('calculate-route') }}" method="GET">
                    <div class="block mb-4">
                        <label for="start" class="block text-sm text-gray-800 mb-1">Start:</label>
                        <input type="text" name="start" value="{{ old('start') }}" class="w-full bg-gray-100 border border-gray-500 py-2 px-4 rounded focus:border-2 focus:border-gray-500 appearance-none outline-none">
                    </div>
                   
                    <div class="block mb-4">
                        <label for="destination" class="block text-sm text-gray-800 mb-1">Destination:</label>
                        <input type="text" name="destination" value="{{ old('destination') }}" class="w-full bg-gray-100 border border-gray-500 py-2 px-4 rounded focus:border-2 focus:border-gray-500 appearance-none outline-none">
                    </div>

                    <div class="block mb-4">
                        <label for="" class="block text-sm text-gray-800 mb-1">Stop every:</label>
                        <div class="flex flex-wrap">
                            <select name="hours" id="" class="w-full md:w-1/2 bg-gray-100 border border-gray-500 py-2 px-4 rounded-l focus:border-2 focus:border-gray-500 appearance-none outline-none">
                            @for($i = 0; $i <= 4; $i++)
                                <option value="{{ $i }}" @if(old('hours', 2) == $i) selected @endif>{{ $i }} {{ Str::plural('hour', $i) }}</option>
                            @endfor
                            </select>

                            <select name="minutes" id="" class="w-full md:w-1/2 bg-gray-100 border-t border-r border-b border-gray-500 py-2 px-4 rounded-r focus:border-2 focus:border-gray-500 appearance-none outline-none">
                            @foreach([0, 15, 30, 45] as $i)
                                <option value="{{ $i }}"  @if(old('minutes', 0) == $i) selected @endif>{{ $i }} {{ Str::plural('minute', $i) }}</option>
                            @endforeach
                            </select>
                        </div>
                        <p class="text-xs text-light text-gray-700 mt-2">
                            It is advised you take a 15 minute break after every 2 hours of driving
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button class="text-lg font-light text-white bg-indigo-700 border border-indigo-800 w-full md:w-auto px-4 py-2 rounded hover:shadow-lg hover:bg-indigo-800" type="submit">Plan Route ></button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>