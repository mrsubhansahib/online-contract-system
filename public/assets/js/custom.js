/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

var asset_url = "http://localhost:8000/";
var asset_url_image = asset_url;
var disableFieldsForView = [
    "autocomplete",
    "button",
    "checkbox-group",
    "date",
    "file",
    "header",
    "hidden",
    "number",
    "paragraph",
    "radio-group",
    "select",
    "starRating",
    "text",
    "textarea",
    "signature",
];
var disableFields = ["autocomplete", "button", "file", "hidden", "starRating"];

var disabledFieldButtons = {
    text: ["remove", "edit", "copy"],
    select: ["remove", "edit", "copy"],
    date: ["remove", "edit", "copy"],
    autocomplete: ["remove", "edit", "copy"],
    button: ["remove", "edit", "copy"],
    "checkbox-group": ["remove", "edit", "copy"],
    date: ["remove", "edit", "copy"],
    file: ["remove", "edit", "copy"],
    header: ["remove", "edit", "copy"],
    hidden: ["remove", "edit", "copy"],
    number: ["remove", "edit", "copy"],
    paragraph: ["remove", "edit", "copy"],
    "radio-group": ["remove", "edit", "copy"],
    select: ["remove", "edit", "copy"],
    starRating: ["remove", "edit", "copy"],
    text: ["remove", "edit", "copy"],
    textarea: ["remove", "edit", "copy"],
    signature: ["remove", "copy", "edit"],
};
const typeUserAttrs = {};

disableFieldsForView.forEach((field) => {
    typeUserAttrs[field] = {
        // requiredCustom: {
        //     label: 'Required',
        //     value: false,
        //     type: 'checkbox',
        // },
        user: {
            // label: 'user',
            value: "",
            type: "hidden",
        },
    };
});

var fields = [
    {
        label: "Signature",
        attrs: {
            type: "signature",
            placeholder: "enter your value",
        },
        icon: '<i class="fas fa-signature"></i>',
    },
    {
        label: "Image",
        attrs: {
            type: "image",
            placeholder: "enter your value",
            value: "",
        },
        icon: '<i class="fas fa-images"></i>',
    },
];
var templates = {
    signature: function (fieldData) {
        let element = {
            field: '<div class="signature-divs" id="' + fieldData.name + '">',
            onRender: function () {
                let value = this.config.value;
                let user = this.config.user ?? false;
                let html;
                if (value) {
                    html = $(`
                        <img class="w-25 d-block" ${
                            value
                                ? "src=" +
                                  asset_url +
                                  "uploads/signatures/" +
                                  value
                                : ""
                        } class="sign-image">
                    `);
                } else {
                    html = $(`
                        <a href="#signature-modal" class="btn btn-primary add-sign" data-value="${
                            fieldData.name
                        }" data-toggle="modal" data-user="${user}">Click Here</a>
                        <img class="w-25 d-block " ${
                            value
                                ? "src=" +
                                  asset_url +
                                  "uploads/signatures/" +
                                  value
                                : ""
                        }" class="sign-image">
                    `);
                }
                $(document.getElementById(fieldData.name)).append(html);
            },
        };
        return element;
    },
    image: function (fieldData) {
        let element = {
            field: '<div class="signature-divs" id="' + fieldData.name + '">',
            onRender: function () {
                let value = this.config.value;
                let html;
                if (value) {
                    html = $(`
                        <img class="w-25 d-block " ${
                            value
                                ? "src=" +
                                  asset_url_image +
                                  "uploads/images/" +
                                  value
                                : ""
                        } class="sign-image">
                    `);
                } else {
                    html = $(`
                          <a href="#image-modal" class="btn btn-primary add-image" data-value="${fieldData.name}" data-toggle="modal">Add Image</a>
                    `);
                }
                $(document.getElementById(fieldData.name)).append(html);
            },
        };
        return element;
    },
};

var templates2 = {
    signature: function (fieldData) {
        let element = {
            field: '<div class="signature-divs" id="' + fieldData.name + '">',
            onRender: function () {
                let value = this.config.value;
                let user = this.config.user ?? false;
                let html;
                if (value) {
                    html = $(`
                        <img class="w-25 d-block " ${
                            value
                                ? "src=" +
                                  asset_url +
                                  "uploads/signatures/" +
                                  value
                                : ""
                        } class="sign-image">
                    `);
                } else {
                    html = $(`
                        <a href="#signature-modal" class="btn btn-primary add-sign" data-value="${
                            fieldData.name
                        }" data-toggle="modal" data-user="${user}">Click Here</a>
                        <img class="w-25 d-block" ${
                            value
                                ? "src=" +
                                  asset_url +
                                  "uploads/signatures/" +
                                  value
                                : ""
                        }" class="sign-image">
                    `);
                }
                $(document.getElementById(fieldData.name)).append(html);
            },
        };
        return element;
    },
    image: function (fieldData) {
        let element = {
            field: '<div class="signature-divs" id="' + fieldData.name + '">',
            onRender: function () {
                let value = this.config.value;
                let html;
                if (value) {
                    html = $(`
                        <img class="w-25 d-block" ${
                            value
                                ? "src=" +
                                  asset_url_image +
                                  "uploads/images/" +
                                  value
                                : ""
                        } class="sign-image">
                    `);
                } else {
                    $(document.getElementById(fieldData.name)).prev().hide();
                    // $(document.getElementById(fieldData.name)).closest(".form-field").find(".field-label").hide()
                }
                $(document.getElementById(fieldData.name)).append(html);
            },
        };
        return element;
    },
};
var fBOptions = {
    scrollToFieldOnAdd: false,
    editOnAdd: true,
    typeUserAttrs,
    onOpenFieldEdit: function (editPanel) {
        let type = $(editPanel).parent().attr("type");
        $(editPanel).find(".value-wrap,.name-wrap").hide();
        if (type != "signature") {
            return;
        }
        let count = $(editPanel).find(".users-select").length;
        let selectTagUsers = "";
        let dataUser = $(editPanel).prev().find(".add-sign").eq(0).data("user");
        // console.log(dataUser);
        allUsers.forEach((user) => {
            selectTagUsers += `<option value="${user.id}" ${
                dataUser == user.id ? "selected" : ""
            }>${user.name}</option>`;
        });
        if (!count) {
            $(editPanel).find(".form-elements").prepend(`
            <div class="form-group label-wrap">
                <label>Users</label>
                <div class="input-wrap">
                    <select class="users-select select2 form-control">
                        <option value="">Select User</option>
                        ${selectTagUsers}
                    </select>
                </div>
            </div>
            `);
            $(".select2").select2();
            // prev-holder
        }
    },
    onCloseFieldEdit: function (editPanel) {
        let type = $(editPanel).parent().attr("type");
        if (type != "signature") {
            return;
        }
        let users = $(editPanel)
            .find(".form-elements .users-select")
            .eq(0)
            .val();
        let name = $(editPanel).prev().find(".signature-divs").attr("id");
        name = name.slice(0, name.lastIndexOf("-"));

        fbInstances.forEach((fb, i) => {
            let singleFormData = fb.actions.getData();
            for (let j = 0; j < singleFormData.length; j++) {
                if (singleFormData[j].name == name) {
                    singleFormData[j].user = users;
                    fbInstances[i].actions.setData(
                        JSON.stringify(singleFormData)
                    );
                    break;
                }
            }
        });
    },
    onSave: function () {
        $("#view-form").submit();
    },
    typeUserDisabledAttrs: {
        signature: [
            "placeholder",
            "className",
            "helpText",
            "access",
            "required",
            // 'name',
            // 'label'
        ],
        image: [
            "placeholder",
            "className",
            "helpText",
            "access",
            "required",
            // 'name',
            // 'label'
        ],
        header: ["className", "access"],
        "checkbox-group": [
            "placeholder",
            "className",
            "helpText",
            "access",
            "toggle",
            "inline",
            "other",
            "description",
            "required",
        ],
        date: [
            "placeholder",
            "className",
            "access",
            "toggle",
            "inline",
            "other",
            "required",
            "description",
        ],
        number: [
            "placeholder",
            "className",
            "description",
            "access",
            "placeholder",
            "min",
            "max",
            "required",
            "step",
        ],
        paragraph: ["subtype", "className", "access"],
        "radio-group": [
            "placeholder",
            "className",
            "helpText",
            "access",
            "toggle",
            "inline",
            "other",
            "required",
            "description",
        ],
        select: [
            "placeholder",
            "className",
            "description",
            "access",
            "multiple",
            "required",
        ],
        textarea: [
            "placeholder",
            "className",
            "description",
            "access",
            "subtype",
            "maxlength",
            "rows",
            "required",
        ],
        text: [
            "placeholder",
            "className",
            "access",
            "subtype",
            "maxlength",
            "required",
        ],
    },
    onAddField: function (fieldId) {
        changesMade = true;
    },
    fields,
    templates,
    disabledActionButtons: ["data", "clear", "save"],
    disableFields,
};

$(document).ready(function () {
    $(".modal").appendTo("body");
    $("body").delegate(".add-sign", "click", function () {
        let element = $(this);
        let name = element.data("value");
        $("#field-id").val(name);
    });
    $("body").delegate(".add-image", "click", function () {
        let element = $(this);
        let name = element.data("value");
        $("#field-image-id").val(name);
    });
    $("body").delegate(".page-count-tabs .nav-link", "click", function () {
        $(this).parent().parent().find("a").removeClass("active");
        $(this).addClass("active");
        $(this).find("a").addClass("active");
    });
    setTimeout(() => {
        $(".frmb-control *").removeAttr("draggable");
    }, 2000);
});
