<?php

/**
 * @author Chris Overfield
 * @copyright 2011
 */

	function GetFetchSQL($fetch_type, &$sql, &$joinclause, &$whereclause)
	{
		$getsql = "";
		
		switch($fetch_type)
		{
			case 't1a':
				# Table 1a-1d
				$joinclause .= " JOIN headingtypes ht on c.heading_type = ht.heading_type_id and ht.heading_type_id in (1,2,3,4) ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case 't1e':
				# Table 1e
				$joinclause .= " JOIN headingtypes ht on c.heading_type = ht.heading_type_id and ht.heading_type_id = 5 ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case 't1f':
				# Table 1f-1h
				$joinclause .= " JOIN headingtypes ht on c.heading_type = ht.heading_type_id and ht.heading_type_id in (6,7,8) ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case 't1k':
				# Table 1e
				$joinclause .= " JOIN headingtypes ht on c.heading_type = ht.heading_type_id and ht.heading_type_id = 10 ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '0':
				# Main numbers starting with 0
				$whereclause .= " AND c.classmark_tag like '0%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '1':
				# Main numbers starting with 1
				$whereclause .= " AND c.classmark_tag like '1%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '2':
				# Main numbers starting with 2
				$whereclause .= " AND c.classmark_tag like '2%' ";
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;
			case '3':
				# Main numbers starting with 3
				$localwhere = $whereclause . " AND c.classmark_tag like '3.%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '30%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '31%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '32%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '33%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag = '3' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '34':
				# Main numbers starting with 34/39
				$localwhere = $whereclause . " AND c.classmark_tag like '34%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '35%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '36%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '37%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '38%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '39%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '5':
				# Main numbers starting with 5
				$localwhere = $whereclause . " AND c.classmark_tag = '5' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '50%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '51':
				# Main numbers starting with 51/53
				$localwhere = $whereclause . " AND c.classmark_tag like '51%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '52%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '53%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '54':
				# Main numbers starting with 54/55
				$localwhere = $whereclause . " AND c.classmark_tag like '54%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '55%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '56':
				# Main numbers starting with 56/59
				$localwhere = $whereclause . " AND c.classmark_tag like '56%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '57%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '58%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '59%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '60':
				# Main numbers starting with 60 and 61 including the single number 6
				$localwhere = $whereclause . " AND c.classmark_tag = '6' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '60%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '61%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '62':
				# Main numbers starting with 62-, 620 and 621 including the single number 62
				$localwhere = $whereclause . " AND c.classmark_tag = '62' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '62-%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '620%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '621%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;		
			case '622':
				# Main numbers starting with 622/623
				$localwhere = $whereclause . " AND c.classmark_tag like '622%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '623%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '624':
				# Main numbers starting with 624/627
				$localwhere = $whereclause . " AND c.classmark_tag like '624%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '625%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '626%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '627%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '628':
				# Main numbers starting with 628
				$localwhere = $whereclause . " AND c.classmark_tag like '628%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '629':
				# Main numbers starting with 628
				$localwhere = $whereclause . " AND c.classmark_tag like '629%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '629':
				# Main numbers starting with 628
				$localwhere = $whereclause . " AND c.classmark_tag like '629%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '63':
				# Main numbers starting with 63
				$localwhere = $whereclause . " AND c.classmark_tag like '63%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '64':
				# Main numbers starting with 64
				$localwhere = $whereclause . " AND c.classmark_tag like '64%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '65':
				# Main numbers starting with 65
				$localwhere = $whereclause . " AND c.classmark_tag like '65%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '66':
				# Main numbers starting with 66
				$localwhere = $whereclause . " AND c.classmark_tag like '66%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '67':
				# Main numbers starting with 67/69
				$localwhere = $whereclause . " AND c.classmark_tag like '67%' ";
				$getsql = "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '68%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				$localwhere = $whereclause . " AND c.classmark_tag like '69%' ";
				$getsql .= " UNION " . "(" . $sql . " " . $joinclause . " " . $localwhere . ")";
				break;
			case '7':
				# Main numbers starting with 7
				$localwhere = $whereclause . " AND c.classmark_tag like '7%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '8':
				# Main numbers starting with 8
				$localwhere = $whereclause . " AND c.classmark_tag like '8%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '9':
				# Main numbers starting with 9
				$localwhere = $whereclause . " AND c.classmark_tag like '9%' ";
				$getsql = $sql . " " . $joinclause . " " . $localwhere;
				break;
			case '*':
			default:
				$getsql = $sql . " " . $joinclause . " " . $whereclause;
				break;

		}

		return $getsql;
	}     

?>