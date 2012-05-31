<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Commenti;
use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

/**
 * CommentoItem class
 *
 * Rappresenta un singolo commento su un FileStudente.
 *
 * @package universibo
 * @subpackage Commenti
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @author Fabrizio Pinto
 * @author Davide Bellettini
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class CommentoItem
{
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';

    /**
     * @var int
     */
    private $id_commento = 0;
    /**
     * @var int
     */
    private $id_file_studente = 0;
    /**
     * @var int
     */
    private $id_utente = 0;
    /**
     * @var string
     */
    private $commento = '';
    /**
     * @var int
     */
    private $voto = -1;

    /**
     * @var string
     */
    private $eliminato = self::NOT_ELIMINATO;

    /**
     * @var DBCommentoItemRepository
     */
    private static $repository;

    /**
     * Crea un oggetto CommentoItem
     * @param $id_file_studente id di un File Studente
     * @param $id_utente id di un utente, quello che ha fatto il commento
     * @param $commento commento a un File Studente
     * @param $voto proposto per un file studente
     */

    public function __construct($id_commento, $id_file_studente, $id_utente,
            $commento, $voto, $eliminato)
    {
        $this->id_commento = $id_commento;
        $this->id_file_studente = $id_file_studente;
        $this->id_utente = $id_utente;
        $this->commento = $commento;
        $this->voto = $voto;
        $this->eliminato = $eliminato;
    }

    public function getIdCommento()
    {
        return $this->id_commento;
    }

    public function isEliminato()
    {
        return $this->eliminato === self::ELIMINATO;
    }

    /**
     * Restituisce l'id_file_studente del commento
     */

    public function getIdFileStudente()
    {
        return $this->id_file_studente;
    }

    /**
     * Setta l'id_file_studente del commento
     */

    public function setIdFileStudente($id_file_studente)
    {
        $this->id_file_studente = $id_file_studente;
    }

    /**
     * Restituisce l'id_utente che ha scritto il commento
     */

    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * Setta l'id_utente che ha scritto il commento
     */

    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Restituisce il commento al File Studente
     */

    public function getCommento()
    {
        return $this->commento;
    }

    /**
     * Setta il commento al File Studente
     */

    public function setCommento($commento)
    {
        $this->commento = $commento;
    }

    /**
     * Restituisce il voto associato al file studente
     */

    public function getVoto()
    {
        return $this->voto;
    }

    /**
     * Setta il voto associato al File Studente
     */

    public function setVoto($voto)
    {
        $this->voto = $voto;
    }

    /**
     * @deprecated
     */
    public static function selectCommentiItem($id_file)
    {
        return self::getRepository()->findByFileId($id_file);
    }

    /**
     * @deprecated
     */
    public static function selectCommentoItem($id_commento)
    {
        return self::getRepository()->find($id_commento);
    }

    /**
     * Conta il numero dei commenti presenti per il file
     *
     * @deprecated
     * @param  int    $id_file identificativo su database del file studente
     * @return numero dei commenti
     */
    public static function quantiCommenti($id_file)
    {
        return self::getRepository()->countByFile($id_file);
    }

    /**
     * Restituisce il nick dello user
     *
     * @deprecated
     * @return il nickname
     */

    public function getUsername()
    {
        return User::getUsernameFromId($this->id_utente);
    }

    /**
     * Aggiunge un Commento sul DB
     */
    public static function insertCommentoItem($id_file_studente, $id_utente, $commento, $voto)
    {
        ignore_user_abort(1);

        $db = FrontController::getDbConnection('main');

        $next_id = $db->nextID('file_studente_commenti_id_commento');
        $this->id_commento = $next_id;
        $return = true;
        $query = 'INSERT INTO file_studente_commenti (id_commento,id_file,id_utente,commento,voto,eliminato) VALUES ('
                . $next_id . ',' . $db->quote($id_file_studente) . ','
                . $db->quote($id_utente) . ',' . $db->quote($commento) . ','
                . $db->quote($voto) . ',' . $db->quote(self::NOT_ELIMINATO)
                . ')';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }

        return $return;
    }

    /**
     * Modifica un Commento sul DB
     */

    public static function updateCommentoItem($id_commento, $commento, $voto)
    {
        $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $return = true;
        $query = 'UPDATE file_studente_commenti SET commento='
                . $db->quote($commento) . ', voto= ' . $db->quote($voto)
                . ' WHERE id_commento=' . $db->quote($id_commento);
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }
        ignore_user_abort(0);

        return $return;
    }

    /**
     * Cancella un commento sul DB
     */

    public static function deleteCommentoItem($id_commento)
    {
        $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);

        $return = self::getRepository()->deleteById($id_commento);

        ignore_user_abort(0);

        return $return;
    }
    /**
     * Questa funzione verifica se esiste già un commento inserito dall'utente
     *
     * @deprecated
     * @param $id_file, $id_utente id del file e dell'utente
     * @return un valore booleano
     */
    public static function esisteCommento($id_file, $id_utente)
    {
        $flag = false;

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_commento FROM file_studente_commenti WHERE id_file ='
                . $db->quote($id_file) . ' AND id_utente = '
                . $db->quote($id_utente) . ' AND eliminato = '
                . $db->quote(self::NOT_ELIMINATO)
                . 'GROUP BY id_file,id_utente,id_commento';
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $res->fetchInto($ris);

        return $ris[0];
    }

    /**
     * @return DBCommentoItemRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = new DBCommentoItemRepository(
                    FrontController::getDbConnection('main'));
        }

        return self::$repository;
    }
}

define('COMMENTO_ELIMINATO', CommentoItem::ELIMINATO);
define('COMMENTO_NOT_ELIMINATO', CommentoItem::NOT_ELIMINATO);
