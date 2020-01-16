<?php
class Usager {
    private $_id,$_infos,$_medecin,$_rdv,$_db;

    public function __construct($id) {
        if($id == null) throw new Exception('id cannot be null');

        $this->_db = DB::getInstance();

        $this->_id = $id;
        $req = $this->_db->query('SELECT * FROM usager WHERE id = ?',array($id));
        $this->_infos = $req->first();
        if($req->count() < 1) throw new Exception('usager not found');

        $this->_rdv = $this->_db->query('SELECT * FROM rdv WHERE id_u = ?',array($id))->results();

        try {
            $this->_medecin = new Medecin($this->_infos->id_m);
        }
        catch(Exception $e) {
            $this->_medecin = null;
        }
    }

    public function getID() {
        return $this->_id;
    }

    public function getInfo() {
        return $this->_infos;
    }

    public function getRDV() {
        return $this->_rdv;
    }

    public function getMedecin() {
        return $this->_medecin;
    }

    public static function add($fields) {
        if($fields == null) throw new Exception('fields cannot be null');
        $db_ = DB::getInstance();
        $res = $db_->insert('usager',$fields);
        if($res) Redirect::to('/users/view/'.$db_->lastId());
        return $res; 
    }

    public function update($fields) {
        if($fields == null) throw new Exception('fields cannot be null');
        return $this->_db->update('usager',$this->_id,$fields); 
    }

    public function delete() {
        $this->_db->query('DELETE FROM rdv WHERE id_u = ?', array($this->_id));
        return $this->_db->deleteById('usager',$this->_id);
    }
    
    public function __destruct() {
        $this->_id = 0;
    }
}