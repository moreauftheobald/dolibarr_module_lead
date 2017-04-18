<?php

$res = @include '../../main.inc.php'; // For root directory
if (! $res)
	$res = @include '../../../main.inc.php'; // For "custom" directory
if (! $res)
	die("Include of main fails");

global $db, $user;
require_once DOL_DOCUMENT_ROOT . '/volvo/class/lead.extend.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.form.class.php';

$lead_id = GETPOST('id_lead');
$new_statut = GETPOST('new_statut');
$form = new Form($db);

$lead = new Leadext($db);
$res = $lead->fetch($lead_id);
if($res>0){
	if(empty($action)){
		$statut = explode('_', $new_statut);
		switch($statut[0]){
			case 'encours':
				$c_status= 5;
				switch($statut[1]){
					case 'chaude':
						$chaude =1;
						break;
					case 'froide':
						$chaude =0;
						break;
				}
				break;
			case 'traite':
				$c_status = 6;
				$chaude=0;
				break;

			case 'perdu':
				$formconfirm = '';
				$formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id_lead=' . $lead_id . '&$new_statut=' . $new_statut, 'test1', 'test2', 'confirm_move', '', 0, 1);
				echo $formconfirm;
				break;

			case 'sanssuite':
				$c_status = 11;
				$chaude = 0;
				break;
		}

		$lead->fk_c_status = $c_status;
		$lead->array_options['options_chaude'] = $chaude;
		$res = $lead->update($user);
		echo $lead->error;
	}else{
		echo $lead->error;
	}
}elseif($action=='confirm_move'){
	$c_status=7;
	$chaude=0;
	$lead->fk_c_status = $c_status;
	$lead->array_options['options_chaude'] = $chaude;
	$res = $lead->update($user);
	echo $lead->error;
}
?>