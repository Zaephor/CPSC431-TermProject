<html>
<body>
<p>
    {{ $session->course->department->title }}<br/>
    {{ $session->course->department->code }}{{ $session->course->code }}<br/>
    Session {{ $session->id }}<br/>
    {{ $session->professor->name }}<br/>
    {{ $session->professor->email }}<br/>
    {{ $session->room }}<br/>
</p>

<p>
    Hello?
</p>
</body>
</html>