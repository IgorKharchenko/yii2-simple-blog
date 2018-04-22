/**
 *
 * @param {Object} parameters - содержит следующие параметры:
 *                              * postListUrl - адрес, на который отправляется ajax-запрос на получение постов.
 *                              * postViewUrl - адрес, который будет вшит в каждый пост.
 *                              По этому адресу можно перейти на пост с нужным id.
 *                              * postsPerPage - количество постов, отображаемых на странице.
 *                              * lastPostId   - id последнего поста на странице. Используется вместо offset-а.
 *                              См. контроллер PostController, метод actionList.
 */
function loadPosts(parameters) {
    if (
        !parameters.postListUrl ||
        !parameters.postViewUrl ||
        !parameters.postsPerPage ||
        !parameters.lastPostId
    ) {
        throw new Exception('Неправильно заданы параметры.');
    }

    let successCallback = function (data) {
        if (0 === data.length || false === data.success) {
            console.log('Не удалось получить посты с сервера. Статус-код: 200');
            return;
        }

        $.each(data.data, function (index, post) {
            let row = $('<div class="row item"><div class="col"></div></div>');
            row.append(`<span class="post-id" style="display: none">${post.id}</span>`);
            row.append(`<h2>${post.title}</h2>`);
            row.append(`<span>${post.short_description}</span>`);
            row.append('<br/>');
            row.append(`<span>${post.description}</span>`);
            row.append('<br/>');
            row.append(`<span>Создана в <i>${post.created_at}</i></span>`);
            row.append('<br/><br/>');

            let postViewUrl = parameters.postViewUrl.replace('postId', post.id);

            row.append(`<a href="${postViewUrl}">Перейти к записи</a>`);

            $('.posts').append(row);
        });
    }.bind(this);

    let errorCallback = function (XHR, textStatus) {
        console.log('Не удалось получить посты с сервера. Статус: ' + textStatus);
    };

    $.ajax({
        method:  'GET',
        url:     parameters.postListUrl,
        data:    {
            amount: parameters.postsPerPage,
            lastId: parameters.lastPostId
        },
        async:   true,
        success: successCallback,
        error:   errorCallback
    });
}