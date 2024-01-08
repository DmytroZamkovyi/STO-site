<!DOCTYPE html>
<html data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Dmytro Zamkovyi">
    <title><?= $title ?></title>
    <link rel="shortcut icon" href="/data/favicon.ico" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <?= $head ?>

    <main style="margin-top: 16px;">
        <div class="container">
            <div class="input-group mb-3">
                <input id="input-search" type="text" class="form-control" aria-describedby="search">
                <button class="btn btn-primary" type="button" id="search">Пошук</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Автомобіль</th>
                        <th>Завдання</th>
                        <th style="width: 60px;">Виконано</th>
                    </tr>
                </thead>
                <tbody id="table"></tbody>
            </table>
        </div>
    </main>

    <script>
        let data = Array();
        window.addEventListener('DOMContentLoaded', () => {
            const inp_search = document.getElementById('input-search');
            const btn_search = document.getElementById('search');
            const table = document.getElementById('table');

            const check = document.getElementsByClassName('checkbox');

            send();
            setInterval(send, 60000);

            btn_search.addEventListener('click', send);

            /**
             * Функція відправки запиту на сервер
             * 
             * @return void
             */
            function send() {
                const search = inp_search.value;
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/employee/task/search');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        let result = JSON.parse(xhr.response);
                        if (result !== data) {
                            data = result;
                            render(data);
                        }
                    }
                };
                xhr.send(post_str({
                    "search": search
                }));
            }

            /**
             * Функція рендерингу таблиці
             * 
             * @param {Array} data - дані для рендерингу
             * @return void
             */
            function render(data) {
                table.innerHTML = '';
                for (let i = 0; i < data.length; i++) {
                    let tr = document.createElement('tr');
                    tr.innerHTML = '<th scope="row">' + (i + 1) + '</th>'
                    tr.innerHTML += '<td>' + sum_strs(' - ', data[i]['make'], data[i]['model'], data[i]['license_plate']) + '</td>'
                    tr.innerHTML += '<td>' + data[i]['service_name'] + '</td>'
                    tr.innerHTML += '<td>' + '<div class="form-check form-check-reverse"><input id="checkbox-' + data[i]['id'] + '" class="form-check-input checkbox" type="checkbox"></div>' + '</td>'
                    table.appendChild(tr);
                }

                for (let i = 0; i < check.length; i++) {
                    check[i].addEventListener('change', () => {
                        if (check[i].checked) {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', '/employee/task/complete/' + data[i]['id']);
                            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.onload = function() {
                                if (xhr.status == 200) {
                                    console.log(xhr.response);
                                    send();
                                } else {
                                    document.getElementById('checkbox-' + xhr.responseText).checked = false;
                                }
                            };
                            xhr.send();
                        }

                    });
                }
            }
        });

        /**
         * Функція перетворення об'єкту в строку
         * 
         * @param {Object} data - об'єкт для перетворення
         * @return {String} - перетворений об'єкт
         */
        function post_str(data) {
            let str = '';
            for (let key in data) {
                str += encodeURIComponent(key) + '=' + encodeURIComponent(data[key]) + '&';
            }
            return str.slice(0, -1);
        }

        /**
         * Функція склеювання строк
         * 
         * @param {String} split - розділювач
         * @param  {...any} theArgs - строки для склеювання
         * @return {String} - склеєна строка
         */
        function sum_strs(split, ...theArgs) {
            let total = '';
            for (const arg of theArgs) {
                if (arg == null) {
                    continue;
                }
                total += arg + split;
            }
            return total.slice(0, -split.length);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>