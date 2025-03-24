$(document).ready(function () {
    var sig = $('#sig').signature({ syncField: '#signature', syncFormat: 'PNG' });
    $('#clear').click(function (e) {
        e.preventDefault();
        sig.signature('clear');
        $("#signature64").val('');
    });
    $("#save-signature2").on("click", function () {
        let value = $("#signature").val();
        let idField = "#" + $(".new-class").val();
        console.log(idField);
        $.ajax({
            type: 'POST',
            url: "/contracts/saveSignature",
            data: {
                signature: value,
            },
            success: function (res) {
                $("#signature-alert").show(500)
                $(idField).find('img').attr('src', asset_url + "uploads/signatures/" + res.name)
                $(idField).find('a').hide();
                sig.signature('clear');
                $("#signature64").val('');
                updateAfterSign(idField.replace("#", "").replace("-preview", ""), res.name)
                $("#signature-modal").modal('hide')
                sig.signature('clear');
                $("#signature64").val('');
            }
        })
    })
    $("body").delegate(".select-sign", "click", function () {
        let selectedSign = $(this).data("name");
        $(this).html('Sign Name Copied &check;')
        copyImgName(selectedSign)
        setTimeout(() => {
            $(this).html('Select')
        }, 3000);
    })

    // Image Upload
    $("#uploading-image").on("change", function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.image-preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    })

    $("#save-image").on("click", function () {

        let idField = "#" + $("#field-image-id").val();
        
        var formData = new FormData();
        if ($('#uploading-image')[0].files.length==0) {
            $("#image-error").show(300)
            return;
        }
        var file = $('#uploading-image')[0].files[0]; // Get the file from input element
        formData.append('file', file); // Append the file to FormData object

        $.ajax({
            url: '/contracts/saveImage', // URL to your server-side upload script
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $("#image-alert").show(500)
                $(idField).find('img').attr('src', asset_url + "uploads/images/" + response)
                $(idField).find('a').hide();
                updateAfterImage(idField.replace("#", "").replace("-preview", ""), response)
                $("#image-modal").modal('hide')
                setTimeout(() => {
                    $('.image-preview').attr("src","")
                    $('#uploading-image').val("")
                    $("#image-alert").hide()
                }, 2000);
            }
        });
        return;
    })
})
function copyImgName(selectedSign) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(selectedSign).select();
    document.execCommand("copy");
    $temp.remove();
}
function updateAfterSign(fieldName, signImgName) {

    const allData = fbInstances.map((fb) => {
        // 
        fb.options.formData.map((field) => {
            if (field.name == fieldName) {
                field.value = signImgName;
            }
            return field;
        })
        return fb;
    });

    fbInstances = allData;
    return;

}

function updateAfterImage(fieldName, signImgName) {

    // console.log(fieldName,signImgName);
    // return;
    
    const allData = fbInstances.map((fb,i) => {

        formOldData = fb.actions.getData()
        formNewData = formOldData.map((field,j) => {
            if (field.name == fieldName) {
                field.value = signImgName;
            }
            return field;
        })
        console.log(formNewData);
        fb.actions.setData(formNewData)
        return fb;

    });

    fbInstances = allData;
}
