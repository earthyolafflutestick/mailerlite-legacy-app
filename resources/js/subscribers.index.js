const $ = require('jquery');
const DataTables = require('datatables.net-bm');

(function (settings) {

    $(function () {
        $('#subscribers').DataTable({
            serverSide: true,
            ajax: {
                url: settings.route,
                dataSrc: 'subscribers',
            },
            columns: [
                {data: 'email'},
                {data: 'name'},
                {data: 'country'},
                {data: 'subscribeDate'},
                {data: 'subscribeTime'},
            ],
            deferLoading: settings.deferLoading,
            pagingType: 'simple',

            initComplete: function () {
                $('#subscribers').fadeIn();
            }
        });
    });

})(_settings);
