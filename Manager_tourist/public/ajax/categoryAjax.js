$(document).ready(function () {
    $(".edit").on("click", function (e) {
        e.preventDefault();
        var url = $(this).data("url");
        editCategory(url);
    });

    function editCategory(url) {
        $("#edit-modal").modal("show");
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                if (res.code == 200) {
                    $("#name").val(res.categories.name);
                    $("#parent_id").val(res.categories.parent_id);
                    $("#categoryId").val(res.categories.id);
                }
            },
        });
    }
});
