/*
 * MedLog Javascript Module (MJM)
 * by unrar (https://github.com/unrar) */

var mjm = function() {

  // MJM configuration
  var mjmConfig = {
    form: "#reg-form" // Form ID - the default is the registration form
  };


  //------GLOBAL FUNCTIONS (for all forms)-------


  /* Function: validateForm();
   * To be used in: all MJM forms
   * Validates the current form */
  function validateForm()
  {
    $(mjmConfig.form).validate({
      submitHandler: function(form) {
        updateForm(); // If successful, do whatever with the current form
        return false;
      }
    });
  }

  /* Function: fetchAndSet(formID);
   * To be used in: MJM
   * Fetch an entrie's data and put it on a form */
  function fetchAndSet(destForm, cb)
  {
    $(mjmConfig.form).validate({
      submitHandler: function(form) {
          fetchAJAX(destForm, cb);
          return false;
        }
      });
  }
  /* Function: updateForm();
   * To be used in: MJM
   * Behavior after a valid form is submitted */
  function updateForm()
  {
    // POST parameters to pass to the PHP script
    // Remember to add a hidden input name="op" containing the endpoint!
    var params = $(mjmConfig.form).serialize();

    // Call AJAX via jQuery
    $.ajax({
      data: params,
      dataType: 'json',
      url: apiURL,
      type: 'post',
      beforeSend: function() {
        $("#incoming").css("display", "inherit");
        $("#incoming").html("Processing...");
      },
      success: function(jres) {
        $("#incoming").html(jres.msg);
      },
      error: function(err) {
        var jres = $.parseJSON(err.responseText);
        $("#incoming").html("Error " + jres.status + ": " + jres.msg);
      }
    });
    return false;
  }

  function fetchAJAX(destForm, cb)
  {
    var params = $(mjmConfig.form).serialize();
    // Call AJAX via jQuery
    $.ajax({
      data: params,
      dataType: 'json',
      url: apiURL,
      type: 'post',

      success: function(jres) {
        
        $(mjmConfig.form).css("display", "none");
        $("#incoming").css("display", "none");
        $(destForm).css("display", "inherit");
        $(destForm + ' input[name="drug"]').val(jres.drug);
        $(destForm + ' input[name="dose"]').val(jres.dose);
        $(destForm + ' input[name="datetime"]').val(jres.date.replace(" ", "T"));
        $(destForm + ' input[id="' + jres.dose_unit + '"]').prop("checked", true);
        $(destForm + ' input[name="comment"]').val(jres.comment);
        $(destForm + ' input[name="id"]').val(jres.id);
        $("#incoming").html(jres.msg);
        cb();
      },
      error: function(err) {
        $("#incoming").css("display", "inherit");
        var jres = $.parseJSON(err.responseText);
        $("#incoming").html("Error " + jres.status + ": " + jres.msg);
      }
    });
  }
  // Public stuff
  return {
    setForm: function(newform) {
      mjmConfig.form = newform;
    },
    watchForm: validateForm,
    fetch: fetchAndSet
  };
}();

