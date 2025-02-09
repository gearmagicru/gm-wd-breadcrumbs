<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\Breadcrumbs\Model;

use Gm;
use Gm\Helper\Str;
use Gm\Panel\Data\Model\WidgetMarkupSettingsModel;

/**
 * Настройка разметки виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\Breadcrumbs\Model
 * @since 1.0
 */
class MarkupSettings extends WidgetMarkupSettingsModel
{
    /**
     * Параметры интерфейса виджета в виде пар "ключ - значение".
     * 
     * @see MarkupSettings::afterValidate()
     * 
     * @var array
     */
    protected array $uiParams = [];

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'ui'     => 'ui', // вид интерфейса виджета
            'encode' => 'encode', // указывает, следует ли HTML-кодировать метки ссылок
        ];
    }

    /**
     * Возвращает параметры / атрибуты интерфейса виджета.
     * 
     * @return array
     */
    public function uiAttributes(): array
    {
        return [
            'navTag', // имя тега навигации
            'navOptions', // атрибуты HTML тега навигации
            'tag', // имя тега контейнера
            'options', // атрибуты HTML тега контейнера
            'itemTemplate', // шаблон отображения неактивных элементов
            'activeItemTemplate' // шаблон отображения активных элементов
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formatterRules(): array
    {
        return [
            [['encode'], 'logic' => [true, false]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        $isValid = parent::afterValidate($isValid);

        if ($isValid) {
            // проверка параметров интерфейса виджета
            $uiParams = $this->getUnsafeAttribute($this->ui);
            $uiAttributes = $this->uiAttributes();
            foreach ($uiAttributes as $name) {
                if (isset($uiParams[$name])) {
                    $this->uiParams[$name] = $uiParams[$name];
                }
            }
            if (empty($this->uiParams)) {
                $this->addError(Gm::t('app', 'Parameter passed incorrectly "{0}"', ['ui']));
                return false;
            }
        }
        return $isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeUpdate(array &$params): void
    {
        $uiParams = $this->uiParams;
        // имя тега навигации (если не указан, нет смысла указывать в параметрах, 
        // т.к. по умолчанию у виджета значение `null`)
        if (empty($uiParams['navTag'])) {
            unset($uiParams['navTag']);
        }
        // атрибуты HTML тега контейнера (т.к. тип string, то преобразуем в array)
        if (empty($uiParams['options']))
            $uiParams['options'] = [];
        else
            $uiParams['options'] = Str::parseStringToArray($uiParams['options']);
        // атрибуты HTML тега навигации (т.к. тип string, то преобразуем в array)
        if (empty($uiParams['navOptions']))
            $uiParams['navOptions'] = [];
        else
            $uiParams['navOptions'] = Str::parseStringToArray($uiParams['navOptions']);
        // вид интерфейса виджета (т.к. по умолчанию у виджета значения 
        // свойства 'custom', то не будем зря указывать в параметрах)
        if ($params['ui'] !== 'custom') {
            $uiParams['ui'] = $params['ui'];
        }
        // следует ли HTML-кодировать метки ссылок (т.к. по умолчанию у виджета значения 
        // свойства `true`, то не будем зря указывать в параметрах)
        if ($params['encode'] === false) {
            $uiParams['encode'] = false;
        }
        // переопределяем параметры
        $params = $uiParams;
    }
}