# <img src="https://raw.githubusercontent.com/gearmagicru/gm-wd-breadcrumbs/refs/heads/main/assets/images/icon.svg" width="64px" height="64px" align="absmiddle"> Виджет "Навигационная цепочка"

Виджет "Хлебные крошки" (навигационные цепочки, breadcrumbs) предназначен для отображения списка ссылок, указывающих положение текущей страницы  в иерархической структуре сайта.

Например, "Главная / Категория / Статья". Элемент "Статья", является активным элементом указывающий на положение текущей страницы. Все остальные элементы, будут иметь ссылки.

Для использования, необходимо укатать параметр "links" в конфигурации виджета.
Пример:
```
echo $this->widget('gm.wd.breadcrumbs', [
     'links' => [
         [
             'label' => 'Категория статьи',
             'url'   => 'post-category'
         ],
         [
              'label' => 'Статья'
          ]
     ]
 ]);
// или
echo $this->widget('gm.wd.breadcrumbs:main', ['ui' => 'bootstrap5'])
```

## Пример применения
### с менеджером виджетов:
```
$breadcrumbs = Gm::$app->widgets->get('gm.wd.breadcrumbs', ['ui' => 'bootstrap5']);
$breadcrumbs->run();
```
### в шаблоне:
```
echo $this->widget('gm.wd.breadcrumbs', ['ui' => 'bootstrap5']);
```
### с namespace:
```
use Gm\Widget\Breadcrumbs\Widget as Breadcrumbs;
echo Breadcrumbs::widget(['ui' => 'bootstrap5']);
```
если namespace ранее не добавлен в PSR, необходимо выполнить:
```
Gm::$loader->addPsr4('Gm\Widget\Breadcrumbs\\', Gm::$app->modulePath . '/gm/gm.wd.breadcrumbs/src');
```

## Установка

Для добавления виджета в ваш проект, вы можете просто выполнить команду ниже:

```
$ composer require gearmagicru/gm-wd-breadcrumbs
```

или добавить в файл composer.json вашего проекта:
```
"require": {
    "gearmagicru/gm-wd-breadcrumbs": "*"
}
```

После добавления виджета в проект, воспользуйтесь Панелью управления GM Panel для установки его в редакцию вашего веб-приложения.
