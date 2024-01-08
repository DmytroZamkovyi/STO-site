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
    <main class="container">
        <div style="margin-top: 16px;">
            <button id="new_emloyee" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-employee">Новий працівник</button>
            <output hidden id="success" class="text-success">Користувача успішно додано!</output>

            <div class="modal fade" id="add-employee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Створення акаунту працівника</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="role">Роль</label>
                                <select class="form-select" id="role">
                                    <? foreach ($role_list as $role) : ?>
                                        <? if ($role['active']) : ?>
                                            <option value="<?= $role['id'] ?>" selected><?= $role['role_name'] ?></option>
                                        <? else : ?>
                                            <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                                        <? endif ?>
                                    <? endforeach ?>
                                </select>
                            </div>

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
                                <span class="input-group-text">Тариф</span>
                                <input id="tarif" type="text" class="form-control">
                                <span class="input-group-text">₴</span>
                                <span class="input-group-text">0.00</span>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">СТО</span>
                                <input id="sto" type="text" class="form-control" list="sto-list">
                                <datalist id="sto-list">
                                    <? foreach ($sto_list as $sto) : ?>
                                        <option value="<?= $sto['sto_name'] ?>"></option>
                                    <? endforeach ?>
                                </datalist>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Відділ</span>
                                <input id="branch" type="text" class="form-control" list="branch-list">
                                <datalist id="branch-list">
                                    <? foreach ($branch_list as $branch) : ?>
                                        <option value="<?= $branch['department_name'] ?>"></option>
                                    <? endforeach ?>
                                </datalist>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Посада</span>
                                <input id="position" type="text" class="form-control" list="position-list">
                                <datalist id="position-list">
                                    <? foreach ($position_list as $position) : ?>
                                        <option value="<?= $position['position_name'] ?>"></option>
                                    <? endforeach ?>
                                </datalist>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Логін</span>
                                <input id="login" type="email" class="form-control">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Пароль</span>
                                <input id="pwd" type="password" class="form-control">
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
                            <label class="input-group-text" for="search-role">Роль</label>
                            <select class="form-select" id="search-role">
                                <option value=""></option>
                                <? foreach ($role_list as $role) : ?>
                                    <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">ПІБ</span>
                            <input id="search-first-name" type="text" class="form-control" placeholder="Прізвище">
                            <input id="search-last-name" type="text" class="form-control" placeholder="Ім'я">
                            <input id="search-father-name" type="text" class="form-control" placeholder="Патронім">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">СТО</span>
                            <input id="search-sto" type="text" class="form-control" list="search-sto-list">
                            <datalist id="search-sto-list">
                                <? foreach ($sto_list as $sto) : ?>
                                    <option value="<?= $sto['sto_name'] ?>"></option>
                                <? endforeach ?>
                            </datalist>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Відділ</span>
                            <input id="search-branch" type="text" class="form-control" list="search-branch-list">
                            <datalist id="search-branch-list">
                                <? foreach ($branch_list as $branch) : ?>
                                    <option value="<?= $branch['department_name'] ?>"></option>
                                <? endforeach ?>
                            </datalist>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Посада</span>
                            <input id="search-position" type="text" class="form-control" list="search-position-list">
                            <datalist id="search-position-list">
                                <? foreach ($position_list as $position) : ?>
                                    <option value="<?= $position['position_name'] ?>"></option>
                                <? endforeach ?>
                            </datalist>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">Логін</span>
                            <input id="search-login" type="email" class="form-control">
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
                        <th scope="col">Роль</th>
                        <th scope="col">Логін</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">Місце роботи</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="table-group-divider"></tbody>
            </table>
        </div>
    </main>

    <script>
        let employee = Array();
        window.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('add-employee'));
            const inp_role = document.getElementById('role');
            const inp_first_name = document.getElementById('first-name');
            const inp_last_name = document.getElementById('last-name');
            const inp_father_name = document.getElementById('father-name');
            const inp_phone_number = document.getElementById('phone-number');
            const inp_tarif = document.getElementById('tarif');
            const inp_sto = document.getElementById('sto');
            const inp_branch = document.getElementById('branch');
            const inp_position = document.getElementById('position');
            const inp_login = document.getElementById('login');
            const inp_pwd = document.getElementById('pwd');

            const btn_create = document.getElementById('create');
            const btn_new_emloyee = document.getElementById('new_emloyee');

            const inp_search_role = document.getElementById('search-role');
            const inp_search_first_name = document.getElementById('search-first-name');
            const inp_search_last_name = document.getElementById('search-last-name');
            const inp_search_father_name = document.getElementById('search-father-name');
            const inp_search_sto = document.getElementById('search-sto');
            const inp_search_branch = document.getElementById('search-branch');
            const inp_search_position = document.getElementById('search-position');
            const inp_search_login = document.getElementById('search-login');

            const btn_search = document.getElementById('search');
            const btn_search_reset = document.getElementById('search-reset');

            const btn_reload = document.getElementById('reload');

            const table_body = document.getElementById('table-body');

            send();

            /**
             * Функція для очищення полів пошуку
             */
            btn_search_reset.addEventListener('click', () => {
                inp_search_role.value = '';
                inp_search_first_name.value = '';
                inp_search_last_name.value = '';
                inp_search_father_name.value = '';
                inp_search_sto.value = '';
                inp_search_branch.value = '';
                inp_search_position.value = '';
                inp_search_login.value = '';

                load_table();
            });

            btn_search.addEventListener('click', load_table);
            btn_reload.addEventListener('click', send);

            /**
             * Функція для очищення полів додавання нового працівника
             */
            btn_new_emloyee.addEventListener('click', () => {
                document.getElementById('success').hidden = true;
                inp_first_name.value = '';
                inp_last_name.value = '';
                inp_father_name.value = '';
                inp_phone_number.value = '';
                inp_tarif.value = '';
                inp_sto.value = '';
                inp_branch.value = '';
                inp_position.value = '';
                inp_login.value = '';
                inp_pwd.value = '';
            });

            /**
             * Функція для додавання нового працівника
             * 
             * @returns void
             */
            btn_create.addEventListener('click', () => {
                let is_error = false;
                let data = {
                    role: inp_role.value,
                    first_name: inp_first_name.value,
                    last_name: inp_last_name.value,
                    father_name: inp_father_name.value,
                    phone_number: inp_phone_number.value,
                    tarif: inp_tarif.value,
                    sto: inp_sto.value,
                    branch: inp_branch.value,
                    position: inp_position.value,
                    login: inp_login.value,
                    pwd: inp_pwd.value
                };

                if (data.role == '') {
                    inp_role.classList.add('is-invalid');
                    inp_role.placeholder = 'Виберіть роль'
                    is_error = true;
                } else {
                    inp_role.classList.remove('is-invalid');
                    data.role = data.role;
                }

                if (data.first_name.length < 3 || data.first_name.length > 40) {
                    inp_first_name.classList.add('is-invalid');
                    inp_first_name.placeholder = 'Введіть ім\'я'
                    is_error = true;
                } else {
                    inp_first_name.classList.remove('is-invalid');
                }

                if (data.last_name.length < 3 || data.last_name.length > 40) {
                    inp_last_name.classList.add('is-invalid');
                    inp_last_name.placeholder = 'Введіть прізвище'
                    is_error = true;
                } else {
                    inp_last_name.classList.remove('is-invalid');
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

                if (data.tarif == '') {
                    inp_tarif.classList.add('is-invalid');
                    inp_tarif.placeholder = 'Введіть тариф'
                    is_error = true;
                } else {
                    inp_tarif.classList.remove('is-invalid');
                    data.tarif = data.tarif;
                }

                if (data.sto.length < 3 || data.sto.length > 40) {
                    inp_sto.classList.add('is-invalid');
                    inp_sto.placeholder = 'Введіть СТО'
                    is_error = true;
                } else {
                    inp_sto.classList.remove('is-invalid');
                }

                if (data.branch.length < 3 || data.branch.length > 40) {
                    inp_branch.classList.add('is-invalid');
                    inp_branch.placeholder = 'Введіть відділ'
                    is_error = true;
                } else {
                    inp_branch.classList.remove('is-invalid');
                }

                if (data.position.length < 3 || data.position.length > 40) {
                    inp_position.classList.add('is-invalid');
                    inp_position.placeholder = 'Введіть посаду'
                    is_error = true;
                } else {
                    inp_position.classList.remove('is-invalid');
                }

                if (data.login.length < 3 || data.login.length > 20) {
                    inp_login.classList.add('is-invalid');
                    inp_login.placeholder = 'Введіть логін'
                    is_error = true;
                } else {
                    inp_login.classList.remove('is-invalid');
                }

                if (data.pwd.length < 3 || data.pwd.length > 20) {
                    inp_pwd.classList.add('is-invalid');
                    inp_pwd.placeholder = 'Введіть пароль'
                    is_error = true;
                } else {
                    inp_pwd.classList.remove('is-invalid');
                }

                if (is_error) {
                    document.getElementById('error').hidden = false;
                    document.getElementById('error').innerHTML = 'ERROR: Неправильно введені дані';
                    return;
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/employee/create');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        document.getElementById('error').hidden = false;
                        document.getElementById('error').innerHTML = 'ERROR: ' + xhr.statusText;
                    } else {
                        document.getElementById('error').hidden = true;
                        modal.hide();
                        document.getElementById('success').hidden = false;

                        setTimeout(() => {
                            document.getElementById('success').hidden = true;
                        }, 10000);
                        send();
                    }
                };

                xhr.send(post_str(data));
            });

            /**
             * Функція для завантаження таблиці
             * 
             * @returns void
             */
            function load_table() {
                let data = Object();

                if (inp_search_role.value != '') {
                    data['role'] = inp_search_role.value;
                }

                if (inp_search_first_name.value != '') {
                    data['first_name'] = inp_search_first_name.value;
                }

                if (inp_search_last_name.value != '') {
                    data['last_name'] = inp_search_last_name.value;
                }

                if (inp_search_father_name.value != '') {
                    data['father_name'] = inp_search_father_name.value;
                }

                if (inp_search_sto.value != '') {
                    data['sto'] = inp_search_sto.value;
                }

                if (inp_search_branch.value != '') {
                    data['branch'] = inp_search_branch.value;
                }

                if (inp_search_position.value != '') {
                    data['position'] = inp_search_position.value;
                }

                if (inp_search_login.value != '') {
                    data['login'] = inp_search_login.value;
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
                xhr.open('POST', '/admin/employee/search');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        document.getElementById('table_body').innerHTML = '';
                    } else {
                        document.getElementById('error').hidden = true;
                        let result = JSON.parse(xhr.responseText);
                        employee = result;
                        table_body.innerHTML = '';
                        for (let i = 0; i < result.length; i++) {
                            let tr = document.createElement('tr');
                            tr.classList.add('col-12', 'col-lg-auto', 'my-2', 'justify-content-center', 'my-md-0');
                            tr.innerHTML = '<th scope="row">' + (i + 1) + '</th>'
                            tr.innerHTML += '<td>' + sum_strs(' ', result[i]['first_name'], result[i]['last_name'], result[i]['fathers_name']) + '</td>'
                            tr.innerHTML += '<td>' + result[i]['role_name'] + '</td>'
                            tr.innerHTML += '<td>' + result[i]['login'] + '</td>'
                            tr.innerHTML += '<td>+' + result[i]['employee_pn'].slice(0, 3) + ' (' + result[i]['employee_pn'].slice(3, 5) + ') ' + result[i]['employee_pn'].slice(5, 8) + '-' + result[i]['employee_pn'].slice(8, 10) + '-' + result[i]['employee_pn'].slice(10) + '</td>'
                            tr.innerHTML += '<td>' + sum_strs(' - ', result[i]['sto_name'], result[i]['department_name'], result[i]['position_name']) + '</td>'
                            tr.innerHTML += '<td>' + '<button type="button" class="edit btn" style="padding: 0;" onclick=edit(' + result[i]['employee_id'] + ')><img src="/data/icon/edit.png" width="20px" height="20px"></button>' + '</td>'
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
         * Функція для перенаправлення на сторінку редагування працівника
         * 
         * @param {Number} i - id працівника
         * @returns void
         */
        function edit(i) {
            window.location.href = '/admin/employee/edit/' + i;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>