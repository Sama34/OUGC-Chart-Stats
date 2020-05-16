<?php

/***************************************************************************
 *
 *	OUGC Chart Stats plugin (/inc/languages/english/admin/ougc_chartstats.lang.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2020 Omar Gonzalez
 *
 *	Website: https://ougc.network
 *	Based off: https://community.mybb.com/thread-226110-post-1341939.html#pid1341939
 *
 *	Display public stats using a chart format using Highcharts.
 *
 ***************************************************************************

****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

// Plugin API
$l['setting_group_ougc_chartstats'] = 'OUGC Chart Stats';
$l['setting_group_ougc_chartstats_desc'] = 'Display public stats using a chart format using Highcharts.';

// ACP Thread Prefixes
$l['setting_ougc_chartstats_groups'] = 'Allowed Groups';
$l['setting_ougc_chartstats_groups_desc'] = 'Select the groups for which this feature will be displayed.';
$l['setting_ougc_chartstats_days'] = 'Date Cut';
$l['setting_ougc_chartstats_days_desc'] = 'Specify the number of days to display. Default is 30.';
$l['setting_ougc_chartstats_cache'] = 'Cache Time';
$l['setting_ougc_chartstats_cache_desc'] = 'Specify the number of hours to keep the cache. Default is 12.';

$l['ougc_chartstats_pluginlibrary'] = 'This plugin requires <a href="{1}">PluginLibrary</a> version {2} or later to be uploaded to your forum. Please upload the necessary files.';