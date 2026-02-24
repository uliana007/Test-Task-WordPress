# Подробная инструкция: Этап 4 — интеграция мультилендинга в WordPress

Ниже — самый простой и стабильный путь, чтобы ваш лендинг корректно отображался со стилями и чтобы задание было засчитано по всем пунктам.

---

## 0) Почему обычно «ломаются» стили

Чаще всего проблема в одном из пунктов:

1. CSS оставлен внутри `test.html`, но в WordPress вы рендерите другой файл (и стили не подключаются).
2. Неверные пути к изображениям (`assets/images/...`), когда шаблон подключен не из того места.
3. Нет `wp_head()` / `wp_footer()` в шаблоне (часть стилей/скриптов WordPress и плагинов не загружается).
4. Тема активирована, но вы открываете страницу без выбранного шаблона.
5. Кэш браузера/плагина кэширования показывает старую версию CSS.

---

## 1) Рекомендуемая структура темы

Создайте тему, например `basketball-wetten`, со структурой:

```text
basketball-wetten/
  style.css
  functions.php
  index.php
  page-basketball.php
  front-page.php          (опционально, если хотите лендинг на главной)
  assets/
    css/
      basketball.css
    images/
      Grundlagen.jpg
      Strategien.jpg
      Expertentipps.jpg
```

> Ключевая идея: вынесите стили из `test.html` в `assets/css/basketball.css` и подключайте их через `wp_enqueue_style()`.

---

## 2) Минимальный `style.css` (обязательно для темы)

Файл `style.css` должен содержать заголовок темы:

```css
/*
Theme Name: Basketball Wetten
Text Domain: basketball-wetten
Version: 1.0
*/
```

Можно добавить базовые стили, но для лендинга лучше держать основные стили в `assets/css/basketball.css`.

---

## 3) `functions.php`: поддержка темы + меню + стили

Создайте/проверьте `functions.php`:

```php
<?php
if (!defined('ABSPATH')) {
    exit;
}

function basketball_wetten_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus([
        'landing_menu' => __('Landing Menu', 'basketball-wetten'),
    ]);
}
add_action('after_setup_theme', 'basketball_wetten_setup');

function basketball_wetten_assets() {
    // Основной style.css темы
    wp_enqueue_style(
        'basketball-wetten-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );

    // Стили лендинга
    wp_enqueue_style(
        'basketball-wetten-landing',
        get_template_directory_uri() . '/assets/css/basketball.css',
        ['basketball-wetten-style'],
        filemtime(get_template_directory() . '/assets/css/basketball.css')
    );
}
add_action('wp_enqueue_scripts', 'basketball_wetten_assets');
```

Почему это важно:
- `wp_enqueue_style()` гарантирует корректную загрузку CSS в WordPress.
- `filemtime(...)` автоматически сбрасывает кэш при изменении файла.

---

## 4) Шаблон страницы с `Template Name` (главный пункт задания)

Создайте `page-basketball.php`:

```php
<?php
/*
Template Name: Basketball Wetten Лендинг
*/

if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <p class="subtitle">Umfassender Leitfaden für NBA, BBL und EuroLeague</p>
    </div>
</header>

<nav>
    <div class="container nav-content">
        <?php
        wp_nav_menu([
            'theme_location' => 'landing_menu',
            'container'      => false,
            'menu_class'     => 'nav-links',
            'fallback_cb'    => false,
        ]);
        ?>
    </div>
</nav>

<main>
    <div class="container">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
```

Важно:
- Комментарий `Template Name` должен быть в начале файла.
- Должны быть `wp_head()` и `wp_footer()`.
- Меню подключается через `wp_nav_menu()`.

---

## 5) Перенос HTML и стилей из `test.html`

1. Из `test.html` переносите **только HTML-разметку контента** в `the_content()` (или прямо в шаблон, если нужно).
2. Все стили из блока `<style>...</style>` перенесите в `assets/css/basketball.css`.
3. Пути к картинкам в контенте задавайте так:

```php
<?php echo esc_url(get_template_directory_uri() . '/assets/images/Grundlagen.jpg'); ?>
```

или, если изображения вставляете из редактора WordPress — загрузите их в «Медиафайлы».

---

## 6) Вариант «как главная страница» (`front-page.php`)

Если по заданию хотите показать 2 способ интеграции, создайте `front-page.php` и вставьте туда тот же каркас, что и в `page-basketball.php`.

Тогда в админке:
- **Настройки → Чтение**
- «На главной странице отображать» → «Статическая страница»
- Выберите нужную страницу как главную.

---

## 7) Как создать мультилендинг (несколько страниц на одном шаблоне)

1. **Страницы → Добавить новую**
2. Название, например:
   - Basketball Wetten Guide
   - NBA Wetten Guide
   - EuroLeague Wetten Guide
3. Справа в «Атрибуты страницы» выберите шаблон: **Basketball Wetten Лендинг**.
4. Для каждой страницы меняйте текст/блоки в редакторе.

Итог: один шаблон, много лендингов с разным контентом.

---

## 8) Меню для мультилендинга

1. **Внешний вид → Меню**
2. Создайте меню «Landing Menu».
3. Добавьте все созданные лендинги.
4. Назначьте меню в область `landing_menu`.

Проверка: в шапке лендинга ссылки должны выводиться динамически.

---

## 9) Пошаговая проверка перед сдачей

- [ ] Тема активирована.
- [ ] У страницы выбран нужный шаблон `Basketball Wetten Лендинг`.
- [ ] Стили загружаются из `assets/css/basketball.css` (проверить в DevTools → Network).
- [ ] Изображения открываются по URL без 404.
- [ ] Меню отображается и ведёт на разные лендинги.
- [ ] Страницы корректно выглядят на мобильном (адаптив).
- [ ] При необходимости очищен кэш (плагин/браузер/CDN).

---

## 10) Быстрое решение вашей текущей проблемы со стилями

Сделайте именно эти 5 действий:

1. Вынесите весь CSS из `test.html` в `assets/css/basketball.css`.
2. Подключите этот файл в `functions.php` через `wp_enqueue_style()`.
3. В шаблоне добавьте `<?php wp_head(); ?>` перед `</head>` и `<?php wp_footer(); ?>` перед `</body>`.
4. Проверьте пути картинок через `get_template_directory_uri()`.
5. Очистите кэш и обновите страницу с `Ctrl + F5`.

После этого в 95% случаев стили начинают отображаться корректно.

---

## 11) Что показать преподавателю/проверяющему

1. Файл шаблона с `Template Name`.
2. Использование `wp_nav_menu()`.
3. Несколько страниц, созданных на одном шаблоне.
4. Корректное отображение каждой страницы в рамках активной темы.
5. (Опционально) наличие `front-page.php` как второй способ интеграции.

Если хотите, следующим шагом могу дать вам **готовый минимальный набор 5 файлов** (копипаст), который вы просто вставите в тему и сразу загрузите в WordPress.
