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
            <div style="margin-bottom: 16px;">
                <button id="new_service" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-service">Нова послуга</button>
                <output hidden id="success" class="text-success">Послугу успішно додано!</output>

                <div class="modal fade" id="add-service" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Додавання послуги</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Назва</span>
                                    <input id="name" type="text" class="form-control">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Опис</span>
                                    <textarea id="description" class="form-control"></textarea>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Ціна</span>
                                    <input id="price" type="text" class="form-control">
                                    <label class="input-group-text">₴</label>
                                    <label class="input-group-text">0.00</label>
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
            <div class="input-group mb-3">
                <input id="input-search" type="text" class="form-control" aria-describedby="search">
                <button class="btn btn-primary" type="button" id="search">Пошук</button>
            </div>
            <div style="margin-top: 16px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Назва</th>
                            <th scope="col">Ціна</th>
                            <th scope="col">Опис</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="table-group-divider"></tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('add-service'));
            const success = document.getElementById('success');
            const error = document.getElementById('error');

            const inp_name = document.getElementById('name');
            const inp_description = document.getElementById('description');
            const inp_price = document.getElementById('price');

            const btn_create = document.getElementById('create');
            const btn_new_service = document.getElementById('new_service');

            const inp_search = document.getElementById('input-search');
            const btn_search = document.getElementById('search');

            const table_body = document.getElementById('table-body');

            search();

            /**
             * Функція для очищення полів вводу при додаанні нової послуги
             * 
             * @returns void
             */
            btn_new_service.addEventListener('click', () => {
                inp_name.value = '';
                inp_description.value = '';
                inp_price.value = '';
            });

            /**
             * Функція для додавання нової послуги
             * 
             * @returns void
             */
            btn_create.addEventListener('click', () => {
                let is_error = false;

                let data = {
                    name: inp_name.value,
                    description: inp_description.value,
                    price: inp_price.value
                };

                if (data.name.length < 3 || data.name.length > 40) {
                    inp_name.classList.add('is-invalid');
                    inp_name.placeholder = 'Введіть назву товару';
                    is_error = true;
                } else {
                    inp_name.classList.remove('is-invalid');
                }

                if (data.price <= 0) {
                    inp_price.classList.add('is-invalid');
                    inp_price.placeholder = 'Введіть ціну товару';
                    is_error = true;
                } else {
                    data.price = parseFloat(data.price);
                    if (isNaN(data.price)) {
                        inp_price.classList.add('is-invalid');
                        inp_price.placeholder = 'Введіть ціну товару';
                        is_error = true;
                    } else {
                        if (data.price <= 0) {
                            inp_price.classList.add('is-invalid');
                            inp_price.placeholder = 'Введіть ціну товару';
                            is_error = true;
                        } else {
                            inp_price.classList.remove('is-invalid');
                        }
                    }
                }

                if (is_error) {
                    error.hidden = false;
                    error.innerHTML = 'Заповніть всі поля!';
                    return;
                } else {
                    error.hidden = true;
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/service/create');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        success.hidden = false;
                        setTimeout(() => {
                            success.hidden = true;
                        }, 5000);
                        modal.hide();
                        search();
                    } else {
                        error.hidden = false;
                        error.innerHTML = xhr.response;
                    }
                };
                xhr.send(post_str(data));
            });

            /**
             * Функція для пошуку послуги
             * 
             * @returns void
             */
            btn_search.addEventListener('click', search);

            /**
             * Функція для надсилання запиту на пошук послуги
             * 
             * @returns void
             */
            function search() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/service/search');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let result = JSON.parse(xhr.responseText);
                        table_body.innerHTML = '';
                        for (let i = 0; i < result.length; i++) {
                            let tr = document.createElement('tr');
                            tr.innerHTML = '<th scope="row">' + (i + 1) + '</th>'
                            tr.innerHTML += '<td>' + result[i].service_name + '</td>'
                            tr.innerHTML += '<td>' + result[i].price + '</td>'
                            tr.innerHTML += '<td>' + result[i].description + '</td>'
                            tr.innerHTML += '<td>' + '<button type="button" class="edit btn" style="padding: 0;" onclick=edit(' + result[i]['id'] + ')><img src="/data/icon/edit.png" width="20px" height="20px"></button>' + '</td>'
                            table_body.appendChild(tr);
                        }
                    }
                };
                xhr.send(post_str({
                    "search": inp_search.value
                }));
            }
        });

        /**
         * Функція для перетворення об'єкту в строку для відправки URL-запитом
         * 
         * @param data - об'єкт для перетворення
         * @returns string
         */
        function post_str(data) {
            let str = '';
            for (let key in data) {
                str += encodeURIComponent(key) + '=' + encodeURIComponent(data[key]) + '&';
            }
            return str.slice(0, -1);
        }

        /**
         * Функція для переходу на сторінку редагування послуги
         * 
         * @param id - id послуги
         * @returns void
         */
        function edit(id) {
            window.location.href = '/admin/service/edit/' + id;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>