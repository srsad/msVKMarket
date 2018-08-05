var msVKMarket = function (config) {
    config = config || {};
    msVKMarket.superclass.constructor.call(this, config);
};
Ext.extend(msVKMarket, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('msvkmarket', msVKMarket);

msVKMarket = new msVKMarket();