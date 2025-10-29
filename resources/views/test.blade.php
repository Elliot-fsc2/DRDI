<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>

<body>
    <div x-data="{
        counter: 0
    }">
        <div>
            <h1 x-text="counter"></h1>
            <button @click="counter++">Increment</button>
        </div>
    </div>
</body>

</html>
