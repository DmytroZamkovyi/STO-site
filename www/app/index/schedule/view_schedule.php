<div class="container">
    <p id="error" class="text-danger" hidden>Помилка, неможливо вивести розклад</p>
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <h1 class="me-lg-auto" id="calendar_title">Грудень · 2023</h1>
        <div>
            <button class="btn">&lt;Попередній</button>
            <button class="btn">Поточний</button>
            <button class="btn">Наступний&gt;</button>
        </div>
    </div>
    <div id="table" class="calendar">
        <div class="calendar_header">
            <p>Понеділок</p>
        </div>
        <div class="calendar_header">
            <p>Вівторок</p>
        </div>
        <div class="calendar_header">
            <p>Середа</p>
        </div>
        <div class="calendar_header">
            <p>Четвер</p>
        </div>
        <div class="calendar_header">
            <p>П'ятниця</p>
        </div>
        <div class="calendar_header">
            <p>Субота</p>
        </div>
        <div class="calendar_header">
            <p>Неділя</p>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        render_calendar();

        let shift = 0;

        const xhr = new XMLHttpRequest();
        const div = document.getElementById('table');
        const btn_prev = document.getElementsByClassName('btn')[0];
        const btn_curr = document.getElementsByClassName('btn')[1];
        const btn_next = document.getElementsByClassName('btn')[2];
        const title = document.getElementById('calendar_title');

        /**
         * Функція для переключення на попередній місяць
         * 
         * @return void
         */
        btn_prev.addEventListener('click', () => {
            shift--;
            while (div.children.length > 7) {
                div.removeChild(div.lastChild);
            }
            render_calendar(shift);
            title.innerHTML = date(shift);
            send_request(shift);
        });

        /**
         * Функція для переключення на поточний місяць
         * 
         * @return void
         */
        btn_curr.addEventListener('click', () => {
            shift = 0;
            while (div.children.length > 7) {
                div.removeChild(div.lastChild);
            }
            render_calendar(shift);
            title.innerHTML = date(shift);
            send_request(shift);
        });

        /**
         * Функція для переключення на наступний місяць
         * 
         * @return void
         */
        btn_next.addEventListener('click', () => {
            shift++;
            while (div.children.length > 7) {
                div.removeChild(div.lastChild);
            }
            render_calendar(shift);
            title.innerHTML = date(shift);
            send_request(shift);
        });

        send_request();

        /**
         * Функція для отримання поточної дати в вигляді місяця і року
         * 
         * @param $shift - здиг місяця
         * @return string
         */
        function date(shift) {
            const date = {
                0: 'Січень',
                1: 'Лютий',
                2: 'Березень',
                3: 'Квітень',
                4: 'Травень',
                5: 'Червень',
                6: 'Липень',
                7: 'Серпень',
                8: 'Вересень',
                9: 'Жовтень',
                10: 'Листопад',
                11: 'Грудень'
            };

            const now = new Date();
            now.setMonth(now.getMonth() + shift);
            return date[now.getMonth()] + ' · ' + now.getFullYear();
        }
    });

    /**
     * Функція для відображення календаря
     * 
     * @param $month - здвиг місяця
     * @param $year - здвиг року
     * @return void
     */
    function render_calendar(month = 0, year = 0) {
        let [table, current_month, current_year] = create_table_array(month, year);
        let div = document.getElementById('table');

        for (let i = 0; i < table.length; i++) {
            let child = document.createElement('div');
            child.innerHTML = '<p>' + table[i].getDate() + '</p>';
            child.setAttribute("month", table[i].getMonth());
            child.setAttribute("date", table[i].getDate());
            if (table[i].getMonth() == current_month) {
                child.classList.add("this_month");
            } else {
                child.classList.add("not_this_month");
            }
            if (table[i].getDate() == new Date().getDate() && table[i].getMonth() == new Date().getMonth() && table[i].getFullYear() == new Date().getFullYear()) {
                child.classList.add("today");
            }
            div.appendChild(child);
        }

        /**
         * Функція для створення масиву дат для відображення
         * 
         * @param $month - здиг місяця
         * @param $year - здвиг року
         * @return array
         */
        function create_table_array(month = 0, year = 0) {
            let [current_year, current_month] = get_current_date(month, year);

            let date = new Date(current_year, current_month);
            let table = Array();
            while (true) {
                if (date.getDay() == 1) {
                    break;
                }
                date.setDate(date.getDate() - 1);
            }

            while (true) {
                if (date.getDay() == 1) {
                    if (current_month == 11) {
                        if (date.getMonth() == 0) {
                            break;
                        }
                    } else {
                        if (current_month == 0) {
                            if (date.getMonth() == 1) {
                                break;
                            }
                        } else {
                            if (date.getMonth() > current_month) {
                                break;
                            }
                        }
                    }
                }

                table.push(new Date(date.getTime()));
                date.setDate(date.getDate() + 1);
            }

            return [table, current_month, current_year];
        }
    }

    /**
     * Функція для отримання дати
     * 
     * @param $month - здиг місяця
     * @param $year - здвиг року
     * @return array
     */
    function get_current_date(month = 0, year = 0) {
        let now = new Date();
        while (month >= 12) {
            month -= 12;
            year++;
        }
        while (month <= -12) {
            month += 12;
            year--;
        }
        let current_month = now.getMonth() + month;
        if (current_month > 11) {
            current_month -= 12;
            year++;
        }
        if (current_month < 0) {
            current_month += 12;
            year--;
        }
        let current_year = now.getFullYear() + year;
        return [current_year, current_month];

    }

    /**
     * Функція для відправки запиту на отримання розкладу
     * 
     * @param $month - здиг місяця
     * @param $year - здвиг року
     * @return void
     */
    function send(month = 0, year = 0) {
        let [current_year, current_month] = get_current_date(month, year);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/index/schedule/schedule');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.status != 200) {
                document.getElementById('error').hidden = false;
                return;
            }

            document.getElementById('error').hidden = true;

            if (xhr.responseText == '{}') {
                return;
            }

            const div = document.getElementById('table');

            let res = JSON.parse(xhr.responseText);
            res['schedule'] = JSON.parse(res['schedule']);
            for (let i = 7; i < div.children.length; i++) {
                if (div.children[i].getAttribute('month') == res['schedule_month'] && res['schedule'][div.children[i].getAttribute('date')]) {
                    if (!res['schedule'][div.children[i].getAttribute('date')]['weekend']) {
                        div.children[i].innerHTML += '<p class="schedule" align="center">' + res['schedule'][div.children[i].getAttribute('date')]['start'] + ' - ' + res['schedule'][div.children[i].getAttribute('date')]['end'] + '</p>';
                    }
                }
            }
        };
        xhr.send('month=' + encodeURIComponent(current_month) + '&year=' + encodeURIComponent(current_year));
    }

    /**
     * Функція для відправки запиту на отримання розкладу для відображення в календарі
     * 
     * @param $month - здиг місяця
     * @param $year - здвиг року
     * @return void
     */
    function send_request(month = 0, year = 0) {
        send(month - 1, year);
        send(month, year);
        send(month + 1, year);
    }
</script>

<style>
    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar div {
        border: 1px solid blanchedalmond;
        height: 100%;
        min-height: 100px;
    }

    .this_month p {
        color: white;
    }

    .not_this_month p {
        color: gray;
    }

    .today {
        background-color: cadetblue;
    }

    .today p {
        color: black;
    }

    .calendar div p {
        margin: 0 6px;
        padding: 0;
        font-size: 1.5em;
    }

    .calendar_header {
        background-color: cadetblue;
        text-align: center;
        height: 100% !important;
        min-height: 0 !important;
    }

    .calendar_header p {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 1.5em !important;
        color: black;
    }

    .btn {
        border: 2px solid white;
        border-radius: 5px;
        width: 150px;
    }

    .btn:active {
        background-color: white !important;
        color: black !important;
    }

    .not_this_month .schedule {
        color: grey !important;
    }

    .this_month .schedule {
        background-color: #343438;
    }

    .today .schedule {
        background-color: cadetblue;
    }
</style>