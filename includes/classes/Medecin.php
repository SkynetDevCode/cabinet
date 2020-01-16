<?php
class Medecin {
    private $_id,$_infos,$_clients,$_rdv,$_db;

    public function __construct($id) {
        if($id == null) throw new Exception('id is null');

        $this->_db = DB::getInstance();
        $this->_id = $id;
        $req = $this->_db->query('SELECT * FROM medecin WHERE id = ?',array($id));
        $this->_infos = $req->first();
        if($req->count() < 1) throw new Exception('medecin not found');

        $this->_rdv = $this->_db->query('SELECT * FROM rdv WHERE id_m = ?',array($id))->results();
        $this->_clients = $this->_db->query('SELECT * FROM usager WHERE id_m = ?',array($id))->results();
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

    public function getClients() {
        return $this->_clients;
    }

    public static function add($fields) {
        if($fields == null) throw new Exception('fields cannot be null');
        $db_ = DB::getInstance();
        $res = $db_->insert('medecin',$fields);
        if($res) Redirect::to('/medecins/view/'.$db_->lastId());
        return $res; 
    }

    public function update($fields) {
        if($fields == null) throw new Exception('fields cannot be null');
        return $this->_db->update('medecin',$this->_id,$fields); 
    }

    public function delete() {
        $this->_db->query('DELETE FROM rdv WHERE id_m = ?', array($this->_id));
        return $this->_db->deleteById('medecin',$this->_id);
    }
}