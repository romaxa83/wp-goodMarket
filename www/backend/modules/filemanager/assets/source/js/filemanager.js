$(document).ready(function () {
    var ajaxRequest = null,
            fileInfoContainer = $("#fileinfo"),
            strictThumb = $(window.frameElement).parents('[role="filemanager-modal"]').attr("data-thumb");

    function setAjaxLoader() {
        $("#fileinfo").html('<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>');
    }

    $('[href="#mediafile"]').on("click", function (e) {
        e.preventDefault();

        if (ajaxRequest) {
            ajaxRequest.abort();
            ajaxRequest = null;
        }

//      $(".item a").removeClass("active");

        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $('#tag-list').show();
            $("#fileinfo").empty();
            $('.file-info[data-id="' + $(this).data('key') + '"]').remove();
        } else {
            $(this).addClass("active");
            $('#tag-list').hide();
            var id = $(this).attr("data-key"),
                    url = $("#filemanager").attr("data-url-info");

            ajaxRequest = $.ajax({
                type: "GET",
                url: url,
                data: "id=" + id + "&strictThumb=" + strictThumb,
                beforeSend: function () {
                    setAjaxLoader();
                },
                success: function (html) {
                    $("#fileinfo").html(html);
                    $('#multiple-file').append($('.file-info.active').removeClass('active'));
                }
            });
        }

    });

    fileInfoContainer.on("click", '[role="delete"]', function (e) {
        e.preventDefault();

        var url = $(this).attr("href"),
                id = $(this).attr("data-id"),
                confirmMessage = $(this).attr("data-message");

        $.ajax({
            type: "POST",
            url: url,
            data: "id=" + id,
            beforeSend: function () {
                if (!confirm(confirmMessage)) {
                    return false;
                }
                $("#fileinfo").html('<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>');
            },
            success: function (json) {
                if (json.success) {
                    $("#fileinfo").html('');
                    $('[data-key="' + id + '"]').fadeOut();
                }
            }
        });
    });

    fileInfoContainer.on("submit", "#control-form", function (e) {
        e.preventDefault();

        var url = $(this).attr("action"),
                data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            beforeSend: function () {
                setAjaxLoader();
            },
            success: function (html) {
                $("#fileinfo").html(html);
            }
        });
    });

    // function collapseInfo(e) {
    //     e.preventDefault();
    //     $(this).toggleClass('active');
    //     $(this).next().stop().slideToggle(250);
    //     var text = $(this).children().text();
    //     $(this).children().text('Скрыть');
    //     if (text == 'Скрыть') {
    //         text = 'Показать';
    //         $(this).children().text(text);
    //     } else {
    //         text = 'Скрыть';
    //         $(this).children().text(text);
    //     }
    // }

    // $('.tag-link.custom-link').on('click', collapseInfo);

    function removeActiveItem() {
        $('#tag-list').show();
        $('body').find('.items .item a.active').click();
    }

    $('#fileinfo').on('click', '.text-detail', removeActiveItem);

});
