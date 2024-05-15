$(document).ready(function () {
    $(".edit").on("click", function (e) {
        e.preventDefault();
        var url = $(this).data("url");
        editDestination(url);
    });

    function editDestination(url) {
        $("#edit-modal").modal("show");
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                if (res.code == 200) {
                    $("#name").val(res.destination.name);
                    $("#address").val(res.destination.address);
                    // Lưu nội dung vào trình soạn thảo TinyMCE trước khi đặt nội dung mới
                    tinymce.triggerSave();
                    // Thiết lập nội dung mới cho trình soạn thảo TinyMCE
                    tinymce.get('description').setContent(res.destination.description);
                    $("#opening_time").val(res.destination.opening_time);
                    $("#closing_time").val(res.destination.closing_time);
                    $("#category_id").val(res.destination.category_id);
                    $("#feature_image_preview").attr("src", res.destination.feature_image_path);
                    $("#destinationId").val(res.destination.id);

                    // Hiển thị ảnh chi tiết
                    res.destinationImage.forEach(function(image) {
                        var imgElement = $("<img>");
                        imgElement.attr("src", image.image_path);
                        imgElement.addClass("other-image");
                        $("#other_images_container").append(imgElement);
                        //  console.log(image.image_path)
                    });
                }
            },
        });
    }
});
