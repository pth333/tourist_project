function actionDelete(event) {
    event.preventDefault();
    let urlRequest = jQuery(this).data("url");
    let that = jQuery(this);
    swal({
        title: "Bạn có chắc chắn muốn xóa?",
        text: "Bạn sẽ không thể hoàn tác lại nó!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            jQuery.ajax({
                type: "GET",
                url: urlRequest,
                success: function (data) {
                    if (data.code == 200) {
                        that.parent().parent().remove();
                    }
                },
                error: function () {},
            });
            swal("Bạn đã xóa thành công!", {
                icon: "success",
            });
        } else {
            swal("Dữ liệu của bạn chưa được xóa!");
        }
    });
}

$(function () {
    $(document).on("click", ".action_delete", actionDelete);
});
