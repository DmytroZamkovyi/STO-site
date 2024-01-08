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
    <main>
        <div class="container">
            <div style="margin-top: 16px;">
                <button id="new-client" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-client">Новий клієнт</button>
                <output hidden id="success" class="text-success">Користувача успішно додано!</output>

                <div class="modal fade" id="add-client" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Створення акаунту клієнта</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">ПІБ</span>
                                    <input id="first-name" type="text" class="form-control" placeholder="Прізвище">
                                    <input id="last-name" type="text" class="form-control" placeholder="Ім'я">
                                    <input id="father-name" type="text" class="form-control" placeholder="Патронім">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Номер телефону</span>
                                    <input id="phone-number" type="text" class="form-control">
                                    <label class="input-group-text" for="phone-number">+ZZZ (YY) XXX-XX-XX</label>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Знижка (опціонально)</span>
                                    <input id="discount" type="text" class="form-control">
                                    <span class="input-group-text">₴</span>
                                    <span class="input-group-text">0.00</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <output hidden id="error" class="text-danger"></output>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Відміна</button>
                                <button id="create" type="button" class="btn btn-primary">Створити</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="accordion" id="according-search">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapseTwo">
                            <h1>Пошук</h1>
                        </button>
                    </h2>
                    <div id="collapse-search" class="accordion-collapse collapse" data-bs-parent="#according-search">
                        <div class="accordion-body">
                            <div class="input-group mb-3">
                                <span class="input-group-text">ПІБ</span>
                                <input id="search-first-name" type="text" class="form-control" placeholder="Прізвище">
                                <input id="search-last-name" type="text" class="form-control" placeholder="Ім'я">
                                <input id="search-father-name" type="text" class="form-control" placeholder="Патронім">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Номер телефону</span>
                                <input id="search-phone-number" type="text" class="form-control">
                                <label class="input-group-text" for="phone-number">+ZZZ (YY) XXX-XX-XX</label>
                            </div>

                            <div class="gap-2 d-md-flex justify-content-md-end">
                                <button id="search" type="button" class="btn btn-primary">Пошук</button>
                                <button id="search-reset" type="button" class="btn btn-outline-primary">Скинути</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 16px;">
                <button id="reload" type="button" class="btn btn-outline-success ms-auto">Оновити</button>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ПІБ</th>
                            <th scope="col">Телефон</th>
                            <th scope="col">Знижка</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="table-group-divider"></tbody>
                </table>
            </div>
    </main>

    <script>
        let client = Object();
        window.addEventListener("DOMContentLoaded", () => {
            const modal = new bootstrap.Modal(document.getElementById('add-client'));
            const btn_new_client = document.getElementById("new-client");
            const btn_create = document.getElementById("create");
            const success = document.getElementById("success");
            const error = document.getElementById("error");
            const inp_first_name = document.getElementById("first-name");
            const inp_last_name = document.getElementById("last-name");
            const inp_father_name = document.getElementById("father-name");
            const inp_phone_number = document.getElementById("phone-number");
            const inp_discount = document.getElementById("discount");

            const inp_search_first_name = document.getElementById("search-first-name");
            const inp_search_last_name = document.getElementById("search-last-name");
            const inp_search_father_name = document.getElementById("search-father-name");
            const inp_search_phone_number = document.getElementById("search-phone-number");
            const btn_search = document.getElementById("search");
            const btn_search_reset = document.getElementById("search-reset");
            const btn_reload = document.getElementById("reload");
            const table_body = document.getElementById("table-body");

            send();

            /**
             * Функція для очищення полів форми при відкритті модального вікна
             * 
             * @returns {void}
             */
            btn_new_client.addEventListener("click", () => {
                success.hidden = true;
                error.hidden = true;
                inp_first_name.value = "";
                inp_last_name.value = "";
                inp_father_name.value = "";
                inp_phone_number.value = "";
                inp_discount.value = "";
            });

            /**
             * Функція для створення нового користувача
             * 
             * @returns {void}
             */
            btn_create.addEventListener("click", () => {
                let is_error = false;
                const data = {
                    first_name: inp_first_name.value,
                    last_name: inp_last_name.value,
                    father_name: inp_father_name.value,
                    phone_number: inp_phone_number.value,
                    discount: parseInt(inp_discount.value)
                };

                if (data.first_name.length < 3 || data.first_name.length > 40) {
                    inp_first_name.classList.add("is-invalid");
                    is_error = true;
                } else {
                    inp_first_name.classList.remove("is-invalid");
                }

                if (data.last_name.length < 3 || data.last_name.length > 40) {
                    inp_last_name.classList.add("is-invalid");
                    is_error = true;
                } else {
                    inp_last_name.classList.remove("is-invalid");
                }

                if (data.father_name.length < 3 || data.father_name.length > 40) {
                    inp_father_name.classList.add("is-invalid");
                    is_error = true;
                } else {
                    inp_father_name.classList.remove("is-invalid");
                }

                if (data.phone_number == '') {
                    inp_phone_number.classList.add('is-invalid');
                    inp_phone_number.placeholder = 'Введіть номер телефону'
                    is_error = true;
                } else {
                    inp_phone_number.classList.remove('is-invalid');
                    let re = /^[ ]*\+?([0-9]{2})[-\( ]*([0-9]{1})[-\( ]*([0-9]{2})[-\) ]*([0-9]{3})[- ]*([0-9]{2})[- ]*([0-9]{2})[ ]*$/gm;
                    let res = re.exec(data.phone_number);
                    if (res) {
                        data.phone_number = res.slice(1).join('');
                    } else {
                        inp_phone_number.classList.add('is-invalid');
                        inp_phone_number.placeholder = 'Невірний формат номеру телефону'
                        is_error = true;
                    }
                }

                if (data.discount < 0 || data.discount > 100) {
                    inp_discount.classList.add("is-invalid");
                    is_error = true;
                } else {
                    if (data.discount == '') {
                        delete data.discount;
                    }
                    inp_discount.classList.remove("is-invalid");
                }

                if (is_error) {
                    error.hidden = false;
                    error.innerHTML = "Перевірте правильність введених даних!";
                    return;
                }

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/admin/client/create");
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        success.hidden = false;
                        error.hidden = true;
                        modal.hide();

                        setTimeout(() => {
                            success.hidden = true;
                        }, 10000);

                        search();
                    } else {
                        error.hidden = false;
                        error.innerHTML = "ERROR: Неможливо створити користувача";
                    }
                };
                xhr.send(post_str(data));

            });

            /**
             * Функція скидування параметрів пошуку
             * 
             * @param {Number} id - id користувача
             * @returns {void}
             */
            btn_search_reset.addEventListener("click", () => {
                inp_search_first_name.value = "";
                inp_search_last_name.value = "";
                inp_search_father_name.value = "";
                inp_search_phone_number.value = "";
                search();
            });

            btn_reload.addEventListener("click", send);
            btn_search.addEventListener("click", search);

            /**
             * Функція для пошуку користувачів
             * 
             * @returns {void}
             */
            function search() {
                let data = {
                    first_name: inp_search_first_name.value,
                    last_name: inp_search_last_name.value,
                    father_name: inp_search_father_name.value,
                    phone_number: inp_search_phone_number.value
                };

                if (data.first_name == '') {
                    delete data.first_name;
                }

                if (data.last_name == '') {
                    delete data.last_name;
                }

                if (data.father_name == '') {
                    delete data.father_name;
                }

                if (data.phone_number == '') {
                    delete data.phone_number;
                }

                if (data.phone_number == '') {
                    delete data.phone_number;
                }

                send(data);
            }

            /**
             * Функція для відправки запиту
             * 
             * @param {Array} inp_data - дані для відправки
             * @returns void
             */
            function send(inp_data = Array()) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/client/search');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        document.getElementById('table_body').innerHTML = '';
                    } else {
                        document.getElementById('error').hidden = true;
                        let result = JSON.parse(xhr.responseText);
                        client = result;
                        table_body.innerHTML = '';
                        for (let i = 0; i < result.length; i++) {
                            let tr = document.createElement('tr');
                            tr.classList.add('col-12', 'col-lg-auto', 'my-2', 'justify-content-center', 'my-md-0');
                            tr.innerHTML = '<th scope="row">' + (i + 1) + '</th>'
                            tr.innerHTML += '<td>' + sum_strs(' ', result[i]['first_name'], result[i]['last_name'], result[i]['fathers_name']) + '</td>'
                            tr.innerHTML += '<td>' + result[i]['phone_number'] + '</td>'
                            if (result[i]['discount'] == null) {
                                result[i]['discount'] = '-';
                            }
                            tr.innerHTML += '<td>' + result[i]['discount'] + '</td>'
                            tr.innerHTML += '<td>' + '<button type="button" class="edit btn" style="padding: 0;" onclick=edit(' + result[i]['id'] + ')><img src="/data/icon/edit.png" width="20px" height="20px"></button>' + '</td>'
                            table_body.appendChild(tr);
                        }
                    }
                };
                xhr.send(post_str(inp_data));
            }
        });

        /**
         * Функція для перетворення об'єкту в строку
         * 
         * @param {Object} data - об'єкт
         * @returns {String} - строка
         */
        function post_str(data) {
            let str = '';
            for (let key in data) {
                if (data[key] == undefined) {
                    continue;
                }
                str += encodeURIComponent(key) + '=' + encodeURIComponent(data[key]) + '&';
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
         * Функція для перенаправлення на сторінку редагування
         * 
         * @param {Number} id - id клієнта
         * @returns {void}
         */
        function edit(id) {
            window.location.href = '/admin/client/edit/' + id;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>