
async function ajax_setting(url, data, method) {
    let response;

    await $.ajax({
        url: url,
        type: method,
        data: data,
        success: function (data, textStatus, xhr) {
            response = {
                data: data,
                status: xhr.status,
            }
        },
        error: function (data) {
            console.log(data)
        },
    });

    return response;
}
