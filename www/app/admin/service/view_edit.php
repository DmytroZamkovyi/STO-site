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

        <a href="/admin/service/index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="margin-left: 16px;">
            <img class="bi me-2" width="32" height="32" src="/data/icon/return.png">
            <span class="fs-4">Назад</span>
        </a>

        <h4 class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">Редагування послуги</h4>

        <button id="delete" type="button" class="btn btn-outline-danger" style="margin: 0 8px;">Видалити</button>
        <button id="escape" type="button" class="btn btn-outline-secondary" style="margin: 0 8px;">Скасувати</button>
        <button id="save" type="button" class="btn btn-primary" style="margin: 0 8px;">Зберегти</button>
    </header>
    <main class="container">
        <div>

            <div class="input-group mb-3">
                <span class="input-group-text">Назва</span>
                <input id="name" type="text" class="form-control" value="<?= $servise['service_name'] ?>">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Опис</span>
                <textarea id="description" class="form-control"><?= $servise['description'] ?></textarea>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Ціна</span>
                <input id="price" type="text" class="form-control" value="<?= $servise['price'] ?>">
                <label class="input-group-text">₴</label>
                <label class="input-group-text">0.00</label>
            </div>

            <p id="error" class="text-danger" hidden></p>
            <p id="success" class="text-success" hidden>Данні успішно збережені</p>
        </div>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const inp_name = document.getElementById('name');
            const inp_description = document.getElementById('description');
            const inp_price = document.getElementById('price');

            const btn_delete = document.getElementById('delete');
            const btn_cancel = document.getElementById('escape');
            const btn_save = document.getElementById('save');

            const error = document.getElementById('error');
            const success = document.getElementById('success');

            /**
             * Функція для повернення на попередню сторінку
             * 
             * @returns void
             */
            btn_cancel.addEventListener('click', () => {
                window.location.href = '/admin/service/index';
            });

            /**
             * Функція для видалення послуги
             * 
             * @returns void
             */
            btn_delete.addEventListener('click', () => {
                if (confirm('Ви дійсно хочете видалити цього працівника?')) {
                    window.location.href = '/admin/service/delete/' + '<?= $servise['id'] ?>';
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
                    id: '<?= $servise['id'] ?>',
                    name: inp_name.value,
                    description: inp_description.value,
                    price: inp_price.value
                };

                if (data.name.length < 3 || data.name.length > 40) {
                    is_error = true;
                    inp_name.classList.add('is-invalid');
                } else {
                    inp_name.classList.remove('is-invalid')
                }

                if (data.price <= 0) {
                    is_error = true;
                    inp_price.classList.add('is-invalid');
                } else {
                    data.price = parseFloat(data.price);
                    if (isNaN(data.price) || data.price <= 0) {
                        is_error = true;
                        inp_price.classList.add('is-invalid');
                    } else {
                        inp_price.classList.remove('is-invalid');
                    }
                }

                if (is_error) {
                    error.hidden = false;
                    return;
                } else {
                    error.hidden = true;
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/service/update/<?= $servise['id'] ?>');
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
                            window.location.href = '/admin/service/index';
                        }, 5000);
                    }
                };

                xhr.send(post_str(data));
            });
        })

        /**
         * Функція для перетворення об'єкту в строку
         * 
         * @param {object} data - об'єкт для перетворення
         * @returns {string} - строка з даними
         */
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