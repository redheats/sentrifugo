<?php
/********************************************************************************* 
 *  This file is part of Sentrifugo.
 *  Copyright (C) 2014 Sapplica
 *   
 *  Sentrifugo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Sentrifugo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Sentrifugo.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Sentrifugo Support <support@sentrifugo.com>
 ********************************************************************************/

class Default_Model_Competencylevel extends Zend_Db_Table_Abstract
{
    protected $_name = 'main_competencylevel';
    protected $_primary = 'id';
	
	public function getCompetencyLevelData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "isactive = 1";
		/*if($columnkey != '' && $columntext != '')
			$where = " ".$columnkey." like '%".$columntext."%' "; */
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$competencyLevelData = $this->select()
    					   ->setIntegrityCheck(false)	    					
						   ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		//echo $dateFormatData->__toString(); 
		return $competencyLevelData;       		
	}
	public function getsingleCompetencyLevelData($id)
	{
		/*
			Purpose:	Get records with isactive status 1.
			Modified Date:	04/10/2013
			Modified By:	Yamini.
		*/
		/*$row = $this->fetchRow("id = '".$id."'");
		if (!$row) {
			throw new Exception("Could not find row $id");
		}
		return $row->toArray();*/
		$db = Zend_Db_Table::getDefaultAdapter();
		$competencyLevelData = $db->query("SELECT * FROM main_competencylevel WHERE id = ".$id." AND isactive=1");
		$res = $competencyLevelData->fetchAll();
		if (isset($res) && !empty($res)) 
		{	
			return $res;
		}
		else
			return 'norows';
	}
	
	public function SaveorUpdateCompetencyLevelData($data, $where)
	{
	    if($where != ''){
			$this->update($data, $where);
			return 'update';
		} else {
			$this->insert($data);
			$id=$this->getAdapter()->lastInsertId('main_competencylevel');
			return $id;
		}
	
	}
	
	public function getCompetencylevelList()
	{
	    $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('c'=>'main_competencylevel'),array('c.id','c.competencylevel'))
                            ->where('c.isactive = 1')
                            ->order('c.competencylevel');
		return $this->fetchAll($select)->toArray();
	
	}
	public function getGrid($sort,$by,$perPage,$pageNo,$searchData,$call,$dashboardcall,$exParam1='',$exParam2='',$exParam3='',$exParam4='')
	{		
        $searchQuery = '';$tablecontent = '';  $searchArray = array();$data = array();$id='';
        $dataTmp = array();
		if($searchData != '' && $searchData!='undefined')
		{
			$searchValues = json_decode($searchData);
			foreach($searchValues as $key => $val)
			{
				$searchQuery .= " ".$key." like '%".$val."%' AND ";
				$searchArray[$key] = $val;
			}
			$searchQuery = rtrim($searchQuery," AND");					
		}

		/** search from grid - END **/
		$objName = 'competencylevel';
		
		$tableFields = array('action'=>'Action','competencylevel' => 'Competency Level','description' => 'Description');
		
			
		$tablecontent = $this->getCompetencyLevelData($sort, $by, $pageNo, $perPage,$searchQuery);     
		$dataTmp = array(
			'sort' => $sort,
			'by' => $by,
			'pageNo' => $pageNo,
			'perPage' => $perPage,				
			'tablecontent' => $tablecontent,
			'objectname' => $objName,
			'extra' => array(),
			'tableheader' => $tableFields,
			'jsGridFnName' => 'getAjaxgridData',
			'jsFillFnName' => '',
			'searchArray' => $searchArray,
			'call'=>$call,'dashboardcall'=>$dashboardcall
		);		
			
		return $dataTmp;
	}
}