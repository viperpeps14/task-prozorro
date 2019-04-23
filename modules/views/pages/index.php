<h1>Звіт про виконану роботу</h1>
<div class="report-container">
    <ol>
        <li>Базу проектував на основі <a href="http://api-docs.openprocurement.org/uk_UA/latest/standard/index.html">Стандарт даних</a> </li>
        <li>Завдання виконував на базі свого простого фреймворка з MVC структурой (з LARAVEL поки не доводилось працювати).</li>
        <li>Синхронізацію робив з врахуванням timeout. Черга запросів виконана з допомогою ajax. 
            При кожній новій синхронізаціїї в БД очищаю всі таблиці, щоб позбутися дублів тендерів. В майбутньому 
        можна доопрацювати, щоб дані оновлювались.
        </li>
        <li>Github: </li>
        <li>Приблизна затрата часу на виконання завданя - 30 годин </li>
    </ol>
</div>
<style>
    .report-container{
        background: white;
        border-radius: 20px;
        font-size: 20px;
    }
    ol {counter-reset: item;}ol li {display: block;}
    ol li:before {content: counter(item) ". "; counter-increment: item; font-weight: bold;}
</style>