<?php 
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
include_once XOOPS_ROOT_PATH.'/language/'.$GLOBALS['xoopsConfig']['language'].'/calendar.php';
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo XOOPS_URL;?>/libraries/jscalendar/aqua/style.css" />
<script type="text/javascript" src="<?php echo XOOPS_URL.'/libraries/jscalendar/calendar.js';?>"></script>
<script type="text/javascript" src="<?php echo XOOPS_URL.'/libraries/jscalendar/calendar-setup.js';?>"></script>
<script type="text/javascript" src="<?php echo XOOPS_URL.'/libraries/jscalendar/constants.js';?>"></script>
<script type="text/javascript">
<!--
var calendar = null;

function selected(cal, date) {
  cal.sel.value = date;
}

function closeHandler(cal) {
  cal.hide();
  Calendar.removeEvent(document, "mousedown", checkCalendar);
}

function checkCalendar(ev) {
  var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
  for (; el != null; el = el.parentNode)
    if (el == calendar.element || el.tagName == "A") break;
  if (el == null) {
    calendar.callCloseHandler(); Calendar.stopEvent(ev);
  }
}
function showCalendar(id) {
  var el = xoopsGetElementById(id);
  if (calendar != null) {
    calendar.hide();
  } else {
    var cal = new Calendar(true, "<?php if (isset($jstime)) { echo $jstime; } else { echo 'null';}?>", selected, closeHandler);
    calendar = cal;
    cal.setRange(1000, 3000);
    calendar.create();
  }
  calendar.sel = el;
  calendar.parseDate(el.value);
  calendar.showAtElement(el);
  Calendar.addEvent(document, "mousedown", checkCalendar);
  return false;
}

</script>