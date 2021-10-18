/**
 * make monday unavailable.
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
    return [day != 1];
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
  var openingTime = 19

  if (d.getDay() == 5 || d.getDay() == 6) {
    if (secondLastFridayOfNovember() <= d.getTime() && d.getTime() <= secondSaturdayOfDecember()) {
      openingTime = 18
    }
  }

  if (n >= openingTime) {
    firstAllowedDate = 1;
  }

  optionsObj.minDate = firstAllowedDate;

  return optionsObj;
});


// helpers
function secondLastFridayOfNovember() {
  var d = new Date()
  var date = new Date(d.getFullYear(), 11, 1, 12);
  let weekday = date.getDay();
  let dayDiff = weekday === 0 ? 9 : weekday + 9;
  date.setDate(date.getDate() - dayDiff);
  return date.getTime();
}

function secondSaturdayOfDecember() {
  var todaysDate = new Date()
  var date = new Date(todaysDate.getFullYear(), 11, 1, 12);
  var weekday = date.getDay();
  let dayDiff = weekday === 6 ? 7 : (6 - weekday) + 7;
  date.setDate(date.getDate() + dayDiff);
  return date.getTime();
}