<?php
// Copyright 2014 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

class ProfileFieldModel extends ETModel {

	public function __construct()
	{
		parent::__construct("profile_field", "fieldId");
	}

	public function getWithSQL($sql)
	{
		return $sql
			->select("f.*")
			->from("profile_field f")
			->orderBy("f.position ASC")
			->exec()
			->allRows();
	}

	public function getData($memberId)
	{
		$sql = ET::SQL()
			->select("d.data")
			->from("profile_data d", "d.fieldId=f.fieldId AND d.memberId=:memberId", "left")
			->bind(":memberId", $memberId);

		return $this->getWithSQL($sql);
	}

	public function setData($memberId, $fieldId, $data)
	{
		ET::SQL()
			->insert("profile_data")
			->set("memberId", $memberId)
			->set("fieldId", $fieldId)
			->set("data", $data)
			->setOnDuplicateKey("data", $data)
			->exec();
	}

	public function delete($wheres = array())
	{
		return ET::SQL()
			->delete("f, d")
			->from("profile_field f")
			->from("profile_data d", "f.fieldId=d.fieldId", "left")
			->where($wheres)
			->exec();
	}

	public function deleteById($id)
	{
		return $this->delete(array("f.fieldId" => $id));
	}

	public function update($values, $wheres = array())
	{
		if (in_array($values["type"], array("select", "radios", "checkboxes")))
			$this->validate("options", $values["options"], array($this, "validateOptions"));

		if ($this->errorCount()) return false;

		return parent::update($values, $wheres);
	}

	public function create($values, $wheres = array())
	{
		if (in_array($values["type"], array("select", "radios", "checkboxes")))
			$this->validate("options", $values["options"], array($this, "validateOptions"));

		if ($this->errorCount()) return false;

		return parent::create($values);
	}

	public function validateOptions($options)
	{
		$options = trim($options);
		if (!strlen($options)) return "empty";
	}
}
