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

        <a href="/manager/order/index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="margin-left: 16px;">
            <img class="bi me-2" width="32" height="32" src="/data/icon/return.png">
            <span class="fs-4">Назад</span>
        </a>

        <button id="delete" type="button" class="btn btn-outline-danger" style="margin: 0 8px;">Видалити</button>
        <button id="save" type="button" class="btn btn-primary" style="margin: 0 8px;">Зберегти</button>
    </header>
    <main>
        <div class="container">
            <div class="input-group mb-3">
                <span class="input-group-text">Замовник</span>
                <input type="text" class="form-control" placeholder="Прізвище" value="<?= $order_info['first_name'] ?> <?= $order_info['last_name'] ?> <?= $order_info['fathers_name'] ?>" disabled>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Номер телефону</span>
                <input type="text" class="form-control" value="+<?= substr($order_info['phone_number'], 0, 3) ?> (<?= substr($order_info['phone_number'], 3, 2) ?>) <?= substr($order_info['phone_number'], 5, 3) ?>-<?= substr($order_info['phone_number'], 8, 2) ?>-<?= substr($order_info['phone_number'], 10, 2) ?>" disabled>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Дата створення</span>
                <input type="date" class="form-control" value="<?= $order_info['create_date'] ?>" disabled>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">СТО</span>
                <input type="text" class="form-control" value="<?= $sto_info['sto_name'] ?>" disabled>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Автомобіль</span>
                <input type="text" class="form-control" value="<?= $order_info['make'] ?> - <?= $order_info['model'] ?> - <?= $order_info['license_plate'] ?>" disabled>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Знижка клієнта</span>
                <?php if ($order_info['discount'] == null) : ?>
                    <input type="text" class="form-control" value="0" disabled>
                <?php else : ?>
                    <input type="text" class="form-control" value="<?= $order_info['discount'] ?>" disabled>
                <?php endif; ?>
                <label class="input-group-text" for="discount">%</label>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Знижка по замовленню</span>
                <?php if ($order_info['order_discount'] == null) : ?>
                    <input id="discount" min="0" max="100" type="number" class="form-control" value="0">
                <?php else : ?>
                    <input id="discount" min="0" max="100" type="number" class="form-control" value="<?= $order_info['order_discount'] ?>">
                <?php endif; ?>
                <label class="input-group-text" for="discount">%</label>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Статус</span>
                <select id="status" class="form-control" value="" disabled>
                    <? if ($order_info['completed'] == 't') : ?>
                        <option value="0" selected>Виконано</option>
                        <option value="1">Не виконано</option>
                    <? else : ?>
                        <option value="0">Виконано</option>
                        <option value="1" selected>Не виконано</option>
                    <? endif; ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Оплата</span>
                <select class="form-control" disabled autocomplete="off">
                    <? if ($order_info['payment'] == 't') : ?>
                        <option value="0" selected>Оплачено</option>
                        <option value="1">Не оплачено</option>
                    <? else : ?>
                        <option value="0">Оплачено</option>
                        <option value="1" selected>Не оплачено</option>
                    <? endif; ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Вартість послуг</span>
                <input id="price" type="text" class="form-control" value="" disabled>
                <label class="input-group-text">₴</label>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Сума знижки</span>
                <input id="discount-price" type="text" class="form-control" value="" disabled>
                <label class="input-group-text">₴</label>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Вартість зі знижкою</span>
                <input id="sum" type="text" class="form-control" value="" disabled>
                <label class="input-group-text">₴</label>
            </div>

            <p id="error" class="text-danger" hidden></p>
        </div>

        <div class="container">
            <button id="new-task" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-task-model">Нове завдання</button>

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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Відміна</button>
                            <button id="create" type="button" class="btn btn-primary">Створити</button>
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
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('add-task-model'));
            const inp_discount = document.getElementById('discount');
            const inp_status = document.getElementById('status');
            const inp_price = document.getElementById('price');
            const inp_discount_price = document.getElementById('discount-price');
            const inp_sum = document.getElementById('sum');
            const inp_employee = document.getElementById('employee');
            const inp_task = document.getElementById('task');

            const employee_list = document.getElementById('employee-list');
            const task_list = document.getElementById('task-list');

            const btn_delete = document.getElementById('delete');
            const btn_save = document.getElementById('save');
            const btn_new_task = document.getElementById('new-task');
            const btn_create = document.getElementById('create');

            const error = document.getElementById('error');
            const table_body = document.getElementById('table-body');

            get_task();
            get_employee_list();
            get_task_list();

            /**
             * Функція для видалення замовлення
             * 
             * @returns void
             */
            btn_delete.addEventListener('click', () => {
                if (confirm('Ви дійсно хочете видалити це замовлення?')) {
                    window.location.href = '/manager/order/delete/' + '<?= $order_id ?>';
                }
            });

            /**
             * Функція для збереження даних
             * 
             * @returns void
             */
            btn_create.addEventListener('click', () => {
                let data = Object();
                data['name'] = inp_employee.value;
                data['task'] = inp_task.value;

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/manager/order/add_task/' + '<?= $order_id ?>');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        error.hidden = false;
                        error.innerHTML = 'ERROR: ' + xhr.statusText;
                    } else {
                        error.hidden = true;
                        modal.hide();
                        get_task();
                    }
                };

                xhr.send(post_str(data));
            });

            /**
             * Функція для збереження даних
             * 
             * @returns void
             */
            btn_save.addEventListener('click', () => {
                let is_error = false;
                let data = Object();
                data['discount'] = inp_discount.value;

                if (inp_discount.value == '') {
                    data['discount'] = null;
                }

                if (inp_discount.value < 0 || inp_discount.value > 100) {
                    is_error = true;
                    inp_discount.classList.add('is-invalid');
                    return;
                }

                if (is_error) {
                    error.hidden = false;
                    error.innerHTML = 'Помилка вводу';
                    return;
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/manager/order/update/' + '<?= $order_id ?>');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        error.hidden = false;
                        error.innerHTML = 'ERROR: ' + xhr.statusText;
                    } else {
                        error.hidden = true;
                        window.location.href = '/manager/order/index';
                    }
                };

                xhr.send(post_str(data));
            });

            /**
             * Функція для очистки полів модального вікна
             * 
             * @returns void
             */
            btn_new_task.addEventListener('click', () => {
                inp_employee.value = '';
                inp_task.value = '';
            });

            /**
             * Функція для підрахунку вартості послуг
             * 
             * @returns void
             */
            inp_discount.addEventListener('change', () => {
                let price = parseInt(inp_price.value);
                let dis = (parseInt(inp_discount.value) / 100) + (<?= $order_info['discount'] ?> / 100);
                if (dis > 1) {
                    dis = 1;
                }
                dis = Math.round(price * dis);
                inp_discount_price.value = dis;
                inp_sum.value = price - dis;
            });

            /**
             * Функція для отримання та обробки списку всіх завдань
             * 
             * @returns void
             */
            function get_task() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/manager/order/get_task/<?= $order_id ?>');
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

                        inp_price.value = price;
                        let dis = (parseInt(inp_discount.value) / 100) + (<?= $order_info['discount'] ?> / 100);
                        if (dis > 1) {
                            dis = 1;
                        }
                        dis = Math.round(price * dis);
                        inp_discount_price.value = dis;
                        inp_sum.value = price - dis;

                        let btns_del = document.getElementsByClassName('del');
                        for (let i = 0; i < btns_del.length; i++) {
                            btns_del[i].addEventListener('click', () => {
                                if (confirm('Ви дійсно хочете видалити цю послугу?')) {
                                    let xhr = new XMLHttpRequest();
                                    xhr.open('POST', '/manager/order/delete_task/' + data[i].id);
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

            /**
             * Функція для отримання та обробки списку всіх працівників СТО
             * 
             * @returns void
             */
            function get_employee_list() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/manager/order/get_employee_list');
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
                    'sto_name': '<?= $sto_info['sto_name'] ?>'
                }));
            }

            /**
             * Функція для отримання та обробки списку всіх послуг
             * 
             * @returns void
             */
            function get_task_list() {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/manager/order/get_task_list');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        task_list.innerHTML = '';
                        data.forEach((item) => {
                            let option = document.createElement('option');
                            option.value = item.service_name;
                            task_list.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }
        });

        /**
         * Функція для перетворення об'єкта в URL строку
         * 
         * @param {Object} data - об'єкт
         * @returns {String} - строка
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