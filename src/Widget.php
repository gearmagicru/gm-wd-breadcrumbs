<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\Breadcrumbs;

use Gm;
use Gm\Helper\Str;
use Gm\Helper\Html;
use Gm\View\WidgetResourceTrait;
use Gm\View\Widget\Breadcrumbs;
use Gm\View\MarkupViewInterface;

/**
 * Виджет "Хлебные крошки" (навигационные цепочки, breadcrumbs) предназначен для 
 * отображения списка ссылок, указывающих положение текущей страницы  в иерархической 
 * структуре сайта.
 * 
 * Например, "Главная / Категория / Статья". Элемент "Статья", является активным 
 * элементом указывающий на положение текущей страницы. Все остальные элементы, будут
 * иметь ссылки.
 * 
 * Для использования, необходимо укатать параметр "links" в конфигурации виджета.
 * Пример:
 * ```php
 * echo $this->widget('gm.wd.breadcrumbs', [
 *     'links' => [
 *         [
 *             'label' => 'Категория статьи',
 *             'url'   => 'post-category'
 *         ],
 *         [
 *              'label' => 'Статья'
 *          ]
 *     ]
 * ]);
 * // или
 * echo $this->widget('gm.wd.breadcrumbs:main', ['ui' => 'bootstrap5'])
 * ```
 * 
 * Пример использования с менеджером виджетов:
 * ```php
 * $breadcrumbs = Gm::$app->widgets->get('gm.wd.breadcrumbs', ['ui' => 'bootstrap5']);
 * $breadcrumbs->run();
 * ```
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\Breadcrumbs
 * @since 1.0
 */
class Widget extends Breadcrumbs implements MarkupViewInterface
{
    use WidgetResourceTrait;

    /**
     * @var string Индивидуальный
     */
    public const CUSTOM_UI = 'custom';

    /**
     * @var string Bootstrap 5.x
     */
    public const BOOTSTRAP5_UI = 'bootstrap5';

    /**
     * @var string Bootstrap 4.x
     */
    public const BOOTSTRAP4_UI = 'bootstrap4';

    /**
     * @var string Bootstrap 3.x
     */
    public const BOOTSTRAP3_UI = 'bootstrap3';

    /**
     * @var string Последняя версия Bootstrap
     */
    public const BOOTSTRAP_UI = 'bootstrap';

    /**
     * {@inheritdoc}
     */
    public bool $useReconfigure = true;

    /**
     * Вид интерфейса виджета.
     * 
     * Например: `CUSTOM_UI`, `BOOTSTRAP3_UI`, `BOOTSTRAP4_UI`, `BOOTSTRAP5_UI`, `BOOTSTRAP_UI`.
     * 
     * @see Widget::defineUI()
     * 
     * @var string
     */
    public string $ui = '';

    /**
     * Использовать список ссылок полученных при запросе к статье.
     * 
     * @var bool
     */
    public bool $useArticle = true;

    /**
     * Имя тега навигации.
     * 
     * Используется для расположения списка ссылок в навигационной панели.
     * Например: 'nav'.
     * 
     * @var string
     */
    public string $navTag = 'nav';

    /**
     * Атрибуты HTML для тега навигационной панели.
     * 
     * Например: `['aria-label' => 'breadcrumb]`.
     * 
     * @var array
     */
    public array $navOptions = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        // только для режима разметки для представлений
        if (Gm::$app->isViewMarkup()) {
            $this->initTranslations();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMarkupOptions(array $options = []): array
    {
        if ($this->ui == self::BOOTSTRAP4_UI || $this->ui == self::BOOTSTRAP5_UI)
            $ui = 'bootstrap';
        else
            $ui = $this->ui;

        // параметры передаваемые в форму настройки разметки
        $itemParams = [
            'id'         => $this->id,
            'calledFrom' => $this->calledFromViewFile,
            'ui'         => $ui, // вид интерфейса виджета
            'encode'     => $this->encode, // указывает, следует ли HTML-кодировать метки ссылок
            'navTag'     => $this->navTag, // имя тега навигации
            'navOptions' => Str::parseArrayToString($this->navOptions), // атрибуты HTML тега навигации
            'tag'        => $this->tag, // имя тега контейнера
            'options'    => Str::parseArrayToString($this->options), // атрибуты HTML тега контейнера
            'itemTemplate'       => addcslashes($this->itemTemplate, '"'), // шаблон отображения неактивных элементов
            'activeItemTemplate' => addcslashes($this->activeItemTemplate, '"') // шаблон отображения активных элементов
        ];

        return [
            'component'  => 'widget',
            'uniqueId'   => $this->id,
            'dataId'     => 0,
            'registryId' => $this->registry['id'] ?? '',
            'title'      => $this->title ?: $this->t('{description}'),
            'control'    => [
                'text'   =>  $this->title ?: $this->t('{name}'), 
                'route'  => '@backend/site-markup/settings/view/' . ($this->registry['rowId'] ?? 0),
                'params' =>  $itemParams,
                'icon'   => $this->getAssetsUrl() . '/images/icon_small.svg'
            ],
            'menu' => [
                [
                    'text'    => $this->t('Markup settings'),
                    'route'   => '@backend/site-markup/settings/view/' . ($this->registry['rowId'] ?? 0),
                    'params'  => $itemParams,
                    'iconCls' => 'gm-markup__icon-markup-settings'
                ]
            ]
        ];
    }

    /**
     * Указывает, какой вид интерфейса использовать в выводе виджета.
     * 
     * @param string $ui Вид интерфейса виджета (например: `BOOTSTRAP3_UI`, `BOOTSTRAP4_UI`, `BOOTSTRAP5_UI`).
     * 
     * @return void
     */
    protected function defineUI(?string $ui): void
    {
        if (empty($this->ui)) {
            $this->ui = self::CUSTOM_UI;
            $this->navTag = '';
            return;
        }

        switch ($ui) {
            // Bootstrap 3.x
            case self::BOOTSTRAP3_UI:
                $this->tag = 'ol';
                $this->navTag = '';
                break;

            // Bootstrap 4.x, 5.x, +
            case self::BOOTSTRAP4_UI:
            case self::BOOTSTRAP5_UI:
            case self::BOOTSTRAP_UI:
                $this->tag = 'ol';
                $this->activeItemTemplate = "<li class=\"breadcrumb-item active\">{link}</li>";
                $this->itemTemplate = "<li class=\"breadcrumb-item\">{link}</li>";
                $this->navTag = 'nav';
                $this->navOptions['aria-label'] = 'breadcrumb';
                break;
        }
    }

    /**
     * Определяет список ссылок.
     * 
     * Если используется статья {@see Widget::$useArticle}, буду определён список 
     * ссылок относительно статьи.
     * 
     * @see Widget::$links
     * 
     * @return void
     */
    public function findLinks(): void
    {
        if ($this->useArticle) {
            $article = Gm::$app->page->findArticle();
            if ($article) {
                $this->links = $article->getBreadcrumbs();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeRun(): bool
    {
        // определение интерфейса виджета
        $this->defineUI($this->ui);

        // определение списка ссылок
        if (empty($this->links)) {
            $this->findLinks();
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function run(): mixed
    {
        $content = parent::run();

        if ($this->navTag) {
            return Html::tag($this->navTag, $content, $this->navOptions);
        }
        return $content;
    }
}