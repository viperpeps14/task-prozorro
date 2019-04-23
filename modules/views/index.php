<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/template/style/style.css">
        <link rel="stylesheet" href="/template/style/progress.css">
        <link rel="stylesheet" href="/template/style/sweet-alert.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        
         <script src="/template/js/jquery-3.3.1.min.js"></script>
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <title>Tenders</title>
    </head>
    <body>
    <div class='container'>
        <header>
            <img src="/template/img/logo-ua.png"/>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="/">Головна</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/pages/synchronicity">Синхронізація</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/pages/tenders">Тендери</a>
                  </li>
                </ul>
              </div>
            </nav>
        </header>
        <div class="content">
            <?= $content; ?>
        </div>
    </div>
    <script src="/template/js/sweet-alert.min.js"></script>
    <script src="/template/js/main.js"></script>
    </body>
</html>
