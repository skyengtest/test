<html>
<head>
    <title>HELP PAGE</title>
</head>
<body>
<p>
    <strong>Примеры URL:</strong>
    <br/>
    <a target="_blank" href="/test/get?a=1&b=2">{{HOST}}/test/get?a=1&b=2</a> - Только для GET-запросов<br/>
    <a target="_blank" href="/test/post?a=1&b=2">{{HOST}}/test/post?a=1&b=2</a> - Только для POST-запросов
    (иначе - страница
    по-умолчанию)<br/>
    <a target="_blank" href="/test/">{{HOST}}/test/</a> - Просто статическая страница<br/>
    <a target="_blank" href="/news/{{date}}/{{id}}">{{HOST}}/news/{{date}}/{{id}}</a> - Пример с передачей части URL в
    экшн<br/>
    <a target="_blank" href="/error">{{HOST}}/error</a> - Намеренная ошибка в коде - проверка работоспособности отображения ошибок<br/>
</p>
</body>
</html>