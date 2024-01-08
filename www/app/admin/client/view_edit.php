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

        <a href="/admin/client/index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="margin-left: 16px;">
            <img class="bi me-2" width="32" height="32" src="/data/icon/return.png">
            <span class="fs-4">Назад</span>
        </a>

        <h4 class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"><?= $user_name ?></h4>

        <button id="escape" type="button" class="btn btn-outline-secondary" style="margin: 0 8px;">Скасувати</button>
        <button id="save" type="button" class="btn btn-primary" style="margin: 0 8px;">Зберегти</button>
    </header>

    <main>
        <div class="container">
            <div class="input-group mb-3">
                <span class="input-group-text">ПІБ</span>
                <input id="first-name" type="text" class="form-control" placeholder="Прізвище" value="<?= $user['first_name'] ?>">
                <input id="last-name" type="text" class="form-control" placeholder="Ім'я" value="<?= $user['last_name'] ?>">
                <input id="father-name" type="text" class="form-control" placeholder="Патронім" value="<?= $user['fathers_name'] ?>">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Номер телефону</span>
                <input id="phone-number" type="text" class="form-control" value="<?= $user['phone_number'] ?>">
                <label class="input-group-text" for="phone-number">+ZZZ (YY) XXX-XX-XX</label>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Знижка</span>
                <input id="discount" type="text" class="form-control" value="<?= (int)$user['discount'] ?>">
                <span class="input-group-text">%</span>
            </div>
        </div>

        <div style="margin-left: 16px; margin-right: 16px;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Автомобіль</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Послуги</th>
                        <th scope="col">Ціна, без знижки</th>
                        <th scope="col">Знижка</th>
                        <th scope="col">Сума</th>
                        <th scope="col">Чи оплачено</th>
                        <th scope="col">Чи виконано</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <? $i = 0; ?>
                    <? foreach ($order as $row) : ?>
                        <? $i++; ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $row['car'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['services'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <? if ($row['discount'] == 0) : ?>
                                <td>-</td>
                            <? else : ?>
                                <td><?= $row['discount'] ?>%</td>
                            <? endif; ?>
                            <td><?= round($row['price'] * (1 - $row['discount'] / 100), 2) ?></td>
                            <? if ($row['payment'] == 'f') : ?>
                                <td><input class="form-check-input" type="checkbox" disabled></td>
                            <? else : ?>
                                <td><input class="form-check-input" type="checkbox" checked disabled></td>
                            <? endif; ?>
                            <? if ($row['completed'] == 'f') : ?>
                                <td><input class="form-check-input" type="checkbox" disabled></td>
                            <? else : ?>
                                <td><input class="form-check-input" type="checkbox" checked disabled></td>
                            <? endif; ?>
                            <td><button type="button" class="edit btn" style="padding: 0;" onclick=edit(<?= $row['id'] ?>)><img src="/data/icon/edit.png" width="20px" height="20px"></button></td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const btn_cancel = document.getElementById('escape');
            const btn_save = document.getElementById('save');
            const inp_first_name = document.getElementById('first-name');
            const inp_last_name = document.getElementById('last-name');
            const inp_father_name = document.getElementById('father-name');
            const inp_phone_number = document.getElementById('phone-number');
            const inp_discount = document.getElementById('discount');

            /**
             * Функція для перенаправлення на сторінку зі списком клієнтів
             * 
             * @returns {void}
             */
            btn_cancel.addEventListener('click', () => {
                window.location.href = '/admin/client/index';
            });

            /**
             * Функція для збереження даних клієнта
             * 
             * @returns {void}
             */
            btn_save.addEventListener('click', () => {
                let is_error = false;
                const data = {
                    id: <?= $user['id'] ?>,
                    first_name: inp_first_name.value,
                    last_name: inp_last_name.value,
                    father_name: inp_father_name.value,
                    phone_number: inp_phone_number.value,
                    discount: parseInt(inp_discount.value)
                };

                if (data.first_name.length < 3 || data.first_name.length > 40) {
                    is_error = true;
                    inp_first_name.classList.add('is-invalid');
                } else {
                    inp_first_name.classList.remove('is-invalid');
                }

                if (data.last_name.length < 3 || data.last_name.length > 40) {
                    is_error = true;
                    inp_last_name.classList.add('is-invalid');
                } else {
                    inp_last_name.classList.remove('is-invalid');
                }

                if (data.father_name != '' && data.father_name.length < 3 || data.father_name.length > 40) {
                    is_error = true;
                    inp_father_name.classList.add('is-invalid');
                } else {
                    inp_father_name.classList.remove('is-invalid');
                }

                if (data.phone_number == '') {
                    inp_phone_number.classList.add('is-invalid');
                    is_error = true;
                } else {
                    inp_phone_number.classList.remove('is-invalid');
                    let re = /^[ ]*\+?([0-9]{2})[-\( ]*([0-9]{1})[-\( ]*([0-9]{2})[-\) ]*([0-9]{3})[- ]*([0-9]{2})[- ]*([0-9]{2})[ ]*$/gm;
                    let res = re.exec(data.phone_number);
                    if (res) {
                        data.phone_number = res.slice(1).join('');
                    } else {
                        inp_phone_number.classList.add('is-invalid');
                        is_error = true;
                    }
                }

                if (data.discount == '') {
                    data.discount = null;
                } else {
                    inp_discount.classList.remove('is-invalid');
                    if (data.discount < 0 || data.discount > 100) {
                        inp_discount.classList.add('is-invalid');
                        is_error = true;
                    }
                }

                if (is_error) {
                    return;
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/client/update', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        btn_save.classList.remove('btn-danger');
                        btn_save.classList.add('btn-success');
                        btn_save.innerHTML = 'Збережено';
                    } else {
                        btn_save.classList.remove('btn-success');
                        btn_save.classList.add('btn-danger');
                        btn_save.innerHTML = 'Помилка';
                    }
                };
                xhr.send(post_str(data));
            });
        });

        /**
         * Функція для перетворення об'єкту в строку
         * 
         * @param {object} data - об'єкт з даними
         * @returns {string} - строка з даними
         */
        function post_str(data) {
            let str = '';
            for (let key in data) {
                str += encodeURIComponent(key) + '=' + encodeURIComponent(data[key]) + '&';
            }
            return str.slice(0, -1);
        }

        /**
         * Функція для перенаправлення на сторінку редагування замовлення
         * 
         * @param {int} id - id замовлення
         * @returns {void}
         */
        function edit(id) {
            window.location.href = '/admin/order/edit/' + id;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>