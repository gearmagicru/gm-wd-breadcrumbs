<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\Breadcrumbs\Settings;

use Gm;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\MarkupSettingsWindow;

/**
 * Интерфейс окна настроек разметки виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\Breadcrumbs\Settings
 * @since 1.0
 */
class MarkupSettings extends MarkupSettingsWindow
{
    /**
     * {@inheritdoc}
     */
    public string $namespaceJs = 'Gm.wd.breadcrumbs';

    /**
     * {@inheritdoc}
     */
    public array $requires = [
        'Gm.view.window.Window',
        'Gm.view.form.Panel',
        'Gm.wd.breadcrumbs.SettingsController'
    ];

    /**
     * {@inheritdoc}
     * 
     * Т.к. виджет вызывает {@see \Gm\Backend\Marketplace\WidgetManager}, то
     * необходимо указать свой путь к ресурсам (иначе, URL-путь будет указан 
     * относительно модуля, который вызывает виджет).
     */
    public function jsPath(string $name = ''): string
    {
        return Gm::getAlias('@module::/gm/gm.wd.breadcrumbs/assets/js') . $name;
    }

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        $request = Gm::$app->request;

        /** @var string $ui Вид интерфейса виджета: bootstrap3, bootstrap, custom  */
        $ui = $request->post('ui', 'custom');
        /** @var array $uiParams Параметры интерфейса виджета  */
        $uiParams = [
            $ui => [
                'navTag'             => $request->post('navTag', ''),
                'navOptions'         => $request->post('navOptions', ''),
                'tag'                => $request->post('tag', ''),
                'options'            => $request->post('options', ''),
                'itemTemplate'       => $request->post('itemTemplate', ''),
                'activeItemTemplate' => $request->post('activeItemTemplate', '')
            ]
        ];

        $this->form->controller = 'gm-wd-breadcrumbs-settings';
        $this->width = 700;
        $this->form->autoScroll = true;
        $this->form->defaults = [
            'labelWidth' => 220,
            'labelAlign' => 'right'
        ];
        $this->form->items = [
            [
                'xtype'      => 'hidden',
                'name'       => 'id',
                'value'      => $request->post('id')
            ],
            [
                'xtype'      => 'textfield',
                'fieldLabel' => '#The markup is done in the template',
                'tooltip'    => '#In the specified template, the widget parameters are changed. You can make changes manually by opening the template for editing.',
                'name'       => 'calledFrom',
                'value'      => $request->post('calledFrom'),
                'maxLength'  => 50,
                'width'      => '100%',
                'readOnly'   => true,
                'allowBlank' => true
            ],
            [
                'xtype'      => 'checkbox',
                'ui'         => 'switch',
                'fieldLabel' => '#HTML encode link labels',
                'name'       => 'encode',
                'value'      => $request->post('encode', true),
            ],
            ExtCombo::local(
                $this->creator->t('Widget interface view'), 'ui', 
                [
                    'fields' => ['id', 'name'],
                    'data'   => [
                        ['custom', '#Custom'],  ['bootstrap3', 'Bootstrap 3'],  ['bootstrap', 'Bootstrap 4, 5']
                    ]
                ],
                [
                    'tooltip'   => '#The appearance of the interface determines how the navigation elements will be displayed: using Bootstrap styles or custom settings',
                    'value'     => $ui,
                    'style'     => 'margin:10px 0 10px 0',
                    'listeners' => ['select' => 'onSelectUI']
                ]
            ),
            $this->getCustomUI(isset($uiParams['custom']), $uiParams['custom'] ?? []),
            $this->getBootstrapUI(isset($uiParams['bootstrap']), $uiParams['bootstrap'] ?? []),
            $this->getBootstrap3UI(isset($uiParams['bootstrap3']), $uiParams['bootstrap3'] ?? [])
        ];
    }

    /**
     * Возвращает параметры отображения интерфейса виджета для Bootstrap 3.
     * 
     * @param bool $visible Отображение параметров.
     * @param array $params Параметры.
     * 
     * @return array
     */
    protected function getBootstrap3UI(bool $visible, array $params): array
    {
        return [
            'id'       => 'gm-breadcrumbs__bootstrap3',
            'xtype'    => 'fieldset',
            'title'    => $this->creator->t('Widget interface view - {0}', ['Bootstrap 3']),
            'hidden'   => !$visible,
            'defaults' => [
                'labelAlign' => 'right',
                'labelWidth' => 200,
                'width'      => '100%',
                'allowBlank' => true
            ],
            'items' => [
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Element container tag name',
                    'tooltip'    => '#The container contains navigation elements with links',
                    'name'       => 'bootstrap3[tag]',
                    'value'      => $params['tag'] ?? 'ul'
                ],
                [
                    'xtype'      => 'textarea',
                    'fieldLabel' => '#Container tag attributes',
                    'tooltip'    => '#Attributes have the form "attribute=value;attribute=value',
                    'name'       => 'bootstrap3[options]',
                    'value'      => $params['options'] ?? 'class=breadcrumb'
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Active element template',
                    'tooltip'    => '#The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element',
                    'name'       => 'bootstrap3[activeItemTemplate]',
                    'value'      => $params['activeItemTemplate'] ?? '<li class="active">{link}</li>'
                ],
                [
                    'xtype'      => 'textfield',
                    'tooltip'    => '#The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element',
                    'name'       => 'bootstrap3[itemTemplate]',
                    'value'      => $params['itemTemplate'] ?? '<li>{link}</li>'
                ],
            ]
        ];
    }

    /**
     * Возвращает параметры отображения интерфейса виджета для Bootstrap.
     * 
     * @param bool $visible Отображение параметров.
     * @param array $params Параметры.
     * 
     * @return array
     */
    protected function getBootstrapUI(bool $visible): array
    {
        return [
            'id'       => 'gm-breadcrumbs__bootstrap',
            'xtype'    => 'fieldset',
            'title'    => $this->creator->t('Widget interface view - {0}', ['Bootstrap 4, 5']),
            'hidden'   => !$visible,
            'defaults' => [
                'labelAlign' => 'right',
                'labelWidth' => 200,
                'width'      => '100%',
                'allowBlank' => true
            ],
            'items' => [
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Navigation tag name',
                    'tooltip'    => '#The navigation tag contains a container of elements and is rendered as a wrapper for the container',
                    'name'       => 'bootstrap[navTag]',
                    'value'      => $params['navTag'] ?? 'nav'
                ],
                [
                    'xtype'      => 'textarea',
                    'fieldLabel' => '#Navigation tag attributes',
                    'tooltip'    => '#Attributes have the form "attribute=value;attribute=value',
                    'name'       => 'bootstrap[navOptions]',
                    'value'      => $params['navOptions'] ?? 'aria-label=breadcrumb'
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Element container tag name',
                    'tooltip'    => '#The container contains navigation elements with links',
                    'name'       => 'bootstrap[tag]',
                    'value'      => $params['tag'] ?? 'ol'
                ],
                [
                    'xtype'      => 'textarea',
                    'fieldLabel' => '#Container tag attributes',
                    'tooltip'    => '#Attributes have the form "attribute=value;attribute=value',
                    'name'       => 'bootstrap[options]',
                    'value'      => $params['options'] ?? 'class=breadcrumb'
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Active element template',
                    'tooltip'    => '#The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element',
                    'name'       => 'bootstrap[activeItemTemplate]',
                    'value'      => $params['activeItemTemplate'] ?? '<li class="breadcrumb-item active">{link}</li>'
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Inactive element template',
                    'tooltip'    => '#The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element',
                    'name'       => 'bootstrap[itemTemplate]',
                    'value'      => $params['itemTemplate'] ?? '<li class="breadcrumb-item">{link}</li>'
                ]
            ]
        ];
    }

    /**
     * Возвращает индивидуальные параметры отображения интерфейса виджета.
     * 
     * @param bool $visible Отображение параметров.
     * @param array $params Параметры.
     * 
     * @return array
     */
    protected function getCustomUI(bool $visible, array $params): array
    {
        return [
            'id'       => 'gm-breadcrumbs__custom',
            'xtype'    => 'fieldset',
            'title'    => $this->creator->t('Widget interface view - {0}', [$this->creator->t('Custom')]),
            'hidden'   => !$visible,
            'defaults' => [
                'labelAlign' => 'right',
                'labelWidth' => 200,
                'width'      => '100%',
                'allowBlank' => true
            ],
            'items' => [
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Navigation tag name',
                    'tooltip'    => '#The navigation tag contains a container of elements and is rendered as a wrapper for the container',
                    'name'       => 'custom[navTag]',
                    'value'      => $params['navTag'] ?? ''
                ],
                [
                    'xtype'      => 'textarea',
                    'fieldLabel' => '#Navigation tag attributes',
                    'tooltip'    => '#Attributes have the form "attribute=value;attribute=value',
                    'name'       => 'custom[navOptions]',
                    'value'      => $params['navOptions'] ?? ''
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Element container tag name',
                    'tooltip'    => '#The container contains navigation elements with links',
                    'name'       => 'custom[tag]',
                    'value'      => $params['tag'] ?? ''
                ],
                [
                    'xtype'      => 'textarea',
                    'fieldLabel' => '#Container tag attributes',
                    'tooltip'    => '#Attributes have the form "attribute=value;attribute=value',
                    'name'       => 'custom[options]',
                    'value'      => $params['options'] ?? ''
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Active element template',
                    'tooltip'    => '#The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element',
                    'name'       => 'custom[activeItemTemplate]',
                    'value'      => $params['activeItemTemplate'] ?? ''
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#Inactive element template',
                    'tooltip'    => '#The template must contain a "{link}" expression, which will be replaced with an actual HTML link for each element',
                    'name'       => 'custom[itemTemplate]',
                    'value'      => $params['itemTemplate'] ?? ''
                ]
            ]
        ];
    }
}