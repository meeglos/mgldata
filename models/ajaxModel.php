<?php 

/** 
 * # Author: miglos
 * # Site:	 https://github.com/meeglos
 * # Email:  miglos@hotmail.es	 
 * # Date:   2014-02-22 23:22:28
 * # Last Modified by:   miglos
 * # Last Modified time: 2014-02-23 01:49:48
 */

	class ajaxModel extends Model
	{
		parent::__construct();
	}

	public function getPaises()
	{
		$paises = $this->_db->query(
			"SELECT * FROM paises"
			);
	}

	public function getCiudades($pais)
	{
		$ciudades = $this->_db->query(
			"SELECT * FROM ciudades WHERE pais = {$pais}"
			);

		$ciudades->setFetchMode(PDO::FETCH_ASSOC);
		return $ciudades->fetchAll();
	}

	public function insertarCiudad($ciudad,$pais)
	{
		$this->_db->query(
			"INSERT INTO ciudades VALUES(null, '{ciudad}','{pais}'"
			);
	}




?>