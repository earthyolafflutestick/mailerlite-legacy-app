const $ = require('jquery');
const DataTables = require('datatables.net-bm');
const axios = require('axios');

(function (settings) {

    $(function () {
        $('#subscribers').DataTable({
            serverSide: true,
            ajax: {
                url: settings.listUrl,
                dataSrc: 'subscribers',
            },
            columns: [
                {data: 'email'},
                {data: 'name'},
                {data: 'country'},
                {data: 'subscribeDate'},
                {data: 'subscribeTime'},
                {
                    render: function (data, type, row, meta) {
                        let id = typeof row.id !== 'undefined' ?
                            row.id :
                            $(data).attr('data-subscriber');

                        return '<a class="button is-small is-danger" data-delete-subscriber="' + id + '">Remove</a>';
                    },
                },
            ],
            deferLoading: settings.deferLoading,
            pagingType: 'simple',

            initComplete: function () {
                $('#subscribers').fadeIn();
            }
        });
    });

    $(document).on('click', '[data-delete-subscriber]', e => {
        e.preventDefault();

        const id = $(e.target).attr('data-delete-subscriber');

        axios.delete(`${_settings.destroyUrl}/${id}`)
            .then((response) => {
                $('.notification').remove();
                $('#main').prepend(response.data.notice);
            })
            .catch((error) => {
                $('.notification').remove();
                $('#main').prepend(response.data.notice);
            });
    });


})(_settings);
