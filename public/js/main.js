$(function () {


    $('#upload').on('click', function () {

        var file_data = $('#zipbase').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);

        $('.response').html('отправка файла');
        $.ajax({
            url: 'api/upload',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (php_script_response) {
                console.log(php_script_response);
                $('.response').html(php_script_response);
            }
        });
    });


    $('#send_zip').on('click', function () {
        $('.zip_out').html('');
        console.log('test ajax zip');
        var zip_code = $('#input_zip').val();
        var form_data = {
            'zip_code': zip_code,
        };
        $.ajax({
            url: 'api/zipcode',
            dataType: 'json',
            cache: false,
            contentType: false,
            data: form_data,
            type: 'get',
            success: function (zipData) {
                console.log(zipData);

                for (key in zipData) {
                    $('.zip_out').append('' + key + ' = ' + zipData[key] + '<br>');
                }
            }
        });
    });



    $('#search_city').on('click', function () {
        $('.zip_out').html('');
        console.log('test ajax city name');
        var city_name = $('#input_city').val();
        var form_data = {
            'search_city': city_name,
        };

        $.ajax({
            url: 'api/zipcode',
            dataType: 'json',
            cache: false,
            contentType: false,
            data: form_data,
            type: 'get',
            success: function (zipData) {
                $('.zip_out').html(zipData);

                if (zipData['error']) {

                    $('.zip_out').append('<p> ' + zipData['error'] + ' </p>');

                } else {

                    var len = zipData.length;
                    $('.zip_out').append('<p> return size object ' + len + ' (look in the console)</p>');

                    zipData.forEach(function(item, i, zipData) {
                        console.log(item.city);
                        for (key in item) {
                            console.log(key + ' = ' + item[key]);
                        }
                        console.log('-----------------------------');
                    });

                }

            }
        });
    });



});
