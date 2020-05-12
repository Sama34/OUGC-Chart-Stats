<?php

/***************************************************************************
 *
 *	OUGC Chart Stats plugin (/inc/languages/espanol/admin/ougc_chartstats.lang.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2020 Omar Gonzalez
 *
 *	Website: https://ougc.network
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
$l['setting_group_ougc_chartstats_desc'] = 'Muestra estadisticas publicas en formato de gráfica (Highcharts).';

// ACP Thread Prefixes
$l['setting_ougc_chartstats_groups'] = 'Grupos Permitidos';
$l['setting_ougc_chartstats_groups_desc'] = 'Select the groups for which this feature will be displayed.';
$l['setting_ougc_chartstats_days'] = 'Dias';
$l['setting_ougc_chartstats_days_desc'] = 'Escribe el numero de dias a considerar. Predeterminado: 30.';
$l['setting_ougc_chartstats_cache'] = 'Cache';
$l['setting_ougc_chartstats_cache_desc'] = 'Escribe el numero de horas a mantener el cache. Predeterminado: 12.';

$l['ougc_chartstats_pluginlibrary'] = 'Este plugin requiere la version {2} de <a href="{1}">PluginLibrary</a>. Sube los archivos necesarios.';