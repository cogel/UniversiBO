<?php

use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

require_once ('Files/FileItemStudenti'.PHP_EXTENSION);
require_once ('Commenti/CommentoItem'.PHP_EXTENSION);

/**
 * ShowFileStudentiCommenti � un'implementazione di PluginCommand.
 *
 * Mostra i file del canale
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * 
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ShowFileStudentiCommento extends PluginCommand {
	
	//todo: rivedere la questione diritti per uno studente...
	
	/**
	 * Esegue il plugin
	 *
	 * @param l'id del file di cui si voglio i commenti
	 */
	function execute($param)
	{
		//$flag_chkDiritti	=  $param['chk_diritti'];
//		var_dump($param['id_notizie']);
//		die();
		
		$bc        = $this->getBaseCommand();
		$user      = $bc->getSessionUser();
		
		$fc        = $bc->getFrontController();
		$template  = $fc->getTemplateEngine();
		$krono     = $fc->getKrono();

		
	
/*		
		$canale_news = $this->getNumNewsCanale($id_canale);

		$template->assign('showNews_desc', 'Mostra le ultime '.$num_news.' notizie del canale '.$id_canale.' - '.$titolo_canale);
*/
		
		$commento = CommentoItem::selectCommentoItem($param['id_commento']);
		$commento_tpl = array();
//		var_dump($elenco_commenti);
//	    die();
		
		
				$id_utente = $commento->getIdUtente();
				$commento_tpl['commento'] = $commento->getCommento();
				$commento_tpl['voto'] = $commento->getVoto();
				$commento_tpl['userLink'] = ('index.php?do=ShowUser&id_utente='.$id_utente);
				$commento_tpl['userNick'] = $commento->getUsername();
				
				
//				var_dump($commento_tpl);
//				die();
				
			$template->assign('showFileStudentiCommenti_commento', $commento_tpl);
		
			
	}	
	
}

?>