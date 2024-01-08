<!DOCTYPE html>
<html data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Dmytro Zamkovyi">
    <title><?= $title ?></title>
    <link rel="shortcut icon" href="/data/favicon.ico" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <main class="form-signin w-100 m-auto">
        <h1 class="h3 mb-3 fw-normal m-auto" style="text-align: center;">Вхід</h1>
        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="login">
            <label for="floatingInput">Логін</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
            <label for="floatingPassword">Пароль</label>
        </div>
        <output hidden id="auth-error" class="text-danger">Неправильний логін чи пароль</output>
        <output hidden id="log-error" class="text-danger">Логін не може бути коротше 3-х символів</output>
        <output hidden id="pwd-error" class="text-danger">Пароль не може бути коротше 3-х символів</output>
        <button id="signin" class="btn btn-primary w-100 py-2" type="submit">Увійти</button>
    </main>

    <style>
        html,
        body {
            height: 100%;
        }

        .form-signin {
            max-width: 330px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function(e) {
            let btn = document.getElementById('signin');
            let login = document.getElementById('floatingInput');
            let password = document.getElementById('floatingPassword');
            let authError = document.getElementById('auth-error');
            let logError = document.getElementById('log-error');
            let pwdError = document.getElementById('pwd-error');

            /**
             * ajax запит на авторизацію
             * 
             * @return void
             */
            btn.addEventListener('click', function(e) {
                btn.disabled = true;
                let error = false;

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/index/index/index');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        window.location.href = '/index/index/index';
                    } else {
                        authError.hidden = false;
                        btn.disabled = false;
                    }
                };
                xhr.send('login=' + encodeURIComponent(login.value) + '&password=' + encodeURIComponent(password.value));
            });
        });
    </script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
</body>

</html>