/* A localised version of the Relative Time Function */

function Loc_relative_time(time_value) {
try {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000,10);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return RelTimeL10n.RelTSeconds;
  } else if(delta < 120) {
    return RelTimeL10n.RelTMinute;
  } else if(delta < (60*60)) {
    return RelTimeL10n.RelTMinutes.replace('%s',parseInt(delta / 60,10).toString());
  } else if(delta < (120*60)) {
    return RelTimeL10n.RelTHour;
  } else if(delta < (24*60*60)) {
    return RelTimeL10n.RelTHours.replace('%s',parseInt(delta/ 3600,10).toString());
  } else if(delta < (48*60*60)) {
    return RelTimeL10n.RelTDay;
  } else {
    return RelTimeL10n.RelTDays.replace('%s',parseInt(delta / 86400,10).toString());
  }
  }
catch(err)
{
    return "-";
}
}