/*!
 * Контроллер настроек разметки виджета.
 * Виджет "Навигационная цепочка".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.wd.breadcrumbs.SettingsController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-wd-breadcrumbs-settings',

    /**
     * Выбор вида отображения интерфейса виджета.
     * @param {Ext.form.field.ComboBox} me
     * @param {Ext.data.Model/Ext.data.Model} record 
     * @param {Object} eOpts
     */
    onSelectUI: function (me, record, eOpts) {
        let store = me.getStore();
        store.each((row) => { Ext.getCmp('gm-breadcrumbs__' + row.id).hide(); });
        Ext.getCmp('gm-breadcrumbs__' + record.id).show();
    }
});
