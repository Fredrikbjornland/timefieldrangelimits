/**
 * Make sunday and monday unavailable.
 */
gform.addFilter("gform_datepicker_options_pre_init", function (
  optionsObj,
  formId,
  fieldId
) {
  if (fieldId !== "7") return optionsObj;

  optionsObj.firstDay = 1;
  optionsObj.beforeShowDay = function (date) {
    day = date.getDay();
    // 0 = sunday, 1=monday.
    return [day != 0 && day != 1];
  };

  return optionsObj;
});

/**
 * If its past 19:00, make todays date unavailable.
 */
gform.addFilter("gform_datepicker_options_pre_init", function (
  optionsObj,
  formId,
  fieldId
) {
  if (fieldId !== "7") return optionsObj;

  var d = new Date();
  var n = d.getHours();
  var firstAllowedDate = 0;

  if (n > 19) {
    firstAllowedDate = 1;
  }

  optionsObj.minDate = firstAllowedDate;

  return optionsObj;
});
