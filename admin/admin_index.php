<?php
/*************************************************************************************************
Libertempo : Gestion Interactive des Congés
Copyright (C) 2015 (Wouldsmina)
Copyright (C) 2015 (Prytoegrian)
Copyright (C) 2005 (cedric chauvineau)

Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les
termes de la Licence Publique Générale GNU publiée par la Free Software Foundation.
Ce programme est distribué car potentiellement utile, mais SANS AUCUNE GARANTIE,
ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation
dans un but spécifique. Reportez-vous à la Licence Publique Générale GNU pour plus de détails.
Vous devez avoir reçu une copie de la Licence Publique Générale GNU en même temps
que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation,
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, États-Unis.
*************************************************************************************************
This program is free software; you can redistribute it and/or modify it under the terms
of the GNU General Public License as published by the Free Software Foundation; either
version 2 of the License, or any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*************************************************************************************************/

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'define.php';

include_once ROOT_PATH .'fonctions_conges.php' ;
include_once INCLUDE_PATH .'fonction.php';
include_once INCLUDE_PATH .'session.php';
include_once ROOT_PATH .'fonctions_calcul.php';

// verif des droits du user à afficher la page
verif_droits_user('is_admin');



/*************************************/
// recup des parametres reçus :
// SERVER
$PHP_SELF=$_SERVER['PHP_SELF'];
// GET / POST
$onglet = getpost_variable('onglet', 'admin-users');


/*********************************/
/*   COMPOSITION DES ONGLETS...  */
/*********************************/

$onglets = array();


$onglets['admin-users']    = _('admin_onglet_gestion_user');
$onglets['ajout-user']    = _('admin_onglet_add_user');

if( $_SESSION['config']['gestion_groupes'] ) {
    if( $_SESSION['config']['admin_see_all'] || $_SESSION['userlogin']=="admin" || is_hr($_SESSION['userlogin']) )
        $onglets['admin-group'] = _('admin_onglet_gestion_groupe');
    $onglets['admin-group-users'] = _('admin_onglet_groupe_user');
    if( $_SESSION['config']['admin_see_all'] || $_SESSION['userlogin']=="admin" || is_hr($_SESSION['userlogin']) )
        $onglets['admin-group-responsables'] = _('admin_onglet_groupe_resp');
}

if ( !isset($onglets[ $onglet ]) && !in_array($onglet, array('chg_pwd_user', 'modif_group', 'modif_user', 'suppr_group','suppr_user')))
    $onglet = 'admin-users';

/*********************************/
/*   COMPOSITION DU HEADER...    */
/*********************************/

   $add_css = '<style>#onglet_menu .onglet{ width: '. (str_replace(',', '.', 100 / count($onglets) )).'% ;}</style>';
header_menu('', 'Libertempo : '._('button_admin_mode'),$add_css);

/*********************************/
/*   AFFICHAGE DES ONGLETS...  */
/*********************************/

echo '<div id="onglet_menu">';
foreach($onglets as $key => $title) {
    echo '<div class="onglet '.($onglet == $key ? ' active': '').'" >
        <a href="'.$PHP_SELF.'?onglet='.$key.'">'. $title .'</a>
    </div>';
}
echo '</div>';


/*********************************/
/*   AFFICHAGE DE L'ONGLET ...    */
/*********************************/


/** initialisation des tableaux des types de conges/absences  **/
// recup du tableau des types de conges (seulement les conges)
$tab_type_cong=recup_tableau_types_conges();

// recup du tableau des types de conges exceptionnels (seulement les conges exceptionnels)
$tab_type_conges_exceptionnels=recup_tableau_types_conges_exceptionnels();

echo '<div class="'.$onglet.' main-content">';
    include_once ROOT_PATH . 'admin/admin_'.$onglet.'.php';
echo '</div>';

/*********************************/
/*   AFFICHAGE DU BOTTOM ...   */
/*********************************/

bottom();
exit;
