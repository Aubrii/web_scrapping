function removeRecord() {
    let id = $(this).parents("div").parents("div").attr("id");

    console.log("id",id)
    if (confirm('Are you sure to remove this record ?')) {
        $.ajax({
            url: '/action.php',
            type: 'POST',
            data: {
                action: 'delete',
                article_id: id
            },
            error: function () {
                alert('Something is wrong');
            },
            success: function (data) {
                console.log('data',data)
                $("#" + id).remove();
                alert("Record removed successfully");
            }
        });
    }
}