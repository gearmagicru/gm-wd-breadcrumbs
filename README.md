# <img src="https://raw.githubusercontent.com/gearmagicru/gm-wd-menu/refs/heads/master/assets/images/icon.svg" width="64px" height="64px" align="absmiddle"> Виджет "Меню"

Виджет предназначен для отображения многоуровневого меню с использованием вложенных HTML-списков на странице сайта.

## Пример применения
### с менеджером виджетов:
```
$menu = Gm::$app->widgets->get('gm.wd.menu', ['menuId' => 1]);
$menu->run();
```
### в шаблоне:
```
echo $this->widget('gm.wd.menu', ['menuId' => 1]);
// или
echo $this->widget('gm.wd.menu:top', ['menuId' => 1])
```
### с namespace:
```
use Gm\Widget\Menu\Widget as Menu;
echo Menu::widget([
    'items' => [
        ['label' => 'Главная', 'url' => '/'],
        [
           'label' => 'Новости',
           'url'   => '#',
           'items' => [
               ['label' => 'Спорт', 'url' => 'news/sport'],
               ['label' => 'Игры',  'url' => 'news/games']
           ]
        ],
        ['label' => 'Авторизация', 'url' => 'login', 'visible' => Gm::$app->user->isGuest()]
    ]
]);
```
если namespace ранее не добавлен в PSR, необходимо выполнить:
```
Gm::$loader->addPsr4('Gm\Widget\Menu\\', Gm::$app->modulePath . '/gm/gm.wd.menu/src');
```

## Установка

Для добавления виджета в ваш проект, вы можете просто выполнить команду ниже:

```
$ composer require gearmagicru/gm-wd-menu
```

или добавить в файл composer.json вашего проекта:
```
"require": {
    "gearmagicru/gm-wd-menu": "*"
}
```

После добавления виджета в проект, воспользуйтесь Панелью управления GM Panel для установки его в редакцию вашего веб-приложения.
