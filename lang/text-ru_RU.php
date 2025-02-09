<?php
/**
 * Этот файл является частью виджета веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Навигационная цепочка',
    '{description}' => 'Навигационная цепочка элементов на сайте',

    // Widget: разметка
    'Markup settings' => 'Настройка разметки',

    // MarkupSettings
    '{markupsettings.title}' => 'Настройка разметки виджета "Навигационная цепочка"',
    // MarkupSettings: поля
    'In the specified template, the widget parameters are changed. You can make changes manually by opening the template for editing.' 
        => 'В указанном шаблоне изменяются параметры виджета. Изменения вы можете сделать вручную, открыв на редактирование шаблон.',
    'The markup is done in the template' => 'Разметка в шаблоне',
    'HTML encode link labels' => 'HTML-кодировать метки ссылок',
    'Widget interface view' => 'Вид интерфейса виджета',
    'Custom' => 'Индивидуальный',
    'The appearance of the interface determines how the navigation elements will be displayed: using Bootstrap styles or custom settings' 
        => 'Вид интерфейса определяет, каким образом будут отображаться элементы навигации: с помощью стилей Bootstrap или индивидуальным настройкам',
    'Widget interface view - {0}' => 'Вид интерфейса виджета - {0}',
    'Navigation tag name' => 'Имя тега навигации',
    'The navigation tag contains a container of elements and is rendered as a wrapper for the container' 
        => 'Тег навигации содержит контейнер элементов и отображается как обёртка для контейнера, например, "nav"',
    'Navigation tag attributes' => 'Атрибуты тега навигации',
    'Attributes have the form "attribute=value;attribute=value' => 'Атрибуты имеют вид "атрибут=значение;атрибут=значение"', 
    'Element container tag name' => 'Имя тега контейнера',
    'The container contains navigation elements with links' => 'Контейнер содержит элементы навигации с сылками, например: "ol", "ul"',
    'Container tag attributes' => 'Атрибуты тега контейнера',
    'Active element template' => 'Шаблон активного элемента',
    'Inactive element template' => 'Шаблон неактивного элемента',
    'The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element' 
        => 'Шаблон должен содержать выражение "{link}", каторое будет заменено фактической ссылкой HTML для каждого элемента. Например: "&lt;li&gt;{link}&lt;/li&gt;"'
];
