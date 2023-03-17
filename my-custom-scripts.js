jQuery(document).ready(function ($) {
  var FormSubmission = {
    formErrorClass: "form-error",

    appendMessage: function (response) {
        const responseEl = $("#response");

        if (response.message && response.success) {
                responseEl.removeClass("alert-danger").addClass("alert-success");

        } else {
            responseEl.removeClass("alert-success").addClass("alert-danger");

            alert(`alert(response.message): ${response.success}`);
        }
        
        responseEl.html(`${response.message} `);

    },

    appendFormErrors: function (data, form) {
      for (var key in data) {
        var error = $(document.createElement("span"))
          .attr("class", FormSubmission.formErrorClass)
          .text(data[key])
          .css({
            padding: "2px 7px;",
            backgroundColor: "#e74c3c",
            color: "#fff",
          });

        form.find("input[name='" + key + "']").before(error);
      }
    },
    postFormSubmission: function (form, isModal, data) {
      FormSubmission.removeFormErrors(form);

      FormSubmission.appendMessage(data);
      if (data["success"] == true) {
        FormSubmission.resetForm(form, isModal);
      } else {
        FormSubmission.appendFormErrors(data, form);
      }

    },

    resetForm: function (form, isModal) {
      FormSubmission.removeFormErrors(form);

      form[0].reset();

      if (isModal == true) {
        FormSubmission.closeModal(form);
      }
    },

    removeFormErrors: function (form) {
      form.find("." + FormSubmission.formErrorClass).remove();
    },
  };

  $("#user-form").on("submit", function (e) {
    e.preventDefault();

    var form = $(this);
    $.ajax({
      url: MyCustomScriptsData.ajax_url,
      type: "POST",
      data: {
        action: "my_user_insert",
        name: $('#user-form input[name="name"]').val(),
        email: $('#user-form input[name="email"]').val(),
        phone: $('#user-form input[name="phone"]').val(),
        city: $('#user-form input[name="city"]').val(),
      },
      success: function (response) {
        console.log(' success: function (response) {. gives: ' + JSON.stringify(response));
        FormSubmission.postFormSubmission(form, false, response);

      },
      
        
        error: function (xhr, status, error) {
        var response = $.parseJSON(xhr.responseText);        
        console.log(response.message);
        //Output the error message   

        $("#response").html(response);
                console.log(' success: function (response) {. gives: ' + JSON.stringify(response));
                $("#response").html(response.message);
      },
    });
  });
});
