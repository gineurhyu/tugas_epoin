<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>

<body>
    <a class="nav-link" href="{{ route('siswa.index') }}">Data Siswa</a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
    <h1>Dashboard Admin</h1>
    @if ($message = Session:get('success'))
    <p>{{ $message }}</p>
    @else
    <p>You are logged in!</p>
    @endif

</body>

<footer>

</footer>

</html>