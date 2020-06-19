<?php

/***************************************************************************
 *
 *	OUGC Chart Stats plugin (/inc/plugins/ougc_chartstats.php)
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

// Die if IN_MYBB is not defined, for security reasons.
defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// Run/Add Hooks
if(!defined('IN_ADMINCP') && defined('THIS_SCRIPT') && THIS_SCRIPT == 'stats.php')
{
	global $templatelist;

	if(isset($templatelist))
	{
		$templatelist .= ',';
	}
	else
	{
		$templatelist = '';
	}

	$plugins->add_hook('stats_end', 'ougc_chartstats_stats_end');

	$templatelist .= 'ougcchartstats_headerinclude, ougcchartstats_data, ougcchartstats';
}

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT.'inc/plugins/pluginlibrary.php');

// Plugin API
function ougc_chartstats_info()
{
	global $lang;
	ougc_chartstats_lang_load();

	return array(
		'name'			=> 'OUGC Chart Stats',
		'description'	=> $lang->setting_group_ougc_chartstats_desc,
		'website'		=> 'https://ougc.network',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'https://ougc.network',
		'version'		=> '1.8.0',
		'versioncode'	=> 1800,
		'compatibility'	=> '18*',
		'codename'		=> 'ougc_chartstats',
		'pl'			=> array(
			'version'	=> 13,
			'url'		=> 'https://community.mybb.com/mods.php?action=view&pid=573'
		)
	);
}

// _activate() routine
function ougc_chartstats_activate()
{
	global $cache, $PL, $lang;
	ougc_chartstats_pl_check();

	// Add settings group
	$PL->settings('ougc_chartstats', $lang->setting_group_ougc_chartstats, $lang->setting_group_ougc_chartstats_desc, array(
		'groups'	=> array(
			'title'			=> $lang->setting_ougc_chartstats_groups,
			'description'	=> $lang->setting_ougc_chartstats_groups_desc,
			'optionscode'	=> 'groupselect',
			 'value'			=>	-1,
		 ),
		 'days'	=> array(
			'title'			=> $lang->setting_ougc_chartstats_days,
			'description'	=> $lang->setting_ougc_chartstats_days_desc,
			'optionscode'	=> 'numeric',
			 'value'			=>	30,
		 ),
		 'cache'	=> array(
			'title'			=> $lang->setting_ougc_chartstats_cache,
			'description'	=> $lang->setting_ougc_chartstats_cache_desc,
			'optionscode'	=> 'numeric',
			 'value'			=>	12,
		 ),
	));

	// Add template group
	$PL->templates('ougcchartstats', 'OUGC Chart Stats', array(
		''	=> '<br />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead"><strong>{$lang->ougc_chartstats_title}</strong></td>
	</tr>
	<tr>
		<td class="tcat"><strong>{$lang->ougc_chartstats_desc}</strong></td>
	</tr>
<tr>
	<td class="trow1">
		<div id="graphstats"></div>
		<script type="text/javascript">
		Highcharts.chart(\'graphstats\',
			{
				chart: {
					type: \'areaspline\',
					//width: 1200
				},
				title: {
					text: \'{$lang->ougc_chartstats_title}\'
				},
				legend: {
					layout: \'horizontal\',
					align: \'center\',
					verticalAlign: \'bottom\',
					backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || \'transparent\',
					itemStyle: {
						color: \'#333\'
					},
					itemHoverStyle:{
						color: \'#000\'
					},
					itemHiddenStyle:{
						color: \'#444\'
					}   
				},
				xAxis: {
					gridLineColor: \'#2f2f2f\',
					type:\'datetime\'
				},
				yAxis: {
					title: {
					text: \'{$lang->ougc_chartstats_count}\'
				},
				gridLineColor: \'#2f2f2f\'
			},
			tooltip: {
				shared: true
			},
			/*credits: {
				enabled: false
			},*/
			plotOptions: {
				areaspline: {
					fillOpacity: 0.4
				}
			},
			series: [
				{
					color:\'#c84040\',
					name: \'{$lang->ougc_chartstats_threads}\',
					data: [
						{$ougc_chartstats_threads}
					],
					pointStart: Date.UTC({$today})
				},
				{
					name: \'{$lang->ougc_chartstats_posts}\',
					data: [
						{$ougc_chartstats_posts}
					],
					pointStart: Date.UTC({$today})
				},
				{
					color: \'#800080\',
					name: \'{$lang->ougc_chartstats_newusers}\',
					data: [
						{$ougc_chartstats_newusers}
					],
					pointStart: Date.UTC({$today})
				},
				{
					color: \'#FFD700\',
					name: \'{$lang->ougc_chartstats_activeusers}\',
					data: [
						{$ougc_chartstats_activeusers}
					],
					pointStart: Date.UTC({$today})
				}
			]
		});
		</script>
	</td>
</tr>
</table>
<style>
	.highcharts-background {
		fill: transparent;
	}
</style>',
		'data'	=> '[Date.UTC({$day}), {$count}]{$comma}',
		'headerinclude'	=> '<script src="https://code.highcharts.com/highcharts.js"></script>',
	));

	// Insert/update version into cache
	$plugins = $cache->read('ougc_plugins');
	if(!$plugins)
	{
		$plugins = array();
	}

	$info = ougc_chartstats_info();

	if(!isset($plugins['chartstats']))
	{
		$plugins['chartstats'] = $info['versioncode'];
	}

	require_once MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('stats', '#'.preg_quote('{$headerinclude}').'#', "{\$headerinclude}\n{\$ougc_chartstats_headerinclude}");
	find_replace_templatesets('stats', '#'.preg_quote('{$footer}').'#', "{\$ougc_chartstats}\n{\$footer}");

	/*~*~* RUN UPDATES START *~*~*/

	/*~*~* RUN UPDATES END *~*~*/

	$plugins['chartstats'] = $info['versioncode'];
	$cache->update('ougc_plugins', $plugins);
}

// _deactivate() routine
function ougc_chartstats_deactivate()
{
	require_once MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('stats', '#'.preg_quote('{$ougc_chartstats_headerinclude}').'#i', '', 0);
	find_replace_templatesets('stats', '#'.preg_quote('{$ougc_chartstats}').'#i', '', 0);
}

// _install() routine
function ougc_chartstats_install()
{
	global $db;
	ougc_chartstats_pl_check();
}

// _is_installed() routine
function ougc_chartstats_is_installed()
{
	global $cache;

	$plugins = $cache->read('ougc_plugins');

	if(!$plugins)
	{
		$plugins = array();
	}

	return isset($plugins['chartstats']);
}

// _uninstall() routine
function ougc_chartstats_uninstall()
{
	global $db, $PL, $cache;
	ougc_chartstats_pl_check();

	$PL->cache_delete('ougc_chartstats');
	$PL->settings_delete('ougc_chartstats');
	$PL->templates_delete('ougcchartstats');

	// Delete version from cache
	$plugins = (array)$cache->read('ougc_plugins');

	if(isset($plugins['chartstats']))
	{
		unset($plugins['chartstats']);
	}

	if(!empty($plugins))
	{
		$cache->update('ougc_plugins', $plugins);
	}
	else
	{
		$PL->cache_delete('ougc_plugins');
	}
}

// Loads language strings
function ougc_chartstats_lang_load()
{
	global $lang;

	isset($lang->setting_group_ougc_chartstats) or $lang->load('ougc_chartstats');
}

// PluginLibrary dependency check & load
function ougc_chartstats_pl_check()
{
	global $lang;

	ougc_chartstats_lang_load();

	$info = ougc_chartstats_info();

	if(file_exists(PLUGINLIBRARY))
	{
		global $PL;
	
		$PL or require_once PLUGINLIBRARY;
	}

	if(!file_exists(PLUGINLIBRARY) || $PL->version < $info['pl']['version'])
	{
		flash_message($lang->sprintf($lang->ougc_chartstats_pluginlibrary, $info['pl']['url'], $info['pl']['version']), 'error');
		admin_redirect('index.php?module=config-plugins');
		exit;
	}
}

function ougc_chartstats_stats_end()
{
	global $mybb, $db, $templates, $ougc_chartstats_headerinclude, $ougc_chartstats, $theme, $lang;

	if(!is_member($mybb->settings['ougc_chartstats_groups']))
	{
		return;
	}

	$stats = $mybb->cache->read('ougc_chartstats');

	$max_days = (int)$mybb->settings['ougc_chartstats_days'];

	$input_days = $mybb->get_input('days', MyBB::INPUT_INT) > 0 ? $mybb->get_input('days', MyBB::INPUT_INT) : $max_days;

	if(!$stats || $max_days !== $input_days || $stats['dateline'] < TIME_NOW - (3600 * (int)$mybb->settings['ougc_chartstats_cache']) )
	{
		$stats = array(
			'dateline' => TIME_NOW,
			'posts' => array(),
			'threads' => array(),
			'newusers' => array(),
			'activeusers' => array()
		);

		$max_dateline = TIME_NOW - (86400 * $max_days);

		$query = $db->simple_select(
			'posts',
			'COUNT(pid) AS stats_count, DAY(FROM_UNIXTIME(dateline)) AS stats_day',
			"dateline>'{$max_dateline}' AND visible='1'",
			array('group_by' => 'stats_day')
		);

		while($post = $db->fetch_array($query))
		{
			$day = (int)$post['stats_day'];

			if($day <= $max_days)
			{
				$stats['posts'][$day] = (int)$post['stats_count'];
			}
		}

		$query = $db->simple_select(
			'threads',
			'COUNT(tid) AS stats_count, DAY(FROM_UNIXTIME(dateline)) AS stats_day',
			"dateline>'{$max_dateline}' AND visible='1'",
			array('group_by' => 'stats_day')
		);

		while($thread = $db->fetch_array($query))
		{
			$day = (int)$thread['stats_day'];

			if($day <= $max_days)
			{
				$stats['threads'][$day] = (int)$thread['stats_count'];
			}
		}

		$query = $db->simple_select(
			'users',
			'COUNT(uid) AS stats_count, DAY(FROM_UNIXTIME(regdate)) AS stats_day',
			"regdate>'{$max_dateline}'",
			array('group_by' => 'stats_day')
		);

		while($user = $db->fetch_array($query))
		{
			$day = (int)$user['stats_day'];

			if($day <= $max_days)
			{
				$stats['newusers'][$day] = (int)$user['stats_count'];
			}
		}

		$query = $db->simple_select(
			'users',
			'COUNT(uid) AS stats_count, DAY(FROM_UNIXTIME(lastvisit)) AS stats_day',
			"lastvisit>'{$max_dateline}'",
			array('group_by' => 'stats_day')
		);

		while($user = $db->fetch_array($query))
		{
			$day = (int)$user['stats_day'];

			if($day <= $max_days)
			{
				$stats['activeusers'][$day] = (int)$user['stats_count'];
			}
		}

		if($max_days === $input_days)
		{
			$mybb->cache->update('ougc_chartstats', $stats);
		}
	}

	ougc_chartstats_lang_load();

	$ougc_chartstats_headerinclude = eval($templates->render('ougcchartstats_headerinclude'));

	$lang->ougc_chartstats_desc = $lang->sprintf($lang->ougc_chartstats_desc, my_number_format($max_days));

	$ougc_chartstats_posts = '';

	$today = '';

	foreach($stats as $type => $data)
	{
		if($type == 'dateline')
		{
			continue;
		}

		$var = 'ougc_chartstats_'.$type;

		for($i = 1; $i <= $max_days; ++$i)
		{
			$day = date('Y,m,d', strtotime('-1 months', TIME_NOW - (86400 * ($i - 1))));

			if(!$today)
			{
				$today = $day;
			}

			$count = isset($data[$i]) ? $data[$i] : 0;
			$comma = $i == $max_days ? '' : ',';

			${$var} .= eval($templates->render('ougcchartstats_data', true, false));
		}
	}

	$ougc_chartstats = eval($templates->render('ougcchartstats'));
}
