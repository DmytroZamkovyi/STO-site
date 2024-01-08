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

        <a href="/admin/employee/index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="margin-left: 16px;">
            <img class="bi me-2" width="32" height="32" src="/data/icon/return.png">
            <span class="fs-4">Назад</span>
        </a>

        <h4 class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"><?= $user_name ?></h4>

        <button id="delete" type="button" class="btn btn-outline-danger" style="margin: 0 8px;">Видалити</button>
        <button id="escape" type="button" class="btn btn-outline-secondary" style="margin: 0 8px;">Скасувати</button>
        <button id="save" type="button" class="btn btn-primary" style="margin: 0 8px;">Зберегти</button>
    </header>
    <main class="container">
        <div class="modal-body">
            <div class="input-group mb-3">
                <label class="input-group-text" for="role">Роль</label>
                <select class="form-select" id="role">
                    <? foreach ($role_list as $role) : ?>
                        <? if ($employee['id'] == $role['id']) : ?>
                            <option value="<?= $role['id'] ?>" selected><?= $role['role_name'] ?></option>
                        <? else : ?>
                            <option value="<?= $role['id'] ?>"><?= $role['role_name'] ?></option>
                        <? endif ?>
                    <? endforeach ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">ПІБ</span>
                <input id="first-name" type="text" class="form-control" placeholder="Прізвище" value="<?= $employee['first_name'] ?>">
                <input id="last-name" type="text" class="form-control" placeholder="Ім'я" value="<?= $employee['last_name'] ?>">
                <input id="father-name" type="text" class="form-control" placeholder="Патронім" value="<?= $employee['fathers_name'] ?>">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Номер телефону</span>
                <input id="phone-number" type="text" class="form-control" value="<?= $employee['employee_pn'] ?>">
                <label class="input-group-text" for="phone-number">+ZZZ (YY) XXX-XX-XX</label>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Тариф</span>
                <input id="tarif" type="text" class="form-control" value="<?= $employee['tariff'] ?>">
                <span class="input-group-text">₴</span>
                <span class="input-group-text">0.00</span>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">СТО</span>
                <input id="sto" type="text" class="form-control" list="sto-list" value="<?= $employee['sto_name'] ?>">
                <datalist id="sto-list">
                    <? foreach ($sto_list as $sto) : ?>
                        <option value="<?= $sto['sto_name'] ?>"></option>
                    <? endforeach ?>
                </datalist>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Відділ</span>
                <input id="branch" type="text" class="form-control" list="branch-list" value="<?= $employee['department_name'] ?>">
                <datalist id="branch-list">
                    <? foreach ($branch_list as $branch) : ?>
                        <option value="<?= $branch['department_name'] ?>"></option>
                    <? endforeach ?>
                </datalist>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Посада</span>
                <input id="position" type="text" class="form-control" list="position-list" value="<?= $employee['position_name'] ?>">
                <datalist id="position-list">
                    <? foreach ($position_list as $position) : ?>
                        <option value="<?= $position['position_name'] ?>"></option>
                    <? endforeach ?>
                </datalist>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Логін</span>
                <input id="login" type="email" class="form-control" value="<?= $employee['login'] ?>">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Пароль</span>
                <input id="pwd" type="password" class="form-control">
            </div>

            <p id="error" class="text-danger" hidden></p>
            <p id="success" class="text-success" hidden>Данні успішно збережені</p>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
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

            const btn_delete = document.getElementById('delete');
            const btn_cancel = document.getElementById('escape');
            const btn_save = document.getElementById('save');

            /**
             * Функція для повернення на попередню сторінку
             * 
             * @returns void
             */
            btn_cancel.addEventListener('click', () => {
                window.location.href = '/admin/employee/index';
            });

            /**
             * Функція для видалення працівника
             * 
             * @returns void
             */
            btn_delete.addEventListener('click', () => {
                if (confirm('Ви дійсно хочете видалити цього працівника?')) {
                    window.location.href = '/admin/employee/delete/' + '<?= $employee['employee_id'] ?>';
                }
            });

            /**
             * Функція для збереження даних
             * 
             * @returns void
             */
            btn_save.addEventListener('click', () => {
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

                if (data.pwd != '' && (data.pwd.length < 3 || data.pwd.length > 20)) {
                    inp_pwd.classList.add('is-invalid');
                    inp_pwd.placeholder = 'Введіть пароль'
                    is_error = true;
                } else {
                    delete data.pwd;
                    inp_pwd.classList.remove('is-invalid');
                }

                if (is_error) {
                    document.getElementById('error').hidden = false;
                    document.getElementById('error').innerHTML = 'ERROR: Неправильно введені дані';
                    return;
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/employee/update/<?= $employee['employee_id'] ?>');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status != 200) {
                        document.getElementById('error').hidden = false;
                        document.getElementById('error').innerHTML = 'ERROR: ' + xhr.statusText;
                    } else {
                        document.getElementById('error').hidden = true;
                        document.getElementById('success').hidden = false;

                        setTimeout(() => {
                            window.location.href = '/admin/employee/index';
                        }, 5000);
                    }
                };

                xhr.send(post_str(data));
            });
        })

        function post_str(data) {
            let str = '';
            for (let key in data) {
                str += encodeURIComponent(key) + '=' + encodeURIComponent(data[key]) + '&';
            }
            return str.slice(0, -1);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>