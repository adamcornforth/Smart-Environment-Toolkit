function checkTime(i)
{
	if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
	return i;
}
function dateToStringOnlyHMS(date_old)
{
	var date_new = new Date(date_old);
	var h=date_new.getHours();
	var m=date_new.getMinutes();
	var s=date_new.getSeconds();
	m = checkTime(m);
	s = checkTime(s);
	return h+":"+m+":"+s;
}
function dateToTimeOnlyHMS(date_old)
{
	var date_new = new Date(date_old);
	var h=date_new.getHours();
	var m=date_new.getMinutes();
	var s=date_new.getSeconds();
	return (h * 3600000) + (m * 60000) + (s * 1000);
}
function msToHMSAsString(ms)
{
	// 1- Convert to seconds:
	var seconds = ms / 1000;
	// 2- Extract hours:
	var hours = parseInt( seconds / 3600 ); // 3,600 seconds in 1 hour
	seconds = seconds % 3600; // seconds remaining after extracting hours
	// 3- Extract minutes:
	var minutes = parseInt( seconds / 60 ); // 60 seconds in 1 minute
	// 4- Keep only seconds not extracted to minutes:
	seconds = seconds % 60;
	return hours+":"+minutes+":"+seconds;
}
function addSeriesToChart(chart, name, colour, data)
{
	var series = chart.addSeries(
	{
		name: name,
		color: colour,
		data: data
	});
	return series;
}