<?php
class RDV {
    private $_id,$_infos,$_client,$_medecin,$_db;

    public function __construct($id) {
        if($id == null) throw new Exception('id is null');

        $this->_db = DB::getInstance();
        
        $this->_id = $id;
        $req = $this->_db->query('SELECT * FROM rdv WHERE id = ?',array($id));
        $this->_infos = $req->first();
        if($req->count() < 1) throw new Exception('rdv not found');

        $this->_client = new Usager($this->_infos->id_u);
        $this->_medecin = new Medecin($this->_infos->id_m);
    }

    public function getID() {
        return $this->_id;
    }

    public function getInfo() {
        return $this->_infos;
    }

    public function getUsager() {
        return $this->_client;
    }

    public function getMedecin() {
        return $this->_medecin;
    }

    public static function add($fields) {
        if($fields == null) throw new Exception('fields cannot be null');
        $db_ = DB::getInstance();
        $res = $db_->insert('rdv',$fields);
        if($res) Redirect::to('/consultations/view/'.$db_->lastId());
        return $res; 
    }

    public function update($fields) {
        if($fields == null) throw new Exception('fields cannot be null');
        return $this->_db->update('rdv',$this->_id,$fields); 
    }

    public function delete() {
        return $this->_db->deleteById('rdv',$this->_id);
    }

    public function checkExistingRDV($date,$time,$duree, $idmedecin, $iduser) {
        $moment1 = date("H:i", strtotime($time));
        $moment2 = date("H:i", strtotime($time." +".$duree." minutes"));
        $format_date = date('Y-m-d',strtotime($date));

        //Vérification des rdv du médecin en cas de chevauchement
        $req = $this->_db->query("SELECT heure_r FROM rdv WHERE date_r = ? AND id_m = ? AND id != ?",array($format_date,$idmedecin,$this->_id))->results();
        foreach($req as $r) {
            $timestart = date("H:i",strtotime($r->heure_r));
            $timeend = date("H:i", strtotime($r->heure_r." +".$r->duree." minutes"));
            if($timestart <= $moment1  && $timeend > $moment1 || $timestart <= $moment2  && $timeend > $moment2) return false;
        }

        //Vérification des rdv de l'usager en cas de chevauchement
        $req1 = $this->_db->query("SELECT heure_r FROM rdv WHERE date_r = ? AND id_u = ? AND id != ?",array($format_date,$iduser,$this->_id))->results();
        foreach($req1 as $r) {
            $timestart = date("H:i",strtotime($r->heure_r));
            $timeend = date("H:i", strtotime($r->heure_r." +".$r->duree." minutes"));
            if($timestart <= $moment1  && $timeend > $moment1 || $timestart <= $moment2  && $timeend > $moment2) return false;
        }
        
        return true;
    }

    public static function checkRDV($date,$time,$duree, $idmedecin, $iduser) {
        $db = DB::getInstance();
        $moment1 = date("H:i", strtotime($time));
        $moment2 = date("H:i", strtotime($time." +".$duree." minutes"));
        $format_date = date('Y-m-d',strtotime($date));

        //Vérification des rdv du médecin en cas de chevauchement
        $req = $db->query("SELECT * FROM rdv WHERE date_r = ? AND id_m = ?",array($format_date,$idmedecin))->results();
        foreach($req as $r) {
            $timestart = date("H:i",strtotime($r->heure_r));
            $timeend = date("H:i", strtotime($r->heure_r." +".$r->duree." minutes"));
            if($timestart <= $moment1  && $timeend > $moment1 || $timestart <= $moment2  && $timeend > $moment2) return false;
        }

        //Vérification des rdv de l'usager en cas de chevauchement
        $req1 = $db->query("SELECT * FROM rdv WHERE date_r = ? AND id_u = ?",array($format_date,$iduser))->results();
        foreach($req1 as $r) {
            $timestart = date("H:i",strtotime($r->heure_r));
            $timeend = date("H:i", strtotime($r->heure_r." +".$r->duree." minutes"));
            if($timestart <= $moment1  && $timeend > $moment1 || $timestart <= $moment2  && $timeend > $moment2) return false;
        }
        
        return true;
    }
}