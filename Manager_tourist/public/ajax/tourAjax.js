$(document).ready(function () {
    $(".edit").on("click", function (e) {
        e.preventDefault();
        var url = $(this).data("url");
        // console.log(url)
        editTour(url);
    });

    function editTour(url) {
        $("#edit-modal").modal("show");
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                if (res.code == 200) {
                    $("#name").val(res.tour.name);
                    $("#price").val(res.tour.price);
                    $("#departure_day").val(res.tour.departure_day);
                    $("#return_day").val(res.tour.return_day);
                    $("#type_vehical").val(res.tour.type_vehical);
                    $("#category_id").val(res.tour.category_id);
                     // Lưu nội dung vào trình soạn thảo TinyMCE trước khi đặt nội dung mới
                    tinymce.triggerSave();
                     // Thiết lập nội dung mới cho trình soạn thảo TinyMCE
                    tinymce.get('description').setContent(res.tour.description);

                    $("#feature_image_preview").attr("src", res.tour.feature_image_path);

                    // Hiển thị ảnh chi tiết
                    res.tourImageDetail.forEach(function(image) {
                        var imgElement = $("<img>");
                        imgElement.attr("src", image.image_path);
                        imgElement.addClass("other-image");
                        $("#other_images_container").append(imgElement);
                        //  console.log(image.image_path)
                    });


                    $("#tourId").val(res.tour.id);
                }
            },
        });
    }
});
