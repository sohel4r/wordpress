<?php
global $NTS_TIME_WEEKDAYS;
$NTS_TIME_WEEKDAYS = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',  );

global $NTS_TIME_WEEKDAYS_SHORT;
$NTS_TIME_WEEKDAYS_SHORT = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );

global $NTS_TIME_MONTH_NAMES;
$NTS_TIME_MONTH_NAMES = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );

global $NTS_TIME_MONTH_NAMES_REPLACE;
$NTS_TIME_MONTH_NAMES_REPLACE = array();
reset( $NTS_TIME_MONTH_NAMES );
foreach( $NTS_TIME_MONTH_NAMES as $mn ){
	$NTS_TIME_MONTH_NAMES_REPLACE[] = $mn;
	}

/* new object oriented style */
class Hc_time extends DateTime {
	var $timeFormat = 'g:i A';
	var $dateFormat = 'd/m/Y';
	var $weekdays = array();
	var $weekdaysShort = array();
	var $monthNames = array();
	var $timezone = '';
	var $weekStartsOn = 1;

	function __construct( $time = 0, $tz = '' ){
//static $initCount;
//$initCount++;
//echo "<h2>init $initCount</h2>";
		if( strlen($time) == 0 )
			$ts = 0;
		if( ! $time )
			$time = time();
		if( is_array($time) )
			$time = $time[0];

		$strTime = '@' . $time;
		parent::__construct( $strTime );

		$CI =& ci_get_instance();

		if( ! $tz ){
			$tz = $CI->app_conf->get('timezone');
			}
		if( $tz ){
			$this->setTimezone( $tz );
			}
		$this->weekStartsOn = $CI->app_conf->get('week_starts');

		$time_format = $CI->app_conf->get('time_format');
		if( $time_format )
			$this->timeFormat = $time_format;
		$date_format = $CI->app_conf->get('date_format');
		if( $date_format )
			$this->dateFormat = $date_format;
		}

	function isDateDb( $date )
	{
		$return = FALSE;
		if( 
			( strlen($date) == 8 ) &&
			(! preg_match('/\D/', $date))
			)
		{
			$return = TRUE;
		}
		return $return;
	}

	function setDateFromFormat( $text, $date_format = '' )
	{
		if( ! $date_format )
			$date_format = $this->dateFormat;

		if( function_exists('date_create_from_format') )
		{
			$date = DateTime::createFromFormat( $date_format, $text ); 
			if( $date )
				$this->setDateDb( $date->format('Ymd') );
			else
				return FALSE;
		}
		else
		{
			$schedule = $text;
			$schedule_format = str_replace(array('Y','m','d', 'H', 'i','a'),array('%Y','%m','%d', '%I', '%M', '%p' ), $date_format );
			// %Y, %m and %d correspond to date()'s Y m and d.
			// %I corresponds to H, %M to i and %p to a
			$ugly = strptime( $schedule, $schedule_format );
			$ymd = sprintf(
				'%04d-%02d-%02d %02d:%02d:%02d',
				$ugly['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
				$ugly['tm_mon'] + 1,      // This will be the month minus one, so we add one.
				$ugly['tm_mday'], 
				$ugly['tm_hour'], 
				$ugly['tm_min'], 
				$ugly['tm_sec']
				);

			$date_db = sprintf(
				'%04d%02d%02d',
				$ugly['tm_year'] + 1900,
				$ugly['tm_mon'] + 1,
				$ugly['tm_mday']
				);
			$this->setDateDb( $date_db );
		}
	}

	function formatToDatepicker( $dateFormat = '' )
    {
		if( ! $dateFormat )
			$dateFormat = $this->dateFormat;

		$pattern = array(
			//day
			'd',	//day of the month
			'j',	//3 letter name of the day
			'l',	//full name of the day
			'z',	//day of the year

			//month
			'F',	//Month name full
			'M',	//Month name short
			'n',	//numeric month no leading zeros
			'm',	//numeric month leading zeros

			//year
			'Y', //full numeric year
			'y'	//numeric year: 2 digit
			);

		$replace = array(
			'dd','d','DD','o',
			'MM','M','m','mm',
			'yyyy','y'
		);
		foreach($pattern as &$p)
		{
			$p = '/'.$p.'/';
		}
		return preg_replace( $pattern, $replace, $dateFormat );
	}

	function setNow(){
		$this->setTimestamp( time() );
		}

	function differ( $other ){
		$other_date = $other->formatDate_Db();
		$this_date = $this->formatDate_Db();

		if( $this_date == $other_date ){
			$delta = 0;
			}
		elseif( $this_date > $other_date ){
			$delta = $this->getTimestamp() - $other->getTimestamp();
			}
		else {
			$delta = $other->getTimestamp() - $this->getTimestamp();
			}

		if( $delta ){
			$return = floor( $delta / (24 * 60 * 60) );
			}
		return $return;
		}
	
	function getDatesOfMonth(){
		$return = array();

		$this->setEndMonth();
		$end_month = $this->formatDate_Db();

		$this->setStartMonth();
		$rex_date = $this->formatDate_Db();
		while( $rex_date <= $end_month ){
			$return[] = $rex_date;
			$this->modify( '+1 day' );
			$rex_date = $this->formatDate_Db();
			}
		return $return;
		}
		
	static function expandPeriodString( $what, $multiply = 1 ){
		$string = '';
		switch( $what ){
			case 'd':
				$string = '+' . 1 * $multiply . ' days';
				break;
			case '2d':
				$string = '+' . 2 * $multiply . ' days';
				break;
			case 'w':
				$string = '+' . 1 * $multiply . ' weeks';
				break;
			case '2w':
				$string = '+' . 2 * $multiply . ' weeks';
				break;
			case '3w':
				$string = '+' . 3 * $multiply . ' weeks';
				break;
			case '6w':
				$string = '+' . 6 * $multiply . ' weeks';
				break;
			case 'm':
				$string = '+' . 1 * $multiply . ' months';
				break;
			}
		return $string;
		}

	function setTimezone( $tz ){
		if( is_array($tz) )
			$tz = $tz[0];

//		if( preg_match('/^-?[\d\.]$/', $tz) ){
//			$currentTz = ($tz >= 0) ? '+' . $tz : $tz;
//			$tz = "Etc/GMT$currentTz";
//			echo "<br><br>Setting timezone as Etc/GMT$currentTz<br><br>";
//			}
		if( ! $tz )
			$tz = date_default_timezone_get();

		$this->timezone = $tz;
		$tz = new DateTimeZone($tz);
		parent::setTimezone( $tz );
		}

	function getLastDayOfMonth(){
		$thisYear = $this->getYear(); 
		$thisMonth = $this->getMonth();

		$this->setDateTime( $thisYear, ($thisMonth + 1), 0, 0, 0, 0 );
		$return = $this->format( 'j' );
		return $return;
		}

	function getTimestamp(){
		if( function_exists('date_timestamp_get') ){
			return parent::getTimestamp();
			}
		else {
			$return = $this->format('U');
			return $return;
			}
		}

	function setTimestamp( $ts ){
		if( function_exists('date_timestamp_set') ){
			return parent::setTimestamp( $ts );
			}
		else {
			$strTime = '@' . $ts;
			parent::__construct( $strTime );
			$this->setTimezone( $this->timezone );
			return;
			}
		}

	static function splitDate( $string ){
		$year = substr( $string, 0, 4 );
		$month = substr( $string, 4, 2 );
		$day = substr( $string, 6, 4 );
		$return = array( $year, $month, $day );
		return $return;
		}

	function timestampFromDbDate( $date ){
		list( $year, $month, $day ) = Hc_time::splitDate( $date );
		$this->setDateTime( $year, $month, $day, 0, 0, 0 );
		$return = $this->getTimestamp();
		return $return;
		}

	function getParts(){
		$return = array( $this->format('Y'), $this->format('m'), $this->format('d'), $this->format('H'), $this->format('i') );
		return $return;
		}

	function getYear(){
		$return = $this->format('Y');
		return $return;
		}

	function getMonth(){
		$return = $this->format('m');
		return $return;
		}

	function getMonthName(){
		global $NTS_TIME_MONTH_NAMES;
		$thisMonth = (int) $this->getMonth();
		$return = $NTS_TIME_MONTH_NAMES[ $thisMonth - 1 ];
		return $return;
		}

	function getDay(){
		$return = $this->format('d');
		return $return;
		}

	function getStartDay(){
		$thisYear = $this->getYear(); 
		$thisMonth = $this->getMonth();
		$thisDay = $this->getDay();

		$this->setDateTime( $thisYear, $thisMonth, $thisDay, 0, 0, 0 );
		$return = $this->getTimestamp();
		return $return;
		}

	function setStartDay(){
		$thisYear = $this->getYear(); 
		$thisMonth = $this->getMonth();
		$thisDay = $this->getDay();

		$this->setDateTime( $thisYear, $thisMonth, $thisDay, 0, 0, 0 );
		$return = $this->getTimestamp();
		return $return;
		}

	function setNextDay(){
		$this->setStartDay();
		$this->modify( '+1 day' );
		}

	function getEndDay(){
		$thisYear = $this->getYear(); 
		$thisMonth = $this->getMonth();
		$thisDay = $this->getDay();

		$this->setDateTime( $thisYear, $thisMonth, ($thisDay + 1), 0, 0, 0 );
		$return = $this->getTimestamp();
		return $return;
		}

	function setStartWeek(){
		$this->setStartDay();
		$weekDay = $this->getWeekday();

		while( $weekDay != $this->weekStartsOn ){
			$this->modify( '-1 day' );
			$weekDay = $this->getWeekday();
			}
		}

	function setEndWeek(){
		$this->setStartDay();
		$this->modify( '+1 day' );
		$weekDay = $this->getWeekday();

		while( $weekDay != $this->weekStartsOn ){
			$this->modify( '+1 day' );
			$weekDay = $this->getWeekday();
			}
		$this->modify( '-1 day' );
		}

	function setStartMonth(){
		$thisYear = $this->getYear(); 
		$thisMonth = $this->getMonth();
		$this->setDateTime( $thisYear, $thisMonth, 1, 0, 0, 0 );
		}

	function setEndMonth(){
		$thisYear = $this->getYear(); 
		$thisMonth = $this->getMonth();
		$this->setDateTime( $thisYear, ($thisMonth + 1), 1, 0, 0, -1 );
		}

	function setStartYear(){
		$thisYear = $this->getYear(); 
		$this->setDateTime( $thisYear, 1, 1, 0, 0, 0 );
		}

	function timezoneShift(){
		$return = 60 * 60 * $this->timezone;
		return $return;
		}

	function setDateTime( $year, $month, $day, $hour, $minute, $second ){
		$this->setDate( $year, $month, $day );
		$this->setTime( $hour, $minute, $second );
		}

	function setDateDb( $date ){
		list( $year, $month, $day ) = Hc_time::splitDate( $date );
		$this->setDateTime( $year, $month, $day, 0, 0, 0 );
		}

	function formatTimeOfDay( $ts ){
		$this->setDateDb('20130315');
		$this->modify( '+' . $ts . ' seconds' );
		return $this->formatTime();
		}

	function formatTime( $duration = 0, $displayTimezone = 0 ){
		$return = $this->format( $this->timeFormat );
		if( $duration ){
			$this->modify( '+' . $duration . ' seconds' );
			$return .= ' - ' . $this->format( $this->timeFormat );
			}

		if( $displayTimezone ){
			$return .= ' [' . Hc_time::timezoneTitle($this->timezone) . ']';
			}
		return $return;
		}

	function formatDate( $format = '' ){
		global $NTS_TIME_MONTH_NAMES, $NTS_TIME_MONTH_NAMES_REPLACE;
		if( ! $format )
			$format = $this->dateFormat;
		$return = $this->format( $format );
	// replace months 
		$return = str_replace( $NTS_TIME_MONTH_NAMES, $NTS_TIME_MONTH_NAMES_REPLACE, $return );
		return $return;
		}

	static function formatDateParam( $year, $month, $day ){
		$return = sprintf("%04d%02d%02d", $year, $month, $day);
		return $return;
		}

	function formatDate_Db(){
		$dateFormat = 'Ymd';
		$return = $this->format( $dateFormat );
		return $return;
		}

	function formatTime_Db(){
		$dateFormat = 'Hi';
		$return = $this->format( $dateFormat );
		return $return;
		}

	function getWeekday(){
		$return = $this->format('w');
		return $return;
		}

	function formatWeekday(){
		global $NTS_TIME_WEEKDAYS;
		$return = $NTS_TIME_WEEKDAYS[ $this->format('w') ];
		return $return;
		}

	function formatFull(){
		$return = $this->formatWeekdayShort() . ', ' . $this->formatDate() . ' ' . $this->formatTime();
		return $return;
		}

	function formatDateFull(){
		$return = $this->formatWeekdayShort() . ', ' . $this->formatDate();
		return $return;
		}

	function formatWeekdayShort(){
		global $NTS_TIME_WEEKDAYS_SHORT;
		$return = $NTS_TIME_WEEKDAYS_SHORT[ $this->format('w') ];
		return $return;
		}

	static function timezoneTitle( $tz ){
		if( is_array($tz) )
			$tz = $tz[0];
		$tzobj = new DateTimeZone( $tz );
		$dtobj = new DateTime();
		$dtobj->setTimezone( $tzobj );
		$offset = $tzobj->getOffset($dtobj);

		$offsetString = 'GMT';
		$offsetString .= ($offset >= 0) ? '+' : '';
		$offsetString = $offsetString . ( $offset/(60 * 60) );

		$return = $tz . ' (' . $offsetString . ')';
		return $return;
		}

	static function getTimezones(){
		$skipStarts = array('Brazil/', 'Canada/', 'Chile/', 'Etc/', 'Mexico/', 'US/');
		$return = array();
		$timezones = timezone_identifiers_list();
		reset( $timezones );
		foreach( $timezones as $tz ){
			if( strpos($tz, "/") === false )
				continue;
			$skipIt = false;
			reset( $skipStarts );
			foreach( $skipStarts as $skip ){
				if( substr($tz, 0, strlen($skip)) == $skip ){
					$skipIt = true;
					break;
					}
				}
			if( $skipIt )
				continue;

			$tzTitle = Hc_time::timezoneTitle( $tz );
			$return[] = array( $tz, $tzTitle );
			}
		return $return;
		}

	static function formatPeriodShort( $ts ){
		$day = (int) ($ts/(24 * 60 * 60));
		$hour = (int) ( ($ts - (24 * 60 * 60)*$day)/(60 * 60));
		$minute = (int) ( $ts - (24 * 60 * 60)*$day - (60 * 60)*$hour ) / 60;

		$formatArray = array();
		if( $day > 0 ){
			$formatArray[] = $day;
			}
		$formatArray[] = sprintf( "%02d", $hour );
		$formatArray[] = sprintf( "%02d", $minute );

		$verbose = join( ':', $formatArray );
		return $verbose;
		}

	static function formatPeriod( $ts ){
//		$conf =& ntsConf::getInstance();
//		$limitMeasure = $conf->get('limitTimeMeasure');

		$limitMeasure = '';
		switch( $limitMeasure ){
			case 'minute':
				$day = 0;
				$hour = 0;
				$minute = (int) ( $ts ) / 60;
				break;
			case 'hour':
				$day = 0;
				$hour = (int) ( ($ts)/(60 * 60));
				$minute = (int) ( $ts - (60 * 60)*$hour ) / 60;
				break;
			default:
				$day = (int) ($ts/(24 * 60 * 60));
				$hour = (int) ( ($ts - (24 * 60 * 60)*$day)/(60 * 60));
				$minute = (int) ( $ts - (24 * 60 * 60)*$day - (60 * 60)*$hour ) / 60;
				break;
			}

		$formatArray = array();
		if( $day > 0 ){
			if( $day > 1 )
				$formatArray[] = $day . ' ' . lang('time_days');
			else
				$formatArray[] = $day . ' ' . lang('time_days');
			}
		if( $hour > 0 ){
			if( $hour > 1 )
				$formatArray[] = $hour . ' ' . lang('time_hours');
			else
				$formatArray[] = $hour . ' ' . lang('time_hour');
			}
		if( $minute > 0 ){
			if( $minute > 1 )
				$formatArray[] = $minute . ' ' . lang('time_minutes');
			else
				$formatArray[] = $minute . ' ' . lang('time_minute');
			}

		$verbose = join( ' ', $formatArray );
		return $verbose;
		}

	function getMonthMatrix( $endDate = '' ){
		$matrix = array();
		$currentMonthDay = 0;
		$startDate = $this->formatDate_Db();

		if( $endDate )
			$this->setDateDb( $endDate );
		else
			$this->setEndMonth( $endDate );
		$this->setEndWeek();
		$endDate = $this->formatDate_Db();

		$this->setDateDb( $startDate );
		$this->setStartWeek();
		$rexDate = $this->formatDate_Db();
		

		while( $rexDate <= $endDate )
		{
			$week = array();
			for( $weekDay = 0; $weekDay <= 6; $weekDay++ )
			{
				$week[] = $rexDate;
				$this->modify('+1 day');
				$rexDate = $this->formatDate_Db();
			}
			$matrix[] = $week;
		}
		return $matrix;
		}
	}
?>