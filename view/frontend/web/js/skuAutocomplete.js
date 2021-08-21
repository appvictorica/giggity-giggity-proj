define(['uiComponent', 'jquery', 'mage/url', 'ko'], function (Component, $, urlBuilder, ko) {
    return Component.extend({
        defaults: {
            searchSku: '',
            searchResult: [],
            searchSkuLength: 3
        },
        initObservable: function () {
            this._super();
            this.observe(['searchSku', 'searchResult']);
            return this;
        },
        initialize: function () {
            this._super();
            this.searchSku.subscribe(this.handleSearch.bind(this));
        },

        handleSearch: function (searchSku) {
            if (searchSku.length >= this.searchSkuLength) {
                $.ajax({
                    showLoader: true,
                    url: urlBuilder.build('victoriamod/index/autocomplete'),
                    data: 'sku=' + searchSku,
                    type: "POST",
                    dataType: 'json'
                }).done(function (data) {
                    this.searchResult(data);
                }.bind(this));
            }
        }
    });
});
