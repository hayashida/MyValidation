<?php
/**
 * カスタムバリデーションクラス
 * @author hayashida
 *
 */
class MyValidation
{
	/**
	 * 数字の妥当性チェック
	 * @param unknown_type $val
	 */
	public static function _validation_valid_numeric($val)
	{
		if (!$val)
		{
			return true;
		}
		return (preg_match('/^[0-9]+$/', $val) > 0);
	}
	
	/**
	 * 英字の妥当性チェック
	 * @param unknown_type $val
	 */
	public static function _validation_valid_alpha($val)
	{
		if (!$val)
		{
			return true;
		}
		return (preg_match('/^[a-zA-Z]+$/', $val) > 0);
	}
	
	/**
	 * 英数字の妥当性チェック
	 * @param unknown_type $val
	 */
	public static function _validation_valid_alpha_numeric($val)
	{
		if (!$val)
		{
			return true;
		}
		return (preg_match('/^[a-zA-Z0-9¥_¥-]+$/', $val) > 0);
	}
	
	/**
	 * 日付の妥当性チェック
	 * @param unknown_type $val
	 */
	public static function _validation_valid_date($val)
	{
		if (!$val)
		{
			return true;
		}
		
		$tmp_val = $val;
		$tmp_val = str_replace('年', '/', $tmp_val);
		$tmp_val = str_replace('月', '/', $tmp_val);
		$tmp_val = str_replace('日', '', $tmp_val);
		
		$parts = array();
		if (!preg_match('/^([0-9]{4})[\-\/\.](0?[0-9]|1[0-2])[\-\/\.]([0-2]?[0-9]|3[01])$/', $tmp_val, $parts))
		{
			return false;
		}
		
		if (checkdate($parts[2], $parts[3], $parts[1]) === true)
		{
			$val = date('Y-m-d', strtotime($tmp_val));
			return $val;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 日付の比較チェック
	 * @param unknown_type $val
	 * @param unknown_type $field
	 */
	public static function _validation_compare_date($val, $field)
	{
		echo '['.$field.']';
		if (!$val)
		{
			return true;
		}
		if (!Input::post($field))
		{
			return true;
		}
		
		$tmp_val_st = strtotime(Input::post($field));
		$tmp_val_ed = strtotime($val);
		
		if ($tmp_val_st > $tmp_val_ed)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * 数値の比較チェック
	 * @param unknown_type $val
	 * @param unknown_type $field
	 */
	public static function _validation_compare_numeric($val, $field)
	{
		if (strlen($val) == 0)
		{
			return true;
		}
		if (strlen(Input::post($field)) == 0)
		{
			return true;
		}
		
		return ((int)Input::post($field) < (int)$val);
	}
	
	/**
	 * チェックボックスの妥当性チェック
	 * @param unknown_type $val
	 * @param unknown_type $options
	 */
	public static function _validation_valid_checkbox($val, $options)
	{
		if ($val)
		{
			if (!is_array($val)){
				return false;
			}
			
			foreach ($val as $v)
			{
				if (!array_key_exists($v, $options))
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * チェックボックスの必須チェック
	 * @param unknown_type $val
	 * @param unknown_type $min
	 */
	public static function _validation_required_checkbox($val, $min = null)
	{
		if (!$val or !is_array($val))
		{
			return false;
		}

		$min_count = $min ? $min : 1;
		
		return count($val) >= $min_count;
	}
	
	/**
	 * 重複する値がないかチェックする
	 * @param unknown_type $val
	 * @param unknown_type $column
	 * @param unknown_type $options
	 */
	public static function _validation_unique($val, $column, $options = null)
	{
		// テーブル名とフィールド名を分ける
		list($table, $field) = explode('.', $column);
		
		$query = DB::select($field)
						->from($table)
						->where($field, '=', $val);
		if (is_array($options) and count($options) == 3)
		{
			$query->where($options[0], $options[1], $options[2]);
		}
		
		$results = $query->execute();
		
		return ($results->count() == 0);
	}
}