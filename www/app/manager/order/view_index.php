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
        <div class="container as castrulla">
            <button id="new-order" type="button" class="btn btn-primary">Нове замовлення</button>
            <output <?= $success ?> id="success" class="text-success">Замовлення успішно додано!</output>
        </div>

        <hr>

        <div class="container accordion" id="according-search">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapseTwo">
                        <h1>Пошук</h1>
                    </button>
                </h2>
                <div id="collapse-search" class="accordion-collapse collapse" data-bs-parent="#according-search">
                    <div class="accordion-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text">ПІБ клієнта</span>
                            <input id="search-first-name" type="text" class="form-control" placeholder="Прізвище">
                            <input id="search-last-name" type="text" class="form-control" placeholder="Ім'я">
                            <input id="search-father-name" type="text" class="form-control" placeholder="Патронім">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Номер телефону</span>
                            <input id="search-phone-number" type="text" class="form-control">
                            <label class="input-group-text" for="phone-number">+ZZZ (YY) XXX-XX-XX</label>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Номер автомобіля</span>
                            <input id="search-car" type="text" class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Дата</span>
                            <input id="search-start-date" type="date" class="form-control">
                            <label class="input-group-text">-</label>
                            <input id="search-end-date" type="date" class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Статус</span>
                            <select id="search-complete" class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">Завершено</option>
                                <option value="2">В процесі</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Оплата</span>
                            <select id="search-pay" class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">Оплачено</option>
                                <option value="2">Не оплачено</option>
                            </select>
                        </div>

                        <div class="gap-2 d-md-flex justify-content-md-end">
                            <button id="search" type="button" class="btn btn-primary">Пошук</button>
                            <button id="search-reset" type="button" class="btn btn-outline-primary">Скинути</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-left: 16px; margin-right: 16px;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ПІБ клієнта</th>
                        <th scope="col">Номер телефону</th>
                        <th scope="col">Автомобіль</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Оплата</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="table-body"></tbody>
            </table>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const btn_create = document.getElementById('new-order');
            const success = document.getElementById('success');

            const inp_search_first_name = document.getElementById('search-first-name');
            const inp_search_last_name = document.getElementById('search-last-name');
            const inp_search_father_name = document.getElementById('search-father-name');
            const inp_search_phone_number = document.getElementById('search-phone-number');
            const inp_search_car = document.getElementById('search-car');
            const inp_search_start_date = document.getElementById('search-start-date');
            const inp_search_end_date = document.getElementById('search-end-date');
            const inp_search_complete = document.getElementById('search-complete');
            const inp_search_pay = document.getElementById('search-pay');
            const btn_search = document.getElementById('search');
            const btn_search_reset = document.getElementById('search-reset');

            const table_body = document.getElementById('table-body');

            send();

            setTimeout(() => {
                success.hidden = true;
            }, 5000);

            /**
             * Функція для перенаправлення на сторінку створення замовлення
             * 
             * @returns void
             */
            btn_create.addEventListener('click', () => {
                window.location.href = '/manager/order/create';
            });

            /**
             * Функція для пошуку замовлень
             * 
             * @returns void
             */
            btn_search.addEventListener('click', () => {
                let search = {};
                let is_error = false;

                if (inp_search_first_name.value != '') {
                    if (inp_search_first_name.value.length < 3 || inp_search_first_name.value.length > 40) {
                        inp_search_first_name.classList.add('is-invalid');
                        is_error = true;
                    } else {
                        inp_search_first_name.classList.remove('is-invalid');
                        search.first_name = encodeURIComponent(inp_search_first_name.value);
                    }
                } else {
                    inp_search_first_name.classList.remove('is-invalid');
                }

                if (inp_search_last_name.value != '') {
                    if (inp_search_last_name.value.length < 3 || inp_search_last_name.value.length > 40) {
                        inp_search_last_name.classList.add('is-invalid');
                        is_error = true;
                    } else {
                        inp_search_last_name.classList.remove('is-invalid');
                        search.last_name = encodeURIComponent(inp_search_last_name.value);
                    }
                } else {
                    inp_search_last_name.classList.remove('is-invalid');
                }

                if (inp_search_father_name.value != '') {
                    if (inp_search_father_name.value.length < 3 || inp_search_father_name.value.length > 40) {
                        inp_search_father_name.classList.add('is-invalid');
                        is_error = true;
                    } else {
                        inp_search_father_name.classList.remove('is-invalid');
                        search.father_name = encodeURIComponent(inp_search_father_name.value);
                    }
                } else {
                    inp_search_father_name.classList.remove('is-invalid');
                }

                if (inp_search_phone_number.value != '') {
                    let re = /^[ ]*\+?([0-9]{2})[-\( ]*([0-9]{1})[-\( ]*([0-9]{2})[-\) ]*([0-9]{3})[- ]*([0-9]{2})[- ]*([0-9]{2})[ ]*$/gm;
                    let res = re.exec(inp_search_phone_number.value);
                    if (res) {
                        search.phone_number = encodeURIComponent(res.slice(1).join(''));
                        inp_search_phone_number.classList.remove('is-invalid');
                    } else {
                        inp_search_phone_number.classList.add('is-invalid');
                        inp_search_phone_number.placeholder = 'Невірний формат номеру телефону'
                        is_error = true;
                    }
                } else {
                    inp_search_phone_number.classList.remove('is-invalid');
                }

                if (inp_search_car.value != '') {
                    search.car = encodeURIComponent(inp_search_car.value);
                }

                if (inp_search_start_date.value != '') {
                    search.start_date = encodeURIComponent(inp_search_start_date.value);
                    inp_search_start_date.classList.remove('is-invalid');
                }

                if (inp_search_end_date.value != '') {
                    inp_search_end_date.classList.remove('is-invalid');
                    search.end_date = encodeURIComponent(inp_search_end_date.value);
                }

                if (inp_search_start_date.value != '' && inp_search_end_date.value != '') {
                    if (inp_search_start_date.value > inp_search_end_date.value) {
                        inp_search_start_date.classList.add('is-invalid');
                        inp_search_end_date.classList.add('is-invalid');
                        is_error = true;
                    } else {
                        inp_search_start_date.classList.remove('is-invalid');
                        inp_search_end_date.classList.remove('is-invalid');
                    }
                } else {
                    inp_search_start_date.classList.remove('is-invalid');
                    inp_search_end_date.classList.remove('is-invalid');
                }

                if (inp_search_complete.value != '') {
                    switch (inp_search_complete.value) {
                        case '1':
                            search.complete = encodeURIComponent('true');
                            break;
                        case '2':
                            search.complete = encodeURIComponent('false');
                            break;
                    }
                }

                if (inp_search_pay.value != '') {
                    switch (inp_search_pay.value) {
                        case '1':
                            search.pay = encodeURIComponent('true');
                            break;
                        case '2':
                            search.pay = encodeURIComponent('false');
                            break;
                    }
                }

                send(search);
            });

            /**
             * Функція для скидання параметрів пошуку
             * 
             * @returns void
             */
            btn_search_reset.addEventListener('click', () => {
                inp_search_first_name.value = '';
                inp_search_last_name.value = '';
                inp_search_father_name.value = '';
                inp_search_phone_number.value = '';
                inp_search_car.value = '';
                inp_search_start_date.value = '';
                inp_search_end_date.value = '';
                inp_search_complete.value = '';
                inp_search_pay.value = '';
            });

            /**
             * Функція для відправки запиту на сервер
             * 
             * @param {Object} search - об'єкт з параметрами пошуку
             * @returns void
             */
            function send(search = {}) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/manager/order/search');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        table_body.innerHTML = '';
                        let data = JSON.parse(xhr.responseText);
                        for (let i = 0; i < data.length; i++) {
                            let tr = document.createElement('tr');
                            tr.classList.add('col-12', 'col-lg-auto', 'my-2', 'justify-content-center', 'my-md-0');
                            tr.innerHTML = '<th score="row">' + (i + 1) + '</th>';
                            tr.innerHTML += '<td>' + sum_strs(' ', data[i].first_name, data[i].last_name, data[i].fathers_name) + '</td>';
                            tr.innerHTML += '<td>' + '+' + data[i].phone_number.slice(0, 3) + ' (' + data[i].phone_number.slice(3, 5) + ') ' + data[i].phone_number.slice(5, 8) + '-' + data[i].phone_number.slice(8, 10) + '-' + data[i].phone_number.slice(10, 12) + '</td>';
                            tr.innerHTML += '<td>' + sum_strs(' - ', data[i].make, data[i].model, data[i].license_plate) + '</td>';
                            tr.innerHTML += '<td>' + data[i].create_date + '</td>';
                            tr.innerHTML += '<td>' + (data[i].completed ? 'Завершено' : 'В процесі') + '</td>';
                            tr.innerHTML += '<td>' + (data[i].payment ? 'Оплачено' : 'Не оплачено') + '</td>';
                            tr.innerHTML += '<td>' + '<button type="button" class="edit btn" style="padding: 0;" onclick=edit(' + data[i]['id'] + ')><img src="/data/icon/edit.png" width="20px" height="20px"></button>' + '</td>'

                            table_body.appendChild(tr);
                        }
                    }
                };
                xhr.send(post_str(search));
            }
        });

        /**
         * Функція для перетворення об'єкта в строку
         * 
         * @param {Object} obj - об'єкт
         * @returns {String} - строка
         */
        function post_str(obj) {
            let str = '';
            for (let p in obj) {
                str += encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]);
                str += '&';
            }
            return str.slice(0, -1);
        }

        /**
         * Функція для склеювання строк
         * 
         * @param {String} split - розділювач
         * @param  {...String} theArgs - строки
         * @returns {String} - склеєна строка
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

        /**
         * Функція для перенаправлення на сторінку редагування працівника
         * 
         * @param {Number} i - id працівника
         * @returns void
         */
        function edit(i) {
            window.location.href = '/manager/order/edit/' + i;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>