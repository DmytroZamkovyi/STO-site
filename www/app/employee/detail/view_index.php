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
    <main style="margin-top: 8px;">
        <div class="container">
            <div class="input-group mb-3">
                <input id="search" type="text" class="form-control" list="search-list" placeholder="Пошук">
                <datalist id="search-list"></datalist>
                <button class="btn btn-primary" type="button" id="add-to-order">Додати</button>
            </div>

            <div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Назва</th>
                            <th scope="col">Кількість</th>
                            <th scope="col">Ціна, за штуку</th>
                            <th scope="col">Ціна, всього</th>
                        </tr>
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>
                <button id="order" class="btn btn-success">Замовити</button>
                <output id="total-price">Всього: 0</output>
            </div>
        </div>
    </main>

    <script>
        let data = Object();
        window.addEventListener('DOMContentLoaded', () => {
            const inp_search = document.getElementById('search');
            const search_list = document.getElementById('search-list');
            const btn_add = document.getElementById('add-to-order');
            const table_body = document.getElementById('table-body');
            const out_total_price = document.getElementById('total-price');
            const btn_order = document.getElementById('order');

            /**
             * Функція відправки запиту на сервер для пошукових запитів
             * 
             * @return void
             */
            inp_search.addEventListener('input', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/employee/detail/search');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        search_list.innerHTML = '';
                        data.forEach((item) => {
                            let option = document.createElement('option');
                            option.value = item['detail_name'];
                            search_list.appendChild(option);
                        });
                    }
                };
                xhr.send('search=' + encodeURIComponent(inp_search.value));
            });

            /**
             * Функція відправки запиту на сервер для отримання даних деталі
             * 
             * @return void
             */
            btn_add.addEventListener('click', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/employee/detail/get');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let res = JSON.parse(xhr.responseText);
                        if (data[res['id']]) {
                            data[res['id']]['count']++;
                        } else {
                            data[res['id']] = {
                                'name': res['detail_name'],
                                'count': 1,
                                'price': parseFloat(res['price'].slice(1).replace(',', ''))
                            };
                        }
                    }
                    render(data);
                };
                xhr.send('search=' + encodeURIComponent(inp_search.value));
            });

            /**
             * Функція рендерингу таблиці
             * 
             * @param {Array} rdata - дані для рендерингу
             * @return void
             */
            function render(rdata) {
                table_body.innerHTML = '';
                let count = 0;
                let total_price = 0;
                for (let i in rdata) {
                    if (rdata[i]['count'] == 0) {
                        delete rdata[i];
                        continue;
                    }
                    total_price += rdata[i]['price'] * rdata[i]['count'];
                    count++;
                    let tr = document.createElement('tr');
                    tr.classList.add('col-12', 'col-lg-auto', 'my-2', 'justify-content-center', 'my-md-0');
                    tr.innerHTML += '<th scope="row">' + count + '</th>';
                    tr.innerHTML += '<td>' + rdata[i]['name'] + '</td>';
                    tr.innerHTML += '<td>' + '<input id="count-' + i +'" class="inp-count" type="number" min=0 value="' + rdata[i]['count'] + '"></td>';
                    tr.innerHTML += '<td>' + rdata[i]['price'] + '</td>';
                    tr.innerHTML += '<td>' + rdata[i]['price'] * rdata[i]['count'] + '</td>';
                    table_body.appendChild(tr);
                }
                out_total_price.value = 'Всього: ' + total_price;

                let inp_count = document.getElementsByClassName('inp-count');
                for (let i = 0; i < inp_count.length; i++) {
                    let key = inp_count[i].id.split('-')[1];
                    inp_count[i].addEventListener('input', () => {
                        rdata[key]['count'] = inp_count[i].value;
                        render(data);
                    });
                }
                data = rdata;
            }

            /**
             * Функція відправки запиту на сервер для оформлення замовлення
             * 
             * @return void
             */
            btn_order.addEventListener('click', () => {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/employee/detail/order');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.status == 200) {
                        let res = JSON.parse(xhr.responseText);
                        alert('Замовлення успішно оформлено');
                        render(Object());
                    } else {
                        alert('Помилка оформлення замовлення');
                    }
                };
                let send_data = Object();
                let count = 0;
                for (let i in data) {
                    send_data[count] = Object();
                    send_data[count]['id'] = i;
                    send_data[count]['count'] = data[i]['count'];
                    count++;
                }
                xhr.send('data=' + encodeURIComponent(JSON.stringify(send_data)));
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>