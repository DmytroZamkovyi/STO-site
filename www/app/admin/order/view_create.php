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
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a id="back" href="/admin/order/index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="margin-left: 16px;">
            <img class="bi me-2" width="32" height="32" src="/data/icon/return.png">
            <span class="fs-4">Назад</span>
        </a>

        <button id="save" type="button" class="btn btn-primary" style="margin: 0 8px;" hidden>Зберегти</button>
    </header>
    <main>
        <div class="container">
            <div class="input-group mb-3">
                <span class="input-group-text">Замовник</span>
                <input id="client" type="text" class="form-control" disabled>
                <button id="add-client-btn" class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#add-client-model">Вибрати</button>
            </div>

            <div id="car-div" class="input-group mb-3" hidden>
                <span class="input-group-text">Автомобіль</span>
                <input id="car" type="text" class="form-control" disabled>
                <button id="add-car-btn" class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#add-car-model">Вибрати</button>
            </div>

            <div id="sto-list-div" class="input-group mb-3" hidden>
                <span class="input-group-text">СТО</span>
                <select id="sto-list" class="form-select" autocomplete="off">
                    <option selected hidden value=""></option>
                    <? foreach ($sto_list as $item) : ?>
                        <option value="<?= $item['sto_name'] ?>"><?= $item['sto_name'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>

            <p id="error" class="text-danger" hidden></p>
        </div>

        <div id="new-task-div" class="container" hidden>
            <button id="new-task" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-task-model">Нове завдання</button>
        </div>

        <div id="table-div" style="margin-left: 16px; margin-right: 16px;" hidden>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Назва послуги</th>
                        <th scope="col">Виконавець</th>
                        <th scope="col">Ціна</th>
                        <th scope="col">Виконано</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="table-body"></tbody>
            </table>
        </div>

        <div class="modal fade" id="add-client-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Додавання клієнта</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input id="client-search" type="text" class="form-control" placeholder="Пошук">
                            <button id="client-search-btn" class="btn btn-outline-primary" type="button">Знайти</button>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">ПІБ</span>
                            <input id="first-name" type="text" class="form-control" placeholder="Прізвище">
                            <input id="last-name" type="text" class="form-control" placeholder="Ім'я">
                            <input id="fathers-name" type="text" class="form-control" placeholder="Патронім">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Номер телефону</span>
                            <input id="phone-number" type="text" class="form-control">
                            <label class="input-group-text" for="phone-number">+ZZZ (YY) XXX-XX-XX</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <output hidden id="client-error" class="text-danger"></output>
                        <button id="add-client" type="button" class="btn btn-primary">Додати</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add-car-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Додавання автомобіля</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input id="car-search" type="text" class="form-control" placeholder="Пошук">
                            <button id="car-search-btn" class="btn btn-outline-primary" type="button">Знайти</button>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Марка</span>
                            <input id="make" type="text" class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Модель</span>
                            <input id="model" type="text" class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Рік випуску</span>
                            <input id="year" type="text" class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Номерний знак</span>
                            <input id="license-plate" type="text" class="form-control">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Опис (опціонально)</span>
                            <textarea id="description" class="form-control" aria-label="With textarea"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <output hidden id="car-error" class="text-danger"></output>
                        <button id="add-car" type="button" class="btn btn-primary">Додати</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add-task-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Створення акаунту клієнта</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Працівник</span>
                            <input id="employee" type="text" class="form-control" list="employee-list">
                            <datalist id="employee-list"></datalist>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Завдання</span>
                            <input id="task" type="text" class="form-control" list="task-list">
                            <datalist id="task-list"></datalist>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <output hidden id="error" class="text-danger"></output>
                        <button id="create" type="button" class="btn btn-primary">Створити</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const task_modal = new bootstrap.Modal(document.getElementById('add-task-model'));
            const client_modal = new bootstrap.Modal(document.getElementById('add-client-model'));
            const car_modal = new bootstrap.Modal(document.getElementById('add-car-model'));

            const inp_client = document.getElementById('client');
            const inp_car = document.getElementById('car');
            const inp_sto_list = document.getElementById('sto-list');
            const error = document.getElementById('error');
            const btn_new_task = document.getElementById('new-task');

            const table_div = document.getElementById('table-div');
            const new_task_div = document.getElementById('new-task-div');
            const car_div = document.getElementById('car-div');
            const sto_list_div = document.getElementById('sto-list-div');

            const inp_client_search = document.getElementById('client-search');
            const btn_client_search = document.getElementById('client-search-btn');
            const client_error = document.getElementById('client-error');
            const btn_add_client = document.getElementById('add-client');
            const inp_client_first_name = document.getElementById('first-name');
            const inp_client_last_name = document.getElementById('last-name');
            const inp_client_fathers_name = document.getElementById('fathers-name');
            const inp_client_phone_number = document.getElementById('phone-number');

            const inp_car_search = document.getElementById('car-search');
            const btn_car_search = document.getElementById('car-search-btn');
            const car_error = document.getElementById('car-error');
            const btn_add_car = document.getElementById('add-car');
            const inp_car_make = document.getElementById('make');
            const inp_car_model = document.getElementById('model');
            const inp_car_year = document.getElementById('year');
            const inp_car_license_plate = document.getElementById('license-plate');
            const inp_car_description = document.getElementById('description');

            const btn_client = document.getElementById('add-client-btn');
            const btn_car = document.getElementById('add-car-btn');

            const employee_list = document.getElementById('employee-list');
            const task_list = document.getElementById('task-list');

            const btn_create = document.getElementById('create');
            const inp_employee = document.getElementById('employee')
            const inp_task = document.getElementById('task');

            const btn_save = document.getElementById('save');

            let client_id = null;
            let car_id = null;
            let order_id = null;

            inp_client.value = '';
            inp_car.value = '';
            task_list.value = '';

            /**
             * Функція для очищення полів
             * 
             * @param void
             */
            btn_client.addEventListener('click', () => {
                inp_client_search.value = '';
                inp_client_first_name.value = '';
                inp_client_last_name.value = '';
                inp_client_fathers_name.value = '';
                inp_client_phone_number.value = '';
            });

            /**
             * Функція для пошуку клієнта
             * 
             * @param void
             */
            btn_client_search.addEventListener('click', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/search_client');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        client_error.hidden = true;
                        client_id = data[0].id;
                        inp_client_first_name.value = data[0].first_name;
                        inp_client_last_name.value = data[0].last_name;
                        inp_client_fathers_name.value = data[0].fathers_name;
                        inp_client_phone_number.value = data[0].phone_number;
                    } else {
                        client_error.hidden = false;
                        client_error.innerText = 'Клієнт не знайдений';
                        setTimeout(() => {
                            client_error.hidden = true;
                        }, 3000);
                    }
                };
                xhr.send(post_str({
                    'search': inp_client_search.value
                }));
            });

            /**
             * Функція для додавання клієнта
             * 
             * @param void
             */
            btn_add_client.addEventListener('click', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/add_client');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        client_modal.hide();
                        let data = JSON.parse(xhr.response);
                        inp_client.value = sum_strs(' ', data[0].first_name, data[0].last_name, data[0].fathers_name);
                        client_id = data[0].id;
                        car_div.hidden = false;
                    } else {
                        client_error.hidden = false;
                        client_error.innerText = 'Клієнт не доданий';
                        setTimeout(() => {
                            client_error.hidden = true;
                        }, 3000);
                    }
                };
                xhr.send(post_str({
                    'first_name': inp_client_first_name.value,
                    'last_name': inp_client_last_name.value,
                    'fathers_name': inp_client_fathers_name.value,
                    'phone_number': inp_client_phone_number.value
                }));
            });
            // TODO: Додати перевірку на введення даних
            
            /**
             * Функція для очищення полів
             * 
             * @param void
             */
            btn_car.addEventListener('click', () => {
                inp_car_search.value = '';
                inp_car_make.value = '';
                inp_car_model.value = '';
                inp_car_year.value = '';
                inp_car_license_plate.value = '';
                inp_car_description.value = '';
            });

            /**
             * Функція для пошуку автомобіля
             * 
             * @param void
             */
            btn_car_search.addEventListener('click', () => {
                console.log(111);
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/search_car');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        car_error.hidden = true;
                        inp_car_make.value = data[0].make;
                        inp_car_model.value = data[0].model;
                        inp_car_year.value = data[0].car_year;
                        inp_car_license_plate.value = data[0].license_plate;
                        inp_car_description.value = data[0].description;
                    } else {
                        car_error.hidden = false;
                        car_error.innerText = 'Автомобіль не знайдений';
                        setTimeout(() => {
                            car_error.hidden = true;
                        }, 3000);
                    }
                };
                xhr.send(post_str({
                    'search': inp_car_search.value
                }));
            });

            /**
             * Функція для додавання автомобіля
             * 
             * @param void
             */
            btn_add_car.addEventListener('click', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/add_car');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        car_modal.hide();
                        let data = JSON.parse(xhr.response);
                        inp_car.value = sum_strs(' ', data[0].make, data[0].model, data[0].car_year);
                        car_id = data[0].id;
                        sto_list_div.hidden = false;
                        inp_sto_list.disabled = false;
                        create_order();
                    } else {
                        car_error.hidden = false;
                        car_error.innerText = 'Автомобіль не доданий';
                        setTimeout(() => {
                            car_error.hidden = true;
                        }, 3000);
                    }
                };
                xhr.send(post_str({
                    'make': inp_car_make.value,
                    'model': inp_car_model.value,
                    'car_year': inp_car_year.value,
                    'license_plate': inp_car_license_plate.value,
                    'description': inp_car_description.value
                }));
            });

            /**
             * Функція для вибору СТО
             * 
             * @param void
             */
            inp_sto_list.addEventListener('change', () => {
                table_div.hidden = false;
                new_task_div.hidden = false;
                btn_new_task.disabled = false;
                get_task_list();
                get_employee_list();
            });

            /**
             * Функція для додавання завдання
             * 
             * @param void
             */
            btn_create.addEventListener('click', () => {
                let data = Object();
                data['name'] = inp_employee.value;
                data['task'] = inp_task.value;

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/add_task/' + order_id);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        error.hidden = false;
                        error.innerHTML = 'ERROR: ' + xhr.statusText;
                    } else {
                        error.hidden = true;
                        task_modal.hide();
                        get_task();
                        document.getElementById('save').hidden = false;
                    }
                };

                xhr.send(post_str(data));
            });

            /**
             * Функція для очищення полів
             * 
             * @param void
             */
            btn_new_task.addEventListener('click', () => {
                inp_employee.value = '';
                inp_task.value = '';
            });

            /**
             * Функція для збереження замовлення
             * 
             * @param void
             */
            btn_save.addEventListener('click', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/add');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send();
                window.location.href = '/admin/order/index'
            });

            /**
             * Функція для отримання списку працівників
             * 
             * @param void
             */
            function get_employee_list() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/get_employee_list');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        employee_list.innerHTML = '';
                        data.forEach((item) => {
                            let option = document.createElement('option');
                            option.value = sum_strs(' ', item.first_name, item.last_name, item.fathers_name);
                            employee_list.appendChild(option);
                        });
                    }
                };
                xhr.send(post_str({
                    'sto_name': inp_sto_list.value
                }));
            }

            /**
             * Функція для отримання списку послуг
             * 
             * @param void
             */
            function get_task_list() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/get_task_list');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        task_list.innerHTML = '';
                        data.forEach((item) => {
                            let option = document.createElement('option');
                            option.value = item['service_name'];
                            task_list.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }

            /**
             * Функція для створення замовлення
             * 
             * @param void
             */
            function create_order() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/create_order');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        order_id = data[0].id;
                        document.getElementById('back').setAttribute('href', '/admin/order/delete/' + order_id)
                    }
                };
                xhr.send(post_str({
                    'client_id': client_id,
                    'car_id': car_id,
                }));
            }

            /**
             * Функція для отримання списку завдань
             * 
             * @param void
             */
            function get_task() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/order/get_task/' + order_id);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        error.hidden = false;
                        error.innerHTML = 'ERROR: ' + xhr.statusText;
                    } else {
                        error.hidden = true;
                        let price = 0;
                        let data = JSON.parse(xhr.response);
                        document.getElementById('table-body').innerHTML = '';
                        for (let i = 0; i < data.length; i++) {
                            price += parseInt(data[i].price);
                            const tr = document.createElement('tr');
                            tr.innerHTML = '<th scope="row">' + (i + 1) + '</th>';
                            tr.innerHTML += '<td>' + data[i].service_name + '</td>';
                            tr.innerHTML += '<td>' + sum_strs(' ', data[i].first_name, data[i].last_name, data[i].fathers_name) + '</td>';
                            tr.innerHTML += '<td>' + data[i].price + '</td>';
                            tr.innerHTML += '<td>' + (data[i].is_done == 't' ? 'Так' : 'Ні') + '</td>';
                            tr.innerHTML += '<td>' + '<button type="button" class="del btn" style="padding: 0;" id="' + i + '"><img src="/data/icon/delete.png" width="20px" height="20px"></button>' + '</td>';
                            document.getElementById('table-body').appendChild(tr);
                        }

                        let btns_del = document.getElementsByClassName('del');
                        for (let i = 0; i < btns_del.length; i++) {
                            btns_del[i].addEventListener('click', () => {
                                if (confirm('Ви дійсно хочете видалити цю послугу?')) {
                                    let xhr = new XMLHttpRequest();
                                    xhr.open('POST', '/admin/order/delete_task/' + data[i].id);
                                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                    xhr.onload = () => {
                                        if (xhr.status != 200) {
                                            error.hidden = false;
                                            error.innerHTML = 'ERROR: ' + xhr.statusText;
                                        } else {
                                            error.hidden = true;
                                            get_task();
                                        }
                                    };
                                    xhr.send();
                                }
                            });
                        }
                    }
                };
                xhr.send();
            }
        });

        /**
         * Функція для склеювання об'єкту в URL строку
         * 
         * @param {Object} data - об'єкт
         * @returns {String} - склеєна строка
         */
        function post_str(data) {
            let str = '';
            for (let key in data) {
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>